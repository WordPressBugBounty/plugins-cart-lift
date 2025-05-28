<?php

use function WP_CLI\Router\add_filter;

if( !defined( 'ABSPATH' ) ) {
	exit;
}


class Cart_Lift_Cart_Actions
{
    public $provider;

    public function __construct( $provider = 'wc' )
    {
        $this->provider = $provider;
    }


    /**
     * @param $session_id
     * @return string|null
     * @since 1.0.0
     */
    public function has_scheduled_email( $session_id )
    {
        global $wpdb;
        $cl_campaign_history_table = $wpdb->prefix . CART_LIFT_CAMPAIGN_HISTORY_TABLE;
        $count                     = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM  $cl_campaign_history_table WHERE session_id = %s", sanitize_text_field( $session_id ) ) );
        return $count;
    }


    /**
     * @param $session_id
     * @return string|null
     */
    public function if_any_email_sent( $session_id )
    {
        global $wpdb;
        $cl_campaign_history_table = $wpdb->prefix . CART_LIFT_CAMPAIGN_HISTORY_TABLE;
        $count                     = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM  $cl_campaign_history_table WHERE email_sent = 1 AND session_id = %s", sanitize_text_field( $session_id ) ) );
        return $count;
    }

    /**
     * Get cart info by user email
     *
     * @param $user_email
     * @return array|object|void|null
     * @since 1.0.0
     */
    public function get_cart_details_by_email( $user_email )
    {
        global $wpdb;
        $cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $result        = $wpdb->get_row(
            $wpdb->prepare( 'SELECT * FROM `' . $cl_cart_table . '` WHERE email = %s AND status in ("processing", "abandoned") LIMIT 1', $user_email ) // phpcs:ignore
        );
        return $result;
    }

