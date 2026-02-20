<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Cart_Lift
 * @subpackage Cart_Lift/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cart_Lift
 * @subpackage Cart_Lift/includes
 * @author     RexTheme <info@rextheme.com>
 */
class Cart_Lift
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Cart_Lift_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('CART_LIFT_VERSION')) {
            $this->version = CART_LIFT_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'cart-lift';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        add_action('init', array($this, 'register_cart_lift_setup_wizard'));
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Cart_Lift_Loader. Orchestrates the hooks of the plugin.
     * - Cart_Lift_i18n. Defines internationalization functionality.
     * - Cart_Lift_Admin. Defines all hooks for the admin area.
     * - Cart_Lift_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cart-lift-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cart-lift-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin & public area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'vendor/autoload.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cart-lift-setup-wizard.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cart-lift-create-contact.php';

        $this->loader = new Cart_Lift_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Cart_Lift_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Cart_Lift_i18n();

        $this->loader->add_action('init', $plugin_i18n, 'load_plugin_textdomain');
    }


    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin    = new Cart_Lift_Admin($this->get_plugin_name(), $this->get_version());
        $plugin_cron_job = new Cart_Lift_Cron();
        $cl_db_action    = new Cart_Lift_DB();
        // $special_banner = new Rex_CartLift_Special_Occasion_Banner(
        //     '4th_of_july_deal_2025',
		//    	'2025-11-16 00:00:00',
		//    	'2025-12-07 23:59:59'
        // ); // Date format: YYYY-MM-DD HH:MM:SS 

        if ( !defined( 'CART_LIFT_PRO_VERSION' ) ) {
            new Rex_CartLift_Sales_Notification_Bar(
                'international_mother_language_day_2026',
                '2026-02-20 00:00:00',
                '2026-02-25 23:59:59'
            );
        }

        // $this->loader->add_action('admin_init', $special_banner, 'init');

        $cl_db_version = get_option('cl_db_version');
        if ($cl_db_version < CART_LIFT_DB_VERSION) {
            $this->loader->add_action('plugins_loaded', $cl_db_action, 'cl_update_database');
        }

        $this->loader->add_action('plugins_loaded', $plugin_admin, 'show_admin_notices');

        $this->loader->add_action('init', $plugin_admin, 'cl_plugin_output_buffer');
        $this->loader->add_action('admin_menu', $plugin_admin, 'cart_lift_menu');
        $this->loader->add_action('plugin_action_links_' . CART_LIFT_BASE, $plugin_admin, 'cart_lift_action_links', 10, 4);
        $this->loader->add_action('admin_init', 'Cart_Lift_Ajax', 'init');
        $this->loader->add_action('admin_init', $plugin_admin, 'admin_redirects');



        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($page === 'cart_lift') {
            $this->loader->add_filter('mce_buttons', $plugin_admin, 'cl_mce_button_filter');
            $this->loader->add_filter('mce_external_plugins', $plugin_admin, 'cl_mce_plugin_filter');
            $this->loader->add_filter('admin_title', $plugin_admin, 'cl_admin_title', 10, 2);
            $this->loader->add_filter('parent_file', $plugin_admin, 'cl_highlight_admin_menu', 99999, 2);
            $this->loader->add_filter('submenu_file', $plugin_admin, 'cl_highlight_admin_submenu', 99999, 2);
        }

        /**
         * action scheduler
         */
        $this->loader->add_action('init', $plugin_admin, 'cl_add_action_scheduler');
        $this->loader->add_action('cart_lift_process_scheduled_email_hook', $plugin_cron_job, 'cart_lift_process_scheduled_email_hook');
        $this->loader->add_action('cart_lift_x_days_cart_remove', $plugin_cron_job, 'cart_lift_x_days_cart_remove');

        // wc custom email template
        $this->loader->add_filter('woocommerce_email_classes', $plugin_admin, 'cl_wc_admin_email_template');
        $this->loader->add_filter('woocommerce_email_actions', $plugin_admin, 'cl_wc_admin_email_actions');
        add_action('cart_lift_email_order_details', 'cl_get_email_product_table', 10, 3);

        //setup wizard ajax
        $this->loader->add_action( 'wp_ajax_cart_lift_create_contact', $plugin_admin, 'create_contact' );
        $this->loader->add_action( 'wp_ajax_nopriv_cart_lift_create_contact', $plugin_admin, 'create_contact' );

        //general setting ajax
        $this->loader->add_action( 'wp_ajax_cart_list_save_general_settings', 'Cart_Lift_Ajax', 'save_setup_wizard_settings');
        $this->loader->add_action( 'wp_ajax_cart_list_save_general_settings', 'Cart_Lift_Ajax',  'save_setup_wizard_settings');

        //search proudcts
        $this->loader->add_action( 'wp_ajax_cl_get_products', 'Cart_Lift_Ajax', 'cl_get_products' );
        $this->loader->add_action( 'wp_ajax_nopriv_cl_get_products', 'Cart_Lift_Ajax', 'cl_get_products' );

        //search categories
        $this->loader->add_action( 'wp_ajax_cl_get_categories', 'Cart_Lift_Ajax', 'cl_get_categories' );
        $this->loader->add_action( 'wp_ajax_nopriv_cl_get_categories', 'Cart_Lift_Ajax', 'cl_get_categories' );
    }


    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public  = new Cart_Lift_Public($this->get_plugin_name(), $this->get_version());
        $cart_action    = new Cart_Lift_WC_Actions();
        $wc_action      = new Cart_Lift_WC_Actions('wc');
        $edd_action     = new Cart_Lift_EDD_Actions('edd');
        $lp_action      = new Cart_Lift_LearnPress_Actions('lp');

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_filter('wp', $cart_action, 'restore_cart_data', 10);
        $this->loader->add_filter('wp', $cart_action, 'unsubscribe_abandon_cart_emails', 10);


        $this->loader->add_action('wp_ajax_cl_save_abandon_cart_data', $cart_action, 'save_abandon_cart_data');
        $this->loader->add_action('wp_ajax_nopriv_cl_save_abandon_cart_data', $cart_action, 'save_abandon_cart_data');

        $this->loader->add_action('wp_ajax_cl_remove_gdpr_notice', $cart_action, 'remove_gdpr_notice');
        $this->loader->add_action('wp_ajax_nopriv_cl_remove_gdpr_notice', $cart_action, 'remove_gdpr_notice');


        // wc hooks
        $this->loader->add_action('woocommerce_add_to_cart', $wc_action, 'add_to_cart_action', 9999);
        $this->loader->add_action('woocommerce_cart_item_removed', $wc_action, 'add_to_cart_action', 9999);
        $this->loader->add_action('woocommerce_cart_item_restored', $wc_action, 'add_to_cart_action', 9999);
        $this->loader->add_action('woocommerce_cart_item_set_quantity', $wc_action, 'add_to_cart_action', 9999);
        $this->loader->add_action('woocommerce_calculate_totals', $wc_action, 'add_to_cart_actions_without_email', 9999);
        $this->loader->add_action('woocommerce_new_order', $wc_action, 'delete_cart_data', 99999);
        $this->loader->add_action('woocommerce_order_status_changed', $wc_action, 'change_cart_status', 99999, 3);
        $this->loader->add_action('woocommerce_checkout_fields', $wc_action, 'cl_set_checkout_required_info_wc', 99999);
        $this->loader->add_filter('woocommerce_cart_totals_coupon_label', $wc_action, 'cl_cart_totals_coupon_label');
        $this->loader->add_filter('woocommerce_thankyou', $wc_action, 'delete_other_session_cart_data');

        // edd hooks
        $this->loader->add_action('edd_post_add_to_cart', $edd_action, 'add_to_cart_action', 10, 3);
        $this->loader->add_filter('edd_add_to_cart_item', $edd_action, 'add_to_cart_item');
        $this->loader->add_action('edd_post_remove_from_cart', $edd_action, 'delete_cart_action', 10, 2);
        $this->loader->add_action('edd_update_payment_status', $edd_action, 'update_cart_status_edd', 10, 3);
        $this->loader->add_action('edd_purchase_form_after_email', $edd_action, 'purchase_form_after_email', 99999);
        $this->loader->add_filter('edd_get_cart_discounts_html', $edd_action, 'cl_get_cart_discounts_html', 99999, 4);
        $this->loader->add_action('edd_purchase_form_after_user_info', $edd_action, 'cl_set_checkout_required_info_edd', 99999);
        $this->loader->add_action('edd_payment_saved', $edd_action, 'delete_other_session_cart_data', 10, 2);

        // learnpress hooks
        $this->loader->add_action('learn_press_review_order_before_cart_contents', $lp_action, 'cl_learnpress_checkout_cart_action', 99999);
        $this->loader->add_action('learn-press/checkout/update-order-meta', $lp_action, 'cl_learnpress_action_payment_save', 99999);
        $this->loader->add_action('learn-press/order/status-changed', $lp_action, 'cl_learnpress_update_cart_status', 99999, 3);

        //recaptcha
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'recaptcha_enqueue_scripts');
        $this->loader->add_action('woocommerce_checkout_after_customer_details', $plugin_public, 'add_recaptcha_to_checkout' );
        $this->loader->add_action('edd_purchase_form_user_info_fields', $plugin_public, 'add_recaptcha_to_checkout');
        $this->loader->add_action('woocommerce_after_checkout_validation', $plugin_public, 'verify_recaptcha_on_checkout', 10, 2);
        $this->loader->add_action('edd_checkout_error_checks', $plugin_public, 'verify_recaptcha_on_edd_checkout', 10, 2);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Cart_Lift_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }
    public function register_cart_lift_setup_wizard(): void
    {
        $plugin_admin    = new Cart_Lift_Admin($this->get_plugin_name(), $this->get_version());
        $plugin_admin->cl_redirect_to_setup_wizard();
    }
}
