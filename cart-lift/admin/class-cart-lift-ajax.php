<?php
/**
 * Class Cart_Lift_Ajax
 */
class Cart_Lift_Ajax
{

	public static function init()
	{

		$validations = array(
			'logged_in' => true,
			'user_can'  => 'manage_options',
		);

		wp_ajax_helper()->handle( 'toggle-email-template-status' )
		                ->with_callback( array( 'Cart_Lift_Ajax', 'toggle_email_template_status' ) )
		                ->with_validation( $validations );


		wp_ajax_helper()->handle( 'send-preview-email' )
		                ->with_callback( array( 'Cart_Lift_Ajax', 'send_preview_email' ) )
		                ->with_validation( $validations );

		wp_ajax_helper()->handle( 'get-analytics-data' )
		                ->with_callback( array( 'Cart_Lift_Ajax', 'get_analytics_data' ) )
		                ->with_validation( $validations );

		wp_ajax_helper()->handle( 'set-other-smtp-data' )
		                ->with_callback( array( 'Cart_Lift_Ajax', 'set_smtp_data' ) )
		                ->with_validation( $validations );

		wp_ajax_helper()->handle( 'test-smtp-data' )
		                ->with_callback( array( 'Cart_Lift_Ajax', 'test_smtp_data' ) )
		                ->with_validation( $validations );

		wp_ajax_helper()->handle( 'general-save-form' )
		                ->with_callback( array( 'Cart_Lift_Ajax', 'save_general_settings' ) )
		                ->with_validation( $validations );

		wp_ajax_helper()->handle( 'campaign-copy-setup' )
		                ->with_callback( array( 'Cart_Lift_Ajax', 'campaign_copy_setup' ) )
		                ->with_validation( $validations );

		wp_ajax_helper()->handle( 'enable-cl-smtp' )
		                ->with_callback( array( 'Cart_Lift_Ajax', 'enable_cl_smtp' ) )
		                ->with_validation( $validations );

		wp_ajax_helper()->handle( 'email-popup-submit' )
		                ->with_callback( array( 'Cart_Lift_Ajax', 'email_popup_submit' ) )
		                ->with_validation( $validations );

		wp_ajax_helper()->handle( 'hide-paddle-notice' )
		                ->with_callback( array( 'Cart_Lift_Ajax', 'hide_paddle_notice' ) )
		                ->with_validation( $validations );
		wp_ajax_helper()->handle( 'run-manual-recovery' )
		                ->with_callback( array( 'Cart_Lift_Ajax', 'run_manual_recovery' ) )
		                ->with_validation( $validations );

        wp_ajax_helper()->handle( 'cl-recaptcha-v3' )
			            ->with_callback( array( 'Cart_Lift_Ajax', 'set_or_update_recaptcha_settings' ) )
			            ->with_validation( $validations );

        wp_ajax_helper()->handle( 'cl-update-schedular-status' )
			            ->with_callback( array( 'Cart_Lift_Ajax', 'cl_update_schedular_status' ) )
			            ->with_validation( $validations );
	}

	/**
	 * Chart data
	 *
	 * @param $payload
	 * @return array
	 */
	public static function get_analytics_data( $payload )
	{
		$range      = $payload[ 'range' ];
		$date_start = $payload[ 'date_start' ];
		$date_end   = $payload[ 'date_end' ];

		$data = cl_get_analytics_data( $range, $date_start, $date_end );
		return array(
			'success' => true,
			'data'    => $data
		);
	}


	/**
	 * Toggle the template status
	 *
	 * @param $payload
	 * @return array
	 */
	public static function toggle_email_template_status( $payload )
	{
		global $wpdb;
		$cl_email_templates_table = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;
		$status                   = $payload[ 'status' ] === 'on' ? 0 : 1;
		$message                  = $payload[ 'status' ] === 'on' ? __( 'Deactivated', 'cart-lift' ) : __( 'Activated', 'cart-lift' );
		$current_status           = $payload[ 'status' ] === 'on' ? 'off' : 'on';
		$wpdb->update(
			$cl_email_templates_table,
			array(
				'active' => $status
			),
			array(
				'id' => $payload[ 'id' ]
			)
		);

		return array(
			'message'        => $message,
			'current_status' => $current_status,
			'value'          => $status
		);
	}