    /**
     * Save cart details to db
     *
     * @param string $provider
     * @param $user_email
     * @since 1.0.0
     */
    public function save_cart_infos( $user_email, $provider = 'wc' )
    {
        $cart_tracking = cl_get_general_settings_data( 'cart_tracking' );
        $cart_tracking = apply_filters( 'cl_cart_tracking_status', $cart_tracking );
        $general_settings = get_option('cl_general_settings');
	    $excluded_products = $general_settings['cl_excluded_products'] ?? [];
        $excluded_categories = $general_settings['cl_excluded_categories'] ?? [];
        $excluded_countries = $general_settings['cl_excluded_countries'] ?? [];

        if( $cart_tracking ) {
            $user          = wp_get_current_user();
            $roles         = $user->roles;

            if( is_user_logged_in() ) {
                $restricted = cl_restricted_users( $roles[ 0 ] );
            } else {
                $restricted = false;
            }

            if( !$restricted ) {

                $remove_guest_tracking = cl_get_general_settings_data( 'remove_carts_for_guest' );

                if( $remove_guest_tracking == '0' || $user_email != '' ) {

                    global $wpdb;
                    $cl_cart_table        = $wpdb->prefix . CART_LIFT_CART_TABLE;
                    $session_cart_details = null;
                    $session_id = '';
                    if( $provider === 'wc' && cl_is_wc_active() && !empty( WC()->session ) ) {
                        $session_id = WC()->session->get( 'cl_wc_session_id' );
                        if( empty( $session_id ) ) {
                            $session_id = md5( uniqid( wp_rand(), true ) );
                            WC()->session->set( 'cl_wc_session_id', $session_id );
                        }
                        $session_cart_details = $this->get_cart_details( $session_id );

                        if( is_null( $session_cart_details ) ) {
                            $session_id = md5( uniqid( wp_rand(), true ) );
                            WC()->session->set( 'cl_wc_session_id', $session_id );
                        } else {
                            $user_email = $session_cart_details->email ? $session_cart_details->email : $user_email;
                        }
                    }

                    if( $provider === 'edd' && cl_is_edd_active() && !empty( EDD()->session ) ) {
                        $session_id           = EDD()->session->get( 'cl_edd_session_id' );
                        if( empty( $session_id ) ) {
                            $session_id = md5( uniqid( wp_rand(), true ) );
                            EDD()->session->set( 'cl_edd_session_id', $session_id );
                        }
                        $session_cart_details = $this->get_cart_details( $session_id );

                        if( is_null( $session_cart_details ) ) {
                            $session_id = md5( uniqid( wp_rand(), true ) );
                            EDD()->session->set( 'cl_edd_session_id', $session_id );
                        } else {
                            $user_email = $session_cart_details->email ? $session_cart_details->email : $user_email;
                        }
                    }

                    if( $provider === 'lp' && cl_is_lp_active() && !empty( LP()->session ) ) {
                        $session_id           = LP()->session->get( 'cl_lp_session_id' );
                        if( empty( $session_id ) ) {
                            $session_id = md5( uniqid( wp_rand(), true ) );
                            LP()->session->set( 'cl_lp_session_id', $session_id, true );
                        }

                        $session_cart_details = $this->get_cart_details( $session_id );
                        if( is_null( $session_cart_details )  ) {
                            $session_id = md5( uniqid( wp_rand(), true ) );
                            LP()->session->set( 'cl_lp_session_id', $session_id, true );
                        } else {
                            $user_email = $session_cart_details->email ? $session_cart_details->email : $user_email;
                        }
                    }

                    $cart_details = $this->prepare_cart_data( $user_email, $provider );

                    //check excluded products
                    if ( !empty( $excluded_products ) && $general_settings[ 'enable_cl_exclude_products' ] == '1' ) {
                        $cart_products    = !empty( $cart_details[ 'cart_contents' ] ) ? unserialize( $cart_details[ 'cart_contents' ] ) : [];
                        $cart_products_id = array_column( $cart_products, 'id' );
                        foreach ( $cart_products_id as $id ) {
                            if ( in_array( strval( $id ), $excluded_products, true ) ) {
                                cl_remove_abandoned_cart_record( $user_email, $session_id );
                                return;
                            }
                        }
                    }

                    if( !empty( $excluded_categories ) && !empty( $general_settings['enable_cl_exclude_categories' ] ) && $general_settings['enable_cl_exclude_categories' ] == '1'){
                        $cart_products = !empty( $cart_details['cart_contents'] ) ? unserialize( $cart_details['cart_contents'] ) : [];
                        $cart_product_ids = array_column($cart_products, 'id');
                        $cart_product_category_ids = get_products_category_id( $cart_product_ids );
                        foreach ( $cart_product_category_ids as $id){
                            if( in_array( strval( $id ), $excluded_categories, true) ){
                                cl_remove_abandoned_cart_record( $user_email, $session_id );
                                return;
                            }
                        }
                    }

                    if( !empty( $excluded_countries ) && !empty( $general_settings['enable_cl_exclude_countries' ] ) && $general_settings['enable_cl_exclude_countries' ] == '1'){
                        $result = apply_filters('cl_exclude_countries', $user_email, $session_id, $excluded_countries);
                        if( !empty( $result ) ){
                            return;
                        }
                    }

                    $cart_details = apply_filters( 'cl_cart_details_before_update', $cart_details, $session_id );

                    if( $cart_details ) {
                        if( $provider === 'edd' && cl_is_edd_active() ) {
                            setcookie( 'cl_edd_session_id', $session_id, time() + 3600, '/' );
                        }

                        if( !is_null( $session_cart_details ) ) {
                            unset( $cart_details[ 'time' ] );
                            $wpdb->update(
                                $cl_cart_table,
                                $cart_details,
                                array( 'session_id' => $session_id )
                            );
                        } else {
                            $cart_details[ 'session_id' ] = $session_id;
                            if( $session_id ) {
                                $wpdb->insert(
                                    $cl_cart_table,
                                    $cart_details
                                );
                            }
                        }
                    } else {
                        $wpdb->delete(
                            $cl_cart_table,
                            array( 'session_id' => $session_id )
                        );
                        if( $provider === 'wc' && cl_is_wc_active() && !empty( WC()->session ) ) {
                            WC()->session->__unset( 'cl_wc_session_id' );
                        }
                        if( $provider === 'edd' && cl_is_edd_active() && !empty( EDD()->session ) ) {
                            EDD()->session->set( 'cl_edd_session_id', '' );
                        }
                        if( $provider === 'lp' && cl_is_lp_active() && !empty( LP()->session ) ) {
                            LP()->session->set( 'cl_lp_session_id', '', true );
                        }
                    }
                }
            }
        }
    }

