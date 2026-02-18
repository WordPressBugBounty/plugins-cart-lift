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

        if (
            !defined('CART_LIFT_PRO_VERSION')
            && ($current_date_time >= $this->start_date && $current_date_time <= $this->end_date)
        ) {
            // Hook into the admin_notices action to display the banner
            add_action( 'admin_notices', [ $this, 'display_banner' ] );
            // Add styles
            add_action( 'admin_head', [ $this, 'enqueue_css' ] );

	        add_action( 'wp_ajax_cart_lift_sales_notification_notice', [ $this, 'cart_lift_sales_notification_notice' ] );
            add_action( 'wp_ajax_nopriv_cart_lift_sales_notification_notice', [ $this, 'cart_lift_sales_notification_notice' ] );
        }
        
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

        // Check if banner was dismissed within last 24 hours
        $dismissed_option = $this->occasion . '_dismissed';
        $dismissed_time = get_option($dismissed_option, 0);
        if ($dismissed_time && (time() - $dismissed_time) < 86400) {
            return; // Don't show if dismissed within last 24 hours
        }

        $btn_link = esc_url( 'https://rextheme.com/cart-lift/#pricing' );

        $img_url  = plugin_dir_url(__FILE__) . '/images/banner-images/heart.webp'; 
        $img_path = plugin_dir_path(__FILE__) . '/images/banner-images/heart.webp';
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
                                <path d="M7.77482 0.75L0.75 7.75" stroke="#C6C5FF" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M7.77482 7.75L0.75 0.75" stroke="#C6C5FF" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </button>

                        <!-- Banner Title + Timer -->
                        <div class="cart-lift-regular-promotional-banner-title">

                            <div class="cart-lift-badge-content">

                                <div class="cart-lift-banner-title">
                                    <div class="heart-icon">
                                        <figure class="cart-lift-banner-img black-friday">
                                            <img src="<?php echo esc_url($img_url); ?>" alt="valentines day"  width="<?php echo esc_attr($img_width); ?>"
                                            height="<?php echo esc_attr($img_height); ?>" />
                                        </figure>
                                    </div>

                                    <h2 id="banner-flash-title">
                                        <?php echo esc_html__('Valentine\'s Day Discount', 'cart-lift'); ?>
                                    </h2>
                                </div>

                                <div class="cart-lift-title cart-lift-banner-offer">
                                    <?php echo esc_html__('Get 30% OFF', 'cart-lift'); ?>
                                </div>
                            </div>

                            <!-- Countdown Timer -->
                            <div class="cart-lift-timer">
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
                        aria-label="<?php esc_attr_e('Get 30% OFF on Valentine\'s Day', 'cart-lift'); ?>">
                            <?php esc_html_e('Get 30% OFF', 'cart-lift'); ?>
                            <span class="arrow-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10">
                                    <path d="M10 0.78V9.22C10 9.65 9.65 10 9.22 10C8.79 10 8.44 9.65 8.44 9.22V2.66L1.33 9.77C1.19 9.92 0.99 10 0.78 10C0.35 10 0 9.65 0 9.22C0 9.01 0.08 8.81 0.23 8.67L7.33 1.56H0.78C0.35 1.56 0 1.21 0 0.78C0 0.35 0.35 0 0.78 0H9.22C9.65 0 10 0.35 10 0.78Z"
                                        fill="#000"/>
                                </svg>
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
                        setTimeout(function() {
                            if (banner) banner.style.display = 'none';
                        }, 2000);
                        return;
                    }

                    // Calculate remaining time
                    const diff = cart_end - now;
                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    // Update numbers
                    if (daysEl) daysEl.textContent = days;
                    if (hoursEl) hoursEl.textContent = hours;
                    if (minutesEl) minutesEl.textContent = minutes;
                    if (secondsEl) secondsEl.textContent = seconds;

                    // Update labels (singular/plural)
                    if (daysLabel) daysLabel.textContent = (days === 0 || days === 1) ? 'DAY' : 'DAYS';
                    if (hoursLabel) hoursLabel.textContent = (hours === 0 || hours === 1) ? 'HR' : 'HRS';
                    if (minutesLabel) minutesLabel.textContent = (minutes === 0 || minutes === 1) ? 'MIN' : 'MINS';
                    if (secondsLabel) secondsLabel.textContent = (seconds === 0 || seconds === 1) ? 'SEC' : 'SECS';
                }

                // Initialize countdown
                cart_updateCountdown(); // Run immediately
                cart_timer = setInterval(cart_updateCountdown, 1000); // Update every second
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
                font-family: 'Grand Hotel';
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/campaign-font/GrandHotel-Regular.woff2"; ?>) format('woff2');
                font-weight: normal;
                font-style: normal;
                font-display: swap;
            }

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

            @keyframes arrowMove {
                0% {
                    transform: translate(0, 0);
                }
                50% {
                    transform: translate(18px, -18px);
                }
                55% {
                    opacity: 0;
                    visibility: hidden;
                    transform: translate(-18px, 18px);
                }
                100% {
                    opacity: 1;
                    visibility: visible;
                    transform: translate(0, 0);
                }
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
            background: #201CFE;
            padding: 10px 0;
            position: relative;
            z-index: 2;
            margin-top: 40px;
            width: calc(100% - 20px);
        }

        .cart-lift-regular-promotional-banner-container {
            max-width: 740px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .cart-lift-regular-promotional-banner-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-banner-title {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 5px;
            line-height: 1.1;
            animation: slideInLeft 0.8s ease-out;
        }

        .cart-lift-regular-promotional-banner-content .heart-icon {
            animation: heartbeat 1.5s infinite;
        }

        .cart-lift-regular-promotional-banner-content .heart-icon figure{
            margin: 0;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-banner-title h2 {
            font-family: 'Grand Hotel';
            color: #FF6DE7;
            font-size: 18px;
            font-weight: 400;
            line-height: 1.1;
            margin: 0;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-regular-promotional-banner-title {
            display: flex;
            align-items: center;
            gap: 80px;
        }

        .cart-lift-regular-promotional-banner-content .linno-banner.closing {
            animation: linno-slideUp 0.5s ease-in forwards;
        }

    /* CLOSE BUTTON */
    .cart-lift-regular-promotional-banner-content .cart-lift-close-btn {
        position: absolute;
        top: 38px;
        right: 40px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background-color: transparent;
        transition: all 0.3s ease-in-out;
    }

.cart-lift-regular-promotional-banner-content .cart-lift-close-btn:hover {
    transform: rotate(90deg);
}

/* TITLE, SUBTITLE, BADGE */
.cart-lift-regular-promotional-banner-content .cart-lift-title {
    font-family: "Inter", sans-serif;
    font-size: 24px;
    font-weight: 700;
    line-height: 1;
    letter-spacing: -0.084px;
    color: #FFF;
    margin: 0;
}

.cart-lift-regular-promotional-banner-content span.arrow-icon {
    margin-left: 10px;
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
    padding: 12px 16px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    border-radius: 4px;
    background: #FF6DE7;
    color: #000;
    font-family: "Inter", sans-serif;
    font-size: 15px;
    font-weight: 600;
    line-height: 1;
    letter-spacing: -0.084px;
    text-decoration: none;
}

.cart-lift-regular-promotional-banner-content .cart-lift-regular-promotional-banner-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.3);
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
    background-color: #1E1BC5;
    padding: 6px 13px;
    text-align: center;
    color: #fff;
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
        line-height: 1.5;
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

    /* ANIMATIONS */
    @keyframes linno-slideDown {
        from { transform: translateY(-100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    @keyframes linno-slideUp {
        from { transform: translateY(0); opacity: 1; }
        to { transform: translateY(-100%); opacity: 0; }
    }

    @keyframes linno-pulse {
        0%,100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    @keyframes linno-float {
        0%,100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    @keyframes heartbeat {
        0%,100% { transform: scale(1); }
        25% { transform: scale(1.2); }
        50% { transform: scale(1); }
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
            max-width: 650px;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-regular-promotional-banner-title {
            gap: 40px;
        }
    }   

    @media only screen and (max-width: 991px) {

        .cart-lift-regular-promotional-banner-container {
            max-width: 600px;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-title {
            font-size: 20px;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-timer-number {
            font-size: 18px;
            line-height: 1.3;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-close-btn {
            top: 34px;
            right: 20px;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-badge {
            font-size: 14px;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-regular-promotional-banner-title {
            gap: 30px;
        }
    }

    @media only screen and (max-width: 767px) {

        .cart-lift-regular-promotional-banner-content {
            flex-direction: column;
            text-align: center;
            gap: 30px;
            padding: 30px 0;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-title {
            font-size: 22px;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-regular-promotional-banner-title {
            flex-direction: column;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-timer-number {
            font-size: 20px;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-timer {
            justify-content: center;
            flex-wrap: wrap;
        }

        .cart-lift-regular-promotional-banner-content .cart-lift-close-btn {
            top: 15px;
            right: 20px;
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
        
        // Store current timestamp for 24-hour dismissal
        $dismissed_option = $this->occasion . '_dismissed';
        update_option($dismissed_option, time());
        
        echo json_encode(['success' => true,]);
        wp_die();
    }
}