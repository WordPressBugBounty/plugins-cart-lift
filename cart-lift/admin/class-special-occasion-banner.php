<?php

/**
 * SpecialOccasionBanner Class
 *
 * This class is responsible for displaying a special occasion banner in the WordPress admin.
 *
 * @package YourVendor\SpecialOccasionPlugin
 *
 * @since 7.3.18
 */
class Rex_CartLift_Special_Occasion_Banner
{

    /**
     * The occasion identifier.
     *
     * @var string
     *
     * @since 7.3.18
     */
    private $occasion;

    /**
     * The start date and time for displaying the banner.
     *
     * @var int
     *
     * @since 7.3.18
     */
    private $start_date;

    /**
     * The end date and time for displaying the banner.
     *
     * @var int
     *
     * @since 7.3.18
     */
    private $end_date;

    /**
     * Constructor method for SpecialOccasionBanner class.
     *
     * @param string $occasion The occasion identifier.
     * @param string $start_date The start date and time for displaying the banner.
     * @param string $end_date The end date and time for displaying the banner.
     *
     * @since 7.3.18
     */
    public function __construct($occasion, $start_date, $end_date)
    {
        $this->occasion   = "rex_cl_{$occasion}";
        $this->start_date = strtotime($start_date);
        $this->end_date   = strtotime($end_date);
    }

    /**
     * Controls the initialization of certain admin-related functionalities based on conditions.
     * It checks the current screen, defined allowed screens, product feed version availability,
     * and date conditions to determine whether to display a banner and enqueue styles.
     *
     * @since 7.3.18
     */
    public function init()
    {
        $current_date_time = current_time('timestamp');

        if (
            'hidden' !== get_option($this->occasion, '')
            && !defined('CART_LIFT_PRO_VERSION')
            && ($current_date_time >= $this->start_date && $current_date_time <= $this->end_date)
        ) {
            // Add styles
            add_action('admin_head', [$this, 'enqueue_css']);
            // Hook into the admin_notices action to display the banner
            add_action('admin_notices', [$this, 'display_banner']);

	        $validations = array(
		        'logged_in' => true,
		        'user_can'  => 'manage_options',
	        );
	        wp_ajax_helper()->handle( 'rex-cl-hide-deal-notice' )
	                        ->with_callback( array( __CLASS__, 'hide_special_deal_notice' ) )
	                        ->with_validation( $validations );
        }
    }

    /**
     * Calculate time remaining until Halloween
     *
     * @return array Time remaining in days, hours, and minutes
     */
    public function rex_get_halloween_countdown() {
	    $diff = $this->end_date - current_time( 'timestamp' );
	    return [
		    'days'  => sprintf("%02d", floor( $diff / ( 60 * 60 * 24 ) )),
		    'hours' => sprintf("%02d", floor( ( $diff % ( 60 * 60 * 24 ) ) / ( 60 * 60 ) ) ),
		    'mins'  => sprintf("%02d", floor( ( $diff % ( 60 * 60 ) ) / 60 ) )
	    ];
    }



    /**
     * Displays the special occasion banner if the current date and time are within the specified range.
     *
     * @since 7.3.18
     */
    public function display_banner()
    {
        $screen          = get_current_screen();
        $allowed_screens = ['dashboard', 'plugins', 'cart_lift'];
        $time_remaining  = $this->end_date - current_time('timestamp');

        if (in_array($screen->base, $allowed_screens) || in_array($screen->parent_base, $allowed_screens) || in_array($screen->post_type, $allowed_screens) || in_array($screen->parent_file, $allowed_screens)) {
            echo '<input type="hidden" id="rex_cl_special_occasion" name="rex_cl_special_occasion" value="' . $this->occasion . '">';

            $countdown = $this->rex_get_halloween_countdown();
        ?>

            <!-- Name: WordPress Anniversary Notification Banner -->
            <div class="rex-feed-tb__notification cartlift-banner" id="rex_cl_deal_notification">
                <div class="banner-overflow">
                    <div class="rex-notification-counter">
                        <div class="rex-notification-counter__container">
                            <div class="rex-notification-counter__content">

                                <figure class="rex-notification-counter__figure-logo">
                                    <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . '/images/halloween/halloween-logo.webp'); ?>" alt="<?php esc_attr_e('Halloween special offer banner', 'cart-lift'); ?>" class="rex-notification-counter__img" >
                                </figure>

                                <figure class="rex-notification-counter__figure-percentage">
                                    <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . '/images/halloween/discount-percent.webp'); ?>" alt="<?php esc_attr_e('Halloween special offer banner', 'cart-lift'); ?>" class="rex-notification-counter__img" >
                                </figure>

