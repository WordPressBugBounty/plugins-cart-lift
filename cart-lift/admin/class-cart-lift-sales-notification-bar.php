<?php

/**
 * Rex_CartLift_Sales_Notification_Bar Class
 *
 * This class is responsible for displaying the sales notification banner in the WordPress admin.
 *
 * @since 3.1.15
 */
class Rex_CartLift_Sales_Notification_Bar
{
    /**
     * Rex_CartLift_Sales_Notification_Bar constructor.
     *
     * @since 3.1.15
     * 
     * 
     * 
     */

    /**
     * The occasion identifier.
     *
     * @var string
     */
    private $occasion;

    /**
     * The start date and time for displaying the banner.
     *
     * @var int
     */
    private $start_date;

    /**
     * The end date and time for displaying the banner.
     *
     * @var int
     */
    private $end_date;

    /**
     * Constructor method for SpecialOccasionBanner class.
     *
     * @param string $occasion   The occasion identifier.
     * @param string $start_date The start date and time for displaying the banner.
     * @param string $end_date   The end date and time for displaying the banner.
     */
   public function __construct($occasion, $start_date, $end_date)
    {
        $this->occasion     = "rex_cart_{$occasion}";
        $this->start_date   = strtotime($start_date);
        $this->end_date     = strtotime($end_date);

        $current_date_time = current_time('timestamp');

        // if (
        //     !defined('CART_LIFT_PRO_VERSION')
        //     && ($current_date_time >= $this->start_date && $current_date_time <= $this->end_date)
        // ) {
            // Hook into the admin_notices action to display the banner
            add_action( 'admin_notices', [ $this, 'display_banner' ] );
            // Add styles
            add_action( 'admin_head', [ $this, 'enqueue_css' ] );

	        add_action( 'wp_ajax_cart_lift_sales_notification_notice', [ $this, 'cart_lift_sales_notification_notice' ] );
            add_action( 'wp_ajax_nopriv_cart_lift_sales_notification_notice', [ $this, 'cart_lift_sales_notification_notice' ] );
        // }
        
    }


