<?php

/**
 * Fired during plugin activation
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Cart_Lift
 * @subpackage Cart_Lift/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cart_Lift
 * @subpackage Cart_Lift/includes
 * @author     RexTheme <info@rextheme.com>
 */
class Cart_Lift_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        $db = Cart_Lift_DB::get_instance();
        $db->create_tables();

        if (! wp_next_scheduled ( 'cart_lift_process_scheduled_email_hook' )) {
            wp_schedule_event( time(), 'cl_fifteen_minutes', 'cart_lift_process_scheduled_email_hook');
        }

        if (! wp_next_scheduled ( 'wp_cl_x_day_cart_remove' )) {
            $general_settings = get_option( 'cl_general_settings' );
            if( !empty( $general_settings['enable_cart_expiration']) ) {
                $current_timestamp = current_time('timestamp');
                wp_schedule_event($current_timestamp , '0 2 * * *', 'cart_lift_x_days_cart_remove'); // 0 2 * * * => 2:00 AM
            }
        }

        // set plugin security key
        $security_key = get_option('cart_lift_security_key', '');
        if(!isset($security_key)) update_option( 'cart_lift_security_key', md5( uniqid( wp_rand(), true ) ) );


        // default settings
        $current_user     = wp_get_current_user();
        $email_from       = ( isset( $current_user->user_firstname ) && ! empty( $current_user->user_firstname ) ) ? $current_user->user_firstname . ' ' . $current_user->user_lastname : 'Admin';
        $settings = array(
            'cl_db_version' => CART_LIFT_DB_VERSION,
            'cl_general_settings' => array(
                'cart_tracking' => 1,
                'remove_carts_for_guest' => 1,
                'notify_abandon_cart' => 0,
                'notify_recovered_cart' => 0,
                'enable_smtp' => 0,
                'enable_webhook' => 0,
                'enable_weekly_report' => 0,
                'cart_webhook' => '',
                'weekly_report_start_day' => '',
				'weekly_report_email'    => '',
                'weekly_report_email_from' => '',
                'weekly_report_email_body' => __('Please find the attached weekly report', 'cart-lift'),
                'enable_gdpr' => 1,
                'gdpr_text' => __('Your email address will help us support your shopping experience throughout the site. Please check our Privacy Policy to see how we use your personal data.', 'cart-lift'),
                'disable_branding' => 0,
                'cart_expiration_time' => 30,
                'cart_abandonment_time' => 15,
                'recovered'              => 0,
                'abandoned'              => 0,
                'completed'              => 0,
                'lost'                   => 0,
                'processing'             => 0,
                'discard'                => 0,
                'enable_cart_expiration' => 0,
	            'enable_cl_exclude_products' => 0,
                'enable_cl_exclude_categories' => 0,
                'enable_cl_exclude_countries' => 0,
            )
        );
        foreach ( $settings as $key => $setting ) {
            if (!get_option($key)) {
                update_option($key, $setting);
            }
        }

        self::set_cart_lift_activation_transients();
        self::update_cl_version();
        self::update_installed_time();
	}

    /**
     * See if we need to redirect the admin to setup wizard or not.
     *
     * @since 3.1.15
     */
    private static function set_cart_lift_activation_transients()
    {
        if (self::is_new_install()) {
            set_transient('rex_cart_lift_activation_redirect', 1, 30);
        }
    }

    /**
     * Update WPFM version to current.
     *
     * @since 3.1.15
     */
    private static function update_cl_version()
    {
        update_site_option('rex_cart_lift_version', CART_LIFT_VERSION );
    }

    /**
     * Brand new install of wpfunnels
     *
     * @return bool
     * @since  1.0.0
     */
    public static function is_new_install()
    {
        return is_null(get_site_option('rex_cart_lift_version', null));
    }

    /**
     * Updates the installed time.
     *
     * This function calls the `get_installed_time` method to update the installed time.
     *
     * @since 7.4.14
     */
    public static function update_installed_time() {
        self::get_installed_time();
    }

    /**
     * Retrieve the time when wpfm is installed
     *
     * @return int|mixed|void
     * @since  3.1.15
     */
    public static function get_installed_time() {
        $installed_time = get_option( 'rex_cart_lift_installed_time' );
        if ( ! $installed_time ) {
            $installed_time = time();
            update_site_option( 'rex_cart_lift_installed_time', $installed_time );
        }
        return $installed_time;
    }

}