	/**
	 * send test email
	 *
	 * @param $payload
	 * @return array
	 */
	public static function send_preview_email( $payload )
	{
		$payload[ 'email_subject' ]       = stripslashes( $payload[ 'email_subject' ] );
		$payload[ 'email_header_text' ]   = stripslashes( $payload[ 'email_header_text' ] );
		$payload[ 'email_checkout_text' ] = stripslashes( $payload[ 'email_checkout_text' ] );

		$sent_email = cl_send_email_templates( null, $payload, true );
		return [ 'success' => $sent_email ];
	}

	/**
	 * save smtp data
	 *
	 * @param $payload
	 * @return array
	 */
	public static function set_smtp_data( $payload )
	{
		$extract = base64_decode( $payload[ 'data' ] );
		$modify  = str_replace( "%40", "@", $extract );

		$data    = explode( '&', $modify );
		$options = array();
		foreach ( $data as $dtvalue ) {
			$data_explode    = explode( '=', $dtvalue );
			$key             = $data_explode[ 0 ];
			$val             = $data_explode[ 1 ];
			$options[ $key ] = $val;
		}

		$mailer   = new Cart_Lift_Mailer();
		$response = $mailer->cart_lift_submit_form( $options );
		wp_send_json( $response );
	}


	/**
	 * test smtp data
	 *
	 * @param $payload
	 * @return array
	 */
	public static function test_smtp_data( $payload )
	{
		$test_email = $payload[ 'data' ];
		$mailer     = new Cart_Lift_Mailer();
		$response   = $mailer->cart_lift_mail_test( $test_email );
		wp_send_json( $response );
	}


	/**
	 * save general data
	 *
	 * @param $payload
	 * @return array
	 * @since 1.0.0
	 */
    public static function save_general_settings( $payload ) {
        global $wp_roles;
        wp_parse_str( $payload[ 'data' ], $params );
        
        $default_options = apply_filters( 'cl_default_general_settings', [
            'cart_tracking'                       => 1,
            'remove_carts_for_guest'              => 0,
            'disable_purchased_products_campaign' => 0,
            'notify_abandon_cart'                 => 0,
            'notify_recovered_cart'               => 0,
            'manually_recovered_cart'             => 0,
            'enable_smtp'                         => 0,
            'enable_webhook'                      => 0,
            'enable_weekly_report'                => 0,
            'cart_webhook'                        => '',
            'weekly_report_start_day'             => '',
            'weekly_report_email'                 => '',
            'weekly_report_email_from'            => '',
            'weekly_report_email_body'            => __( 'Please find the attached weekly report' ),
            'enable_gdpr'                         => 1,
            'gdpr_text'                           => __( 'Your email address will help us support your shopping experience throughout the site. Please check our Privacy Policy to see how we use your personal data.', 'cart-lift' ),
            'cart_expiration_time'                => 30,
            'cart_abandonment_time'               => 15,
            'disable_branding'                    => 0,
            'recovered'                           => 0,
            'abandoned'                           => 0,
            'completed'                           => 0,
            'lost'                                => 0,
            'processing'                          => 0,
            'discard'                             => 0,
            'enable_cart_expiration'              => 0,
            'enable_cl_exclude_products'          => 0,
            'enable_cl_exclude_categories'          => 0,
            'enable_cl_exclude_countries'          => 0,
        ] );


        foreach ( $wp_roles->roles as $role_key => $role_value ) {
            $default_options[ $role_key ] = 0;
        }
        
        $options                           = array_merge( $default_options, array_intersect_key( $params, $default_options ) );
        $options[ 'cl_excluded_products' ] = $params[ 'cl_excluded_products' ] ?? [];
        $options[ 'cl_excluded_categories' ] = $params[ 'cl_excluded_categories' ] ?? [];
        $options[ 'cl_excluded_countries' ] = $params[ 'cl_excluded_countries' ] ?? [];

        update_option( 'cl_general_settings', $options );
        
        return [
            'status'  => 'success',
            'message' => __( 'Successfully saved', 'cart-lift' ),
        ];
    }




