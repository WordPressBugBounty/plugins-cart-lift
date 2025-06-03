<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Cart_Lift
 * @subpackage Cart_Lift/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cart_Lift
 * @subpackage Cart_Lift/admin
 * @author     RexTheme <info@rextheme.com>
 */
class Cart_Lift_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cart_Lift_Loader as all the hooks are defined
		 * in that particular class.
		 *
		 * The Cart_Lift_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        $adscreen = get_current_screen();

        if ($adscreen->id === "cart-lift_page_rex-cart-lift-setup-wizard") {
            wp_enqueue_style(
                'cart-lift-setup-wizard-manager-style',
                plugin_dir_url( __FILE__ ) . 'css/style.css',
                array(),
                $this->version,
                'all'
            );
        }


        if( ($hook !== 'toplevel_page_cart_lift' ) ){
            return;
        }
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'cart-lift-chart', plugin_dir_url( __FILE__ ) . 'css/chart.min.css', array(), $this->version );
		wp_enqueue_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version );
		wp_enqueue_style( 'nice-select', plugin_dir_url( __FILE__ ) . 'css/nice-select.min.css', array(), $this->version );
		wp_enqueue_style( $this->plugin_name.'-style', plugin_dir_url( __FILE__ ) . 'css/main.css', array(), $this->version );
        wp_style_add_data( $this->plugin_name.'-style', 'rtl', 'replace' );
        wp_enqueue_style( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', [], $this->version );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cart_Lift_Loader as all the hooks are defined
		 * in that particular class.
		 *
		 * The Cart_Lift_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_script( $this->plugin_name.'_global', plugin_dir_url( __FILE__ ) . 'js/cart-lift-global.js', array( 'jquery' ), $this->version, true );
        wp_localize_script( $this->plugin_name.'_global', 'cart_lift_global', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'security' => wp_create_nonce('rex-cart-lift-global-security'),
            ]
        );

        $adscreen = get_current_screen();

        if ($adscreen->id === "cart-lift_page_rex-cart-lift-setup-wizard") {
            wp_enqueue_script(
                'cart-lift-setup-wizard-manager',
                plugin_dir_url( __FILE__ ) . 'js/library/setupwizard.bundle.js',
                array('jquery'),
                $this->version,
                true
            );

            wp_localize_script(
                    'cart-lift-setup-wizard-manager',
                'cart_lift_setup_wizard_obj',
                array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'security' => wp_create_nonce('rex-cart-lift-setup-wizard'),
                    'user_information' => $this->get_logged_in_user_information(),
                    'is_cart_lift_pro_active' => apply_filters('is_cl_pro_active', false),
                )
            );
        }


        if( ($hook !== 'toplevel_page_cart_lift' ) ){
            return;
        }

        if ( 'cart_lift' === filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) ) {
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_script( 'jquery-ui-tabs' );
            wp_enqueue_script( 'cart-lift-chart-js', plugin_dir_url( __FILE__ ) . 'js/chart.min.js', array(), true );
            wp_enqueue_script( 'nice-select-js', plugin_dir_url( __FILE__ ) . 'js/jquery.nice-select.min.js', array( 'jquery' ), true );
            wp_enqueue_script( $this->plugin_name . '-mce', plugin_dir_url( __FILE__ ) . 'js/cart-lift-mce.js', array( 'jquery' ), true );
	        wp_enqueue_script( $this->plugin_name.'-select-2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array('jquery'), true );
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cart-lift-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );
            wp_localize_script( $this->plugin_name, 'cart_lift_js_translatable', array(
                    'abandoned'     => __( 'Abandoned', 'cart-lift' ),
                    'recovered'     => __( 'Recovered', 'cart-lift' ),
                    'cart_overview' => __( 'Cart Overview', 'cart-lift' ),
                )
            );
	        wp_localize_script($this->plugin_name.'-select-2', 'select2_object', array(
		        'ajax_url' => admin_url('admin-ajax.php'),
		        'security' => wp_create_nonce('cart-lift-select2'),
	        ));
        }
	}


    /**
     * returns output buffer
     */
    public function cl_plugin_output_buffer() {
        ob_start();
    }


    public function cl_mce_button_filter($buttons) {
        $buttons[] = 'cl_mce_button';
        return $buttons;
    }


    public function cl_mce_plugin_filter($plugins) {
        $plugins['cl_mce_button'] = CART_LIFT_URL . 'admin/js/cart-lift-mce.js';
        return $plugins;
    }


    /**
     * admin title for cl menus
     *
     * @param $admin_title
     * @param $title
     * @return string
     * @since 1.0.0
     */
    public function cl_admin_title($admin_title, $title) {
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sub_action = filter_input(INPUT_GET, 'sub_action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($sub_action) {
            if($sub_action === 'edit_email_template') {
                return str_replace('Dashboard', __('Edit Campaign', 'cart-lift'), $admin_title);
            }
        }else {
            if($action === 'carts') {
                return str_replace('Dashboard', __('Carts', 'cart-lift'), $admin_title);
            }elseif ($action === 'email_templates') {
                return str_replace('Dashboard', __('Campaigns', 'cart-lift'), $admin_title);
            }elseif ($action === 'settings') {
                return str_replace('Dashboard', __('Settings', 'cart-lift'), $admin_title);
            }elseif ($action === 'compare') {
                return str_replace('Dashboard', __('Free vs Pro', 'cart-lift'), $admin_title);
            }elseif ($action === 'license') {
                return str_replace('Dashboard', __('Manage License', 'cart-lift'), $admin_title);
            }
        }
        return $admin_title;
    }


    /**
     * active class for menu sub-item
     *
     * @param $parent_file
     * @return string
     * @since 1.0.0
     */
    public function cl_highlight_admin_menu($parent_file) {
        global $submenu_file;
        $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($page === 'cart_lift') {
            if(isset($action) && $action !== 'dashboard') {
                $submenu_file = 'admin.php?page=cart_lift&action='.$action;
            }
        }
        return $parent_file;
    }



    public function cl_highlight_admin_submenu($parent_file, $submenu_file) {
        $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($page === 'cart_lift') {
            if(isset($action) && $action !== 'dashboard') {
                $submenu_file = 'admin.php?page=cart_lift&action='.$action;
            }
        }
        return $submenu_file;
    }

    /**
     * Add plugin menu page
     *
     * @since 1.0.0
     */
    public function cart_lift_menu() {
        add_menu_page(
            __('Dashboard', 'cart-lift'),
            __('Cart Lift', 'cart-lift'),
            'manage_options',
            'cart_lift',
            array( $this, 'cart_lift_menu_page' ),
            $this->get_menu_icon(),
            20
        );

        add_submenu_page(
            'cart_lift',
            __('Dashboard', 'cart-lift'),
            __('Dashboard', 'cart-lift'),
            'manage_options',
            'cart_lift'
        );


        add_submenu_page(
            'cart_lift',
            __('All Carts', 'cart-lift'),
            __('All Carts', 'cart-lift'),
            'manage_options',
            'admin.php?page=cart_lift&action=carts'
        );


        add_submenu_page(
            'cart_lift',
            __('Campaigns', 'cart-lift'),
            __('Campaigns', 'cart-lift'),
            'manage_options',
            'admin.php?page=cart_lift&action=email_templates'
        );


        add_submenu_page(
            'cart_lift',
            __('Settings', 'cart-lift'),
            __('Settings', 'cart-lift'),
            'manage_options',
            'admin.php?page=cart_lift&action=settings'
        );

        add_submenu_page( 'cart_lift', 'Cart Lift setup wizard', __('Setup Wizard','cart-lift'),'manage_options', 'rex-cart-lift-setup-wizard', array( $this, 'cart_lift_setup_wizard'));

        if(!apply_filters('is_cl_pro_active', false))  {
            add_submenu_page(
                'cart_lift',
                '',
                '<span class="dashicons dashicons-star-filled" style="font-size: 17px; color: #6e41d3;"></span> ' . __( 'Go Pro', 'cart-lift' ),
                'manage_options', 'go_cl_pro',
                array($this, 'cl_redirect_to_pro')
            );
        }

        do_action('cl_pro_sub_menu_items');
    }

    private function get_menu_icon() {
		return 'data:image/svg+xml;base64,' . base64_encode(        //phpcs:ignore
			'<?xml version="1.0" encoding="utf-8"?>
			<!-- Generator: Adobe Illustrator 27.1.1, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
			<svg width="23" height="22" fill="none" viewBox="0 0 23 22" xmlns="http://www.w3.org/2000/svg"><path fill="#9DA2A7" d="M18.672 4.883l-1.326-.128a.154.154 0 01-.005.055c-.03.41-.341.705-.685.656-.344-.048-.606-.415-.569-.825l-.002-.012-3.622-.352a.432.432 0 010 .098c-.027.38-.33.65-.668.606-.343-.043-.596-.392-.562-.772.003-.017 0-.036.005-.054L9.66 4.002a.453.453 0 00-.484.37l-.648 3.332c1.807 1.095 5.577 2.759 9.214.48 0 0-2.709 4.383-9.46.802l-.806 4.15a.633.633 0 00.446.727c.03.01.06.013.09.017l11.233 1.081a.558.558 0 00.6-.546.296.296 0 00-.004-.073l-.155-1.933-1.032-.068a.483.483 0 01-.439-.48.42.42 0 01.452-.43l.938.064-.075-.918-.159-.01a.476.476 0 01-.413-.505.418.418 0 01.363-.403.22.22 0 01.063 0l.065.006-.076-.924-.17-.01a.476.476 0 01-.413-.504.418.418 0 01.363-.404.266.266 0 01.063 0l.083.005-.2-2.466a.494.494 0 00-.432-.479h.005zm-5.436 1.901c.048.665-.325 1.215-.83 1.23-.505.014-.954-.513-1.002-1.179-.049-.665.324-1.215.834-1.23.505-.014.95.513.998 1.179zm3.457.378c-.01.664-.428 1.175-.933 1.14-.505-.033-.913-.594-.902-1.26.01-.663.428-1.174.932-1.14.505.034.912.59.903 1.26z"/><path fill="#9DA2A7" d="M16.463 7.075c.012.375-.245.677-.578.67-.33-.008-.607-.31-.614-.686-.007-.375.245-.677.577-.67.1.004.19.032.277.078-.088.086-.081.232.006.326.04.046.102.07.16.072a.184.184 0 00.124-.04.639.639 0 01.048.25zm-3.509-.215c.012.375-.245.677-.578.67-.33-.008-.608-.317-.614-.686-.007-.37.245-.677.577-.67.1.004.19.032.277.078a.235.235 0 00.001.327c.08.083.197.097.29.035a.88.88 0 01.047.245v.001zm-.977-2.076c.131.013.24-.05.255-.15.056-.347.224-1.15.684-1.76 1.092-1.445 3.255-1.665 3.65 2.149.015.114.133.214.286.23.177.015.316-.089.305-.222-.06-.612-.3-2.198-1.339-3.14-1.305-1.186-2.648-.072-2.648-.072s-1.138.883-1.43 2.74c-.017.1.096.206.237.225zm6.209 5.552l1.53.105c.243.016.441.24.438.493a.431.431 0 01-.451.436l-1.53-.106a.488.488 0 01-.438-.493c.008-.26.21-.45.451-.435zm.594 1.826l1.53.106c.244.015.44.24.439.493a.431.431 0 01-.451.435l-1.53-.106a.487.487 0 01-.438-.493c.003-.259.208-.458.45-.435zm.624-3.56l.976.068c.243.015.44.238.437.49a.43.43 0 01-.45.432l-.98-.066a.485.485 0 01-.438-.49c.003-.258.208-.456.457-.434h-.002zm1.523 1.847a.48.48 0 01.434.484.425.425 0 01-.446.427.48.48 0 01-.433-.484.426.426 0 01.445-.427zm-1.737 5.75L7.167 14.92c-.335-.032-.612-.298-.68-.646L5.705 2.701c-.046-.367-.334-.664-.681-.7L.902 1.68c-.361-.053-.677.212-.705.585a.917.917 0 00.765.954l3.401.236.784 11.58c.124.67.656 1.195 1.301 1.29.08.041.166.07.254.08l12.395 1.312a.72.72 0 00.791-.646.801.801 0 00-.66-.866l-.036-.009-.001.002zM8.875 19.866c.638-.056 1.087-.642 1.003-1.308-.083-.667-.668-1.162-1.305-1.106-.638.056-1.087.642-1.003 1.308.083.667.668 1.162 1.305 1.106zm7.022.502c.637-.056 1.086-.642 1.003-1.308-.084-.666-.668-1.161-1.306-1.105-.637.056-1.086.642-1.002 1.308.083.666.668 1.161 1.305 1.105z"/><path fill="#9DA2A7" d="M3.928 6.097l-2.295-.141c-.39-.028-.703-.327-.7-.679.004-.345.329-.608.72-.588l2.17.14.107 1.266-.002.002zm.116 2.39l-1.727-.105c-.294-.016-.53-.315-.527-.665.004-.343.252-.61.547-.592l1.634.107.075 1.256-.002-.001z"/></svg>

			'
		);
	}


    public static function cl_redirect_to_pro() {
        $pro_url = add_query_arg( 'cl-dashboard', '1', 'https://rextheme.com/cart-lift' );
        wp_redirect($pro_url);
        exit();
    }


    /**
     * render menu page contents
     *
     * @since 1.0.0
     */
    public function cart_lift_menu_page() {
        $tab_view = new Cart_Lift_Tab_View();
        require_once CART_LIFT_DIR . '/admin/partials/cart-lift-tabs-display.php';
    }


    /**
     * Get the action links
     *
     * @param $actions
     * @param $plugin_file
     * @param $plugin_data
     * @param $context
     * @return array
     */
    public function cart_lift_action_links($actions, $plugin_file, $plugin_data, $context) {
        $actions['dashboard'] = sprintf(
            '<a href="%s">%s</a>',
            esc_url( admin_url( 'admin.php?page=cart_lift' ) ),
            esc_html__( 'Dashboard', 'cart-lift' )
        );
        $actions['documentation'] = sprintf(
            '<a href="%s" target="_blank">%s</a>',
            esc_url( 'https://rextheme.com/docs/cart-lift?cl-dashboard=1' ),
            esc_html__( 'Documentation', 'cart-lift' )
        );

        if(!apply_filters('is_cl_pro_active', false))  {
            $actions['go-pro'] = sprintf(
                '<a href="%s" target="_blank"  style="color: #6e42d3; font-weight: bold;">%s</a>',
                esc_url( 'https://rextheme.com/cart-lift?cl-dashboard=1' ),
                esc_html__( 'Go Pro', 'cart-lift' )
            );
        }
        return $actions;
    }


    /**
     * Set the cart status to abandoned and
     * send abandoned cart emails
     *
     * Ran every 15 minutes
     *
     * @internal
     */
    public function cl_add_action_scheduler() {

        if ( false === as_next_scheduled_action( 'cart_lift_process_scheduled_email_hook' ) ) {
            $schedule_time = apply_filters( 'cart_lift_cron_schedule', '*/10 * * * *' );
            wp_clear_scheduled_hook( 'cart_lift_process_scheduled_email_hook' );
            as_schedule_cron_action( time(), $schedule_time, 'cart_lift_process_scheduled_email_hook' );
        }

        if ( false === as_next_scheduled_action( 'cart_lift_x_days_cart_remove' ) ) {
            $general_settings = get_option( 'cl_general_settings' );
            if( !empty( $general_settings['enable_cart_expiration']) ){
                $current_timestamp = current_time('timestamp');
                wp_clear_scheduled_hook( 'cart_lift_x_days_cart_remove' );
                as_schedule_cron_action( $current_timestamp , '0 2 * * *', 'cart_lift_x_days_cart_remove' );
            }
        }
    }


    /**
     * 15 min cron schedule
     *
     * @param $schedules
     * @return mixed
     */
     public function cl_custom_schedules() {
         $cron_time = apply_filters( 'cart_lift_cron_interval', 15 );
         $schedules['cl_fifteen_minutes'] = array(
             'interval'  => $cron_time * 60,
             'display'   => sprintf(__( 'Every %d Minutes', 'cart-lift' ), $cron_time),
         );
         return $schedules;
     }


    /**
     * @param $email_classes
     * @return array
     * @since 1.0.0
     */
     public function cl_wc_admin_email_template($email_classes) {
         require_once CART_LIFT_DIR.'/includes/wc-email/class-cart-lift-abandoned-email-template.php';
         $email_classes['Cart_Lift_Admin_Abandoned_Email_Template'] = new Cart_Lift_Admin_Abandoned_Email_Template();
         return $email_classes;
     }


    /**
     * @param $actions
     * @return array
     * @since 1.0.0
     */
     public function cl_wc_admin_email_actions($actions) {
         $actions[] = 'cl_trigger_abandon_cart_email';
         return $actions;
     }


    /**
     * Show admin notices after all plugins loaded.
     */
     public function show_admin_notices() {
         if( cl_is_paddle_gateway_active() ) {
             add_action( 'admin_notices', [ $this, 'show_paddle_notice' ] );
         }
     }


    /**
     * show notice for paddle
     * payment gateway
     *
     * @internal
     */
     public function show_paddle_notice() {
         $notice_meta = get_option('cl_paddle_notice', false);
         $start_date = new DateTime(current_time(CART_LIFT_DATETIME_FORMAT));
         $message = __( 'If you are using Paddle Payment Gateway, please ensure that you have disabled Abandoned Cart Recovery from there.', 'cart-lift' );
         if($notice_meta) {
             $since_start = $start_date->diff(new DateTime($notice_meta['time']));
             if($since_start->m >= 1) {
                 ?>
                 <div class="notice notice-success cl-paddle-notice is-dismissible">
                     <p><?php echo $message; ?></p>
                 </div>
                 <?php
             }
         }else {
             ?>
             <div class="notice notice-success cl-paddle-notice is-dismissible">
                 <p><?php echo $message; ?></p>
             </div>
             <?php
         }
     }

        public function cart_lift_setup_wizard(): void
        {
            add_action('admin_menu', function () {
                add_dashboard_page('Setup Wizard', 'Cart-Lift Setup Wizard', 'manage_options', 'cart-lift-setup-wizard', function () {
                    return '';
                });
            });
            add_action('current_screen', function () {
                ( new Cart_Lift_Setup_Wizard() )->setup_wizard();
            }, 999);
        }

    /**
     * Creates a contact using the provided name and email.
     *
     * This function verifies a nonce for security, then extracts the name and email
     * from the POST request. It then creates a new contact instance and sends it via webhook.
     *
     * @since 3.1.15
     * @return void
     */
    public function create_contact() {
        $nonce = filter_input(INPUT_POST, 'security', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( !wp_verify_nonce( $nonce, 'rex-cart-lift-setup-wizard' ) ) {
            wp_send_json_error( array( 'message' => __('Unauthorized request', 'rex-product-feed') ), 400 );
            return;
        }

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $name = !empty( $name) ? $name  : '';


        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $email = !empty($email) ? $email : '';

        if ( empty( $email ) ) {
            wp_send_json_error( array( 'message' => __('Email is required', 'rex-product-feed') ), 400 );
        }elseif(!is_email( $_POST['email'])){
            wp_send_json_error( array( 'message' => __('Email is invalid', 'rex-product-feed') ), 400 );
        }

        $create_contact_instance = new Cart_Lift_Create_Contact( $email, $name );

        $response = $create_contact_instance->create_contact_via_webhook();

        if ( $response ) {
            wp_send_json_success( array( 'message' => __('Contact created successfully', 'rex-product-feed') ), 200 );
        } else {
            wp_send_json_error( array( 'message' => __('Failed to create contact', 'rex-product-feed') ), 500 );
        }
    }

    /**
     * Redirects the user to the setup wizard after activation.
     *
     * @since 8.4.10
     */
    public function admin_redirects()
    {
        $this->cl_redirect_to_setup_wizard();
    }

    /**
     * Retrieve the currently logged-in user's email and name.
     *
     * @since 8.4.10
     *
     * @return array An associative array containing the logged-in user's email and name.
     */
    public function get_logged_in_user_information(): array
    {
        $admin_user = wp_get_current_user();
        return array(
            'email' => !empty( $admin_user->user_email ) ? $admin_user->user_email : '',
            'name' => !empty( $admin_user->display_name ) ? $admin_user->display_name : '',
        );
    }
    

    /**
     * Redirects the user to the setup wizard after activation.
     *
     * This function checks for required plugins and verifies if the setup wizard page is being accessed.
     * If the conditions are met, it adds the setup wizard page to the admin menu and triggers the setup wizard.
     * Additionally, it handles the redirection to the setup wizard after plugin activation.
     *
     * @since 3.1.17
     */
    public function cl_redirect_to_setup_wizard() {
        $required_plugins = array(
            'WooCommerce' => 'woocommerce/woocommerce.php',
            'Easy Digital Download' => 'easy-digital-downloads/easy-digital-downloads.php',
            'Easy Digital Downloads (Pro)' => 'easy-digital-downloads-pro/easy-digital-downloads.php',
        );

        $dependency = new Cart_Lift_Dependency_Checker( $required_plugins, __FILE__, 'cart-lift' );
        if ( $dependency->is_active() && !empty($_GET['page']) && 'rex-cart-lift-setup-wizard' == sanitize_text_field($_GET['page'])) {
            add_action('admin_menu', function () {
                add_dashboard_page('Setup Wizard', 'Cart-Lift Setup Wizard', 'manage_options', 'cart-lift-setup-wizard', function () {
                    return '';
                });
            });
            add_action('current_screen', function () {
                (new Cart_Lift_Setup_Wizard())->setup_wizard();
            }, 999);

        }

        if ( $dependency->is_active() && get_transient( 'rex_cart_lift_activation_redirect' ) ) {
            $do_redirect = true;
            // On these pages, or during these events, postpone the redirect.
            if ( wp_doing_ajax() || is_network_admin() || !current_user_can( 'manage_options' ) ) {
                $do_redirect = false;
            }

            if ( $do_redirect ) {
                delete_transient( 'rex_cart_lift_activation_redirect' );
                $url = admin_url( 'admin.php?page=rex-cart-lift-setup-wizard' );
                wp_safe_redirect( wp_sanitize_redirect( esc_url_raw( $url ) ) );
                exit;
            }
        }
    }
}
