<?php

/**
 * Class Cart Lift Cron
 */
class Cart_Lift_Cron
{

    /**
     * email list scheduler
     *
     * @internal
     */
    public function cart_lift_process_scheduled_email_hook()
    {
        global $wpdb;
        $cl_cart_table             = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $cl_email_table            = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;
        $cl_campaign_history_table = $wpdb->prefix . CART_LIFT_CAMPAIGN_HISTORY_TABLE;
        $current_time              = current_time( CART_LIFT_DATETIME_FORMAT );

        $general = cl_get_general_settings();
        if ( isset( $general[ 'cart_abandonment_time' ] ) ) {
            $abandoned_time = $general[ 'cart_abandonment_time' ];
        }
        else {
            $abandoned_time = 15;
        }
        
        /**
         * send emails to abandoned cart emails
         * check if there are any scheduled email on the email history table
         * that are not sent yet. if there are any scheduled email and the
         * current time exceeds the schedule time then send the emails to that user.
         * if any email can not be sent due to any error or system fail,
         * it will try to send it to the next schedule run
         *
         */
        $scheduled_email_query = "SELECT h.id, h.campaign_id, h.session_id, h.email_sent, e.email_subject, e.email_body, e.email_meta, c.email, c.status, c.cart_contents, c.cart_total, c.cart_meta, c.provider, c.time, c.id as cart_id from $cl_campaign_history_table as h
            INNER JOIN $cl_email_table as e ON h.campaign_id = e.id
            INNER JOIN $cl_cart_table as c ON h.session_id = c.session_id
            WHERE h.email_sent = 0 AND c.unsubscribed = 0 AND h.schedule_time <= %s AND c.status = %s AND c.schedular_status = %s";

        $schedule_emails = array();

        try {
            $schedule_emails = $wpdb->get_results( $wpdb->prepare( $scheduled_email_query, $current_time, 'abandoned', 'active' ) );
        }
        catch ( Exception $e ) {
        }

        try {
            foreach ( $schedule_emails as $schedule ) {
                $process_email = apply_filters( 'cl_before_process_email', true, $schedule );

                if( $process_email ) {
                    $email_data    = cl_get_scheduled_log_data( $schedule );
                    $track_purchased_product_status = cl_get_general_settings_data( 'disable_purchased_products_campaign' );
                    $is_product_ordered = false;

                    if ( $track_purchased_product_status ) {
                        $orders = cl_get_orders_by_email( $email_data->email, $email_data->provider, $email_data->time );
                        $is_product_ordered = cl_check_if_product_is_ordered( $orders, $email_data->cart_contents, $email_data->provider );
                    }

                    if ( $is_product_ordered ) {
                        $wpdb->update(
                            $cl_campaign_history_table,
                            array(
                                'email_sent' => -1,
                            ),
                            array(
                                'id' => $schedule->id
                            )
                        );
                        $wpdb->update(
                            $cl_cart_table,
                            array(
                                'status' => 'discard',
                            ),
                            array(
                                'session_id' => $email_data->session_id
                            )
                        );
                    }
                    else {
                        $is_email_sent = cl_send_email_templates( $email_data );

                        if ( $is_email_sent ) {
                            $wpdb->update(
                                $cl_campaign_history_table,
                                array(
                                    'email_sent' => 1,
                                ),
                                array(
                                    'id' => $schedule->id
                                )
                            );
                            $wpdb->update(
                                $cl_cart_table,
                                array(
                                    'last_sent_email' => $schedule->campaign_id,
                                ),
                                array(
                                    'session_id' => $email_data->session_id
                                )
                            );
                        }
                    }
                }
            }
        }
        catch ( Exception $e ) {
        }

        do_action( 'cl_after_email_sent' );

        self::cl_update_cart_status( $cl_cart_table, $cl_email_table, $cl_campaign_history_table, $abandoned_time, $current_time, $general );
    }