    /**
     * Get cart details from cart table
     *
     * @param $session_id
     * @return array|object|void|null
     * @since 1.0.0
     */
    public function get_cart_details( $session_id )
    {
        global $wpdb;
        $cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $result        = $wpdb->get_row(
            $wpdb->prepare( 'SELECT * FROM `' . $cl_cart_table . '` WHERE session_id = %s and status in ( "processing", "abandoned", "recovered" )', $session_id ) // phpcs:ignore
        );
        return $result;
    }

    /**
     * prepare abandon cart data
     *
     * @param $email
     * @return array
     * @since 1.0.0
     */
    public function prepare_cart_data( $email, $provider = 'wc' )
    {
        $current_time = current_time( CART_LIFT_DATETIME_FORMAT );
        if( 'wc' === $provider && cl_is_wc_active() ) {
            $cart_contents = array();
            $cart_total    = 0;
            $cart_items = WC()->cart->get_cart();
            $formatted_woosb_product_ids = array();
            if( !empty( $cart_items ) ) {
                foreach( $cart_items as $key => $item ) {
                    $product = wc_get_product($item['product_id']);
                    $product_type = $product && is_a($product, 'WC_Product') ? $product->get_type() : '';
                    $item_product_id = $product && is_a($product, 'WC_Product') ? $product->get_id() : 0;
                    if (
                        cl_is_woosb_active() && !empty($formatted_woosb_product_ids) && in_array((string)$item_product_id, $formatted_woosb_product_ids, true) && isset($item['woosb_parent_id'])
                    ) {
                        continue;
                    }
                    if( cl_is_woosb_active() &&  'woosb' === $product_type){
                        $woosb_instance = new WC_Product_Woosb($item['product_id']);
                        $woosb_product_ids[] = $woosb_instance->get_items();
                        if(!empty($woosb_product_ids[0])){
                            foreach ($woosb_product_ids[0] as $key => $internal_item) {
                                if (isset($internal_item['id']) && !in_array($internal_item['id'], $formatted_woosb_product_ids)) {
                                    $formatted_woosb_product_ids[] = (string)$internal_item['id'];
                                }
                            }
                        }
                    }
                    $product_id   = isset( $item[ 'product_id' ] ) ? (int)$item[ 'product_id' ] : 0;
                    $variation_id = $item[ 'variation_id' ] ?? '';
                    $price        = get_post_meta( !empty( $variation_id ) ? $variation_id : $product_id, '_price', true );
                    $quantity     = $item[ 'quantity' ] ?? 0;
                    $line_total   = (float)$price * (int)$quantity;
                    $line_tax     = $item[ 'line_tax' ] ?? 0;

                    $cart_contents[] = array(
                        'key'               => $key,
                        'id'                => $product_id,
                        'quantity'          => $quantity,
                        'variation_id'      => $item[ 'variation_id' ] ?? '',
                        'variation'         => $item[ 'variation' ] ?? '',
                        'data_hash'         => $item[ 'data_hash' ] ?? '',
                        'line_subtotal'     => $item[ 'line_subtotal' ] ?? '',
                        'line_subtotal_tax' => $item[ 'line_subtotal_tax' ] ?? '',
                        'line_total'        => $line_total,
                        'line_tax'          => $line_tax,
                        'is_new'            => 1,
                    );
                    $cart_total      += $line_total;
                }
            }
        }

        if( $provider === 'edd' && cl_is_edd_active() ) {
            $cart_total    = edd_get_cart_total();
            $cart_contents = edd_get_cart_contents();
        }

        if( $provider === 'lp' && cl_is_lp_active() ) {
            $cart  = learn_press_get_checkout_cart();
            $items = $cart->get_items();

            foreach( $items as $cart_item_key => $cart_item ) {
                $item_id  = $cart_item[ 'item_id' ];
                $quantity = $cart_item[ 'quantity' ];

                $cart_contents[] = array(
                    'lp_checkout_item_key' => $cart_item_key,
                    'id'                   => $item_id,
                    'quantity'             => $quantity
                );

                $cart_total = $cart_item[ 'subtotal' ];
            }
        }

        if( empty( $cart_contents ) )
            return null;

        return array(
            'email'         => $email,
            'cart_contents' => serialize( $cart_contents ),
            'cart_total'    => sanitize_text_field( $cart_total ),
            'time'          => sanitize_text_field( $current_time ),
            'provider'      => $provider,
        );
    }