	/**
	 * Campaign copy setup
	 *
	 * @param $payload
	 * @return array
	 * @since 1.0.0
	 */
	public static function campaign_copy_setup( $payload )
	{

		global $wpdb;
		$cl_email_table = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;
		$id             = $payload[ 'data' ];
		$result         = $wpdb->get_results( "INSERT INTO $cl_email_table (template_name, email_subject, email_body, frequency, unit, active, email_meta, created_at) SELECT template_name, email_subject, email_body, frequency, unit, active, email_meta, created_at FROM $cl_email_table WHERE id = $id" );

		return array(
			'status'  => 'success',
			'message' => __('Successfully saved', 'cart-lift'),
		);
	}

	/**
	 * SMTP Switcher
	 *
	 * @param $payload
	 * @return array
	 * @since 1.0.0
	 */
	public static function enable_cl_smtp( $payload )
	{

		$data = $payload[ 'data' ];
		update_option( 'enable_cl_smtp', $data );

		return array(
			'data'    => $data,
			'message' => __('Successfully saved', 'cart-lift'),
		);
	}

	/**
	 * Popup submit
	 *
	 * @param $payload
	 * @return array
	 * @since 1.0.0
	 */
	public static function email_popup_submit( $payload )
	{
		$payload[ 'enabler' ] = $payload[ 'enabler' ] ? 1 : 0;
		update_option( 'cl_popup_settings', $payload );
		return array(
			'success' => true,
			'message' => __('Successfully saved', 'cart-lift'),
		);
	}

	/**
	 * hide the paddle notice
	 *
	 * @param $payload
	 * @return array
	 */
	public static function hide_paddle_notice( $payload )
	{
		update_option(
			'cl_paddle_notice', array(
				'show' => false,
				'time' => current_time( CART_LIFT_DATETIME_FORMAT )
			)
		);
		return array(
			'success' => true,
		);
	}

