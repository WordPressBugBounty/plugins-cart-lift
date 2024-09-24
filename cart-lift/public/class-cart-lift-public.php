<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Cart_Lift
 * @subpackage Cart_Lift/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cart_Lift
 * @subpackage Cart_Lift/public
 * @author     RexTheme <info@rextheme.com>
 */
class Cart_Lift_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cart_Lift_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cart_Lift_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cart-lift-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cart_Lift_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cart_Lift_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        $general_settings = cl_get_general_settings();

        $current_user = wp_get_current_user();
        $roles  = $current_user->roles;

        $role   = array_shift( $roles );
        if (array_key_exists($role, $general_settings)) {
            if ($general_settings[$role] == 1) {
                return;
            }
        }

        $gdpr_enabled = 0;
        if($general_settings['enable_gdpr']) {
            if(isset( $_COOKIE['cart_lift_skip_tracking_data'])) {
                if($_COOKIE['cart_lift_skip_tracking_data']) {
                    $gdpr_enabled = 0;
                }else {
                    $gdpr_enabled = 1;
                }
            }else {
                $gdpr_enabled = 1;
            }
        }
        
        wp_enqueue_script( $this->plugin_name. 'cookie', plugin_dir_url( __FILE__ ) . 'js/js.cookie.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cart-lift-public.js', array( 'jquery' ), $this->version, true );
        wp_localize_script($this->plugin_name, 'cl_localized_vars', array(
            'ajaxurl'       => admin_url( 'admin-ajax.php' ),
            'security'      => wp_create_nonce( 'cart-lift' ),
            'gdpr_nonce'    => wp_create_nonce( 'cart-lift-disable-gdpr' ),
            'gdpr'          => $gdpr_enabled,
            'gdpr_messages' => $general_settings[ 'gdpr_text' ],
            'no_thanks'     => __( 'No thanks', 'cart-lift' ),
            'enable_exclude_countries' => $this->enable_excluded_countries(),
        ));
	}

    /**
     * Enqueue reCAPTCHA scripts.
     *
     * @since 3.1.15
     */
    public function recaptcha_enqueue_scripts() {
        $recaptcha_v3_setting = get_option('cl_recaptcha_settings');
        if( !empty( $recaptcha_v3_setting  ) && is_array($recaptcha_v3_setting ) && !empty( $recaptcha_v3_setting['recaptcha_enable_status'] ) ){
            wp_enqueue_script('cart_lift_recaptcha', 'https://www.google.com/recaptcha/api.js?render=6LfSTBsqAAAAAAmrlwmKoC430XTdlPwaJO5BbRin');
        }
    }

    /**
     * Add reCAPTCHA to checkout.
     *
     * @since 3.1.15
     */
    public function add_recaptcha_to_checkout() {
        $recaptcha_v3_setting = get_option('cl_recaptcha_settings');
        $recaptcha_v3_site_key = $recaptcha_v3_setting['recaptcha_site_key'] ?? '';
        $recaptcha_v3_enable_status = $recaptcha_v3_setting['recaptcha_enable_status'] ?? '';
        if( !empty( $recaptcha_v3_enable_status ) && 1 === $recaptcha_v3_enable_status ){
        ?>
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute('<?php echo esc_js($recaptcha_v3_site_key); ?>', {action: 'checkout'}).then(function(token) {
                    var recaptchaResponse = document.getElementById('cartLiftRecaptchaResponse');
                    if (recaptchaResponse) {
                        recaptchaResponse.value = token;
                    }
                });
            });
        </script>
        <input type="hidden" name="cart_lift_recaptcha_response" id="cartLiftRecaptchaResponse">
        <?php
        }
    }

    /**
     * Verify reCAPTCHA on checkout.
     *
     * @since 3.1.15
     */
    public function verify_recaptcha_on_checkout($data, $errors) {
        $recaptcha_v3_setting = get_option('cl_recaptcha_settings');
        $recaptcha_secret = $recaptcha_v3_setting['recaptcha_secret_key'] ?? '';
        $recaptcha_v3_score = $recaptcha_v3_setting['recaptcha_score'] ?? '0.5';
        $recaptcha_enable_status = $recaptcha_v3_setting['recaptcha_enable_status'] ?? '';

        if( empty( $recaptcha_enable_status ) || 1 !== $recaptcha_enable_status ) {
            return;
        }

        $recaptcha_response = filter_input(INPUT_POST, 'cart_lift_recaptcha_response', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( empty( $recaptcha_response ) ) {
            $errors->add('validation', __('reCAPTCHA verification failed.','cart-lift'));
            return;
        }


        $recaptcha_url = sanitize_url('https://www.google.com/recaptcha/api/siteverify');

        $response = wp_remote_post($recaptcha_url, array(
            'body' => array(
                'secret' => $recaptcha_secret,
                'response' => $recaptcha_response
            )
        ));

        $response_body = wp_remote_retrieve_body($response);
        $result = json_decode($response_body, true);

        if (!$result['success'] || $result['score'] < (float)$recaptcha_v3_score) {
            $errors->add('validation', __('reCAPTCHA verification failed.', 'cart-lift'));
        }
    }

    /**
     * Verifies the reCAPTCHA response during the EDD checkout process.
     *
     * This method checks the validity of the reCAPTCHA response submitted during checkout.
     * It retrieves the reCAPTCHA settings from the options, sends a request to the Google reCAPTCHA API,
     * and validates the response based on the provided secret key and score threshold.
     * If the reCAPTCHA verification fails, it adds a validation error to the checkout errors.
     *
     * @param array $data The checkout data array, which includes user-submitted fields.
     * @param WP_Error $errors The errors object where validation errors can be added.
     *
     * @return void
     *
     * @since 3.1.15
     */
  public function verify_recaptcha_on_edd_checkout($data, $errors){
      $recaptcha_v3_setting = get_option('cl_recaptcha_settings');
      $recaptcha_secret = $recaptcha_v3_setting['recaptcha_secret_key'] ?? '';
      $recaptcha_v3_score = $recaptcha_v3_setting['recaptcha_score'] ?? '0.5';
      $recaptcha_enable_status = $recaptcha_v3_setting['recaptcha_enable_status'] ?? '';

      if( empty( $recaptcha_enable_status ) || 1 !== $recaptcha_enable_status ) {
          return;
      }

      $recaptcha_response = filter_input(INPUT_POST, 'cart_lift_recaptcha_response', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      if (empty( $recaptcha_response  ) ) {
          edd_set_error('validation', __('reCAPTCHA verification failed.','cart-lift'));
          return;
      }

      $recaptcha_url = sanitize_url('https://www.google.com/recaptcha/api/siteverify');

      $response = wp_remote_post($recaptcha_url, array(
          'body' => array(
              'secret' => $recaptcha_secret,
              'response' => $recaptcha_response
          )
      ));

      $response_body = wp_remote_retrieve_body($response);
      $result = json_decode($response_body, true);

      if (!$result['success'] || $result['score'] < (float)$recaptcha_v3_score) {
          edd_set_error('validation', __('reCAPTCHA verification failed.', 'cart-lift'));
      }
  }

    /**
     * Check if excluded countries feature is enabled.
     *
     * This function retrieves the general settings and checks if the excluded countries
     * feature is enabled. It returns a boolean indicating the status of the feature.
     *
     * @since 3.1.15
     * @return bool True if the excluded countries feature is enabled, false otherwise.
     */
  public function enable_excluded_countries(){
      $general_settings = get_option('cl_general_settings');
      $excluded_countries = $general_settings['enable_cl_exclude_countries'] ?? '';
      $excluded_countries_status = false;
      if( !empty( $excluded_countries ) && $excluded_countries == '1' ){
          $excluded_countries_status = true;
      }
      return $excluded_countries_status;
  }

}
