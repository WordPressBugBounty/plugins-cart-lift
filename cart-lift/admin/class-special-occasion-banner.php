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
        $this->occasion   = "rex_feed_{$occasion}";
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
        }
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

        $btn_link = 'https://rextheme.com/cart-lift/?utm_source=plugin-notification-CTA&utm_medium=cart-lift-plugin&utm_campaign=eid-ul-adha-24#pricing';

        if (in_array($screen->base, $allowed_screens) || in_array($screen->parent_base, $allowed_screens) || in_array($screen->post_type, $allowed_screens) || in_array($screen->parent_file, $allowed_screens)) {
            echo '<input type="hidden" id="rexfeed_special_occasion" name="rexfeed_special_occasion" value="' . $this->occasion . '">';
?>

            <!-- Name: WordPress Anniversary Notification Banner -->

            <div class="rex-feed-tb__notification" id="rex_deal_notification">

                <div class="banner-overflow">
                    <div class="wpcart-lift-anniv__container-area">

                        <div class="wpcart-lift-anniv__image wpcart-lift-anniv__image--left">
                            <figure>
                                <img src="<?php echo plugin_dir_url(__FILE__) . './images/eid-ul-adha/moon.webp';
                                            ?>" alt="Eid Ul Adha" />
                            </figure>
                        </div>

                        <div class="wpcart-lift-anniv__content-area">


                            <div class="wpcart-lift-anniv__image--group">

                                <div class='wpcart-lift-anniv__image wpcart-lift-anniv__image--eid-mubarak'>
                                    <figure>
                                        <img src="<?php echo plugin_dir_url(__FILE__) . './images/eid-ul-adha/eid-mubarak.webp'; ?>" alt="Eid Ul Adha" />
                                    </figure>
                                </div>

                                <div class='wpcart-lift-anniv__image wpcart-lift-anniv__image--cartlift-logo'>
                                    <figure>
                                        <img src="<?php echo plugin_dir_url(__FILE__) . './images/eid-ul-adha/cart-lift-logo.webp'; ?>" alt="Cartlift Logo" />
                                    </figure>
                                </div>

                                <div class="wpcart-lift-anniv__image wpcart-lift-anniv__image--four">
                                    <figure>
                                        <img src="<?php echo plugin_dir_url(__FILE__) . 'images/eid-ul-adha/discount.webp'; ?>" alt="20% discount" />
                                    </figure>
                                </div>



                                <div class="wpcart-lift-anniv__text-divider">

                                    <div class="wpcart-lift-anniv__lead-text">
                                        <span>
                                            <svg width="33" height="30" fill="none" viewBox="0 0 33 30" xmlns="http://www.w3.org/2000/svg">
                                                <path fill="#EE8133" stroke="#EE8133" d="M28.584 25.483a257.608 257.608 0 00-.525-1.495c-.28-.795-.569-1.614-.769-2.199a1.432 1.432 0 01-.084-.552c.014-.211.106-.57.487-.726a.828.828 0 01.416-.064.754.754 0 01.38.161c.139.11.248.274.309.366l.02.032.003.004c.127.191.203.355.265.49l.04.09.572 1.176a185.411 185.411 0 011.49 3.11c.193.412.306.86.404 1.245l.027.106h0c.077.301.093.67-.128.977-.224.313-.587.415-.925.429h0a54.91 54.91 0 01-3.43.022h-.001l-.166-.003c-1.395-.027-2.84-.055-4.268-.29h-.003c-.312-.053-.574-.138-.78-.299a1.212 1.212 0 01-.371-.523l-.01-.024-.008-.024a.692.692 0 01.175-.694c.137-.136.31-.205.428-.243.248-.08.538-.105.687-.117a5.511 5.511 0 011.039 0c.766.051 1.528.104 2.297.157l.16.01c-5.037-2.4-9.838-5.23-14.007-9.083C7.962 13.508 4.206 9.005 1.53 3.652h0l-.002-.004-.02-.04c-.183-.377-.397-.817-.517-1.283A2.45 2.45 0 00.985 2.3c-.025-.088-.08-.28-.068-.479.016-.273.144-.526.401-.728l.027-.02.029-.018a.729.729 0 01.792.026c.18.117.325.3.442.47.17.24.35.506.507.787l.001.002c2.4 4.35 5.404 8.244 8.893 11.79l-.343.338.343-.338c4.39 4.463 9.63 7.735 15.16 10.655.463.242.93.466 1.415.697z" />
                                            </svg>
                                        </span>

                                        <h2>
                                            <?php echo __("Ends <br> Soon", 'rextheme') ?>
                                        </h2>

                                    </div>

                                </div>

                            </div>

                            <!-- .wpcart-lift-anniv__image end -->
                            <div class="wpcart-lift-anniv__btn-area">

                                <a href="<?php echo esc_url($btn_link); ?>" role="button" class="wpcart-lift-anniv__btn" target="_blank">
                                    <?php echo __('Claim Offer Now', 'rextheme') ?>
                                </a>
                                <svg width="70" height="63" fill="none" viewBox="0 0 70 63" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="#FF44BC" d="M4.607 7.083c1.027-1.909 1.655-3.536 1.59-5.337a5.106 5.106 0 00-3.25-.907A9.527 9.527 0 011.34 6.08c1.486-.104 2.372.062 3.267 1.002z" />
                                    <path fill="url(#paint0_linear_2007_3)" d="M67.79 55.948c-1.79-.111-3.545-.96-6.504-4.16a9.216 9.216 0 00-1.916 5.447 16.383 16.383 0 007.3 3.89 8.389 8.389 0 011.12-5.177z" />
                                    <path fill="#EE8134" d="M60.724 14.364a18.229 18.229 0 01-6.33 3.313 5.826 5.826 0 001.984 3.154 24.284 24.284 0 006.717-2.97c-1.715-1.08-2.408-1.907-2.37-3.497z" />
                                    <defs>
                                        <linearGradient id="paint0_linear_2007_3" x1="27276.4" x2="27248.7" y1="7756.52" y2="7812.21" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#4D8EFF" />
                                            <stop offset=".43" stop-color="#3F76FF" />
                                            <stop offset="1" stop-color="#2850FF" />
                                        </linearGradient>
                                    </defs>
                                </svg>

                            </div>

                        </div>

                        <div class="wpcart-lift-anniv__image wpcart-lift-anniv__image--right">
                            <figure>
                                <img src="<?php echo plugin_dir_url(__FILE__) . 'images/eid-ul-adha/mosque.webp'; ?>" alt="Masjid" />
                            </figure>
                        </div>

                    </div>

                </div>

                <div class="rex-feed-tb__cross-top" id="rex_deal_close">
                    <img src="<?php echo  plugin_dir_url(__FILE__) . 'images/cross-top.svg'; ?>" />

                </div>

            </div>
            <!-- .rex-feed-tb-notification end -->

            <script>
                rexfeed_deal_countdown_handler();
                /**
                 * Handles count down on deal notice
                 *
                 * @since 7.3.18
                 */
                function rexfeed_deal_countdown_handler() {
                    // Pass the calculated time remaining to JavaScript
                    let timeRemaining = <?php echo $time_remaining; ?>;

                    // Update the countdown every second
                    setInterval(function() {
                        const daysElement = document.getElementById('rex-feed-tb__days');
                        const hoursElement = document.getElementById('rex-feed-tb__hours');
                        const minutesElement = document.getElementById('rex-feed-tb__mins');
                        //const secondsElement = document.getElementById('seconds');

                        timeRemaining--;

                        if (daysElement && hoursElement && minutesElement) {
                            // Decrease the remaining time

                            // Calculate new days, hours, minutes, and seconds
                            let days = Math.floor(timeRemaining / (60 * 60 * 24));
                            let hours = Math.floor((timeRemaining % (60 * 60 * 24)) / (60 * 60));
                            let minutes = Math.floor((timeRemaining % (60 * 60)) / 60);
                            //let seconds = timeRemaining % 60;

                            // Format values with leading zeros
                            days = (days < 10) ? '0' + days : days;
                            hours = (hours < 10) ? '0' + hours : hours;
                            minutes = (minutes < 10) ? '0' + minutes : minutes;
                            //seconds = (seconds < 10) ? '0' + seconds : seconds;

                            // Update the HTML
                            daysElement.textContent = days;
                            hoursElement.textContent = hours;
                            minutesElement.textContent = minutes;
                        }
                        // Check if the countdown has ended
                        if (timeRemaining <= 0) {
                            // rexfeed_hide_deal_notice();
                        }
                    }, 1000); // Update every second
                }

                document.getElementById('rex_deal_close').addEventListener('click', rexfeed_hide_deal_notice);

                /**
                 * Hide deal notice and save parameter to keep it hidden for future
                 *
                 * @since 7.3.2
                 */
                function rexfeed_hide_deal_notice() {
                    document.getElementById('rex_deal_notification').style.display = 'none';
                    const payload = {
                        occasion: document.getElementById('rexfeed_special_occasion')?.value
                    }

                    wpAjaxHelperRequest('rex-feed-hide-deal-notice', payload);
                }
            </script>

        <?php
        }
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
        <style type="text/css">
            /* notification var css */

            @font-face {
                font-family: 'Lexend Deca';
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/wp-anniversary-campaign-font/LexendDeca-SemiBold.woff2"; ?>) format('woff2'),
                    url(<?php echo "{$plugin_dir_url}assets/fonts/wp-anniversary-campaign-font/LexendDeca-SemiBold.woff"; ?>) format('woff');
                font-weight: 600;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Lexend Deca';
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/wp-anniversary-campaign-font/LexendDeca-Bold.woff2"; ?>) format('woff2'),
                    url(<?php echo "{$plugin_dir_url}assets/fonts/wp-anniversary-campaign-font/LexendDeca-Bold.woff"; ?>) format('woff');
                font-weight: bold;
                font-style: normal;
                font-display: swap;
            }


            .rex-feed-tb__notification,
            .rex-feed-tb__notification * {
                box-sizing: border-box;
            }

            .rex-feed-tb__notification {
                background-color: #d6e4ff;
                width: calc(100% - 20px);
                margin: 50px 0 20px;
                background-image: url(<?php echo "{$plugin_dir_url}images/eid-ul-adha/notification-bg.webp"; ?>);
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                position: relative;
                border: none;
                box-shadow: none;
                display: block;
                max-height: 110px;
            }

            .rex-feed-tb__notification .banner-overflow {
                overflow: hidden;
                position: relative;
                width: 100%;
            }

            .rex-feed-tb__notification .rex-feed-tb__cross-top {
                position: absolute;
                top: -10px;
                right: -9px;
                background: #fff;
                border: none;
                padding: 0;
                border-radius: 50%;
                cursor: pointer;
                z-index: 9;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .rex-feed-tb__notification .rex-feed-tb__cross-top img {
                width: 22px;
            }

            .rex-feed-tb__notification .rex-feed-tb__cross-top svg {
                display: block;
                width: 15px;
                height: 15px;
            }

            .wpcart-lift-anniv__container {
                width: 100%;
                margin: 0 auto;
                max-width: 1640px;
                position: relative;
                padding-right: 15px;
                padding-left: 15px;
            }

            .wpcart-lift-anniv__container-area {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .wpcart-lift-anniv__content-area {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: space-evenly;
                max-width: 1310px;
                position: relative;
                padding-right: 15px;
                padding-left: 15px;
                margin: 0 auto;
                z-index: 1;
            }

            .wpcart-lift-anniv__image--left {
                position: absolute;
                left: 140px;
                top: 50%;
                transform: translateY(-50%);
            }

            .wpcart-lift-anniv__image--right {
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
            }

            .wpcart-lift-anniv__image--group {
                display: flex;
                align-items: center;
                gap: 50px;
            }

            .wpcart-lift-anniv__image--left img {
                width: 100%;
                max-width: 108px;
            }

            .wpcart-lift-anniv__image--eid-mubarak img {
                width: 100%;
                max-width: 165px;
            }

            .wpcart-lift-anniv__image--cartlift-logo img {
                width: 100%;
                max-width: 110px;
            }

            .wpcart-lift-anniv__image--four img {
                width: 100%;
                max-width: 254px;
            }

            .wpcart-lift-anniv__lead-text {
                display: flex;
                gap: 11px;
            }

            .wpcart-lift-anniv__lead-text h2 {
                font-size: 42px;
                line-height: 1;
                margin: 0;
                color: #6E42D3;
                font-weight: 700;
                font-family: 'Lexend Deca';

            }



            .wpcart-lift-anniv__image--right img {
                width: 100%;
                max-width: 152px;
            }

            .wpcart-lift-anniv__image figure {
                margin: 0;
            }

            .wpcart-lift-anniv__text-container {
                position: relative;
                max-width: 330px;
            }

            .wpcart-lift-anniv__campaign-text-images {
                position: absolute;
                top: -10px;
                right: -15px;
                max-width: 100%;
                max-height: 24px;
            }



            .wpcart-lift-anniv__btn-area {
                display: flex;
                align-items: flex-end;
                justify-content: flex-end;
                position: relative;
            }

            .wpcart-lift-anniv__btn-area svg {
                position: absolute;
                width: 70px;
                right: -20px;
                top: -15px;
            }

            .wpcart-lift-anniv__btn {
                font-family: 'Lexend Deca';
                font-size: 18px;
                font-style: normal;
                font-weight: 500;
                line-height: 1;
                text-align: center;
                border-radius: 30px;
                background: -webkit-gradient(linear, left bottom, left top, from(#9B79E7), to(#6E42D3));
                background: -moz-linear-gradient(bottom, #9B79E7 0, #6E42D3 100%);
                background: -o-linear-gradient(180deg, #9B79E7 11.67%, #6E42D3 100%);
                background: linear-gradient(180deg, #9B79E7 11.67%, #6E42D3 100%);
                box-shadow: 0px 10px 30px rgba(12, 10, 81, 0.25);
                color: #fff;
                padding: 17px 26px;
                display: inline-block;
                text-decoration: none;
                cursor: pointer;
                text-transform: capitalize;
                -webkit-transition: all .5s linear;
                -o-transition: all .5s linear;
                -moz-transition: all .5s linear;
                transition: all .5s linear;
            }

            a.wpcart-lift-anniv__btn:hover {
                color: #6E42D4;
                background: linear-gradient(0deg, #ffffff 100%, #fff 0);
            }

            .wpcart-lift-anniv__btn-area a:focus {
                color: #fff;
                box-shadow: none;
                outline: 0px solid transparent;
            }

            .wpcart-lift-anniv__btn:hover {
                background-color: #201cfe;
                color: #fff;
            }

            .wpcartlift-banner-title p {
                margin: 0;
                font-weight: 700;
                max-width: 315px;
                font-size: 24px;
                color: #ffffff;
                line-height: 1.3;
            }

            @media only screen and (min-width: 1921px) {
                .wpcart-lift-anniv__image--left img {
                    max-width: 108px;
                }
            }


            @media only screen and (max-width: 1710px) {

                .wpcart-lift-anniv__image--left {
                    left: 100px;
                }

                .wpcart-lift-anniv__lead-text h2 {
                    font-size: 36px;
                }

                .wpcart-lift-anniv__content-area {
                    justify-content: center;
                }

                .wpcart-lift-anniv__image--group {
                    gap: 30px;
                }

                .wpcart-lift-anniv__content-area {
                    gap: 30px;
                }

                .wpcart-lift-anniv__btn {
                    font-size: 18px;
                }

                .wpcart-lift-anniv__btn-area svg {
                    position: absolute;
                    width: 70px;
                    right: -20px;
                    top: -15px;
                }

            }


            @media only screen and (max-width: 1440px) {

                .rex-feed-tb__notification {
                    max-height: 99px;
                }

                .wpcart-lift-anniv__image--left {
                    left: 40px;
                }

                .wpcart-lift-anniv__image--left img {
                    width: 90%;
                }

                .wpcart-lift-anniv__image--eid-mubarak img {
                    width: 90%;
                }

                .wpcart-lift-anniv__image--cartlift-logo img {
                    width: 90%;
                }

                .wpcart-lift-anniv__image--four img {
                    width: 90%;
                }

                .wpcart-lift-anniv__image--right img {
                    width: 90%;
                }

                .wpcart-lift-anniv__lead-text h2 {
                    font-size: 28px;
                }

                .wpcart-lift-anniv__image--group {
                    gap: 25px;
                }

                .wpcart-lift-anniv__content-area {
                    gap: 30px;
                    justify-content: center;
                }

                .wpcart-lift-anniv__btn {
                    font-size: 16px;
                    font-weight: 400;
                    border-radius: 30px;
                    padding: 12px 16px;
                }

                .wpcart-lift-anniv__btn-area svg {
                    position: absolute;
                    width: 60px;
                    right: -15px;
                    top: -15px;
                }

            }


            @media only screen and (max-width: 1399px) {

                .rex-feed-tb__notification {
                    max-height: 79px;
                }

                .wpcart-lift-anniv__image--left {
                    left: 20px;
                }

                .wpcart-lift-anniv__image--left img {
                    max-width: 86.39px;
                }

                .wpcart-lift-anniv__image--eid-mubarak img {
                    max-width: 132px;
                }

                .wpcart-lift-anniv__image--cartlift-logo img {
                    max-width: 88px;
                }

                .wpcart-lift-anniv__image--four img {
                    max-width: 203px;
                }

                .wpcart-lift-anniv__image--right img {
                    max-width: 121.5px;
                }

                .wpcart-lift-anniv__lead-text h2 {
                    font-size: 24px;
                }

                .wpcart-lift-anniv__image--group {
                    gap: 20px;
                }

                .wpcart-lift-anniv__content-area {
                    gap: 35px;
                }

                .wpcart-lift-anniv__btn {
                    font-size: 14px;
                    font-weight: 600;
                    border-radius: 30px;
                    padding: 12px 16px;
                }

                .wpcart-lift-anniv__btn-area svg {
                    width: 45px;
                    right: -13px;
                    top: -21px;
                }

            }

            @media only screen and (max-width: 1024px) {
                .rex-feed-tb__notification {
                    max-height: 75px;
                }

                .wpcart-lift-anniv__image--left img {
                    max-width: 76.39px;
                }

                .wpcart-lift-anniv__image--eid-mubarak img {
                    max-width: 122px;
                }

                .wpcart-lift-anniv__image--cartlift-logo img {
                    max-width: 82px;
                }

                .wpcart-lift-anniv__image--four img {
                    max-width: 193px;
                }

                .wpcart-lift-anniv__image--right img {
                    max-width: 111.5px;
                }

                .wpcart-lift-anniv__lead-text h2 {
                    font-size: 22px;
                }

                .wpcart-lift-anniv__lead-text svg {
                    width: 25px;
                    margin-top: -10px;
                }


                .wpcart-lift-anniv__content-area {
                    gap: 30px;
                }

                .wpcart-lift-anniv__image--group {
                    gap: 15px;
                }

                .wpcart-lift-anniv__btn {
                    font-size: 12px;
                    line-height: 1.2;
                    padding: 11px 12px;
                    font-weight: 400;
                }

                .wpcart-lift-anniv__btn {
                    box-shadow: none;
                }

                .wpcart-lift-anniv__image--right,
                .wpcart-lift-anniv__image--left {
                    display: none;
                }

                .wpcart-lift-anniv__btn-area svg {
                    width: 40px;
                    right: -15px;
                    top: -23px;
                }


            }

            @media only screen and (max-width: 768px) {

                .rex-feed-tb__notification {
                    margin: 60px 0 20px;
                }

                .wpcart-lift-anniv__container-area {
                    padding: 0 15px;
                }

                .wpcart-lift-anniv__container-area {
                    justify-content: center;
                    gap: 20px;
                }

                .rex-feed-tb__notification {
                    max-height: 64px;
                }

                .wpcart-lift-anniv__image--left img {
                    max-width: 76.39px;
                }

                .wpcart-lift-anniv__image--eid-mubarak img {
                    max-width: 92px;
                }

                .wpcart-lift-anniv__image--cartlift-logo img {
                    max-width: 71px;
                }

                .wpcart-lift-anniv__image--four img {
                    max-width: 163px;
                }

                .wpcart-lift-anniv__image--right img {
                    max-width: 111.5px;
                }

                .wpcart-lift-anniv__lead-text h2 {
                    font-size: 22px;
                }

                .wpcart-lift-anniv__content-area {
                    gap: 30px;
                }

                .wpcart-lift-anniv__image--group {
                    gap: 15px;
                }

                .rex-feed-tb__notification .rex-feed-tb__cross-top {
                    width: 25px;
                    height: 25px;
                }

                .wpcart-lift-anniv__image--group {
                    gap: 20px;
                }

                .wpcart-lift-anniv__image--left,
                .wpcart-lift-anniv__image--right {
                    display: none;
                }

                .wpcart-lift-anniv__btn {
                    font-size: 12px;
                    line-height: 1;
                    font-weight: 400;
                    padding: 10px 12px;
                    margin-left: 0;
                    box-shadow: none;
                }

                .wpcart-lift-anniv__content-area {
                    display: contents;
                    gap: 25px;
                    text-align: center;
                    align-items: center;
                }

                .wpcart-lift-anniv__lead-text svg {
                    width: 22px;
                    margin-top: -8px;
                }


            }

            @media only screen and (max-width: 767px) {
                .wpvr-promotional-banner {
                    padding-top: 20px;
                    padding-bottom: 30px;
                    max-height: none;
                }

                .wpvr-promotional-banner {
                    max-height: none;
                }

                .wpcart-lift-anniv__image--right,
                .wpcart-lift-anniv__image--left {
                    display: none;
                }

                .wpcart-lift-anniv__stroke-font {
                    font-size: 16px;
                }

                .wpcart-lift-anniv__content-area {
                    display: contents;
                    gap: 25px;
                    text-align: center;
                    align-items: center;
                }

                .wpcart-lift-anniv__btn-area {
                    justify-content: center;
                    padding-top: 5px;
                }

                .wpcart-lift-anniv__btn {
                    font-size: 12px;
                    padding: 15px 24px;
                }

                .wpcart-lift-anniv__image--group {
                    gap: 10px;
                    padding: 0;
                }
            }
        </style>

<?php
    }
}