	/**
	 * Run manual recovery
	 *
	 * @param $payload
	 * @return array
	 */
	public static function run_manual_recovery( $payload ){
		$cart_id = isset($payload['cart_id']) ? $payload['cart_id'] : 0;
		$user_email = isset($payload['user_email']) ? $payload['user_email'] : '';
		$session_id = isset($payload['session_id']) ? $payload['session_id'] : '';
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
         $scheduled_email_query = "SELECT h.id, h.campaign_id, h.session_id, h.email_sent, e.email_subject, e.email_body, e.email_meta, c.email, c.status, c.cart_contents, c.cart_total, c.cart_meta, c.provider, c.id as cart_id from $cl_campaign_history_table as h
             INNER JOIN $cl_email_table as e ON h.campaign_id = e.id
             INNER JOIN $cl_cart_table as c ON h.session_id = c.session_id
             WHERE h.email_sent = 0 AND c.unsubscribed = 0 AND c.status = %s AND c.email = %s AND c.session_id = %d AND c.schedular_status = %s ORDER BY h.schedule_time ASC limit 1";

         $schedule_emails = array();
         try {
             $schedule_emails = $wpdb->get_results( $wpdb->prepare( $scheduled_email_query, 'abandoned',$user_email,$session_id, 'active' ) );
		}
         catch ( Exception $e ) {
         }

         try {
             foreach ( $schedule_emails as $schedule ) {
		 		if( $cart_id && $cart_id == $schedule->cart_id || $user_email && $user_email == $schedule->email || $session_id && $session_id == $schedule->session_id ){
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
                                $current_time_stamp = current_time('timestamp');
                                $one_minute_later = strtotime('-1 minute', $current_time_stamp);
                                $formatted_time = date('Y-m-d H:i:s', $one_minute_later);
		 						$wpdb->update(
		 							$cl_campaign_history_table,
		 							array(
		 								'email_sent' => 1,
                                        'schedule_time' => $formatted_time,
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
         }
         catch ( Exception $e ) {
			
         }

         do_action( 'cl_after_email_sent' );
		 Cart_lift_Cron::cl_update_cart_status( $cl_cart_table, $cl_email_table, $cl_campaign_history_table, $abandoned_time, $current_time, $general );
		
		if( count( $schedule_emails)  > 0 ) {
			return array(
				'success' => true,
				'message' => __('Email has been sent successfully.', 'cart-lift'),
			);
		
		} else{
			return array(
				'success' => true,
				'message' => __('No email found to send.', 'cart-lift'),
			);
		}
	}

    /**
     * Sets or updates reCAPTCHA settings.
     *
     * This function will set or update the reCAPTCHA settings including the enable status,
     * site key, secret key, and score. If values are not provided or are empty, defaults
     * will be applied where appropriate.
     *
     * @param array $payload Associative array containing the settings to update. Expected keys:
     *                       - 'recaptcha_enable_status': Boolean or integer (0/1) to enable/disable.
     *                       - 'recaptcha_site_key': String containing the site key.
     *                       - 'recaptcha_secret_key': String containing the secret key.
     *                       - 'recaptcha_score': String or float representing the reCAPTCHA score.
     *
     * @return array Contains:
     *               - 'success': Boolean indicating if the operation was successful.
     *               - 'message': String containing the result message.
     *
     * @since 3.1.15
     */
    public static function set_or_update_recaptcha_settings($payload){
        $payload[ 'recaptcha_enable_status' ] = $payload['recaptcha_enable_status'] ? 1 : 0;
        $payload[ 'recaptcha_site_key' ] = !empty( $payload['recaptcha_site_key'] ) ? $payload['recaptcha_site_key']: '';
        $payload[ 'recaptcha_secret_key' ] = !empty( $payload['recaptcha_secret_key'] ) ? $payload['recaptcha_secret_key']: '';
        $payload[ 'recaptcha_score' ] = !empty( $payload['recaptcha_score'] ) ? $payload['recaptcha_score'] : '.5';

        update_option( 'cl_recaptcha_settings', $payload );

        return array(
            'success' => true,
            'message' => __('Successfully saved', 'cart-lift'),
        );
    }

    /**
     * Saves the general settings for the WPVR plugin.
     *
     * This function handles the nonce verification, sanitizes the input fields,
     * and updates the options in the database. It responds with a JSON success or error message.
     *
     * @since 3.1.15
     */
    public static function save_setup_wizard_settings(){
        $nonce = filter_input(INPUT_POST, 'security', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( !wp_verify_nonce( $nonce, 'rex-cart-lift-setup-wizard' ) ) {
            wp_send_json_error( array( 'message' => 'Invalid nonce' ), 400 );
            return;
        }

        $default_options = apply_filters(
            'cl_default_general_settings', [
                'cart_tracking'          => 1,
                'remove_carts_for_guest' => 0,
                'disable_purchased_products_campaign' => 0,
                'notify_abandon_cart'    => 0,
                'notify_recovered_cart'  => 0,
                'manually_recovered_cart' => 0,
                'enable_smtp'            => 0,
                'enable_webhook'         => 0,
                'enable_weekly_report'   => 0,
                'cart_webhook'           => '',
                'weekly_report_start_day' => '',
                'weekly_report_email'    => '',
                'weekly_report_email_from' => '',
                'weekly_report_email_body' => __('Please find the attached weekly report'),
                'enable_gdpr'            => 1,
                'gdpr_text'              => __( 'Your email address will help us support your shopping experience throughout the site. Please check our Privacy Policy to see how we use your personal data.', 'cart-lift' ),
                'cart_expiration_time'   => 30,
                'cart_abandonment_time'  => 15,
                'disable_branding'       => 0,
                'recovered'              => 0,
                'abandoned'              => 0,
                'completed'              => 0,
                'lost'                   => 0,
                'processing'             => 0,
                'discard'                => 0,
                'enable_cart_expiration' => 0
            ]
        );

        $saved_options                             = get_option( 'cl_general_settings', $default_options );
        $saved_options[ 'cart_tracking' ]          = filter_input( INPUT_POST, 'enableAbandonedCartTracking', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) !== 'false' ? 1 : 0;
        $saved_options[ 'remove_carts_for_guest' ] = filter_input( INPUT_POST, 'removeNonActionAbleCart', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) !== 'false' ? 1 : 0;
        $saved_options[ 'notify_abandon_cart' ]    = filter_input( INPUT_POST, 'notifyAdminForAbandonedCart', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) !== 'false' ? 1 : 0;
        $saved_options[ 'enable_gdpr' ]            = filter_input( INPUT_POST, 'enableGDPRIntegration', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) !== 'false' ? 1 : 0;

        update_option( 'cl_general_settings', $saved_options );

        wp_send_json_success( array( 'message' => __('General setting data successfully saved.', 'cart-lift') ), 200 );
    }

    /**
     * Handles AJAX request to get WooCommerce and EDD products for Select2 dropdown.
     *
     * This function checks for the security nonce, fetches products from both
     * WooCommerce and Easy Digital Downloads (EDD) based on the search term
     * provided, and returns the products in JSON format.
     *
     * @since 3.1.15
     *
     * @return void Outputs the JSON-encoded array of products.
     */
    public static function cl_get_products(){
        check_ajax_referer( 'cart-lift-select2', 'security' );
        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( !empty( $term ) ) {
            $term = (string) wp_unslash( $term );
        }
        if ( empty( $term ) ) {
            wp_die();
        }

        $wc_products = cl_get_wc_products( $term );

        $edd_products = cl_get_edd_products( $term );

        $products  = $wc_products + $edd_products;

        wp_send_json($products);
    }

    /**
     * Handles AJAX request to get WooCommerce and EDD categories for Select2 dropdown.
     *
     * This function checks for the security nonce, fetches categories from both
     * WooCommerce and Easy Digital Downloads (EDD) based on the search term
     * provided, and returns the categories in JSON format.
     *
     * @since 3.1.15
     *
     * @return void Outputs the JSON-encoded array of categories.
     */
    public static function cl_get_categories() {
        check_ajax_referer( 'cart-lift-select2', 'security' );
        $term = filter_input( INPUT_GET, 'term', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        if ( !empty( $term ) ) {
            $term = (string)wp_unslash( $term );
        }
        if ( empty( $term ) ) {
            wp_die();
        }
        wp_send_json( cl_get_taxonomies( $term ) );
    }

    /**
     * Update the schedular status of a cart.
     *
     * This function updates the `schedular_status` column of a specific cart in the database.
     * It retrieves the cart ID and status from the GET request, verifies the nonce, and performs the update.
     * After updating, it logs the result and redirects to the carts page.
     *
     * @since 3.1.17
     */
    public static function cl_update_schedular_status($payload) {
        global $wpdb;
        $cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $cart_id = isset($payload['id']) ? filter_var($payload['id'], FILTER_SANITIZE_NUMBER_INT) : '';
        $schedular_status = isset($payload['schedular_status']) ? filter_var($payload['schedular_status'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : 'active';
        if (!empty($cart_id) && !empty($schedular_status)) {
            $updated = $wpdb->update(
                $cl_cart_table,
                array('schedular_status' => $schedular_status),
                array('id' => $cart_id)
            );
            if ($updated !== false) {
                wp_send_json(array('response' => 'success'));
            } else {
                wp_send_json(array('response' => 'error', 'message' => 'Failed to update status'), 500);
            }
        } else {
            wp_send_json(array('response' => 'error', 'message' => 'Invalid input'), 400);
        }
    }
}