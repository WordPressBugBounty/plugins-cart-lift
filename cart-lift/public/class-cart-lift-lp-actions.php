<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'Cart_Lift_LearnPress_Actions' ) ) {
    class Cart_Lift_LearnPress_Actions extends Cart_Lift_Cart_Actions
    {
        /**
         * LearnPress after product added to cart
         * action
         */
        function cl_learnpress_checkout_cart_action()
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
	     * save custom meta for
	     * payment
	     *
	     * @param $order_id
	     */
        public function cl_learnpress_action_payment_save( $order_id )
        {
	        $session_id = LP()->session->get( 'cl_lp_session_id' );
	        update_post_meta( $order_id, 'cl_lp_session_id', $session_id );
	        if( isset( $_COOKIE[ 'cart_lift_recovered_cart' ] ) ) {
		        $is_recovered_cart = $_COOKIE[ 'cart_lift_recovered_cart' ];
		        if( $is_recovered_cart == 'true' ) {
			        update_post_meta( $order_id, 'cl_recovered_cart', 'yes' );
		        } else {
			        update_post_meta( $order_id, 'cl_recovered_cart', 'no' );
		        }
	        }
	        return $order_id;
        }


	    /**
	     * LearnPress when purchase is marked
	     * completed
	     *
	     * @param $order_id
	     * @param $old_status
	     * @param $new_status
	     */
        public function cl_learnpress_update_cart_status( $order_id, $old_status, $new_status )
        {
	        if( $new_status == 'completed' ) {
		        $order = learn_press_get_order( $order_id );
		        $session_id = get_post_meta($order_id, 'cl_lp_session_id', true);
		        $cart_details = $this->get_cart_details($session_id);

		        if(!is_null($cart_details)) {
			        $cart_details->cart_total = $order->get_total();
			        $this->reinitialize_cart_data($session_id, $order_id, $cart_details, $this->provider);
		        }
	        }
        }
    }
}