                                <div id="rex-halloween-countdown" class="rex-notification-counter__countdown" aria-live="polite">
                                    <h3 class="screen-reader-text"><?php esc_html_e('Offer Countdown', 'cart-lift'); ?></h3>
                                    <ul class="rex-notification-counter__list">
                                        <?php foreach (['days', 'hours', 'mins'] as $unit): ?>
                                            <li class="rex-notification-counter__item">
                                                <span id="rex-cl-halloween-<?php echo esc_attr($unit); ?>" class="rex-notification-counter__time">
                                                    <?php echo esc_html($countdown[$unit]); ?>
                                                </span>
                                                <span class="rex-notification-counter__label">
                                                    <?php echo esc_html($unit); ?>
                                                </span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>

                                <div class="rex-notification-counter__btn-area">
                                    <a 
                                        href="<?php echo esc_url( 'https://rextheme.com/cart-lift/?utm_source=plugin-CTA&utm_medium=cartlift-free-plugin&utm_campaign=halloween-2024' ); ?>"
                                        class="rex-notification-counter__btn"
                                        target="_blank"
                                    >

                                        <span class="screen-reader-text"><?php esc_html_e('Click to view Halloween sale products', 'cart-lift'); ?></span>

                                        <?php esc_html_e('FLAT', 'cart-lift'); ?>
                                        <strong class="rex-notification-counter__stroke-font">30%</strong> 
                                        <?php esc_html_e('OFF', 'cart-lift'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rex-feed-tb__cross-top" id="rex_cl_deal_close">
                    <svg width="12" height="13" fill="none" viewBox="0 0 12 13" xmlns="http://www.w3.org/2000/svg"><path stroke="#7A8B9A" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 1.97L1 11.96m0-9.99l10 9.99" /></svg>
                </div>
            </div>
            <!-- .rex-feed-tb-notification end -->

            <script>
                rex_cl_deal_countdown_handler();
                /**
                 * Handles count down on deal notice
                 *
                 * @since 7.3.18
                 */
                function rex_cl_deal_countdown_handler() {
                    // Pass the calculated time remaining to JavaScript
                    let timeRemaining = <?php echo $time_remaining; ?>;

                    // Update the countdown every second
                    setInterval(function() {
                        const daysElement = document.getElementById('rex-cl-halloween-days');
                        const hoursElement = document.getElementById('rex-cl-halloween-hours');
                        const minutesElement = document.getElementById('rex-cl-halloween-mins');
                        //const secondsElement = document.getElementById('seconds');

                        timeRemaining--;

                        if (daysElement && hoursElement && minutesElement) {
                            // Decrease the remaining time

                            // Calculate new days, hours, minutes, and seconds
                            let days = Math.floor(timeRemaining / (60 * 60 * 24)).toString().padStart(2, '0');
                            let hours = Math.floor((timeRemaining % (60 * 60 * 24)) / (60 * 60)).toString().padStart(2, '0');
                            let minutes = Math.floor((timeRemaining % (60 * 60)) / 60).toString().padStart(2, '0');

                            // Update the HTML
                            daysElement.textContent = days;
                            hoursElement.textContent = hours;
                            minutesElement.textContent = minutes;
                        }
                        // Check if the countdown has ended
                        if (timeRemaining <= 0) {
                            rex_cl_hide_deal_notice();
                        }
                    }, 1000); // Update every second
                }

                document.getElementById('rex_cl_deal_close').addEventListener('click', rex_cl_hide_deal_notice);

                /**
                 * Hide deal notice and save parameter to keep it hidden for future
                 *
                 * @since 7.3.2
                 */
                function rex_cl_hide_deal_notice() {
                    document.getElementById('rex_cl_deal_notification').style.display = 'none';
                    const payload = {
                        occasion: document.getElementById('rex_cl_special_occasion')?.value
                    }

                    wpAjaxHelperRequest('rex-cl-hide-deal-notice', payload);
                }
            </script>

        <?php
        }
    }

	/**
	 * Hide special deal notice
	 *
	 * @param array $payload Payload data for ajax call.
	 * @return array
	 */
	public static function hide_special_deal_notice( $payload ) {
		$occasion = $payload[ 'occasion' ] ?? null;
		if ( $occasion ) {
			update_option( $occasion, 'hidden' );
			return [ 'status' => true ];
		}
		return [ 'status' => false ];
	}

    /**
     * Adds internal CSS styles for the special occasion banners.
     *
     * @since 7.3.18
     */
    public function enqueue_css()
    {
        $plugin_dir_url = plugin_dir_url(__FILE__);
        ?>
        <style id="wpvr-promotional-banner-style" type="text/css">
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
                font-family: "Circular Std Book";
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/campaign-font/CircularStd-Book.woff2"; ?>) format('woff2');
                font-weight: 400;
                font-style: normal;
                font-display: swap;
            }

            .rex-feed-tb__notification,
            .rex-feed-tb__notification * {
                box-sizing: border-box;
            }

            .rex-feed-tb__notification.cartlift-banner {
                background-color: #03031e;
                width: calc(100% - 20px);
                margin: 50px 0 20px;
                background-image: url(<?php echo "{$plugin_dir_url}images/halloween/promotional-banner-bg.webp"; ?>);;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                position: relative;
                border: none;
                box-shadow: none;
                display: block;
                max-height: 110px;
                object-fit: cover;
            }

            .cartlift-banner .rex-notification-counter {
                position: relative;
                z-index: 1111;
                padding: 9px 0 4px;
            }
            .cartlift-banner .rex-notification-counter__container {
                position: relative;
                width: 100%;
                max-height: 110px;
                max-width: 1310px;
                margin: 0 auto;
                padding: 0px 15px;
            }
            .cartlift-banner .rex-notification-counter__content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 20px;
            }
            .cartlift-banner .rex-notification-counter__figure-logo {
                max-width: 268px;
                margin: 0;
            }
            .cartlift-banner .rex-notification-counter__figure-percentage {
                max-width: 248px;
                margin: 0;
            }
            .cartlift-banner .rex-notification-counter__img {
                width: 100%;
                max-width: 100%;
                display: block;
            }
            .cartlift-banner .rex-notification-counter__list {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin: 0;
                padding: 0;
                list-style: none;
            }
            @media only screen and (max-width: 991px) {
                .cartlift-banner .rex-notification-counter__list {
                    gap: 10px;
                }
            }
            @media only screen and (max-width: 767px) {
                .cartlift-banner .rex-notification-counter__list {
                    align-items: center;
                    justify-content: center;
                    gap: 15px;
                }
            }
            .cartlift-banner .rex-notification-counter__item {
                display: flex;
                flex-direction: column;
                width: 56.14px;
                font-family: "Circular Std Book";
                font-size: 15px;
                font-weight: 500;
                line-height: normal;
                letter-spacing: 0.75px;
                text-transform: uppercase;
                text-align: center;
                color: #fff;
                margin: 0;
            }
            @media only screen and (max-width: 1199px) {
                .cartlift-banner .rex-notification-counter__item {
                    width: 44px;
                    font-size: 12px;
                }
            }
            @media only screen and (max-width: 991px) {
                .cartlift-banner .rex-notification-counter__item {
                    font-size: 10px;
                }
            }
            @media only screen and (max-width: 767px) {
                .cartlift-banner .rex-notification-counter__item {
                    font-size: 13px;
                    width: 47px;
                }
            }
            .cartlift-banner .rex-notification-counter__time {
                font-size: 32px;
                font-family: "Inter";
                font-style: normal;
                font-weight: 700;
                line-height: normal;
                color: #fff;
                text-align: center;
                margin-bottom: 6px;
                border-radius: 3px 3px 10px 10px;
                border: 1px solid #6746D0;
                border-bottom-width: 5px;
                background: linear-gradient(155deg, #6746D0 2.02%, #100E35 55.1%, #100E35 131.47%);
            }
            @media only screen and (max-width: 1199px) {
                .cartlift-banner .rex-notification-counter__time {
                    font-size: 30px;
                }
            }
            @media only screen and (max-width: 991px) {
                .cartlift-banner .rex-notification-counter__time {
                    font-size: 24px;
                }
            }
            .cartlift-banner .rex-notification-counter__btn-area {
                display: flex;
                align-items: flex-end;
                justify-content: flex-end;
            }
            .cartlift-banner .rex-notification-counter__btn {
                position: relative;
                background-color: #6746D0;
                font-family: "Inter";
                font-size: 20px;
                font-weight: 500;
                line-height: normal;
                color: #fff;
                text-align: center;
                filter: drop-shadow(0px 30px 60px rgba(21, 19, 119, 0.20));
                padding: 12px 22px;
                display: inline-block;
                border-radius: 10px;
                cursor: pointer;
                text-transform: uppercase;
                transition: all 0.3s ease;
                text-decoration: none;
                box-shadow: none;
            }
            .cartlift-banner .rex-notification-counter__btn:hover {
                background-color: #fff;
                color: #6746D0;
            }
            .cartlift-banner .rex-notification-counter__stroke-font {
                font-size: 26px;
                font-family: "Inter";
                font-weight: 600;
            }

            .rex-feed-tb__notification.cartlift-banner .rex-feed-tb__cross-top {
                position: absolute;
                top: -10px;
                right: -9px;
                background: #fff;
                border: none;
                padding: 0;
                border-radius: 50%;
                cursor: pointer;
                z-index: 9999;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            

            @media only screen and (max-width: 1599px) {
                .cartlift-banner .rex-notification-counter__container {
                    max-width: 1030px;
                }
                .cartlift-banner .rex-notification-counter__figure-logo {
                    max-width: 200px;
                }
                .cartlift-banner .rex-notification-counter__figure-percentage {
                    max-width: 190px;
                }

                .cartlift-banner .rex-notification-counter__btn {
                    font-size: 16px;
                }
                .cartlift-banner .rex-notification-counter__stroke-font {
                    font-size: 22px;
                }

            }

            @media only screen and (max-width: 1399px) {
                .cartlift-banner .rex-feed-tb__notification {
                    background-position: left center;
                }

            }

            @media only screen and (max-width: 1199px) {
                .cartlift-banner .rex-notification-counter__container {
                    max-width: 740px;
                }
                .cartlift-banner .rex-notification-counter__figure-logo {
                    max-width: 140px;
                }
                .cartlift-banner .rex-notification-counter__figure-percentage {
                    max-width: 140px;
                }
                .cartlift-banner .rex-notification-counter__time {
                    font-size: 22px;
                    padding: 2px 0;
                    font-weight: 500;
                }
                .cartlift-banner .rex-notification-counter__btn {
                    font-size: 13px;
                    padding: 8px 16px;
                    transform: translateY(-2px);
                }
                .cartlift-banner .rex-notification-counter__stroke-font {
                    font-size: 20px;
                }

            }

            @media only screen and (max-width: 991px) {
                .cartlift-banner .rex-notification-counter__item {
                    font-size: 12px;
                }

            }
        </style>

<?php
    }
}