    /**
     * Delete data after x days
     *
     */
    public function cart_lift_x_days_cart_remove(){
        $general  = get_option( 'cl_general_settings' );
        $statuses = [ 'abandoned', 'recovered', 'completed', 'processing', 'discard', 'lost' ];

        $in_status_condition_array  = array_filter( $statuses, function ( $status ) use ( $general ) {
            return !empty( $general[ $status ] );
        } );
        $in_status_condition_string = implode( ',', array_map( function ( $status ) {
            return "'" . esc_sql( $status ) . "'";
        }, $in_status_condition_array ) );

        if ( !empty( $general[ 'cart_expiration_time' ] ) ) {
            $cart_expiration_time = (int)$general[ 'cart_expiration_time' ];
            global $wpdb;
            $cl_cart_table  = $wpdb->prefix . CART_LIFT_CART_TABLE;
            $current_time   = current_time( 'mysql', false );
            $day_in_seconds = $cart_expiration_time * 86400;

            if ( !empty( $in_status_condition_string ) ) {
                $query   = $wpdb->prepare(
                    "SELECT * FROM $cl_cart_table WHERE status IN ($in_status_condition_string)"
                );
                $results = $wpdb->get_results( $query );

                foreach ( $results as $data_value ) {
                    $X_time = date( 'Y-m-d H:i:s', strtotime( $data_value->time ) + $day_in_seconds );
                    if ( $X_time <= $current_time ) {
                        $wpdb->delete( $cl_cart_table, [ 'id' => $data_value->id ] );
                    }
                }
            }
        }
    }


