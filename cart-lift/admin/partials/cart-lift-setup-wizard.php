<?php

/**
 * Setup wizard view
 *
 * @package ''
 * @since 7.4.14
 */
?>

<!DOCTYPE html>
<html style="background-color: #F2EFFF;" lang="en" xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php esc_html_e('Cart-lift - Setup Wizard', 'cart-lift'); ?></title>
    <?php do_action('admin_enqueue_scripts'); ?>
    <?php do_action('admin_print_styles'); ?>
    <?php do_action('admin_head'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">
        addLoadEvent = function(func) {
            if (typeof jQuery != "undefined") jQuery(document).ready(func);
            else if (typeof wpOnload != 'function') {
                wpOnload = func;
            } else {
                var oldonload = wpOnload;
                wpOnload = function() {
                    oldonload();
                    func();
                }
            }
        };
        var ajaxurl = '<?php echo admin_url('admin-ajax.php', 'relative'); ?>';
        var pagenow = '';
    </script>
</head>

<body>
    <div class="wp-cart-lift-setup-wizard__container">
        <div class="setup-wizard__inner-container">
            <div id="wizardContainer" style="height:100vh;">

            </div>
        </div>
    </div>
    <?php
    wp_enqueue_media(); // add media
    wp_print_scripts(); // window.wp
    do_action('admin_footer');
    $data = array(
        'stepOne' => array(
            'step_text' => __("Welcome", "cart-lift"),
            'heading' => __("Hello, welcome to", "cart-lift"),
            'strong_heading' => array(
                __("Cart Lift", "cart-lift"),
            ),
            'strong_description' => __("Product Feed Manager for WooCommerce,", "cart-lift"),
            'description' => __("Recover over 20% of your abandoned cart customers with automated e-mail campaigns. Enjoy immediate increase in your ROI.", "cart-lift"),
            'img_alt' => __("Preview video image ", "cart-lift"),
            'button_text' => array(
                __("Let’s create your first Campaign", "cart-lift"),
                __("Check the guide", "cart-lift"),
            ),
            'pfm_feature_content' => array(
                __("Cart Lift Features", "cart-lift"),
                __("Cart Recovery Made Easy With Cart Lift!", "cart-lift"),
            ),

            'pfm_feature_heading' => array(
                __('Automated recovery', 'cart-lift'),
                __('Proven email templates', 'cart-lift'),
                __('Detailed reports', 'cart-lift'),
                __('Track abandoned orders', 'cart-lift'),
                __('Smart discount coupons', 'cart-lift'),
                __('Exit intent popup', 'cart-lift'),
            ),

            'pfm_feature_description' => array(
                __('Extensive Filtering Options For Exporting A Precised Feed', 'cart-lift'),
                __('Tailor Your Product Feed to Perfection with Feed Rules', 'cart-lift'),
                __('Track Facebook Pixel To Measure Feed Performance', 'cart-lift'),
                __('Customize Compelling Product Titles With Combined Attributes', 'cart-lift'),
                __('Manipulate Your Product Price Using Dynamic Pricing', 'cart-lift'),
                __("Unleash Your Store's Global Potential with Multilingual Capabilities", 'cart-lift'),
            ),

            'pfm_feature_pro_heading' => array(
                __("Cart Lift ", "cart-lift"),
                __("Pro Features", "cart-lift"),
            ),

            'pfm_feature_pro_list_heading' => array(
                __('Notification For Recovered Cart', 'cart-lift'),
                __('Manual Recovery Process', 'cart-lift'),
                __('Disable Branding', 'cart-lift'),
                __('Weekly Report', 'cart-lift'),
                __('GDPR Integration', 'cart-lift'),
                __('Cart Lift Popup', 'cart-lift'),
                __('Twilio SMS Notification', 'cart-lift'),
                __('Promotional discount', 'cart-lift'),
            ),

        ),

        'stepTwo' => array(
            'step_text' => __("Plugins & Settings", "cart-lift"),
            'heading' => __("Necessary", "cart-lift"),
            'strong_heading' => array(
                __("Plugins", "cart-lift"),
            ),
            'label' => __(" License Key", "cart-lift"),
            'button_text' => array(
                __("Activate License", "cart-lift"),
                __("Next", "cart-lift"),
            ),
            'error_text' => __('Please enter a valid one.', 'cart-lift'),
            'strong_error_text' => __('Invalid license key', 'cart-lift'),
            'success_text' => __('Success', 'cart-lift'),
        ),

        'stepThree' => array(
            'step_text' => __("Done", "cart-lift"),
            'heading' => __("Get", "cart-lift"),
            'testimonials_description' => array(
                __("We are searching for a simple and clean solution for a long time and we found that solution. As PRO license consumers we can highly recommend this plugin. This is absolutely a must-have feature for the e-commerce marketplace.
                Graftype", "cart-lift"),
                __("Really, if I could give you 1000 stars, not 5. I started with buying Cart Lift, and now I am happy that I bought the agency plan.
                The Cart Lift and all the plugins do their job, but what distinguishes the team is that it listens to your suggestions and implements them, as well as listens to your problems and solves them", "cart-lift"),
            ),
            'testimonials_author' => array(
                __("Graftype", "cart-lift"),
                __("Ale320", "cart-lift"),
            ),
            'button_text' => array(
                __("Let’s create your first feed", "cart-lift"),
                __("Upgrade To Pro", "cart-lift"),
            ),
        ),

        'stepFour' => array(
            'step_text' => __("Select Merchant", "cart-lift"),
            'heading' => __("Select Your ", "cart-lift"),
            'strong_heading' => array(
                __("Favourite Merchant", "cart-lift"),
            ),
            'button_text' => array(
                __("Google Shopping", "cart-lift"),
                __("Facebook", "cart-lift"),
                __("Etsy", "cart-lift"),
                __("Bing", "cart-lift"),
                __("eBay", "cart-lift"),
            ),
        ),
    );

    ?>
    <script type="text/javascript">
        const rex_wpvr_wizard_translate_string = <?php echo wp_json_encode($data); ?>;
        const logoUrl = <?php echo json_encode(esc_url(CART_LIFT_ASSET_PATH . 'setup-wizard-images/wpcr-logo.webp')); ?>;
        const bannerUrl = <?php echo json_encode(esc_url(CART_LIFT_ASSET_PATH . 'setup-wizard-images/welcome-image.webp')); ?>;
        const thumnailImage = <?php echo json_encode(esc_url(CART_LIFT_ASSET_PATH . 'setup-wizard-images/youtube-thumbnail.webp')); ?>;
        const woocommerceUrl = <?php echo json_encode(esc_url(CART_LIFT_ASSET_PATH . 'setup-wizard-images/woocommerce-logo.webp')); ?>;
        const eddUrl = <?php echo json_encode(esc_url(CART_LIFT_ASSET_PATH . 'setup-wizard-images/edd.webp')); ?>;
        const wcStatus = <?php echo json_encode( defined( 'WC_VERSION' ) ? 'Active' : 'Inactive' ); ?>;
        const eddStatus = <?php echo json_encode( defined( 'EDD_VERSION' ) ? 'Active' : 'Inactive' ); ?>;
    </script>
    <script src="<?php echo  CART_LIFT_ADMIN_JS_PATH . '/setup-wizard/setup_wizard.js'; ?>'"></script>
</body>

</html>