    /**
     * Reinit cart status
     *
     * @param $session_id
     * @param $order_id
     * @param null $cart_details
     * @param string $provider
     * @since 1.0.0
     */
    public function reinitialize_cart_data( $session_id, $order_id, $cart_details = null, $provider = 'wc' )
    {
        if( !empty( $cart_details ) ) {
            if( $provider === 'wc' && cl_is_wc_active() && !empty( WC()->session ) ) {
                $order = wc_get_order( $order_id );

                WC()->session->__unset( 'cl_wc_session_id' );

                if( 'abandoned' === $cart_details->status ) {
                    // check if email is scheduled for
                    // this cart
                    $is_recovered_cart = $order->get_meta( 'cl_recovered_cart' );

                    if( 'yes' === $is_recovered_cart ) {
                        $this->update_cart_status( $session_id, $order_id, $cart_details );
                    }
                }
                elseif( 'recovered' === $cart_details->status ) {
                    $this->update_cart_status( $session_id, $order_id, $cart_details, 'completed' );
                }
                else {
                    $this->delete_cart_data_on_completion( $session_id );
                }
            }

            if( $provider === 'edd' && cl_is_edd_active() && !empty( EDD()->session ) ) {
                EDD()->session->set( 'cl_edd_session_id', NULL );
            }

            if( $provider === 'lp' && cl_is_lp_active() && !empty( LP()->session ) ) {
                LP()->session->set( 'cl_lp_session_id', NULL );
            }
        }
    }

    /**
     * @param $session_id
     * @param $order_id
     * @param bool $completed
     * @since 1.0.0
     */
    public function update_cart_status( $session_id, $order_id, $cart_details = null, $status = 'recovered' )
    {
        global $wpdb;
        $cl_cart_table             = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $cl_campaign_history_table = $wpdb->prefix . CART_LIFT_CAMPAIGN_HISTORY_TABLE;
        if( 'completed' === $status ) {
            $webhook = cl_get_general_settings_data( 'enable_webhook' );
            if( $webhook ) {
                $webhook_data = array(
                    'email'         => $cart_details->email,
                    'session_id'    => $session_id,
                    'order_id'      => $order_id,
                    'status'        => $status,
                    'cart_total'   => $cart_details->cart_total,
                    'provider'      => $cart_details->provider,
                    'product_table' => cl_get_email_product_table( $cart_details->cart_contents, $cart_details->cart_total, $cart_details->provider, false, false ),
                );
                cl_trigger_webhook( $webhook_data );
            }

            $wpdb->update(
                $cl_cart_table,
                array(
                    'order_id'   => $order_id,
                    'cart_total' => $cart_details->cart_total,
                    'status'     => $status,
                ),
                array(
                    'session_id' => $session_id,
                )
            );
        } else {
            $webhook = cl_get_general_settings_data( 'enable_webhook' );
            if( $webhook ) {
                $webhook_data = array(
                    'email'         => $cart_details->email,
                    'session_id'    => $session_id,
                    'order_id'      => $order_id,
                    'status'        => $status,
                    'cart_total'   => $cart_details->cart_total,
                    'provider'      => $cart_details->provider,
                    'product_table' => cl_get_email_product_table( $cart_details->cart_contents, $cart_details->cart_total, $cart_details->provider, false, false ),
                );
                cl_trigger_webhook( $webhook_data );
            }


            $wpdb->update(
                $cl_cart_table,
                array(
                    'order_id'   => $order_id,
                    'cart_total' => $cart_details->cart_total,
                    'status'     => $status,
                ),
                array(
                    'session_id' => $session_id,
                )
            );

            if( cl_get_general_settings_data( 'notify_recovered_cart' ) ) {
                if( $cart_details->provider === 'wc' ) {
                    $mailer = WC()->mailer();
                    do_action( 'cl_trigger_recovered_cart_email', $order_id );
                }
                if( $cart_details->provider === 'edd' ) {
                    do_action( 'cl_trigger_recovered_cart_email_edd', $cart_details );
                }
            }
        }

        // stop all future scheduled email
        $wpdb->update(
            $cl_campaign_history_table,
            array(
                'email_sent' => -1,
            ),
            array(
                'session_id' => $session_id,
                'email_sent' => 0,
            )
        );
        self::desctroy_recoverd_cart_cookie();
    }