    /**
     * @desc Update cart status & schedule email list for that cart
     * update the cart status if the current time is greater than the cut-off time
     * default cut-off time is 15 minutes. and then schedule the upcoming email list
     * for that user
     * @since 3.1.2
     * @param $cl_cart_table
     * @param $cl_email_table
     * @param $cl_campaign_history_table
     * @param $abandoned_time
     * @param $current_time
     * @param $general
     * @return void
     */
    public static function cl_update_cart_status( $cl_cart_table, $cl_email_table, $cl_campaign_history_table, $abandoned_time, $current_time, $general ) {
        global $wpdb;


      $general_settings  = get_option( 'cl_general_settings' );

      $excluded_products_id = $general_settings['cl_excluded_products'] ?? '';
      $enable_excluded_products = $general_settings['enable_cl_exclude_products'] ?? '';

        $excluded_categories_id = $general_settings['cl_excluded_categories'] ?? '';
        $enable_excluded_categories = $general_settings['enable_cl_exclude_categories'] ?? '';

        $cart_session_ids = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * from $cl_cart_table WHERE status = %s and schedular_status = %s and ADDDATE(`time`, INTERVAL %d MINUTE)<=%s",
                'processing', 'active',
                $abandoned_time,
                $current_time
            )
        );


        $all_active_templates = $wpdb->get_results( "SELECT * FROM $cl_email_table WHERE active=1" );
        foreach ( $cart_session_ids as $item ) {
            $email_data    = cl_get_scheduled_log_data( $item );
            $email = isset( $email_data->email ) ? $email_data->email : '';
            $provider = isset( $email_data->provider ) ? $email_data->provider : '';
            $time = isset( $email_data->time ) ? $email_data->time : '';
            $cart_contents = isset( $email_data->cart_contents ) ? $email_data->cart_contents : '';
            $track_purchased_product_status = cl_get_general_settings_data( 'disable_purchased_products_campaign' );
            $is_product_ordered = false;

            $item_cart_contents = !empty( $item->cart_contents  ) ? unserialize( $item->cart_contents ) : '';
            foreach ( $item_cart_contents as $value ) {
                if ( empty( $value['is_new'] ) && !empty( $value[ 'id' ] ) ) {
                    if ( ( !empty( $enable_excluded_products ) && '1' == $enable_excluded_products ) && in_array( $value[ 'id' ], $excluded_products_id, true ) ) {
                        cl_remove_abandoned_cart_record( $email, $email_data->session_id );
                        return;
                    }

                    if ( ( !empty( $enable_excluded_categories ) && '1' == $enable_excluded_categories ) && in_array( $value[ 'id' ], $excluded_categories_id, true ) ) {
                        cl_remove_abandoned_cart_record( $email, $email_data->session_id );
                        return;
                    }
                }
            }

            if ( $track_purchased_product_status ) {
                $orders = cl_get_orders_by_email( $email, $provider, $time );
                $is_product_ordered = cl_check_if_product_is_ordered( $orders, $cart_contents, $provider );
            }

            if ( $is_product_ordered ) {
                $wpdb->update(
                    $cl_cart_table,
                    array(
                        'status' => 'discard',
                    ),
                    array(
                        'session_id' => $email_data->session_id ?? ''
                    )
                );
            }
            else {
                if ( isset( $item->session_id ) ) {

                    $notify_abandon_cart = isset( $general[ 'notify_abandon_cart' ] ) ? $general[ 'notify_abandon_cart' ] : 0;
                    if ( $notify_abandon_cart ) {
                        if ( $item->provider === 'wc' ) {
                            $mailer = WC()->mailer();
                            do_action( 'cl_trigger_abandon_cart_email', $item );
                        }
                        if ( $item->provider === 'edd' ) {
                            do_action( 'cl_trigger_abandon_cart_email_edd', $item );
                        }
                    }

                    $webhook = cl_get_general_settings_data( 'enable_webhook' );
                    if ( $webhook ) {
                        $cart_contents = isset( $item->cart_contents ) ? $item->cart_contents : '';
                        $cart_total    = isset( $item->cart_total ) ? $item->cart_total : '';
                        $provider      = isset( $item->provider ) ? $item->provider : '';
                        $first_name = '';
                        $last_name  = '';
                        if ( isset( $item->cart_meta ) && ! empty( $item->cart_meta ) ) {
                            $cart_meta = maybe_unserialize( $item->cart_meta );
                            if ( is_array( $cart_meta ) ) {
                                $first_name = isset( $cart_meta['first_name'] ) ? sanitize_text_field( $cart_meta['first_name'] ) : '';
                                $last_name  = isset( $cart_meta['last_name'] )  ? sanitize_text_field( $cart_meta['last_name'] )  : '';
                            }
                        }

                        if ( ( empty( $first_name ) || empty( $last_name ) ) && ! empty( $email ) ) {
                            $user = get_user_by( 'email', $email );
                            if ( $user && $user instanceof WP_User ) {
                                if ( empty( $first_name ) ) {
                                    $first_name = get_user_meta( $user->ID, 'first_name', true );
                                    $first_name = sanitize_text_field( $first_name );
                                }
                                if ( empty( $last_name ) ) {
                                    $last_name = get_user_meta( $user->ID, 'last_name', true );
                                    $last_name = sanitize_text_field( $last_name );
                                }
                            }
                        }

                        $webhook_data = array(
                            'first_name'    => $first_name,
                            'last_name'     => $last_name,
                            'email'         => isset( $item->email ) ? $item->email : '',
                            'status'        => 'abandoned',
                            'cart_total'    => $cart_total,
                            'provider'      => $provider,
                            'product_table' => cl_get_email_product_table( $cart_contents, $cart_total, $provider, false, false ),
                        );
                        cl_trigger_webhook( $webhook_data );
                    }


                    $wpdb->update(
                        $cl_cart_table,
                        array(
                            'status' => 'abandoned',
                        ),
                        array(
                            'session_id' => $item->session_id
                        )
                    );


                    // remove guest carts
                    $remove_guest_carts = isset( $general[ 'remove_carts_for_guest' ] ) ? $general[ 'remove_carts_for_guest' ] : 0;
                    if ( $remove_guest_carts && !$item->email ) {
                        $wpdb->delete(
                            $cl_cart_table,
                            array(
                                'session_id' => $item->session_id
                            )
                        );
                    }

                    self::cl_schedule_email_templates( $all_active_templates, $cl_campaign_history_table, $cl_email_table, $current_time, $item );
                }
            }
        }
    }


    /**
     * @desc Schedule email list for
     * abandoned carts
     * @since 3.1.2
     * @param $all_active_templates
     * @param $cl_campaign_history_table
     * @param $cl_email_table
     * @param $current_time
     * @return void
     */
    public static function cl_schedule_email_templates( $all_active_templates, $cl_campaign_history_table, $cl_email_table, $current_time, $item ) {
        global $wpdb;

        foreach ( $all_active_templates as $template ) {
            $session_id = isset( $item->session_id ) ? $item->session_id : '';
            $schedule_campaigns = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM  $cl_email_table as e INNER JOIN $cl_campaign_history_table as h ON e.id = h.campaign_id WHERE h.session_id = %s", sanitize_text_field( $session_id ) ) );
            $schedule_column    = array_column( $schedule_campaigns, 'campaign_id' );

            $template_id = isset( $template->id ) ? $template->id : '';
            $template_frequency = isset( $template->frequency ) ? $template->frequency : '';
            $template_unit = isset( $template->unit ) ? $template->unit : '';

            if ( !in_array( $template_id, $schedule_column ) ) {
                $schedule_time = '+' . $template_frequency . ' ' . ucfirst( $template_unit ) . 'S';
                $schedule_time = gmdate( CART_LIFT_DATETIME_FORMAT, strtotime( $current_time . $schedule_time ) );

                if ( $item->email ) {
                    $wpdb->replace(
                        $cl_campaign_history_table,
                        array(
                            'campaign_id'   => isset( $template->id ) ? $template->id : '',
                            'session_id'    => isset( $item->session_id ) ? $item->session_id : '',
                            'schedule_time' => $schedule_time,
                        )
                    );
                }
            }
        }
    }
}
