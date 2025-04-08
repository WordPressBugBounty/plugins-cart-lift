<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://rextheme.com/
 * @since             1.0.0
 * @package           Cart_Lift
 *
 * @wordpress-plugin
 * Plugin Name:       Cart Lift - Abandoned Cart Recovery for WooCommerce and EDD
 * Plugin URI:        https://rextheme.com/cart-lift
 * Description:       Win back your abandoned cart customers with automated email recovery campaign.
 * Version:           3.1.34
 * Author:            RexTheme
 * Author URI:        https://rextheme.com/cart-lift
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cart-lift
 * Domain Path:       /languages
 *
 * WP Requirement & Test
 * Requires at least: 5.0
 * Tested up to: 6.7.2
 * Requires PHP: 7.4
 *
 * WC Requirement & Test
 * WC requires at least: 3.8.0
 * WC tested up to: 9.7.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */

define('CART_LIFT_VERSION', '3.1.34' );
define('CART_LIFT_FILE', __FILE__ );
define('CART_LIFT_BASE', plugin_basename( CART_LIFT_FILE ) );
define('CART_LIFT_DIR', plugin_dir_path(CART_LIFT_FILE));
define('CART_LIFT_URL', plugin_dir_url(CART_LIFT_FILE));
define('CART_LIFT_DEV_MODE', false);

define('CART_LIFT_ADMIN_JS_PATH', plugin_dir_url(__FILE__) . 'admin/js/');
define('CART_LIFT_ASSET_PATH', plugin_dir_url(__FILE__) . 'admin/assets/');

if ( !defined( 'CART_LIFT_DATETIME_FORMAT' ) ) {
	define( 'CART_LIFT_DATETIME_FORMAT', 'Y-m-d H:i:s' );
}
if ( !defined( 'CART_LIFT_CART_TABLE' ) ) {
	define('CART_LIFT_CART_TABLE', 'cl_abandon_cart');
}
if ( !defined( 'CART_LIFT_EMAIL_TEMPLATE_TABLE' ) ) {
	define('CART_LIFT_EMAIL_TEMPLATE_TABLE', 'cl_email_templates');
}
if ( !defined( 'CART_LIFT_CAMPAIGN_HISTORY_TABLE' ) ) {
	define('CART_LIFT_CAMPAIGN_HISTORY_TABLE', 'cl_campaign_history');
}
if ( !defined( 'CART_LIFT_DB_VERSION' ) ) {
	define('CART_LIFT_DB_VERSION', '5.1');
}
define('CART_LIFT_SECURITY_KEY', get_option('cart_lift_security_key', ''));

define('CART_LIFT_WEBHOOK_URL', sanitize_url('https://rextheme.com/?mailmint=1&route=webhook&topic=contact&hash=d0f0d434-e185-40af-b8f1-7a51a01bd9c5' ));


require plugin_dir_path( __FILE__ ) . 'includes/aes-encryption/class-cart-lift-aes.php';
require plugin_dir_path( __FILE__ ) . 'includes/aes-encryption/class-cart-lift-aes-counter.php';
require plugin_dir_path( __FILE__ ) . 'includes/actions.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-cart-lift-dependency-checker.php';
require plugin_dir_path( __FILE__ ) . 'includes/helper.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cart-lift-activator.php
 */
function activate_cart_lift() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cart-lift-activator.php';
	Cart_Lift_Activator::activate();
}

function store_default_pop_up_settings(){
	$properties = array(
		'cl_popup_modal' => array(),
		'cl_modal_body' => array(
			'cl_popup_title' => array(
				'text-align' => 'center',
				'font-size' => '22px',
				'font-weight' => '500',
				'font-style' => 'normal',
				'text-transform' => 'none',
				'text-decoration' => 'none',
				'color' => '#000000',
			),
			'cl_popup_description' => 'Sign up for our newsletter and get 10% off your first order',
			'cl_popup_email_input'=> '',
			'cl_popup_submit_button' => 'Sign Up',
			'cl_ignore_email_form' => 'Sign Up',
		),
	);

	$submitted_data = array();
	$data = array(
		$properties,
		$submitted_data
	);
	update_option('cl_popup_settings', $data);
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cart-lift-deactivator.php
 */
function deactivate_cart_lift() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cart-lift-deactivator.php';
	Cart_Lift_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cart_lift' );
register_deactivation_hook( __FILE__, 'deactivate_cart_lift' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cart-lift.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cart_lift() {

	$plugin = new Cart_Lift();
	$plugin->run();

	$required_plugins = array(
		'WooCommerce' => 'woocommerce/woocommerce.php',
		'Easy Digital Download' => 'easy-digital-downloads/easy-digital-downloads.php',
		'Easy Digital Downloads (Pro)' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
	);

	new Cart_Lift_Dependency_Checker( $required_plugins, __FILE__, 'cart-lift' );

}
run_cart_lift();


/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function cl_appsero_init_tracker_cart_lift() {
	$client = new Appsero\Client( 'e157907f-38d4-4d69-ad9d-a64a818f88d4', 'Cart Lift', __FILE__ );
	$client->insights()
	       ->init();
}
cl_appsero_init_tracker_cart_lift();

/**
 * Declare plugin's compatibility with WooCommerce HPOS
 *
 * @return void
 * @since 3.1.10
 */
function cl_wc_hpos_compatibility() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__ );
	}
}
add_action( 'before_woocommerce_init', 'cl_wc_hpos_compatibility' );