    /**
     * @param $session_id
     * @since 1.0.0
     */
    public function delete_cart_data_on_completion( $session_id )
    {
        global $wpdb;
        $cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $wpdb->delete(
            $cl_cart_table,
            array(
                'session_id' => $session_id,
            )
        );
    }

    /**
     * save abandon cart data from ajax request
     *
     * when user inserts email, the email is updated if cart exits.
     * If not check save the cart data.
     *
     * @since 1.0.0
     */
    public function save_abandon_cart_data()
    {
        check_ajax_referer( 'cart-lift', 'security' );

        $cart_tracking = cl_get_general_settings_data( 'cart_tracking' );
        $cart_tracking = apply_filters( 'cl_cart_tracking_status_ajax', $cart_tracking );

        if( $cart_tracking ) {
            $user          = wp_get_current_user();
            $roles         = $user->roles;

            if( is_user_logged_in() ) {
                $restricted = cl_restricted_users( $roles[ 0 ] );
            } else {
                $restricted = false;
            }
            if( !$restricted ) {
                $post_data  = $this->sanitize_cart_post_data();
                $user_email = $post_data[ 'email' ];
                $provider   = $post_data[ 'provider' ];

                if( isset( $post_data[ 'email' ] ) ) {
                    global $wpdb;
                    $cl_cart_table        = $wpdb->prefix . CART_LIFT_CART_TABLE;
                    $session_cart_details = null;

                    if( $provider === 'wc' && cl_is_wc_active() && !empty( WC()->session ) ) {
                        $session_id = WC()->session->get( 'cl_wc_session_id' );
                        if( empty( $session_id ) ) {
                            $session_id = md5( uniqid( wp_rand(), true ) );
                            WC()->session->set( 'cl_wc_session_id', $session_id );
                        }
                        $session_cart_details = $this->get_cart_details( $session_id );
                        if ( is_null( $session_cart_details )  ) {
                            $session_id = md5( uniqid( wp_rand(), true ) );
                            WC()->session->set( 'cl_wc_session_id', $session_id );
                        }
                    }

                    if( $provider === 'edd' && cl_is_edd_active() && !empty( EDD()->session ) ) {
                        $session_id = EDD()->session->get( 'cl_edd_session_id' );
                        if( empty( $session_id ) ) {
                            $session_id = md5( uniqid( wp_rand(), true ) );
                            EDD()->session->set( 'cl_edd_session_id', $session_id );
                        }
                        $session_cart_details = $this->get_cart_details( $session_id );
                        if ( is_null( $session_cart_details )  ) {
                            $session_id = md5( uniqid( wp_rand(), true ) );
                            EDD()->session->set( 'cl_edd_session_id', $session_id );
                        }
                    }

                    if( $provider === 'lp' && cl_is_lp_active() && !empty( LP()->session ) ) {
                        $session_id = LP()->session->get( 'cl_lp_session_id' );
                        if( empty( $session_id ) ) {
                            $session_id = md5( uniqid( wp_rand(), true ) );
                            LP()->session->set( 'cl_lp_session_id', $session_id, true );
                        }
                        $session_cart_details = $this->get_cart_details( $session_id );
                        if( is_null( $session_cart_details )  ) {
                            $session_id = md5( uniqid( wp_rand(), true ) );
                            LP()->session->set( 'cl_lp_session_id', $session_id, true );
                        }
                    }

                    /**
                     * save cart data to the cart table
                     */
                    $cart_details = $this->prepare_cart_data( $user_email, $provider );

                    $cart_meta = array(
                        'first_name' => $post_data[ 'first_name' ],
                        'last_name'  => $post_data[ 'last_name' ],
                        'phone'      => $post_data[ 'phone' ],
                        'country'    => $post_data[ 'country' ],
                        'address'    => $post_data[ 'address' ],
                        'city'       => $post_data[ 'city' ],
                        'postcode'   => $post_data[ 'postcode' ],
                    );

                    if( isset( $post_data[ 'wpfunnels_checkout_id' ] ) ) {
                        $wpfunnels_checkout_id = $post_data[ 'wpfunnels_checkout_id' ];
                        $wpfunnel_id = WPFunnels\Wpfnl_functions::get_funnel_id_from_step( $wpfunnels_checkout_id );

                        if( $wpfunnel_id != 0 ) {
                            $cart_meta['wpfunnel_id'] = $wpfunnel_id;
                            $cart_meta['wpfunnels_checkout_id'] = $wpfunnels_checkout_id;
                        }
                    }

                    $cart_meta    = apply_filters( 'cl_cart_meta', $cart_meta );

                    $cart_details[ 'cart_meta' ] = serialize( $cart_meta );

                    $cart_details = apply_filters( 'cl_cart_details_before_update_ajax', $cart_details, $session_id );

                    if( $cart_details ) {
                        if( !is_null( $session_cart_details ) ) {
                            unset( $cart_details[ 'time' ] );
                            $wpdb->update(
                                $cl_cart_table,
                                $cart_details,
                                array( 'session_id' => $session_id )
                            );
                        } else {
                            $cart_details[ 'session_id' ] = $session_id;
                            $wpdb->insert(
                                $cl_cart_table,
                                $cart_details
                            );
                        }
                    } else {
                        $wpdb->delete(
                            $cl_cart_table,
                            array( 'session_id' => $session_id )
                        );
                        if( $provider === 'wc' && cl_is_wc_active() && !empty( WC()->session ) ) {
                            WC()->session->__unset( 'cl_wc_session_id' );
                        }
                        if( $provider === 'edd' && cl_is_edd_active() && !empty( EDD()->session ) ) {
                            EDD()->session->set( 'cl_edd_session_id', '' );
                        }
                        if( $provider === 'lp' && cl_is_lp_active() && !empty( LP()->session ) ) {
                            LP()->session->set( 'cl_lp_session_id', '', true );
                        }
                    }
                }
            }
        }
        wp_send_json_success();
    }

