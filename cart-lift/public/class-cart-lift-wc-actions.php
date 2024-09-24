<?php


class Cart_Lift_WC_Actions extends Cart_Lift_Cart_Actions {

    function add_to_cart_action()
    {
        // TODO: Implement add_to_cart_action() method.
	    $user_email = '';
	    if(is_user_logged_in()) {
		    $user_id = get_current_user_id();
		    $user_info = get_userdata($user_id);
		    $user_email = $user_info->user_email;
	    }else {
		    $user_email = isset( $_COOKIE['cart_lift_user_email']) ? $_COOKIE['cart_lift_user_email'] : '';
	    }
	    $this->save_cart_infos($user_email, $this->provider);
    }


    /**
     * Delete cart data if
     * it is normal order
     *
     * @param $order_id
     * @since 1.0.0
     */
    public function delete_cart_data( $order_id ) {
	    if ( !empty( WC()->session ) ) {
		    $order      = wc_get_order( $order_id );
		    $status     = $order->get_status();
		    $session_id = WC()->session->get( 'cl_wc_session_id' );

		    if ( !empty( $session_id ) ) {
			    $order->update_meta_data( 'cl_wc_session_id', $session_id );

			    if ( isset( $_COOKIE[ 'cart_lift_recovered_cart' ] ) ) {
				    $is_recovered_cart = $_COOKIE[ 'cart_lift_recovered_cart' ];
				    if ( $is_recovered_cart == 'true' ) {
					    $recovery_status = 'yes';
				    }
				    else {
					    $recovery_status = 'no';
				    }
				    $order->update_meta_data( 'cl_recovered_cart', $recovery_status );
			    }

			    $cart_details = $this->get_cart_details( $session_id );

			    if ( $status == 'completed' || $status == 'processing' ) {
				    $cart_details->cart_total = $order->get_total();
				    $this->reinitialize_cart_data( $session_id, $order_id, $cart_details );
			    }
		    }
		    $order->save();
	    }
    }


    /**
     * WC add to cart action
     * with email from
     * session
     *
     * @since 1.0.0
     */
	public function add_to_cart_actions_without_email() {
		$session_id = !empty( WC()->session ) ? WC()->session->get( 'cl_wc_session_id' ) : null;
		if ( !empty( $session_id ) ) {
			$session_cart_details = $this->get_cart_details( $session_id );
			if ( !is_null( $session_cart_details ) ) {
				$this->save_cart_infos( $session_cart_details->email, 'wc' );
			}
		}
	}


    /**
     * Update cart data status
     *
     * @param $order_id
     * @param $old_status
     * @param $new_status
     * @since 1.0.0
     */
    public function change_cart_status( $order_id, $old_status, $new_status ) {
        if( 'completed' === $new_status || 'processing' === $new_status || 'on-hold' === $new_status ) {
            $order = wc_get_order( $order_id );
            $session_id = $order->get_meta( 'cl_wc_session_id' );
            $cart_details = $this->get_cart_details($session_id);

            if( !is_null( $cart_details ) ) {
                $cart_details->cart_total = $order->get_total();
                $this->reinitialize_cart_data( $session_id, $order_id, $cart_details );
            }
        }
    }


    /**
     * unsubscribe abandon cart
     * emails
     *
     * @since 1.0.0
     */
    public function unsubscribe_abandon_cart_emails() {
        $unsubscribe  = filter_input( INPUT_GET, 'unsubscribe', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $unsubscribe_key = filter_input( INPUT_GET, 'unsubscribe_key', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( ($unsubscribe === 'Yes')) {
            global $wpdb;
            $cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;
            $key = cl_decrypt_key( $unsubscribe_key );
            if(isset($key)) {
                $wpdb->update(
                    $cl_cart_table,
                    array( 'unsubscribed' => 1 ),
                    array( 'id' => $key )
                );
                wp_die( esc_html__( 'You have successfully unsubscribed from our email list.', 'cart-lift' ), esc_html__( 'Unsubscribed', 'cart-lift' ) );
            }
        }
    }


    /**
     * remove gdpr notice
     *
     * @since 1.0.0
     */
	public function remove_gdpr_notice() {
		check_ajax_referer( 'cart-lift-disable-gdpr', 'security' );
		global $wpdb;
		$cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;

		if ( isset( $_POST[ 'provider' ] ) ) {
			if ( $_POST[ 'provider' ] === 'wc' && !empty( WC()->session ) ) {
				$session_id = WC()->session->get( 'cl_wc_session_id' );

				if ( !empty( $session_id ) ) {
					$wpdb->delete( $cl_cart_table, array( 'session_id' => sanitize_key( $session_id ) ) );
				}
			}

			setcookie( 'cart_lift_skip_tracking_data', 'true', 0, '/' );
		}
		wp_send_json_success();
	}


    /**
     * @desc Delete different session cart data [status = processing]
     * of same user on same product purchase
     * @since 3.1.2
     * @param $order_id
     * @return void
     */
    public function delete_other_session_cart_data( $order_id ) {
        global $wpdb;
        $cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $order = wc_get_order( $order_id );
        $ordered_product_ids = [];
        $ordered_usr_email = $order->get_billing_email();

        $query = $wpdb->prepare( "SELECT `session_id`, `cart_contents` FROM {$cl_cart_table} WHERE `status` = %s AND `email` = %s AND `provider` = %s", 'processing', $ordered_usr_email, 'wc' );
        $carts = $wpdb->get_results( $query );

        if ( !empty( $carts ) ) {
            $order_items = $order->get_items();
            foreach ( $order_items as $item ) {
                $item_data = $item->get_data();
                if ( isset( $item_data[ 'product_id' ] ) ) {
                    $ordered_product_ids[] = $item_data[ 'product_id' ];
                }

            }

            foreach ( $carts as $cart ) {
                if ( isset( $cart->session_id ) && '' !== $cart->session_id && isset( $cart->cart_contents ) && '' !== $cart->cart_contents ) {
                    $cart_contents = unserialize( $cart->cart_contents ); //phpcs:ignore
                    $cart_content_ids = array_column( $cart_contents, 'id' );

                    if ( array_intersect( $cart_content_ids, $ordered_product_ids ) ) {
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