    /**
     * Displays the special occasion banner if the current date and time are within the specified range.
     *
     * @since 3.1.15
     */
    public function display_banner() {
        $screen          = get_current_screen();
        $allowed_screens = [ 'dashboard', 'plugins', 'cart_lift' ];

        if ( !in_array( $screen->base, $allowed_screens ) && !in_array( $screen->parent_base, $allowed_screens ) && !in_array( $screen->post_type, $allowed_screens ) && !in_array( $screen->parent_file, $allowed_screens ) ) {
            return;
        }

        if ( $screen->base === 'plugins' || $screen->base === 'dashboard' ) {
            if ( defined( 'REX_SPECIAL_OCCASION_BANNER_SHOWN_GLOBAL' ) ) {
                return;
            }
            define( 'REX_SPECIAL_OCCASION_BANNER_SHOWN_GLOBAL', true );
        }

        // Check if banner was dismissed within last 5 days
        $dismissed_option = $this->occasion . '_dismissed';
        $dismissed_time = get_option($dismissed_option, 0);
        if ($dismissed_time && (time() - $dismissed_time) < 432000) {
            return; // Don't show if dismissed within last 5 days
        }

        $base_url = esc_url( 'https://rextheme.com/ugc-for-woocommerce-ugcify/' );

        $utm_params = array(
            'utm_source'   => 'plugin',
            'utm_medium'   => 'dashboard-banner-cl',
            'utm_campaign' => 'ugcify-early-access',
        );

        $btn_link = add_query_arg( $utm_params, $base_url );

        $img_url  = plugin_dir_url(__FILE__) . '/images/banner-images/ramadan-kareem.webp'; 
        $img_path = plugin_dir_path(__FILE__) . '/images/banner-images/ramadan-kareem.webp';
        $img_size = getimagesize($img_path);
        $img_width  = $img_size[0];
        $img_height = $img_size[1];


        ?>
        <div class="cart-lift-promo-banner-area">

            <section class="cart-lift-promo-banner cart-lift-promo-banner--regular" aria-labelledby="cart-lift-promo-banner-title" id="cart-lift-promo-banner">
                  <div class="cart-lift-regular-promotional-banner" id="cart-lift-regular-promotional-banner" role="region" aria-labelledby="banner-flash-title">

                <div class="cart-lift-regular-promotional-banner-container">

                    <div class="cart-lift-regular-promotional-banner-content" id="banner-flash">

                        <!-- Close Button -->
                        <button class="cart-lift-close-btn"
                                type="button"
                                aria-label="<?php esc_attr_e('Close banner', 'cart-lift'); ?>"
                                id="cart-lift-promo-banner__cross-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 9 9" fill="none">
                                <path d="M7.77482 0.75L0.75 7.75" stroke="#7E7E7E" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M7.77482 7.75L0.75 0.75" stroke="#7E7E7E" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </button>

                        <!-- Banner Title + Timer -->
                        <div class="cart-lift-regular-promotional-banner-title">

                            <div class="banner-logo-area">
                                <span class="new-tool-text"><?php echo __('New Tool Coming:', 'cart-lift'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="20" viewBox="0 0 23 20" fill="none"><path d="M1.06618 12.1359C8.90142 22.3152 17.4084 17.0302 21.0104 12.1256C21.0976 12.0069 21.286 12.1052 21.2364 12.2439C17.5346 22.5755 4.52336 22.5942 0.84551 12.2448C0.797424 12.1095 0.97859 12.0221 1.06618 12.1359Z" fill="#201cfe"/><path d="M3.38441 14.7801C7.55671 19.3578 14.4767 19.3577 18.6683 14.7962L19.2305 18.0621C19.4048 19.0746 18.6283 20 17.6009 20H4.4095C3.37407 20 2.59495 19.0608 2.78274 18.0425L3.38441 14.7801ZM18.4936 13.7809C14.7261 17.1915 8.66183 18.7568 3.58106 13.7132L5.28946 4.4507H16.8876L18.4936 13.7809ZM11.4455 8.30703C11.1791 7.76715 10.7417 7.76715 10.4726 8.30703L9.97917 9.30214C9.91189 9.44065 9.73246 9.57354 9.58387 9.59899L8.68947 9.74872C8.11753 9.84484 7.98578 10.2633 8.39511 10.676L9.09037 11.377C9.20812 11.4957 9.27268 11.7247 9.23624 11.8887L9.0371 12.7566C8.88013 13.4406 9.24466 13.7091 9.84463 13.3502L10.6829 12.8498C10.8371 12.7594 11.0866 12.7593 11.238 12.8498L12.0763 13.3502C12.679 13.7091 13.0407 13.4435 12.8837 12.7566L12.6847 11.8887C12.6482 11.7247 12.7127 11.4957 12.8304 11.377L13.5258 10.676C13.9379 10.2633 13.8032 9.84483 13.2313 9.74872L12.337 9.59899C12.1856 9.57355 12.0062 9.44065 11.9389 9.30214L11.4455 8.30703Z" fill="#201cfe"/><path d="M2.13955 12.1528C1.65099 12.0253 0.571895 12.1146 0.164049 13.491C-0.473269 12.5989 0.864968 10.942 2.13955 12.1528Z" fill="#201cfe"/><path d="M21.652 13.492C21.5932 12.9905 21.118 12.0176 19.6874 12.1378C20.287 11.2198 22.3169 11.8646 21.652 13.492Z" fill="#201cfe"/><path d="M14.02 3.50497C14.02 2.57539 13.6977 1.68389 13.1241 1.02658C12.5504 0.369272 11.7724 7.01809e-08 10.9611 0C10.1499 -7.01809e-08 9.37184 0.369272 8.7982 1.02658C8.22455 1.68389 7.90228 2.57539 7.90228 3.50496H8.7852C8.7852 2.84371 9.01445 2.20953 9.42252 1.74195C9.83058 1.27437 10.384 1.01169 10.9611 1.01169C11.5382 1.01169 12.0917 1.27437 12.4997 1.74195C12.9078 2.20953 13.1371 2.84371 13.1371 3.50497H14.02Z" fill="#201cfe"/></svg>
                                <span class="tool-name">UGCify</span>
                            </div>

                            <div class="banner-text">
                                <?php echo __('Build trust and increase conversions with UGC for WooCommerce!', 'cart-lift'); ?>
                            </div>

                            <!-- Countdown Timer -->
                            <div class="cart-lift-timer" style="display: none;">
                                <div class="cart-lift-timer-box">
                                    <span class="cart-lift-timer-number" id="cl_days">12</span>
                                    <span class="cart-lift-timer-label">DAY</span>
                                </div>
                                <div class="cart-lift-timer-box">
                                    <span class="cart-lift-timer-number" id="cl_hours">10</span>
                                    <span class="cart-lift-timer-label">HR</span>
                                </div>
                                <div class="cart-lift-timer-box">
                                    <span class="cart-lift-timer-number" id="cl_minutes">45</span>
                                    <span class="cart-lift-timer-label">MIN</span>
                                </div>
                                <div class="cart-lift-timer-box">
                                    <span class="cart-lift-timer-number" id="cl_seconds">30</span>
                                    <span class="cart-lift-timer-label">SEC</span>
                                </div>
                            </div>

                        </div>

                        <!-- CTA Button -->
                        <a href="<?php echo esc_url( $btn_link ); ?>"
                        target="_blank"
                        class="cart-lift-regular-promotional-banner-link"
                        role="button"
                        aria-label="<?php esc_attr_e('Request Early Access for UGCify', 'cart-lift'); ?>">
                            <?php esc_html_e('Request Early Access', 'cart-lift'); ?>
                            <span class="arrow-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="9" viewBox="0 0 8 9" fill="none"><path d="M8 0.703124V8.29686C8 8.48334 7.93415 8.66218 7.81694 8.79405C7.69973 8.92591 7.54076 8.99999 7.375 8.99999C7.20924 8.99999 7.05027 8.92591 6.93306 8.79405C6.81585 8.66218 6.75 8.48334 6.75 8.29686V2.40061L1.06695 8.79406C0.949738 8.92592 0.790765 9 0.625004 9C0.459243 9 0.30027 8.92592 0.183059 8.79406C0.0658485 8.6622 0 8.48335 0 8.29687C0 8.11039 0.0658485 7.93155 0.183059 7.79968L5.86613 1.40625H0.625012C0.459252 1.40625 0.300281 1.33217 0.183071 1.20031C0.0658608 1.06845 1.28672e-05 0.889604 1.28672e-05 0.703124C1.28672e-05 0.516644 0.0658608 0.337802 0.183071 0.20594C0.300281 0.0740789 0.459252 0 0.625012 0L7.375 0C7.54076 0 7.69973 0.0740789 7.81694 0.20594C7.93415 0.337802 8 0.516644 8 0.703124Z" fill="#201cfe"/></svg>
                            </span>
                        </a>

                    </div>
                </div>
            </div>

            </section>
        </div>

        <script>

            document.addEventListener("DOMContentLoaded", function () {
                if (typeof tinymce !== "undefined") {
                    // safe to use tinymce
                    console.log("TinyMCE loaded");
                } else {
                    console.warn("TinyMCE not available");
                }
            });

            (function () {
                // Get timer elements
                const daysEl = document.getElementById('cl_days');
                const hoursEl = document.getElementById('cl_hours');
                const minutesEl = document.getElementById('cl_minutes');
                const secondsEl = document.getElementById('cl_seconds');
                const banner = document.getElementById('cart-lift-promo-banner');

                // Get labels (next siblings of timer numbers)
                const daysLabel = daysEl ? daysEl.nextElementSibling : null;
                const hoursLabel = hoursEl ? hoursEl.nextElementSibling : null;
                const minutesLabel = minutesEl ? minutesEl.nextElementSibling : null;
                const secondsLabel = secondsEl ? secondsEl.nextElementSibling : null;

                // Configure end time from PHP
                const cart_end = new Date(<?php echo json_encode(date('Y-m-d H:i:s', $this->end_date)); ?>);

                let cart_timer;

                // Update countdown timer
                function cart_updateCountdown() {
                    const now = new Date();

                    // Check if deal expired
                    if (now > cart_end) {
                        if (daysEl) daysEl.textContent = '0';
                        if (hoursEl) hoursEl.textContent = '0';
                        if (minutesEl) minutesEl.textContent = '0';
                        if (secondsEl) secondsEl.textContent = '0';
                        clearInterval(cart_timer);
                        // Auto-hide banner after countdown expires
                        /*setTimeout(function() {
                            if (banner) banner.style.display = 'none';
                        }, 2000);*/
                        return;
                    }

                    // Calculate remaining time
                    const diff = cart_end - now;
                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    // Update numbers (pad single digits with leading zero)
                    if (daysEl) daysEl.textContent = days < 10 ? '0' + days : days;
                    if (hoursEl) hoursEl.textContent = hours < 10 ? '0' + hours : hours;
                    if (minutesEl) minutesEl.textContent = minutes < 10 ? '0' + minutes : minutes;
                    if (secondsEl) secondsEl.textContent = seconds < 10 ? '0' + seconds : seconds;



                    // Update labels (singular/plural)
                    if (daysLabel) daysLabel.textContent = (days === 0 || days === 1) ? 'DAY' : 'DAYS';
                    if (hoursLabel) hoursLabel.textContent = (hours === 0 || hours === 1) ? 'HR' : 'HRS';
                    if (minutesLabel) minutesLabel.textContent = (minutes === 0 || minutes === 1) ? 'MIN' : 'MINS';
                    if (secondsLabel) secondsLabel.textContent = (seconds === 0 || seconds === 1) ? 'SEC' : 'SECS';
                }

                // Initialize countdown
                // cart_updateCountdown(); // Run immediately
                // cart_timer = setInterval(cart_updateCountdown, 1000); // Update every second
            })();


            (function ($) {
                /**
                 * Dismiss sale notification notice
                 *
                 * @param e
                 */
                
                function cart_lift_sales_notification_notice(e) {
                    e.preventDefault();
                    $('#cart-lift-promo-banner').hide(); // Ensure the correct element is selected
                    jQuery.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {
                            action: 'cart_lift_sales_notification_notice',
                            nonce: cart_lift_global?.security
                        },
                        success: function (response) {
                            $('#cart-lift-promo-banner').hide(); // Ensure the correct element is selected
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX request failed:', status, error);
                        }
                    });
                }

                jQuery(document).ready(function($) {
                    $(document).on('click', '#cart-lift-promo-banner__cross-icon', cart_lift_sales_notification_notice);
                });
                
            })(jQuery);
        </script>
        <!-- .rex-feed-tb-notification end -->
        <?php
    }

    /**
     * Adds internal CSS styles for the special occasion banners.
     *
     * @since 3.1.15
     */
    public function enqueue_css() {
        $plugin_dir_url = plugin_dir_url(__FILE__ );
        ?>
         <style type="text/css">

            @font-face {
                font-family: 'Inter';
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/campaign-font/Inter-Bold.woff2"; ?>) format('woff2');
                font-weight: 700;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Inter';
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/campaign-font/Inter-SemiBold.woff2"; ?>) format('woff2');
                font-weight: 600;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: "Inter";
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/campaign-font/Inter-Regular.woff2"; ?>) format('woff2');
                font-weight: 400;
                font-style: normal;
                font-display: swap;
            }

            .cart-lift-promo-banner * {
                box-sizing: border-box;
            }

            .toplevel_page_cart_lift .cart-lift-promo-banner-area {
                margin: 0 32px 0 25px;
            }

            @media screen and (max-width: 1440px) {
                .toplevel_page_cart_lift .cart-lift-promo-banner-area {
                    margin: 0;
                }
            }

            
        .cart-lift-regular-promotional-banner {
            padding: 10px 0;
            position: relative;
            z-index: 2;
            margin-top: 40px;
            width: calc(100% - 20px);
            border-radius: 10px;
            background: #FFF;
            box-shadow: 0 1px 1px 0 rgba(32, 28, 254, 0.10);
        }

        .cart-lift-regular-promotional-banner-container {
            /* max-width: 830px; */
            margin: 0 auto;
            padding: 0 15px;
        }

        .cart-lift-regular-promotional-banner-content {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
            gap: 24px;
            row-gap: 8px;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-regular-promotional-banner-title {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .cart-lift-regular-promotional-banner-content .new-tool-text {
            color: #666;
            font-size: 12px;
            font-weight: 500;
            line-height: 1;
            display: block;
        }
        .cart-lift-regular-promotional-banner-content .tool-name {
            color: #090939;
            font-size: 14px;
            font-weight: 600;
            line-height: 1;
            display: block;
        }
        .cart-lift-regular-promotional-banner-content .banner-logo-area {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .cart-lift-regular-promotional-banner-content .banner-text {
            font-size: 15px;
            color: #100627;
            font-weight: 400;
            line-height: 1.4;
            text-transform: capitalize;
            letter-spacing: 0;
        }

        /* CLOSE BUTTON */
        .cart-lift-regular-promotional-banner-content .cart-lift-close-btn {
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background-color: transparent;
            transition: all 0.3s ease-in-out;
        }


        /* TITLE, SUBTITLE, BADGE */
        .cart-lift-regular-promotional-banner-content .cart-lift-title {
            font-family: "Inter", sans-serif;
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -0.084px;
            color: #24EC2C;
            margin: 0;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-badge {
            font-family: "Inter", sans-serif;
            font-size: 16px;
            font-weight: 600;
            line-height: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #24EC2C;
        }

        /* BUTTON */
        .cart-lift-regular-promotional-banner-content .cart-lift-regular-promotional-banner-link {
            color: #201CFE;
            font-size: 15px;
            font-style: normal;
            font-weight: 500;
            line-height: 1;
            text-decoration: underline;
            text-decoration-thickness: 1px;
            text-underline-offset: 5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
            background: #fff;
            padding: 9px 14px;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-regular-promotional-banner-link:hover {
            text-decoration: none;
        }
        .cart-lift-regular-promotional-banner-content .cart-lift-regular-promotional-banner-link svg {
            transition: all 0.3s ease;
        }
        .cart-lift-regular-promotional-banner-content .cart-lift-regular-promotional-banner-link:hover svg {
            transform: translateX(3px);
        }


    /* TIMER */
    .cart-lift-regular-promotional-banner-content .cart-lift-timer {
        display: flex;
        gap: 3px;
    }

    .cart-lift-regular-promotional-banner-content .cart-lift-timer-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 6px 11px;
        text-align: center;
        color: #fff;
        border: 1px solid #2d5d1a;
        background: rgba(29, 58, 16, .4);
    }

    .cart-lift-regular-promotional-banner-content .cart-lift-timer-box:first-child {
        border-radius: 4px 0 0 4px;
    }

    .cart-lift-regular-promotional-banner-content .cart-lift-timer-box:last-child {
        border-radius: 0 4px 4px 0;
    }

    .cart-lift-regular-promotional-banner-content .cart-lift-timer-number {
        font-family: "Inter", sans-serif;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 6px;
        color: #FFF;
    }

    .cart-lift-regular-promotional-banner-content .cart-lift-timer-label {
        font-family: "Inter", sans-serif;
        font-size: 12px;
        font-weight: 400;
        line-height: 1;
        letter-spacing: 0.24px;
        text-transform: uppercase;
        opacity: 0.8;
    }

    /* REDUCED MOTION */
    @media (prefers-reduced-motion: reduce) {
        .cart-lift-regular-promotional-banner {
            transition: none;
        }
    }

    /* RESPONSIVE */
    @media only screen and (max-width: 1199px) { 
        .cart-lift-regular-promotional-banner {
            margin-top: 55px;
        }

        .cart-lift-regular-promotional-banner-container {
            max-width: 760px;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-regular-promotional-banner-link {
            padding-top: 0;
        }
    }  

        </style>

        <?php
    }

    /**
     * Hide the sales notification bar
     *
     * @since 3.1.15
     */
    public function cart_lift_sales_notification_notice()
    {
        if ( !wp_verify_nonce( filter_input( INPUT_POST, 'nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS ), 'rex-cart-lift-global-security')) {
            wp_die(__('Permission check failed', 'cart-lift'));
        }
        
        // Store current timestamp for 5-day dismissal
        $dismissed_option = $this->occasion . '_dismissed';
        update_option($dismissed_option, time());
        
        echo json_encode(['success' => true,]);
        wp_die();
    }
}