    /**
     * sanitize post data
     *
     * @return array
     * @since 1.0.0
     */
    public function sanitize_cart_post_data()
    {
        $sanitized_fields = array();
        if( isset( $_POST[ 'email' ] ) ) {
            $sanitized_fields[ 'email' ] = sanitize_email( $_POST[ 'email' ] );
        } else {
            $sanitized_fields[ 'email' ] = '';
        }

        if( isset( $_POST[ 'first_name' ] ) ) {
            $sanitized_fields[ 'first_name' ] = sanitize_text_field( $_POST[ 'first_name' ] );
        } else {
            $sanitized_fields[ 'first_name' ] = '';
        }

        if( isset( $_POST[ 'last_name' ] ) ) {
            $sanitized_fields[ 'last_name' ] = sanitize_text_field( $_POST[ 'last_name' ] );
        } else {
            $sanitized_fields[ 'last_name' ] = '';
        }

        if( isset( $_POST[ 'phone' ] ) ) {
            $sanitized_fields[ 'phone' ] = sanitize_text_field( $_POST[ 'phone' ] );
        } else {
            $sanitized_fields[ 'phone' ] = '';
        }

        if( isset( $_POST[ 'country' ] ) ) {
            $sanitized_fields[ 'country' ] = sanitize_text_field( $_POST[ 'country' ] );
        } else {
            $sanitized_fields[ 'country' ] = '';
        }

        if( isset( $_POST[ 'address' ] ) ) {
            $sanitized_fields[ 'address' ] = sanitize_text_field( $_POST[ 'address' ] );
        } else {
            $sanitized_fields[ 'address' ] = '';
        }

        if( isset( $_POST[ 'city' ] ) ) {
            $sanitized_fields[ 'city' ] = sanitize_text_field( $_POST[ 'city' ] );
        } else {
            $sanitized_fields[ 'city' ] = '';
        }

        if( isset( $_POST[ 'postcode' ] ) ) {
            $sanitized_fields[ 'postcode' ] = sanitize_text_field( $_POST[ 'postcode' ] );
        } else {
            $sanitized_fields[ 'postcode' ] = '';
        }

        if( isset( $_POST[ 'provider' ] ) ) {
            $sanitized_fields[ 'provider' ] = sanitize_text_field( $_POST[ 'provider' ] );
        } else {
            $sanitized_fields[ 'provider' ] = 'wc';
        }

        if( isset( $_POST[ 'wpfunnels_checkout_id' ] ) ) {
            $sanitized_fields[ 'wpfunnels_checkout_id' ] = sanitize_text_field( $_POST[ 'wpfunnels_checkout_id' ] );
        }

        return $sanitized_fields;
    }

