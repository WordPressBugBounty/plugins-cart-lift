<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Cart_Lift
 * @subpackage Cart_Lift/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Cart_Lift
 * @subpackage Cart_Lift/includes
 * @author     RexTheme <info@rextheme.com>
 */
class Cart_Lift_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		
	    wp_clear_scheduled_hook( 'cart_lift_process_scheduled_email_hook' );
        wp_clear_scheduled_hook( 'cart_lift_x_days_cart_remove' );

        if ( false !== as_next_scheduled_action( 'cart_lift_process_scheduled_email_hook' ) ) {
            as_unschedule_action( 'cart_lift_process_scheduled_email_hook' );
        }

        if ( false !== as_next_scheduled_action( 'cart_lift_x_days_cart_remove' ) ) {
            as_unschedule_action( 'cart_lift_x_days_cart_remove' );
        }

	}

}
