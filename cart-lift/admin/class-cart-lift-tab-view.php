<?php

/**
 * Class Cart_Lift_Tab_View
 */


class Cart_Lift_Tab_View
{

	public  $is_dashboard_tab_active;
	public  $is_leads_tab_active;
	public  $is_analytics_tab_active;
	public  $is_email_tab_active;
	public  $is_settings_tab_active;
	public  $is_compare_tab_active;
	public  $is_license_tab_active;
	private $action;
	private $sub_action;
	private $is_cl_premium;

	private $is_cl_pro_active;


	/**
	 * Cart_Lift_Tab_View constructor.
	 */
	public function __construct()
	{
		$this->action           = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$this->sub_action       = filter_input( INPUT_GET, 'sub_action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$this->is_cl_premium    = apply_filters( 'is_cl_premium', false );
		$this->is_cl_pro_active = apply_filters( 'is_cl_pro_active', false );
		$this->init();
	}

	/**
	 * initialization
	 */
	public function init()
	{
		switch ( $this->get_action() ) {
			case 'dashboard':
				$this->is_dashboard_tab_active = true;
				$this->is_leads_tab_active     = false;
				$this->is_analytics_tab_active = false;
				$this->is_email_tab_active     = false;
				$this->is_settings_tab_active  = false;
				$this->is_compare_tab_active   = false;
				$this->is_compare_tab_active   = false;
				break;
			case 'carts':
				$this->is_dashboard_tab_active = false;
				$this->is_leads_tab_active     = true;
				$this->is_analytics_tab_active = false;
				$this->is_email_tab_active     = false;
				$this->is_settings_tab_active  = false;
				$this->is_compare_tab_active   = false;
				$this->is_license_tab_active   = false;
				break;
			case 'analytics':
				$this->is_dashboard_tab_active = false;
				$this->is_leads_tab_active     = false;
				$this->is_analytics_tab_active = true;
				$this->is_email_tab_active     = false;
				$this->is_settings_tab_active  = false;
				$this->is_compare_tab_active   = false;
				$this->is_license_tab_active   = false;
				break;
			case 'email_templates':
				$this->is_dashboard_tab_active = false;
				$this->is_leads_tab_active     = false;
				$this->is_analytics_tab_active = false;
				$this->is_email_tab_active     = true;
				$this->is_settings_tab_active  = false;
				$this->is_compare_tab_active   = false;
				$this->is_license_tab_active   = false;
				break;
			case 'settings':
				$this->is_dashboard_tab_active = false;
				$this->is_leads_tab_active     = false;
				$this->is_analytics_tab_active = false;
				$this->is_email_tab_active     = false;
				$this->is_settings_tab_active  = true;
				$this->is_compare_tab_active   = false;
				$this->is_license_tab_active   = false;
				break;
			case 'compare':
				$this->is_dashboard_tab_active = false;
				$this->is_leads_tab_active     = false;
				$this->is_analytics_tab_active = false;
				$this->is_email_tab_active     = false;
				$this->is_settings_tab_active  = false;
				$this->is_compare_tab_active   = true;
				$this->is_license_tab_active   = false;
				break;
			case 'license':
				$this->is_dashboard_tab_active = false;
				$this->is_leads_tab_active     = false;
				$this->is_analytics_tab_active = false;
				$this->is_email_tab_active     = false;
				$this->is_settings_tab_active  = false;
				$this->is_compare_tab_active   = false;
				$this->is_license_tab_active   = true;
				break;
			default:
				$this->is_dashboard_tab_active = true;
				$this->is_leads_tab_active     = false;
				$this->is_analytics_tab_active = false;
				$this->is_email_tab_active     = false;
				$this->is_settings_tab_active  = false;
				$this->is_license_tab_active   = false;
				break;
		}
	}


	/**
	 * Get the tab action
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function get_action()
	{
		return $this->action;
	}

	/**
	 * Get premium of
	 * tab
	 */
	public function is_cl_pro_active()
	{
		return $this->is_cl_pro_active;
	}

	/**
	 * show error/warning/success
	 * messages
	 */
	public function show_messages()
	{
		$cart_data_deleted      = filter_input( INPUT_GET, 'cl_cart_data_deleted', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$cart_data_bulk_deleted = filter_input( INPUT_GET, 'cl_cart_data_bulk_deleted', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		$template_created      = filter_input( INPUT_GET, 'cl_template_created', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$template_updated      = filter_input( INPUT_GET, 'cl_template_updated', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$template_deleted      = filter_input( INPUT_GET, 'cl_template_deleted', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$template_bulk_deleted = filter_input( INPUT_GET, 'cl_template_bulk_deleted', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( $cart_data_deleted === 'yes' ) { ?>
            <div id="message" class="notice notice-success is-dismissible">
                <p>
                    <strong>
						<?php
						esc_html_e( 'Cart data has been successfully deleted.', 'cart-lift' ); ?>
                    </strong>
                </p>
            </div>
			<?php
		}

		if ( $cart_data_bulk_deleted === 'yes' ) { ?>
            <div id="message" class="notice notice-success is-dismissible">
                <p>
                    <strong>
						<?php
						esc_html_e( 'Cart data has been successfully deleted.', 'cart-lift' ); ?>
                    </strong>
                </p>
            </div>
			<?php
		}

		if ( $template_created === 'yes' ) { ?>
            <div id="message" class="notice notice-success is-dismissible">
                <p>
                    <strong>
						<?php
						esc_html_e( 'The email template has been successfully created.', 'cart-lift' ); ?>
                    </strong>
                </p>
            </div>

			<?php
		}

		if ( $template_updated === 'yes' ) { ?>
            <div id="message" class="notice notice-success is-dismissible">
                <p>
                    <strong>
						<?php
						esc_html_e( 'The email template has been successfully updated.', 'cart-lift' ); ?>
                    </strong>
                </p>
            </div>

			<?php
		}

		if ( $template_deleted === 'yes' ) { ?>
            <div id="message" class="notice notice-success is-dismissible">
                <p>
                    <strong>
						<?php
						esc_html_e( 'The email template has been successfully deleted.', 'cart-lift' ); ?>
                    </strong>
                </p>
            </div>

			<?php
		}

		if ( $template_bulk_deleted === 'yes' ) { ?>
            <div id="message" class="notice notice-success is-dismissible">
                <p>
                    <strong>
						<?php
						esc_html_e( 'The email templates has been successfully deleted.', 'cart-lift' ); ?>
                    </strong>
                </p>
            </div>

			<?php
		}
	}

	/**
	 * print dashboard tabs
	 */
	public function print_dashboard_tab_contents()
	{
		$analytics_data = cl_get_analytics_data( 'weekly' );
		$pro_email_sent = true;
		if ( $this->is_cl_premium ) {
			$pro_email_sent = false;
		}
		require_once CART_LIFT_DIR . '/admin/partials/cart-lift-dashboard-templates-tab.php';
	}

	/**
	 * print lead tabs
	 */
	public function print_carts_tab_content()
	{
		if ( $this->get_sub_action() == 'view_cart_data' ) {
			$this->render_cart_details_board();
			exit();
		}
		if ( $this->get_sub_action() == 'unsubscribe_cl_emails' ) {
			$this->unsubscribe_cl_emails();
		}
        elseif ( $this->get_sub_action() == 'delete_cart_data' ) {
			$this->delete_cart_data();
		}
        elseif ( $this->get_sub_action() == 'delete_bulk_cart_data' ) {
			$this->delete_bulk_cart_data();
		}
		else {
			if ( isset( $_GET[ 'pageno' ] ) ) {
				$pageno = sanitize_text_field( $_GET[ 'pageno' ] );
			}
			else {
				$pageno = 1;
			}
			$per_page = 10;
			$offset   = ( $pageno - 1 ) * $per_page;


			$search_query = array(
				's'      => isset( $_GET[ 'cart_search' ] ) ? sanitize_email( $_GET[ 'cart_search' ] ) : '',
				'status' => isset( $_GET[ 'status' ] ) ? sanitize_text_field( $_GET[ 'status' ] ) : '',
			);


			$is_pro_cart = false;
			if ( $this->is_cl_premium ) {
				$is_pro_cart = true;
			}

			$cart_data = cl_get_cart_data( $offset, $per_page, $search_query );
			require_once CART_LIFT_DIR . '/admin/partials/cart-lift-carts-tab.php';
		}
	}

	/**
	 * Get sub action of
	 * tab
	 * @return string
	 * @since 1.0.0
	 */
	public function get_sub_action()
	{
		return $this->sub_action;
	}

	/**
	 * render cart details
	 */
	public function render_cart_details_board()
	{
		$cart_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
		$param   = array(
			'page'   => 'cart_lift',
			'action' => 'leads',
		);
		$result  = $this->get_cart_data_by_id( $cart_id );
		if ( $result ) {
			if ( isset( $result->id ) ) {
				$cart_id = $result->id;
			}
			if ( isset( $result->email ) ) {
				$email = $result->email;
			}
			if ( isset( $result->cart_contents ) ) {
				$cart_contents = $result->cart_contents;
			}
			if ( isset( $result->cart_total ) ) {
				$cart_total = $result->cart_total;
			}
			if ( isset( $result->status ) ) {
				$status = $result->status;
			}
			if ( isset( $result->last_sent_email ) ) {
				$last_sent_email = $result->last_sent_email;
			}
			if ( isset( $result->time ) ) {
				$time = $result->time;
			}
			if ( isset( $result->unsubscribed ) ) {
				$unsubscribed = $result->unsubscribed;
			}
			if ( isset( $result->provider ) ) {
				$provider = $result->provider;
			}

			require_once CART_LIFT_DIR . '/admin/partials/cart-lift-view-cart-data.php';
		}
		else {
			$redirect_url = add_query_arg( $param, admin_url( '/admin.php' ) );
			wp_safe_redirect( $redirect_url );
			exit();
		}

	}

	/**
	 * get cart data by id
	 *
	 * @param $id
	 * @return object
	 * @since 1.0.0
	 */
	public function get_cart_data_by_id( $id )
	{
		global $wpdb;
		$cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;
		$query         = 'SELECT * FROM ' . $cl_cart_table . ' WHERE id = %d';
		return $wpdb->get_row( $wpdb->prepare( $query, $id ) ); // phpcs:ignore
	}

	/**
	 * unsubscribe user from
	 * getting email
	 *
	 */
	public function unsubscribe_cl_emails()
	{
		global $wpdb;
		$cl_cart_table             = $wpdb->prefix . CART_LIFT_CART_TABLE;
		$cl_campaign_history_table = $wpdb->prefix . CART_LIFT_CAMPAIGN_HISTORY_TABLE;
		$cart_id                   = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );
		$session_id                = $wpdb->get_var( $wpdb->prepare( "SELECT session_id FROM $cl_cart_table WHERE id=%d", $cart_id ) );
		$wpdb->update(
			$cl_cart_table,
			array(
				'unsubscribed' => 1
			),
			array(
				'id' => $cart_id
			)
		);

		$wpdb->update(
			$cl_campaign_history_table,
			array(
				'email_sent' => -1
			),
			array(
				'session_id' => $session_id,
				'email_sent' => 0
			)
		);

		$param        = array(
			'page'   => 'cart_lift',
			'action' => 'carts',
		);
		$redirect_url = add_query_arg( $param, admin_url( '/admin.php' ) );
		wp_safe_redirect( $redirect_url );
		exit();
	}

	/**
	 * delete cart data of
	 * specific user
	 *
	 */
	public function delete_cart_data()
	{
		global $wpdb;
		$cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;
		$lead_id       = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );
		check_ajax_referer( 'cl_cart_nonce' );
		$wpdb->delete(
			$cl_cart_table,
			array(
				'id' => $lead_id
			)
		);

		$param        = array(
			'page'                 => 'cart_lift',
			'action'               => 'carts',
			'cl_cart_data_deleted' => 'yes',
		);
		$redirect_url = add_query_arg( $param, admin_url( '/admin.php' ) );
		wp_safe_redirect( $redirect_url );
		exit();
	}

	/**
	 * print analytics tab
	 */
	public function print_analytics_tab_content()
	{
		require_once CART_LIFT_DIR . '/admin/partials/cart-lift-analytics-tab.php';
	}

	/**
	 * print email templates tab
	 */
	public function print_email_tab_contents()
	{
		if ( $this->get_sub_action() == 'edit_email_template' ) {
			$this->render_email_template_form();
		}
        elseif ( $this->get_sub_action() == 'save_email_template' ) {
			check_ajax_referer( 'email_template_nonce', '_wpnonce' );
			$form_action = filter_input( INPUT_POST, 'cl_form_action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			if ( $form_action == 'save_template' ) {
				$this->save_email_template();
			}
            elseif ( $form_action == 'update_template' ) {
				$this->update_email_template();
			}
		}
        elseif ( $this->get_sub_action() == 'delete_email_template' ) {
			$this->delete_email_template();
		}
        elseif ( $this->get_sub_action() == 'delete_bulk_email_templates' ) {
			$this->delete_bulk_email_templates();
		}
        elseif ( $this->get_sub_action() == 'new_email_template' ) {
			$this->render_email_template_form();
		}
		else {
			$page_url             = admin_url( 'admin.php?page=cart_lift&action=email_templates' );
			$campaign_data        = cl_get_campaign_data();
			$total_email_template = cl_get_total_email_template();
			$enable_add_new_btn   = false;
			$pro_campaign_list    = false;
			if ( $this->is_cl_premium ) {
				$enable_add_new_btn = true;
			}
			else {
				if ( $total_email_template < 3 ) {
					$enable_add_new_btn = true;
				}
			}
			if ( $this->is_cl_premium ) {
				$pro_campaign_list = true;
			}
			require_once CART_LIFT_DIR . '/admin/partials/cart-lift-email-templates-tab.php';
		}
	}

	/**
	 * render email form
	 */
	public function render_email_template_form()
	{
		$template_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
		$param       = array(
			'page'       => 'cart_lift',
			'action'     => 'email_templates',
			'sub_action' => 'save_email_template',
		);
		if ( isset( $template_id ) ) {
			$param[ 'id' ] = $template_id;
		}

		$is_pro = false;
		if ( $this->is_cl_premium ) {
			$is_pro = true;
		}

        $form_action                       = esc_url( add_query_arg( $param, admin_url( '/admin.php' ) ) );
        $form_header                       = __( 'Create a New Campaign', 'cart-lift' );
        $action                            = 'save_template';
        $button_text                       = __( 'Save Campaign', 'cart-lift' );
        $template_name                     = '';
        $email_subject                     = '';
        $email_body                        = '';
        $twilio_sms_body                   = '';
        $frequency                         = 15;
        $unit                              = 'minute';
        $active                            = 1;
        $coupon                            = 0;
        $twilio_enabled                    = 0;
        $funnel                            = 0;
        $coupon_amount                     = '';
        $coupon_type                       = '';
        $discount_amount                   = 10;
        $coupon_frequency                  = '';
        $coupon_frequency_unit             = '';
        $enable_coupon_condition           = 0;
        $coupon_included_products          = array();
        $coupon_included_products_amount   = '';
        $coupon_included_categories        = array();
        $coupon_included_categories_amount = '';
        $free_shipping                     = false;
        $individual_use                    = false;
        $coupon_auto_apply                 = false;
        $email_checkout_text               = 'Checkout';
        $email_checkout_color              = '#6e42d3';
        $email_header_text                 = __( 'Please Consider Your Cart', 'cart-lift' );
        $email_header_color                = '#6e42d3';

		if ( isset( $template_id ) ) {
            $template_details = cl_get_email_template_by_id( $template_id );
            $form_header      = __( 'Edit Campaign', 'cart-lift' );
            $action           = __( 'update_template', 'cart-lift' );
            $button_text      = __( 'Update Campaign', 'cart-lift' );

			if ( $template_details ) {
                $template_name                     = isset( $template_details->template_name ) ? $template_details->template_name : '';
                $email_subject                     = isset( $template_details->email_subject ) ? $template_details->email_subject : '';
                $email_body                        = isset( $template_details->email_body ) ? $template_details->email_body : '';
                $frequency                         = isset( $template_details->frequency ) ? $template_details->frequency : '';
                $unit                              = isset( $template_details->unit ) ? $template_details->unit : '';
                $active                            = isset( $template_details->active ) ? $template_details->active : '';
                $coupon                            = isset( $template_details->coupon ) ? $template_details->coupon : '';
                $funnel                            = isset( $template_details->funnel ) ? $template_details->funnel : '';
                $coupon_type                       = isset( $template_details->type ) ? $template_details->type : '';
                $coupon_amount                     = isset( $template_details->amount ) ? $template_details->amount : '';
                $coupon_frequency                  = isset( $template_details->coupon_frequency ) ? $template_details->coupon_frequency : '';
                $coupon_frequency_unit             = isset( $template_details->coupon_frequency_unit ) ? $template_details->coupon_frequency_unit : '';
                $enable_coupon_condition           = isset( $template_details->conditional_discount ) ? $template_details->conditional_discount : '';
                $coupon_included_products_amount   = isset( $template_details->coupon_included_products_amount ) ? $template_details->coupon_included_products_amount : '';
                $free_shipping                     = isset( $template_details->free_shipping ) ? $template_details->free_shipping : '';
                $individual_use                    = isset( $template_details->individual_use ) ? $template_details->individual_use : '';
                $coupon_auto_apply                 = isset( $template_details->coupon_auto_apply ) ? $template_details->coupon_auto_apply : '';
                $coupon_included_categories_amount = isset( $template_details->coupon_included_categories_amount ) ? $template_details->coupon_included_categories_amount : '';
                $email_header_text                 = isset( $template_details->email_header_text ) ? $template_details->email_header_text : '';
                $email_header_color                = isset( $template_details->email_header_color ) ? $template_details->email_header_color : '';
                $email_checkout_color              = isset( $template_details->email_checkout_color ) ? $template_details->email_checkout_color : '';
                $email_checkout_text               = isset( $template_details->email_checkout_text ) ? $template_details->email_checkout_text : '';
                $twilio_enabled                    = isset( $template_details->twilio_sms ) ? $template_details->twilio_sms == 'enabled' ? 1 : 0 : '';
                $twilio_sms_body                   = isset( $template_details->twilio_sms_body ) ? $template_details->twilio_sms_body : '';

				if ( isset( $template_details->coupon_included_products ) ) {
					if ( !is_array( $template_details->coupon_included_products ) ) {
						$coupon_included_products = unserialize( $template_details->coupon_included_products );
					}
					else {
						$coupon_included_products = $template_details->coupon_included_products;
					}
				}
                else {
                    $coupon_included_products = '';
                }

				if ( isset( $template_details->coupon_included_categories ) ) {
					if ( !is_array( $template_details->coupon_included_categories ) ) {
						$coupon_included_categories = unserialize( $template_details->coupon_included_categories );
					}
					else {
						$coupon_included_categories = $template_details->coupon_included_categories;
					}
				}
                else {
                    $coupon_included_categories = '';
                }
			}
		}
		require_once CART_LIFT_DIR . '/admin/partials/cart-lift-edit-email-template.php';
	}

	/**
	 * save email contents
	 */
	public function save_email_template()
	{
		global $wpdb;
		$cl_email_templates_table = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;
		$sanitized_fields         = $this->sanitize_email_fields();
		$sanitized_meta_fields    = $this->sanitize_email_meta_fields();

        $email_template_details = array(
            'template_name'   => $sanitized_fields[ 'cl_template_name' ],
            'email_subject'   => $sanitized_fields[ 'cl_email_subject' ],
            'email_body'      => $sanitized_fields[ 'cl_email_body' ],
            'twilio_sms'      => $sanitized_fields[ 'cl_campaign_enable_twilio' ] == 1 ? 'enabled' : 'disabled',
            'twilio_sms_body' => $sanitized_fields[ 'cl_twilio_sms_body' ],
            'frequency'       => $sanitized_fields[ 'cl_email_frequency' ],
            'unit'            => $sanitized_fields[ 'cl_email_unit' ],
            'active'          => $sanitized_fields[ 'cl_email_template_status' ],
            'email_meta'      => serialize( $sanitized_meta_fields ),
            'created_at'      => current_time( CART_LIFT_DATETIME_FORMAT ),
        );
        $email_template_details = apply_filters( 'cl_before_email_template_insert', $email_template_details );

        $wpdb->insert(
            $cl_email_templates_table,
            $email_template_details
        );

		$template_id = $wpdb->insert_id;
		$param       = array(
            'page'                => 'cart_lift',
            'action'              => 'email_templates',
            'sub_action'          => 'edit_email_template',
            'id'                  => $template_id,
            'cl_template_created' => 'yes',
        );
		$redirect_url = add_query_arg( $param, admin_url( '/admin.php' ) );
		wp_safe_redirect( $redirect_url );
		exit();
	}

	/**
	 * Sanitize fields for emails
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function sanitize_email_fields()
	{
		$sanitized_fields = array();

		if ( isset( $_POST[ 'id' ] ) ) {
			$sanitized_fields[ 'id' ] = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );
		}
		else {
			$sanitized_fields[ 'id' ] = '';
		}
		if ( isset( $_POST[ 'cl_template_name' ] ) ) {
			$sanitized_fields[ 'cl_template_name' ] = filter_input( INPUT_POST, 'cl_template_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		}
		else {
			$sanitized_fields[ 'cl_template_name' ] = '';
		}
		if ( isset( $_POST[ 'cl_email_subject' ] ) ) {
			$sanitized_fields[ 'cl_email_subject' ] = filter_input( INPUT_POST, 'cl_email_subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		}
		else {
			$sanitized_fields[ 'cl_email_subject' ] = '';
		}
		if ( isset( $_POST[ 'cl_email_body' ] ) ) {
			$sanitized_fields[ 'cl_email_body' ] = wpautop( wptexturize( wp_kses_post( $_POST[ 'cl_email_body' ] ) ) );
		}
		else {
			$sanitized_fields[ 'cl_email_body' ] = '';
		}
		if ( isset( $_POST[ 'cl_email_frequency' ] ) ) {
			$sanitized_fields[ 'cl_email_frequency' ] = filter_input( INPUT_POST, 'cl_email_frequency', FILTER_SANITIZE_NUMBER_INT );
		}
		else {
			$sanitized_fields[ 'cl_email_frequency' ] = '';
		}
		if ( isset( $_POST[ 'cl_email_unit' ] ) ) {
			$sanitized_fields[ 'cl_email_unit' ] = filter_input( INPUT_POST, 'cl_email_unit', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		}
		else {
			$sanitized_fields[ 'cl_email_unit' ] = '';
		}
		if ( isset( $_POST[ 'cl_email_unit' ] ) ) {
			$sanitized_fields[ 'cl_email_unit' ] = filter_input( INPUT_POST, 'cl_email_unit', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		}
		else {
			$sanitized_fields[ 'cl_email_unit' ] = '';
		}
		if ( isset( $_POST[ 'cl_email_template_status' ] ) ) {
			$sanitized_fields[ 'cl_email_template_status' ] = filter_input( INPUT_POST, 'cl_email_template_status', FILTER_SANITIZE_NUMBER_INT );
		}
		else {
			$sanitized_fields[ 'cl_email_template_status' ] = '';
		}
		if ( isset( $_POST[ 'cl_campaign_enable_twilio' ] ) ) {
			$sanitized_fields[ 'cl_campaign_enable_twilio' ] = filter_input( INPUT_POST, 'cl_campaign_enable_twilio', FILTER_SANITIZE_NUMBER_INT );
		}
		else {
			$sanitized_fields[ 'cl_campaign_enable_twilio' ] = '';
		}
		if ( isset( $_POST[ 'cl_twilio_sms_body' ] ) ) {

			$sanitized_fields[ 'cl_twilio_sms_body' ] = filter_input( INPUT_POST, 'cl_twilio_sms_body', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		}
		else {

			$sanitized_fields[ 'cl_twilio_sms_body' ] = '';
		}

		return $sanitized_fields;
	}


	/**
	 * Sanitize fields for email-meta
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function sanitize_email_meta_fields()
	{
		$meta_array         = array();
        $coupon_meta_fields = apply_filters(
            'cl_campaign_coupon_post_data',
            array(
                'cl_campaign_coupon'           => 0,
                'cl_campaign_coupon_type'      => '',
                'cl_campaign_coupon_amount'    => '',
                'cl_campaign_coupon_frequency' => '',
                'cl_campaign_coupon_unit'      => '',
                'cl_campaign_funnel'           => ''
            )
        );

		foreach ( $coupon_meta_fields as $key => $value ) {
			if ( isset( $_POST[ $key ] ) ) {
				if ( is_array( $_POST[ $key ] ) ) {
					$meta_array[ $key ] = sanitize_text_field( serialize( $_POST[ $key ] ) );
				}
				else {
					$meta_array[ $key ] = sanitize_text_field( $_POST[ $key ] );
				}
			}
			else {
				$meta_array[ $key ] = $value;
			}
		}

        $campaign_meta_field = apply_filters(
            'cl_campaign_meta_post_data',
            array(
                'cl_campaign_email_header_text' => __( 'Please Consider Your Cart', 'cart-lift' ),
                'cl_campaign_email_header_color' => '#6e42d3',
                'cl_campaign_checkout_color' => '#6e42d3',
                'cl_campaign_checkout_text' => 'Checkout',
            )
        );

		foreach ( $campaign_meta_field as $key => $value ) {
			if ( isset( $_POST[ $key ] ) ) {
				$meta_array[ $key ] = sanitize_text_field( $_POST[ $key ] );
			}
			else {
				$meta_array[ $key ] = $value;
			}
		}

        return apply_filters(
            'cl_campaign_meta',
            array(
                'coupon'                => $meta_array[ 'cl_campaign_coupon' ],
                'type'                  => $meta_array[ 'cl_campaign_coupon_type' ],
                'amount'                => $meta_array[ 'cl_campaign_coupon_amount' ],
                'coupon_frequency'      => $meta_array[ 'cl_campaign_coupon_frequency' ],
                'coupon_frequency_unit' => $meta_array[ 'cl_campaign_coupon_unit' ],
                'email_header_text'     => $meta_array[ 'cl_campaign_email_header_text' ],
                'email_header_color'    => $meta_array[ 'cl_campaign_email_header_color' ],
                'email_checkout_color'  => $meta_array[ 'cl_campaign_checkout_color' ],
                'email_checkout_text'   => $meta_array[ 'cl_campaign_checkout_text' ],
            ),
            $meta_array
        );
	}

	/**
	 * update email template
	 */
	public function update_email_template()
	{
		global $wpdb;
		$cl_email_templates_table = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;
		$sanitized_fields         = $this->sanitize_email_fields();
		$sanitized_meta_fields    = $this->sanitize_email_meta_fields();

        $email_template_details = array(
            'template_name'   => $sanitized_fields[ 'cl_template_name' ],
            'email_subject'   => $sanitized_fields[ 'cl_email_subject' ],
            'email_body'      => $sanitized_fields[ 'cl_email_body' ],
            'twilio_sms'      => $sanitized_fields[ 'cl_campaign_enable_twilio' ] == 1 ? 'enabled' : 'disabled',
            'twilio_sms_body' => $sanitized_fields[ 'cl_twilio_sms_body' ],
            'frequency'       => $sanitized_fields[ 'cl_email_frequency' ],
            'unit'            => $sanitized_fields[ 'cl_email_unit' ],
            'active'          => $sanitized_fields[ 'cl_email_template_status' ],
            'email_meta'      => serialize( $sanitized_meta_fields ),
        );

        $email_template_details = apply_filters( 'cl_before_email_template_update', $email_template_details );

		$res = $wpdb->update(
			$cl_email_templates_table,
            $email_template_details,
			array( 'id' => $sanitized_fields[ 'id' ] )
		);

		$param        = array(
			'page'                => 'cart_lift',
			'action'              => 'email_templates',
			'sub_action'          => 'edit_email_template',
			'id'                  => $sanitized_fields[ 'id' ],
			'cl_template_updated' => 'yes',
		);
		$redirect_url = add_query_arg( $param, admin_url( '/admin.php' ) );
		wp_safe_redirect( $redirect_url );
		exit();
	}

	/**
	 * delete email template
	 */
	public function delete_email_template()
	{
		global $wpdb;
		$cl_email_templates_table = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;
		$id                       = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
		$wpnonce                  = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( $wpnonce && wp_verify_nonce( $wpnonce, 'email_template_nonce' ) ) {
			if ( $id ) {
				$wpdb->delete(
					$cl_email_templates_table,
					array( 'id' => $id )
				);
				$param        = array(
					'page'                => 'cart_lift',
					'action'              => 'email_templates',
					'cl_template_deleted' => 'yes',
				);
				$redirect_url = add_query_arg( $param, admin_url( '/admin.php' ) );
				wp_safe_redirect( $redirect_url );
				exit();
			}
		}
	}

	/**
	 * delete bulk email templates
	 */
	public function delete_bulk_email_templates()
	{
		$wcf_template_list = new Cart_Lift_Email_Templates_Table();
		$wcf_template_list->process_bulk_action();
		$param        = array(
			'page'                     => 'cart_lift',
			'action'                   => 'email_templates',
			'cl_template_bulk_deleted' => 'yes',
		);
		$redirect_url = add_query_arg( $param, admin_url( '/admin.php' ) );
		wp_safe_redirect( $redirect_url );
		exit();
	}

	/**
	 * print compare tabs
	 */
	public function print_compare_tab_contents()
	{
		require_once CART_LIFT_DIR . '/admin/partials/cart-lift-compare-templates-tab.php';
	}

	/**
	 * print settings tab
	 * @since 1.0.0
	 */
	public function print_settings_tab_content()
	{
		$cl_pro_tag = true;
		if ( $this->is_cl_premium ) {
			$cl_pro_tag = false;
		}

		// general settings data
		$general_settings = cl_get_general_settings();

		// other mailer settings
		$default_other_smtp_settings = array(
			'username'   => '',
			'from'       => '',
			'fromname'   => '',
			'host'       => '',
			'port'       => '',
			'smtpsecure' => 'none',
			'smtpauth'   => 'no',
		);
		$other_smtp_settings         = get_option( 'cl_other_mailer', $default_other_smtp_settings );
		$smtp_integrations           = apply_filters(
			'cl_smtp_integrations', array(
			'other' => $other_smtp_settings
		) );

		$settings = array(
			'general_settings' => $general_settings,
			'smtp'             => $smtp_integrations
		);

		require_once CART_LIFT_DIR . '/admin/partials/cart-lift-settings-tab.php';
	}

}