    /**
     * @desc set woocommerce default required data while
     * going to the checkout field from email.
     *
     * @param $fields
     * @return mixed
     */
    function cl_set_checkout_required_info_wc( $fields )
    {
        $token = filter_input( INPUT_GET, 'cl_token', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if( cl_is_valid_token( $token ) ) {
            $token_data = cl_decode_token( $token );

            $fields[ 'billing' ][ 'billing_email' ][ 'default' ]      = $token_data[ 'email' ];
            $fields[ 'billing' ][ 'billing_first_name' ][ 'default' ] = $token_data[ 'first_name' ];
            $fields[ 'billing' ][ 'billing_last_name' ][ 'default' ]  = $token_data[ 'last_name' ];
            $fields[ 'billing' ][ 'billing_phone' ][ 'default' ]      = $token_data[ 'phone' ];
            $fields[ 'billing' ][ 'billing_address_1' ][ 'default' ]  = $token_data[ 'address' ];
            $fields[ 'billing' ][ 'billing_city' ][ 'default' ]       = $token_data[ 'city' ];
            $fields[ 'billing' ][ 'billing_postcode' ][ 'default' ]   = $token_data[ 'postcode' ];
        }

        return $fields;
    }

    /**
     * @desc set edd default required data while
     * going to the checkout field from email.
     *
     * @param $required_fields
     * @return mixed
     */
    function cl_set_checkout_required_info_edd( $required_fields )
    {
        $token = filter_input( INPUT_GET, 'cl_token', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if( cl_is_valid_token( $token ) ) {
            $token_data = cl_decode_token( $token );
            ?>
            <input type="text" id="cl_edd_email" value="<?php echo $token_data[ 'email' ]; ?>" hidden>
            <input type="text" id="cl_edd_first_name" value="<?php echo $token_data[ 'first_name' ]; ?>" hidden>
            <?php
        }
    }

    /**
     * Restore the cart data
     * from session_id
     *
     * @throws Exception
     * @since 1.0.0
     */
    public function restore_cart_data()
    {
        $token = filter_input( INPUT_GET, 'cl_token', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

        if( cl_is_valid_token( $token ) ) {
            $token_data = cl_decode_token( $token );

            if( isset( $token_data[ 'cl_session_id' ] ) ) {
                self::set_recoverd_cart_cookie();
                $session_id   = $token_data[ 'cl_session_id' ];
                $cart_details = $this->get_cart_details( $session_id );

                if( !is_null( $cart_details ) && $cart_details->status === 'abandoned' ) {
                    $cart_content = unserialize( $cart_details->cart_contents );
                    if( $cart_details->provider === 'wc' && !empty( WC()->session ) ) {
                        global $woocommerce;
                        $woocommerce->cart->empty_cart();
                        wc_clear_notices();
                        WC()->session->set( 'cl_wc_session_id', $session_id );
                        foreach( $cart_content as $item ) {
                            $product_id     = $item[ 'id' ];
                            $quantity       = $item[ 'quantity' ];
                            $variation_id   = $item[ 'variation_id' ];
                            $variation      = $item[ 'variation' ];
                            $cart_item_data = array(
                                'cl_wc_session_id' => $session_id
                            );
                            WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data );
                        }
                        if( isset( $token_data[ 'coupon_code' ] ) && !$woocommerce->cart->applied_coupons ) {
                            $coupon_code = $token_data[ 'coupon_code' ];

                            if( $token_data[ 'coupon_auto_apply' ] === 'yes' ) {
                                WC()->cart->add_discount( $coupon_code );
                            }
                        }
                    }

                    if( $cart_details->provider === 'edd' && !empty( EDD()->session ) ) {
                        EDD()->cart->empty_cart();
                        EDD()->session->set( 'cl_edd_session_id', $session_id );
                        foreach( $cart_content as $item ) {
                            edd_add_to_cart( $item[ 'id' ], $item[ 'options' ] );
                        }
                        if( isset( $token_data[ 'coupon_code' ] ) ) {
                            $coupon_code = $token_data[ 'coupon_code' ];

                            if( $token_data[ 'coupon_auto_apply' ] === 'yes' ) {
                                edd_set_cart_discount( $coupon_code );
                            }
                        }
                    }

                    if( $cart_details->provider === 'lp' && !empty( LP()->session ) ) {
                        LP()->cart->empty_cart();
                        LP()->session->set( 'cl_lp_session_id', $session_id, true );

                        foreach( $cart_content as $item ) {
                            LP()->cart->add_to_cart( $item[ 'id' ], 1 );
                        }
                    }
                }
            }
        }
        elseif( 'wpfunnels_checkout' === $token ) {
            self::set_recoverd_cart_cookie();
        }
    }


    /**
     * Set cookie to check if cart is recovered or not.
     */
    protected function set_recoverd_cart_cookie() {
        setcookie( 'cart_lift_recovered_cart', 'true', 0, '/' );
    }


    /**
     * Set cookie to check if cart is recovered or not.
     */
    protected function desctroy_recoverd_cart_cookie() {
        setcookie( 'cart_lift_recovered_cart', null, time() - 3600, '/' );
    }

    /**
     * @desc Removing discount code from woocommerce
     * checkout page.
     *
     * @param $coupon
     * @return mixed
     */
    public function cl_cart_totals_coupon_label( $coupon )
    {
        return esc_html__( 'Coupon', 'cart-lift' );
    }

    /**
     * @desc Removing discount code from edd
     * checkout page.
     *
     * @param $html
     * @param $discounts
     * @param $rate
     * @param $remove_url
     * @return string|void
     */
    public function cl_get_cart_discounts_html( $html, $discounts, $rate, $remove_url )
    {
        if( !$discounts ) {
            $discounts = EDD()->cart->get_discounts();
        }

        if( empty( $discounts ) ) {
            return;
        }

        $html = '';

        foreach( $discounts as $discount ) {
            $discount_id = edd_get_discount_id_by_code( $discount );
            $rate        = edd_format_discount_rate( edd_get_discount_type( $discount_id ), edd_get_discount_amount( $discount_id ) );

            $remove_url = add_query_arg(
                array(
                    'edd_action'    => 'remove_cart_discount',
                    'discount_id'   => $discount_id,
                    'discount_code' => $discount
                ),
                edd_get_checkout_uri()
            );

            $discount_html = '';
            $discount_html .= "<span class=\"edd_discount\">\n";
            $discount_html .= "<span class=\"edd_discount_rate\">&ndash;&nbsp;$rate</span>\n";
            $discount_html .= "<a href=\"$remove_url\" data-code=\"$discount\" class=\"edd_discount_remove\"></a>\n";
            $discount_html .= "</span>\n";

            $html .= apply_filters( 'edd_get_cart_discount_html', $discount_html, $discount, $rate, $remove_url );
        }

        return $html;
    }
}