<?php

class Cart_Lift_EDD_Actions extends Cart_Lift_Cart_Actions
{


    /**
     * EDD after product added to cart
     * action
     * @param $download_id
     * @param $options
     * @param $items
     * @since 1.0.0
     */
    function add_to_cart_action( $download_id, $options, $items )
    {

        // TODO: Implement add_to_cart_action() method.
	    $user_email = '';
	    if( is_user_logged_in() ) {
		    $user_id    = get_current_user_id();
		    $user_info  = get_userdata( $user_id );
		    $user_email = $user_info->user_email;
	    } else {
		    $user_email = isset( $_COOKIE[ 'cart_lift_user_email' ] ) ? $_COOKIE[ 'cart_lift_user_email' ] : '';
	    }
	    $this->save_cart_infos( $user_email, $this->provider );
    }


    /**
     * delete cart data if user
     * remove cart
     * @param $key
     * @param $item_id
     * @since 1.0.0
     */
    function delete_cart_action( $key, $item_id )
    {

        // TODO: Implement delete_to_cart_action() method.
        $user_email = '';
        if( is_user_logged_in() ) {
            $user_id    = get_current_user_id();
            $user_info  = get_userdata( $user_id );
            $user_email = $user_info->user_email;
        } else {
            $user_email = isset( $_COOKIE[ 'cart_lift_user_email' ] ) ? $_COOKIE[ 'cart_lift_user_email' ] : '';
        }
        $this->save_cart_infos( $user_email, $this->provider );
    }


    /**
     * manipulating item options before
     * adding to cart
     *
     * @param $item
     * @return mixed
     * @since 1.0.0
     */
    public function add_to_cart_item( $item )
    {
        $session_id = EDD()->session->get( 'cl_edd_session_id' );
        if( !$session_id ) {
            $session_id = md5( uniqid( wp_rand(), true ) );
            EDD()->session->set( 'cl_edd_session_id', $session_id );
        }
        if( isset( $item[ 'id' ] ) ) {
            $item[ 'options' ][ 'cl_edd_session_id' ] = $session_id;
        }
        return $item;
    }


    /**
     * EDD pre purchase
     * hook
     *
     * @param $payment_id
     * @since 1.0.0
     */
    public function edd_pre_complete_purchase( $payment_id )
    {
        if( isset( EDD()->session ) ) {
            $session_id = EDD()->session->get( 'cl_edd_session_id' );
            if( isset( $session_id ) && !empty( $session_id ) ) {
                update_post_meta( $payment_id, 'cl_edd_session_id', $session_id );
            }
        }
    }


    /**
     * EDD when purchase is marked
     * completed
     *
     * @param $payment_id
     * @param $payment
     * @param $customer
     * @since 1.0.0
     */
    public function update_cart_status_edd( $payment_id, $new_status, $old_status )
    {
        if( isset( $_COOKIE[ 'cart_lift_recovered_cart' ] ) && 'pending' === $new_status || 'complete' === $new_status ) {
            if( 'true' === $_COOKIE[ 'cart_lift_recovered_cart' ] ) {
                update_post_meta( $payment_id, 'cl_recovered_cart', 'yes' );
            } else {
                update_post_meta( $payment_id, 'cl_recovered_cart', 'no' );
            }
            $session_id = EDD()->session->get( 'cl_edd_session_id' );
            if( !$session_id ) {
                $session_id = get_post_meta( $payment_id, 'cl_edd_session_id', true );
            }
            if( $session_id ) {
                update_post_meta( $payment_id, 'cl_edd_session_id', $session_id );
                $cart_details = $this->get_cart_details( $session_id );
                if( !empty( $cart_details ) ){
                    $this->reinitialize_cart_data( $session_id, $payment_id, $cart_details, $this->provider );
                }
            }
        }
    }


    /**
     * show gdpr message after
     * the email form
     *
     * @since 1.0.0
     */
    public function purchase_form_after_email()
    {
        $gdpr_enabled     = cl_get_general_settings_data( 'enable_gdpr' );
        if( $gdpr_enabled ) {
            if( isset( $_COOKIE[ 'cart_lift_skip_tracking_data' ] ) ) {
                if( $_COOKIE[ 'cart_lift_skip_tracking_data' ] ) {
                    $gdpr_enabled = 0;
                } else {
                    $gdpr_enabled = 1;
                }
            } else {
                $gdpr_enabled = 1;
            }
        }
        if( $gdpr_enabled ) {
            echo "<span id='cl_gdpr_message'><span>" .  cl_get_general_settings_data( 'gdpr_text' )  . "<a style='cursor: pointer' id='cl_gdpr_no_thanks'>".__( 'No thanks', 'cart-lift' )."</a></span></span>";
        }
    }


    /**
     * @desc Delete different session cart data [status = processing]
     * of same user on same product purchase
     * @since 3.1.2
     * @param $order_id
     * @return void
     */
    public function delete_other_session_cart_data( $payment_id, $payment ) {
        global $wpdb;
        $cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;

        $payment_meta = $payment->get_meta( '_edd_payment_meta' );
        $downloads    = isset( $payment_meta[ 'downloads' ] ) ? array_column( $payment_meta[ 'downloads' ], 'id' ) : [];
        $ordered_usr_email = isset( $payment_meta[ 'email' ] ) ? $payment_meta[ 'email' ] : '';

        $query = $wpdb->prepare( "SELECT `session_id`, `cart_contents` FROM {$cl_cart_table} WHERE `status` = %s AND `email` = %s AND `provider` = %s", 'processing', $ordered_usr_email, 'edd' );
        $carts = $wpdb->get_results( $query );

        if ( !empty( $carts ) ) {
            foreach ( $carts as $cart ) {
                if ( isset( $cart->session_id ) && '' !== $cart->session_id && isset( $cart->cart_contents ) && '' !== $cart->cart_contents ) {
                    $cart_contents = unserialize( $cart->cart_contents ); //phpcs:ignore
                    $cart_content_ids = array_column( $cart_contents, 'id' );

                    if ( array_intersect( $cart_content_ids, $downloads ) ) {
                        $wpdb->delete(
                            $cl_cart_table,
                            array(
                                'session_id' => sanitize_key( $cart->session_id )
                            )
                        );
                    }
                }
            }
        }
    }
}