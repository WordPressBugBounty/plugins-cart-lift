<?php

//===General Data===//
$general_data = $settings[ 'general_settings' ] ?? [];

$cart_tracking_status = 'no';
$cart_tracking        = $general_data[ 'cart_tracking' ];
if ( $cart_tracking ) {
    $cart_tracking_status = 'yes';
}

$remove_carts_for_guest_status = 'yes';
$remove_carts_for_guest        = '';
if ( isset( $general_data[ 'remove_carts_for_guest' ] ) ) {
    $remove_carts_for_guest = $general_data[ 'remove_carts_for_guest' ];
}
if ( $remove_carts_for_guest ) {
    $remove_carts_for_guest_status = 'yes';
}

$disable_purchased_products_campaign_status = 'no';
$disable_purchased_products_campaign        = '';
if ( isset( $general_data[ 'disable_purchased_products_campaign' ] ) ) {
    $disable_purchased_products_campaign = $general_data[ 'disable_purchased_products_campaign' ];
}
if ( $disable_purchased_products_campaign ) {
    $disable_purchased_products_campaign_status = 'yes';
}

$notify_abandon_cart_status = 'no';
$notify_abandon_cart        = $general_data[ 'notify_abandon_cart' ];
if ( $notify_abandon_cart ) {
    $notify_abandon_cart_status = 'yes';
}

$notify_recovered_cart_status = 'no';
$notify_recovered_cart        = $general_data[ 'notify_recovered_cart' ];
if ( $notify_recovered_cart ) {
    $notify_recovered_cart_status = 'yes';
}

$manually_recovered_cart_status = 'no';
$manually_recovered_cart        = isset( $general_data[ 'manually_recovered_cart' ] ) ? $general_data[ 'manually_recovered_cart' ] : '0';
if ( $manually_recovered_cart ) {
    $manually_recovered_cart_status = 'yes';
}

$enable_smtp_status = 'no';
$enable_smtp        = $general_data[ 'enable_smtp' ];
if ( $enable_smtp ) {
    $enable_smtp_status = 'yes';
}

$enable_webhook_status = 'no';
$enable_webhook        = $general_data[ 'enable_webhook' ];
if ( $enable_webhook ) {
    $enable_webhook_status = 'yes';
}


$enable_weekly_report_status = 'no';
$enable_weekly_report        = isset( $general_data[ 'enable_weekly_report' ] ) ? $general_data[ 'enable_weekly_report' ] : '0';
if ( $enable_weekly_report ) {
    $enable_weekly_report_status = 'yes';
}

$enable_gdpr_status = 'no';
$enable_gdpr        = '';
if ( isset( $general_data[ 'enable_gdpr' ] ) ) {
    $enable_gdpr = $general_data[ 'enable_gdpr' ];
}

if ( $enable_gdpr ) {
    $enable_gdpr_status = 'yes';
}

$disable_branding_status = '';
$disable_branding        = $general_data[ 'disable_branding' ];
if ( $disable_branding ) {
    $disable_branding_status = 'checked';
}

$week_start_day            = !empty( $general_data[ 'weekly_report_start_day' ] ) ? $general_data[ 'weekly_report_start_day' ] : get_option( 'start_of_week' );
$weekly_report_admin_email = !empty( $general_data[ 'weekly_report_email' ] ) ? $general_data[ 'weekly_report_email' ] : get_option( 'admin_email' );
$weekly_report_email_from  = $general_data[ 'weekly_report_email_from' ] ?? '';
$weekly_report_email_body  = !empty( $general_data[ 'weekly_report_email_body' ] ) ? $general_data[ 'weekly_report_email_body' ] : __( 'Your email address will help us support your shopping experience throughout the site. Please check our Privacy Policy to see how we use your personal data.', 'cart-lift' );

$enable_cart_expiration_status = 'no';
$enable_cart_expiration        = $general_data[ 'enable_cart_expiration' ] ?? '0';
if ( $enable_cart_expiration ) {
    $enable_cart_expiration_status = 'yes';
}

$enable_cl_exclude_products_status = 'no';
$enable_cl_exclude_products       = $general_data[ 'enable_cl_exclude_products' ] ?? '0';
if ( $enable_cl_exclude_products  ) {
	$enable_cl_exclude_products_status = 'yes';
}

$enable_cl_exclude_categories_status = 'no';
$enable_cl_exclude_categories       = $general_data[ 'enable_cl_exclude_categories' ] ?? '0';
if ( $enable_cl_exclude_categories  ) {
    $enable_cl_exclude_categories_status = 'yes';
}

$enable_cl_exclude_countries_status = 'no';
$enable_cl_exclude_countries       = $general_data[ 'enable_cl_exclude_countries' ] ?? '0';
if ( $enable_cl_exclude_countries  ) {
    $enable_cl_exclude_countries_status = 'yes';
}

$selected_excluded_products = $general_data['cl_excluded_products'] ?? [];
$selected_excluded_categories = $general_data['cl_excluded_categories'] ?? [];
$selected_excluded_countries = $general_data['cl_excluded_countries'] ?? [];

$get_wc_and_edd_countries = get_wc_and_edd_countries();

?>

<h4 class="settings-tab-heading"><?php echo __('General', 'cart-lift'); ?></h4>
<form action="" id="general-settings-form">
    <div class="inner-wrapper">
        <div class="cl-form-group">
            <span class="title"><?php echo __('Enable abandoned cart tracking:', 'cart-lift'); ?></span>
            <span class="cl-switcher">
                <input class="cl-toggle-option" type="checkbox" id="cart_tracking" name="cart_tracking" data-status="<?php echo $cart_tracking_status; ?>" value="<?php echo $cart_tracking; ?>" <?php checked('1', $cart_tracking); ?> />
                <label for="cart_tracking"></label>
            </span>
            <div class="tooltip">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                        <defs>
                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                            </clipPath>
                        </defs>
                        <style>
                            tspan {
                                white-space: pre
                            }

                            .shp0 {
                                fill: #6e42d3
                            }
                        </style>
                        <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                            <g id="name">
                                <g id="question">
                                    <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                    <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                    <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                </g>
                            </g>
                        </g>
                    </svg>
                </span>
                <p><?php echo __('Allow Cart Lift to track abandoned carts.', 'cart-lift'); ?></p>
            </div>
        </div>

        <?php do_action('cl_after_abandoned_cart_tracking_field', $general_data); ?>

        <div class="cl-form-group">
            <span class="title"><?php echo __('Remove non-actionable carts:', 'cart-lift'); ?></span>
            <span class="cl-switcher">
                <input class="cl-toggle-option" type="checkbox" id="remove_carts_for_guest" name="remove_carts_for_guest" data-status="<?php echo $remove_carts_for_guest_status; ?>" value="<?php echo $remove_carts_for_guest; ?>" <?php checked('1', $remove_carts_for_guest); ?> />
                <label for="remove_carts_for_guest"></label>
            </span>
            <div class="tooltip">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                        <defs>
                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                            </clipPath>
                        </defs>
                        <style>
                            tspan {
                                white-space: pre
                            }

                            .shp0 {
                                fill: #6e42d3
                            }
                        </style>
                        <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                            <g id="name">
                                <g id="question">
                                    <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                    <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                    <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                </g>
                            </g>
                        </g>
                    </svg>
                </span>
                <p><?php echo __('Remove abandoned cart information if no email is captured.', 'cart-lift'); ?></p>
            </div>
        </div>

        <div class="cl-form-group">
            <span class="title"><?php echo __('Disable campaign emails for purchased products:', 'cart-lift'); ?></span>
            <span class="cl-switcher">
                <input class="cl-toggle-option" type="checkbox" id="disable_purchased_products_campaign" name="disable_purchased_products_campaign" data-status="<?php echo $disable_purchased_products_campaign_status; ?>" value="<?php echo $disable_purchased_products_campaign; ?>" <?php checked('1', $disable_purchased_products_campaign); ?> />
                <label for="disable_purchased_products_campaign"></label>
            </span>
            <div class="tooltip">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                        <defs>
                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                            </clipPath>
                        </defs>
                        <style>
                            tspan {
                                white-space: pre
                            }

                            .shp0 {
                                fill: #6e42d3
                            }
                        </style>
                        <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                            <g id="name">
                                <g id="question">
                                    <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                    <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                    <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                </g>
                            </g>
                        </g>
                    </svg>
                </span>
                <p><?php echo __('Disable campaign emails if the abandoned product is already purchased by the user.', 'cart-lift'); ?></p>
            </div>
        </div>

        <div class="cl-form-group">
            <span class="title"><?php echo __('Notify admin for abandoned cart:', 'cart-lift'); ?></span>
            <span class="cl-switcher">
                <input class="cl-toggle-option" type="checkbox" id="notify_abandon_cart" name="notify_abandon_cart" data-status="<?php echo $notify_abandon_cart_status; ?>" value="<?php echo $notify_abandon_cart; ?>" <?php checked('1', $notify_abandon_cart); ?> />
                <label for="notify_abandon_cart"></label>
            </span>
            <div class="tooltip">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                        <defs>
                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                            </clipPath>
                        </defs>
                        <style>
                            tspan {
                                white-space: pre
                            }

                            .shp0 {
                                fill: #6e42d3
                            }
                        </style>
                        <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                            <g id="name">
                                <g id="question">
                                    <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                    <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                    <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                </g>
                            </g>
                        </g>
                    </svg>
                </span>
                <p><?php echo __('Admin will get an email notification when cart is abandoned.', 'cart-lift'); ?></p>
            </div>
        </div>

        <div class="cl-form-group <?php if ($cl_pro_tag) echo 'cl-pro' ?>">
            <span class="title"><?php echo __('Notify admin for recovered cart:', 'cart-lift'); ?></span>
            <span class="cl-switcher">
                <?php
                $pro_url = add_query_arg('cl-dashboard', '1', 'https://rextheme.com/cart-lift');
                ?>
                <?php
                if ($cl_pro_tag) {
                    $notify_recovered_cart = 0;
                ?>
                    <a href="<?php echo $pro_url; ?>" target="_blank" title="<?php _e('Click to Upgrade Pro', 'cart-lift'); ?>" class="pro-tag"><?php echo __('pro', 'cart-lift'); ?></a>
                <?php } ?>
                <input class="cl-toggle-option" type="checkbox" id="notify_recovered_cart" name="notify_recovered_cart" data-status="<?php echo $notify_recovered_cart_status; ?>" value="<?php echo $notify_recovered_cart; ?>" <?php checked('1', $notify_recovered_cart); ?> <?php if ($cl_pro_tag) echo 'disabled' ?> />
                <label for="notify_recovered_cart"></label>
            </span>
            <div class="tooltip">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                        <defs>
                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                            </clipPath>
                        </defs>
                        <style>
                            tspan {
                                white-space: pre
                            }

                            .shp0 {
                                fill: #6e42d3
                            }
                        </style>
                        <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                            <g id="name">
                                <g id="question">
                                    <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                    <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                    <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                </g>
                            </g>
                        </g>
                    </svg>
                </span>
                <p><?php echo __('Admin will get an email notification when cart is recovered.', 'cart-lift'); ?></p>
            </div>
        </div>

        <div class="cl-form-group <?php if ($cl_pro_tag) echo 'cl-pro' ?>">
            <span class="title"><?php echo __('Enable Manual Recovery Process:', 'cart-lift'); ?></span>
            <span class="cl-switcher">
                <?php
                $pro_url = add_query_arg('cl-dashboard', '1', 'https://rextheme.com/cart-lift');
                ?>
                <?php
                if ($cl_pro_tag) {
                    $manually_recovered_cart = 0;
                ?>
                    <a href="<?php echo $pro_url; ?>" target="_blank" title="<?php _e('Click to Upgrade Pro', 'cart-lift'); ?>" class="pro-tag"><?php echo __('pro', 'cart-lift'); ?></a>
                <?php } ?>
                <input class="cl-toggle-option" type="checkbox" id="manually_recovered_cart" name="manually_recovered_cart" data-status="<?php echo $manually_recovered_cart_status; ?>" value="<?php echo $manually_recovered_cart; ?>" <?php checked('1', $manually_recovered_cart); ?> <?php if ($cl_pro_tag) echo 'disabled' ?> />
                <label for="manually_recovered_cart"></label>
            </span>
            <div class="tooltip">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                        <defs>
                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                            </clipPath>
                        </defs>
                        <style>
                            tspan {
                                white-space: pre
                            }

                            .shp0 {
                                fill: #6e42d3
                            }
                        </style>
                        <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                            <g id="name">
                                <g id="question">
                                    <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                    <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                    <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                </g>
                            </g>
                        </g>
                    </svg>
                </span>
                <p><?php echo __('This will enable the process of manually recovering abandoned carts in the cart view popup.', 'cart-lift'); ?></p>
            </div>
        </div>

        <div class="cl-form-group <?php if ($cl_pro_tag) echo 'cl-pro' ?>">
            <span class="title"><?php echo __('Disable branding:', 'cart-lift'); ?></span>
            <span class="cl-switcher">
                <?php
                $pro_url = add_query_arg('cl-dashboard', '1', 'https://rextheme.com/cart-lift');
                ?>
                <?php
                if ($cl_pro_tag) {
                    $disable_branding_status = '';
                ?>
                    <a href="<?php echo $pro_url; ?>" target="_blank" title="<?php _e('Click to Upgrade Pro', 'cart-lift'); ?>" class="pro-tag"><?php echo __('pro', 'cart-lift'); ?></a>
                <?php } ?>
                <input class="cl-toggle-option" type="checkbox" id="disable_branding" name="disable_branding" data-status="" value="" <?php if ($cl_pro_tag) echo 'disabled' ?> <?php echo $disable_branding_status; ?> />
                <label for="disable_branding"></label>
            </span>
            <div class="tooltip">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                        <defs>
                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                            </clipPath>
                        </defs>
                        <style>
                            tspan {
                                white-space: pre
                            }

                            .shp0 {
                                fill: #6e42d3
                            }
                        </style>
                        <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                            <g id="name">
                                <g id="question">
                                    <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                    <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                    <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                </g>
                            </g>
                        </g>
                    </svg>
                </span>
                <p><?php echo __('Remove Cart Lift branding from footer.', 'cart-lift'); ?></p>
            </div>
        </div>

        <div class="cl-form-group cl-webhook">
            <div class="cl-form-group-webhook">
                <span class="title"><?php echo __('Enable external webhook:', 'cart-lift'); ?></span>
                <span class="cl-switcher">
                    <input class="cl-toggle-option" type="checkbox" id="enable_cl_webhook" name="enable_webhook" data-status="<?php echo $enable_webhook_status; ?>" value="<?php echo $enable_webhook; ?>" <?php checked('1', $enable_webhook); ?> />
                    <label for="enable_cl_webhook"></label>
                </span>
                <div class="tooltip">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                            <defs>
                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                    <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                </clipPath>
                            </defs>
                            <style>
                                tspan {
                                    white-space: pre
                                }

                                .shp0 {
                                    fill: #6e42d3
                                }
                            </style>
                            <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                <g id="name">
                                    <g id="question">
                                        <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                        <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                        <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>
                    <p><?php echo __('Allows to connect with 3rd-party services.', 'cart-lift'); ?></p>
                </div>

            </div>

            <?php if ($enable_webhook_status == 'yes') { ?>
                <div id="cart_webhook">
                    <span class="title"><?php echo __('Webhook URL:', 'cart-lift'); ?></span>
                    <div class="cl-cart-webhook-notice">
                        <div class="cl-cart-webhook-area">
                            <input class="cart_webhook" type="text" name="cart_webhook" id="webhook_url" value="<?php echo $general_data['cart_webhook']; ?>">
                            <div class="cl-cart-webhook-button">
                                <button type="button" class="cl-btn trigger-webhook-btn" id="trigger_webhook" name="trigger_webhook"><?php echo __('Test', 'cart-lift'); ?></button>
                                <div class="tooltip">
                                    <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                            <defs>
                                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                    <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                                </clipPath>
                                            </defs>
                                            <style>
                                                tspan {
                                                    white-space: pre
                                                }

                                                .shp0 {
                                                    fill: #6e42d3
                                                }
                                            </style>
                                            <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                                <g id="name">
                                                    <g id="question">
                                                        <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                        <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                        <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <p><?php echo __('Enter url here.', 'cart-lift'); ?></p>
                                </div>
                            </div>
                        </div>
                        <p id="webhook-notice" class="cl-notice" style="margin-left:5px; display:none;"></p>
                   </div>
                </div>
                <?php } else { ?>
                    <div  id="cart_webhook" style="display:none;">
                        <span class="title"><?php echo __('Webhook URL:', 'cart-lift'); ?></span>
                        <div class="cl-cart-webhook-notice">
                            <div class="cl-cart-webhook-area">
                                <input class="cart_webhook" type="text" name="cart_webhook" id="webhook_url" value="<?php echo $general_data['cart_webhook']; ?>">
                                <div class="cl-cart-webhook-button">
                                    <button type="button" class="cl-btn trigger-webhook-btn" id="trigger_webhook" name="trigger_webhook"><?php echo __('Test', 'cart-lift'); ?></button>
                                    <div class="tooltip">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                                <defs>
                                                    <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                        <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                                    </clipPath>
                                                </defs>
                                                <style>
                                                    tspan {
                                                        white-space: pre
                                                    }

                                                    .shp0 {
                                                        fill: #6e42d3
                                                    }
                                                </style>
                                                <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                                    <g id="name">
                                                        <g id="question">
                                                            <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                            <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                            <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </span>
                                        <p><?php echo __('Enter url here.', 'cart-lift'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <p id="webhook-notice" class="cl-notice" style="margin-left:5px; display:none;"></p>
                        </div>
                    </div>
             <?php } ?>

        </div>


        <div class="cl-form-group <?php if ($cl_pro_tag) echo 'cl-pro' ?> cl-weekly">
            <div class="cl-form-weekly">
                <span class="title"><?php echo __('Enable Weekly Report:', 'cart-lift'); ?></span>
                <span class="cl-switcher">
                    <?php
                    $pro_url = add_query_arg('cl-dashboard', '1', 'https://rextheme.com/cart-lift');
                    ?>
                    <?php
                    if ($cl_pro_tag) {
                        $enable_weekly_report = 0;
                    ?>
                        <a href="<?php echo $pro_url; ?>" target="_blank" title="<?php _e('Click to Upgrade Pro', 'cart-lift'); ?>" class="pro-tag"><?php echo __('pro', 'cart-lift'); ?></a>
                    <?php } ?>
                    <input class="cl-toggle-option" type="checkbox" id="enable_cl_weekly_report" name="enable_weekly_report" data-status="<?php echo $enable_weekly_report_status; ?>" value="<?php echo $enable_weekly_report; ?>" <?php checked('1', $enable_weekly_report); ?> <?php if ($cl_pro_tag) echo 'disabled' ?> />
                    <label for="enable_cl_weekly_report"></label>
                </span>
                <div class="tooltip">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                            <defs>
                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                    <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                </clipPath>
                            </defs>
                            <style>
                                tspan {
                                    white-space: pre
                                }

                                .shp0 {
                                    fill: #6e42d3
                                }
                            </style>
                            <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                <g id="name">
                                    <g id="question">
                                        <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                        <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                        <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>
                    <p><?php echo __('Allows you to get the weekly report.', 'cart-lift'); ?></p>
                </div>
            </div>


            <?php
                if ($enable_weekly_report_status == 'yes' && $enable_weekly_report == '1') {
            ?>
            <div id="enable_weekly_report">
                <div class="cl-form-weekly-report-area-left">
                    <div class="cl-form-group-weekly">
                        <span class="title"><?php echo __('Choose Week Day:', 'cart-lift'); ?></span>
                        <div class="cl-form-group-weekly-area">
                            <select id="chart-option" name="weekly_report_start_day">
                                <option value="0" <?php echo isset($week_start_day) && $week_start_day == '0' ? 'selected' : ''; ?>><?php echo __('Sunday', 'cart-lift'); ?></option>
                                <option value="1" <?php echo isset($week_start_day) && $week_start_day == '1' ? 'selected' : ''; ?>><?php echo __('Monday', 'cart-lift'); ?></option>
                                <option value="2" <?php echo isset($week_start_day) && $week_start_day == '2' ? 'selected' : ''; ?>><?php echo __('Tuesday', 'cart-lift'); ?></option>
                                <option value="3" <?php echo isset($week_start_day) && $week_start_day == '3' ? 'selected' : ''; ?>><?php echo __('Wednesday', 'cart-lift'); ?></option>
                                <option value="4" <?php echo isset($week_start_day) && $week_start_day == '4' ? 'selected' : ''; ?>><?php echo __('Thursday', 'cart-lift'); ?></option>
                                <option value="5" <?php echo isset($week_start_day) && $week_start_day == '5' ? 'selected' : ''; ?>><?php echo __('Friday', 'cart-lift'); ?></option>
                                <option value="6" <?php echo isset($week_start_day) && $week_start_day == '6' ? 'selected' : ''; ?>><?php echo __('Saturday', 'cart-lift'); ?></option>
                            </select>
                            <div class="tooltip">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                        <defs>
                                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                            </clipPath>
                                        </defs>
                                        <style>
                                            tspan {
                                                white-space: pre
                                            }

                                            .shp0 {
                                                fill: #6e42d3
                                            }
                                        </style>
                                        <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                            <g id="name">
                                                <g id="question">
                                                    <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                    <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                    <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                                <p><?php echo __('Select week start day', 'cart-lift'); ?></p>
                            </div>

                        </div>
                    </div>
                    <div class="cl-form-group-weekly">
                        <span class="title"><?php echo __('Email To:', 'cart-lift'); ?></span>
                        <div class="cl-form-group-weekly-area">
                            <input type="text" id="weekly_report_email" name="weekly_report_email" value="<?php echo isset($weekly_report_admin_email) ? $weekly_report_admin_email : ''; ?>">
                            <div class="tooltip">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                        <defs>
                                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                            </clipPath>
                                        </defs>
                                        <style>
                                            tspan {
                                                white-space: pre
                                            }

                                            .shp0 {
                                                fill: #6e42d3
                                            }
                                        </style>
                                        <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                            <g id="name">
                                                <g id="question">
                                                    <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                    <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                    <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                                <p><?php echo __('Enter recipients (comma separated) for this email.', 'cart-lift'); ?></p>
                            </div>
                       </div>
                    </div>
                    <div class="cl-form-group-weekly">
                        <span class="title"><?php echo __('Email From:', 'cart-lift'); ?></span>
                        <div class="cl-form-group-weekly-area">
                            <input type="text" id="weekly_report_email_form" name="weekly_report_email_from" value="<?php echo isset($weekly_report_email_from) ? $weekly_report_email_from : ''; ?>">
                            <div class="tooltip">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                        <defs>
                                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                            </clipPath>
                                        </defs>
                                        <style>
                                            tspan {
                                                white-space: pre
                                            }

                                            .shp0 {
                                                fill: #6e42d3
                                            }
                                        </style>
                                        <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                            <g id="name">
                                                <g id="question">
                                                    <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                    <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                    <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                                <p><?php echo __('Enter sender email.', 'cart-lift'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cl-form-weekly-report-area-right">
                    <div class="cl-form-group-weekly-email-body">
                        <label class="title" for="weekly-report-email-body">
                            <?php echo __('Email Body:', 'cart-lift'); ?>
                        </label>
                        <?php
                            wp_editor(
                                $weekly_report_email_body,
                                'weekly_report_email_body',
                                array(
                                    'media_buttons' => true,
                                    'textarea_rows' => 15,
                                    'tabindex' => 4,
                                    'tinymce'  => array(
                                        'theme_advanced_buttons1' => 'bold,italic,underline,|,bullist,numlist,blockquote,|,link,unlink,|,spellchecker,fullscreen,|,formatselect,styleselect',
                                    ),
                                )
                            );
                        ?>
                        
                    </div>
                </div>
                <p id="weekly-report-notice" class="cl-weekly-report-notice" style="margin-left:5px; display:none;"></p>
            </div>

            <?php } else { ?>
                
                <div id="enable_weekly_report" style="display:none;">
                    <div class="cl-form-weekly-report-area-left">
                        <div class="cl-form-group-weekly">
                            <span class="title"><?php echo __('Choose Week Day:', 'cart-lift'); ?></span>
                            <div class="cl-form-group-weekly-area">
                                <select id="chart-option" name="weekly_report_start_day">
                                    <option value="0" <?php echo isset($week_start_day) && $week_start_day == '0' ? 'selected' : ''; ?>><?php echo __('Sunday', 'cart-lift'); ?></option>
                                    <option value="1" <?php echo isset($week_start_day) && $week_start_day == '1' ? 'selected' : ''; ?>><?php echo __('Monday', 'cart-lift'); ?></option>
                                    <option value="2" <?php echo isset($week_start_day) && $week_start_day == '2' ? 'selected' : ''; ?>><?php echo __('Tuesday', 'cart-lift'); ?></option>
                                    <option value="3" <?php echo isset($week_start_day) && $week_start_day == '3' ? 'selected' : ''; ?>><?php echo __('Wednesday', 'cart-lift'); ?></option>
                                    <option value="4" <?php echo isset($week_start_day) && $week_start_day == '4' ? 'selected' : ''; ?>><?php echo __('Thursday', 'cart-lift'); ?></option>
                                    <option value="5" <?php echo isset($week_start_day) && $week_start_day == '5' ? 'selected' : ''; ?>><?php echo __('Friday', 'cart-lift'); ?></option>
                                    <option value="6" <?php echo isset($week_start_day) && $week_start_day == '6' ? 'selected' : ''; ?>><?php echo __('Saturday', 'cart-lift'); ?></option>
                                </select>
                                <div class="tooltip">
                                    <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                            <defs>
                                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                    <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                                </clipPath>
                                            </defs>
                                            <style>
                                                tspan {
                                                    white-space: pre
                                                }

                                                .shp0 {
                                                    fill: #6e42d3
                                                }
                                            </style>
                                            <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                                <g id="name">
                                                    <g id="question">
                                                        <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                        <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                        <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <p><?php echo __('Select week start day', 'cart-lift'); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="cl-form-group-weekly">
                            <span class="title"><?php echo __('Email To:', 'cart-lift'); ?></span>
                            <div class="cl-form-group-weekly-area">
                                <input type="text" id="weekly_report_email" name="weekly_report_email" value="<?php echo isset($weekly_report_admin_email) ? $weekly_report_admin_email : ''; ?>">
                                <div class="tooltip">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                        <defs>
                                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                            </clipPath>
                                        </defs>
                                        <style>
                                            tspan {
                                                white-space: pre
                                            }

                                            .shp0 {
                                                fill: #6e42d3
                                            }
                                        </style>
                                        <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                            <g id="name">
                                                <g id="question">
                                                    <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                    <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                    <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                                <p><?php echo __('Enter recipients (comma separated) for this email.', 'cart-lift'); ?></p>
                                </div>
                          </div>
                        </div>

                        <div class="cl-form-group-weekly">
                            <span class="title"><?php echo __('Email From:', 'cart-lift'); ?></span>
                            <div class="cl-form-group-weekly-area">
                                <input type="text" id="weekly_report_email_form" name="weekly_report_email_from" value="<?php echo isset($weekly_report_admin_email_from) ? $weekly_report_admin_email_from : ''; ?>">
                   
                                <div class="tooltip">
                                    <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                            <defs>
                                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                    <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                                </clipPath>
                                            </defs>
                                            <style>
                                                tspan {
                                                    white-space: pre
                                                }

                                                .shp0 {
                                                    fill: #6e42d3
                                                }
                                            </style>
                                            <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                                <g id="name">
                                                    <g id="question">
                                                        <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                        <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                        <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <p><?php echo __('Enter sender this email.', 'cart-lift'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cl-form-weekly-report-area-right">
                        <div class="cl-form-group-weekly-email-body">
                            <label class="title" for="weekly-report-email-body">
                                <?php echo __('Email Body:', 'cart-lift'); ?>
                            </label>
                            <?php
                                wp_editor(
                                    $weekly_report_email_body,
                                    'weekly_report_email_body',
                                    array(
                                        'media_buttons' => true,
                                        'textarea_rows' => 15,
                                        'tabindex' => 4,
                                        'tinymce'  => array(
                                            'theme_advanced_buttons1' => 'bold,italic,underline,|,bullist,numlist,blockquote,|,link,unlink,|,spellchecker,fullscreen,|,formatselect,styleselect',
                                        ),
                                    )
                                );
                            ?>
                        </div>
                    </div>

                    <p id="weekly-report-notice" class="cl-weekly-report-notice" style="margin-left:5px; display:none;"></p>

                </div>

            <?php } ?>

        </div>
        

        <div class="cl-form-group cl-gdpr-integration">
            <div class="cl-gdpr-integration">
                <span class="title"><?php echo __('Enable GDPR integration:', 'cart-lift'); ?></span>
                <span class="cl-switcher">
                    <input class="cl-toggle-option" type="checkbox" id="cl_enable_gdpr" name="enable_gdpr" data-status="<?php echo $enable_gdpr_status; ?>" value="<?php echo $enable_gdpr; ?>" <?php checked('1', $enable_gdpr); ?> />
                    <label for="cl_enable_gdpr"></label>
                </span>

                <div class="tooltip">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                            <defs>
                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                    <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                </clipPath>
                            </defs>
                            <style>
                                tspan {
                                    white-space: pre
                                }

                                .shp0 {
                                    fill: #6e42d3
                                }
                            </style>
                            <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                <g id="name">
                                    <g id="question">
                                        <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                        <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                        <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>
                    <p><?php echo __('Ask confirmation from the user/customer before tracking data.', 'cart-lift'); ?></p>
                </div>
            </div>

            <div class="cl-gdpr-message" id="cl-gdpr-message" style="display: <?php echo $enable_gdpr ? 'grid' : 'none'; ?>">
                <span class="title"><?php echo __('GDPR message:', 'cart-lift'); ?></span>
                <div>
                    <textarea role="1" class="cl-gdpr-message" type="text" name="gdpr_text" id="cl_gdpr_message"><?php echo isset($general_data['gdpr_text']) ? $general_data['gdpr_text'] : ''; ?></textarea>
                    <span class="hints"><?php echo __('Note: This confirmation message will show below the email field on checkout page.', 'cart-lift'); ?></span>
                </div>
            </div>

        </div>

        <div class="cl-form-group cl-cart-expiration-area">
            <div class="cl-cart-expiration">
                <span class="title"><?php echo __('Enable Cart Expiration:', 'cart-lift'); ?></span>
                <span class="cl-switcher">
                    <input class="cl-toggle-option" type="checkbox" id="enable_cart_expiration_toggle" name="enable_cart_expiration" data-status="<?php echo $enable_cart_expiration_status; ?>" value="<?php echo $enable_cart_expiration; ?>" <?php checked('1', $enable_cart_expiration); ?> />
                    <label for="enable_cart_expiration_toggle"></label>
                </span>
                <div class="tooltip">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                            <defs>
                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                    <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                </clipPath>
                            </defs>
                            <style>
                                tspan {
                                    white-space: pre
                                }

                                .shp0 {
                                    fill: #6e42d3
                                }
                            </style>
                            <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                <g id="name">
                                    <g id="question">
                                        <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                        <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                        <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>
                    <p><?php echo __('Lets you set cart expiration based on status', 'cart-lift'); ?></p>
                </div>
            </div>

            <?php
            if ($enable_cart_expiration_status == 'yes' && $enable_cart_expiration == '1') {
            ?>

            <div id="enable_cart_expiration">
                <div class="cl-form-expiration">
                    <span class="title"><?php echo __('Cart Expiration Time:', 'cart-lift'); ?></span>
                    <input class="cart_expiration_time" type="number" name="cart_expiration_time" id="cl-expiration-time" value="<?php echo (int)$general_data['cart_expiration_time']; ?>">
                    <div class="cl-form-expiration-tooltip">
                        <span class="hits"><?php echo __('Days', 'cart-lift'); ?></span>
                        <div class="tooltip">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                    <defs>
                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                            <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                        </clipPath>
                                    </defs>
                                    <style>
                                        tspan {
                                            white-space: pre
                                        }

                                        .shp0 {
                                            fill: #6e42d3
                                        }
                                    </style>
                                    <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                        <g id="name">
                                            <g id="question">
                                                <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <p><?php echo __('Orders will be deleted with a minimum time limit of 7 days.', 'cart-lift'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="cl-form-expiration-status">
                    <span class="title"><?php echo __('Cart Expiration Status:', 'cart-lift'); ?></span>
                    <div>
                    <ul>
                        <li class="cl-checkbox">
                            <input type="checkbox" class="cl-toggle-option" id="recovered" name="recovered" value="<?php echo !empty( $general_data['recovered'] ) ? '1' : '' ?>" <?php echo !empty( $general_data['recovered'] ) ? "checked" : ''; ?> />
                            <label for="recovered"><?php echo __('Recovered', 'cart-lift'); ?></label>
                        </li>
                        <li class="cl-checkbox">
                            <input type="checkbox" class="cl-toggle-option" id="completed" name="completed" value="<?php echo !empty( $general_data['completed'] ) ? '1' : '' ?>" <?php echo !empty( $general_data['completed'] ) ? "checked" : ''; ?> />
                            <label for="completed"><?php echo __('Completed', 'cart-lift'); ?></label>
                        </li>
                        <li class="cl-checkbox">
                            <input type="checkbox" class="cl-toggle-option" id="abandoned" name="abandoned" value="<?php echo !empty( $general_data['abandoned'] ) ? '1' : '' ?>" <?php echo !empty( $general_data['abandoned'] ) ? "checked" : ''; ?>>
                            <label for="abandoned"><?php echo __('Abandoned', 'cart-lift'); ?></label>
                        </li>
                        <li class="cl-checkbox">
                            <input type="checkbox" class="cl-toggle-option" id="processing" name="processing" value="<?php echo !empty( $general_data['processing'] ) ? '1' : '' ?>" <?php echo !empty( $general_data['processing'] ) ? "checked" : ''; ?>>
                            <label for="processing"><?php echo __('Processing', 'cart-lift'); ?></label>
                        </li>
                        <li class="cl-checkbox">
                            <input type="checkbox" class="cl-toggle-option" id="discard" name="discard" value="<?php echo !empty( $general_data['discard'] ) ? '1' : '' ?>" <?php echo !empty( $general_data['discard'] ) ? "checked" : ''; ?>>
                            <label for="discard"><?php echo __('Discard', 'cart-lift'); ?></label>
                        </li>
                        <li class="cl-checkbox">
                            <input type="checkbox" class="cl-toggle-option" id="lost" name="lost" value="<?php echo !empty( $general_data['lost'] ) ? '1' : '' ?>" <?php echo !empty( $general_data['lost'] ) ? "checked" : ''; ?>>
                            <label for="lost"><?php echo __('Lost', 'cart-lift'); ?></label>
                        </li>
                        </ul>
                        <span class="hints cart-expiration-hints-text"><?php echo __('<b>Hints:</b> It will delete orders with the selected status from the database.', 'cart-lift'); ?></span>
                    </div>
                </div>
            </div>
            <?php } else { ?>

            <div id="enable_cart_expiration" style="display: none;">
                <div class="cl-form-expiration">
                    <span class="title"><?php echo __('Cart Expiration Time:', 'cart-lift'); ?></span>
                    <input class="cart_expiration_time" type="number" name="cart_expiration_time" id="cl-expiration-time" value="<?php echo (int)$general_data['cart_expiration_time']; ?>">
                   
                    <div class="cl-form-expiration-tooltip">
                        <span class="hits"><?php echo __('Days', 'cart-lift'); ?></span>
                        <div class="tooltip">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                    <defs>
                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                            <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                        </clipPath>
                                    </defs>
                                    <style>
                                        tspan {
                                            white-space: pre
                                        }

                                        .shp0 {
                                            fill: #6e42d3
                                        }
                                    </style>
                                    <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                        <g id="name">
                                            <g id="question">
                                                <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <p><?php echo __('Orders will be deleted with a minimum time limit of 7 days.', 'cart-lift'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="cl-form-expiration-status">
                    <span class="title"><?php echo __('Cart Expiration Status:', 'cart-lift'); ?></span>
                    <div>
                        <ul>
                            <li class="cl-checkbox">
                                <input type="checkbox" class="cl-toggle-option" id="recovered" name="recovered" value="<?php echo !empty( $general_data['recovered'] ) ? '1' : '' ?>" <?php echo !empty( $general_data['recovered'] ) ? "checked" : ''; ?>/>
                                <label for="recovered"><?php echo __('Recovered', 'cart-lift'); ?></label>
                            </li>

                            <li class="cl-checkbox">
                                <input type="checkbox" class="cl-toggle-option" id="completed" name="completed" value="<?php echo !empty( $general_data['completed'] ) ? '1' : '' ?>" <?php echo !empty( $general_data['completed'] ) ? "checked" : ''; ?>/>
                                <label for="completed"><?php echo __('Completed', 'cart-lift'); ?></label>
                            </li>
                            <li class="cl-checkbox">
                                <input type="checkbox" class="cl-toggle-option" id="abandoned" name="abandoned" value="<?php echo !empty( $general_data['abandoned'] ) ? '1' : '' ?>" <?php echo !empty( $general_data['abandoned'] ) ? "checked" : ''; ?>>
                                <label for="abandoned"><?php echo __('Abandoned', 'cart-lift'); ?></label>
                            </li>
                            <li class="cl-checkbox">
                                <input type="checkbox" class="cl-toggle-option" id="processing" name="processing" value="<?php echo !empty( $general_data['processing'] ) ? '1' : '' ?>" <?php echo !empty( $general_data['processing'] ) ? "checked" : ''; ?>>
                                <label for="processing"><?php echo __('Processing', 'cart-lift'); ?></label>
                            </li>
                            <li class="cl-checkbox">
                                <input type="checkbox" class="cl-toggle-option" id="discard" name="discard" value="<?php echo !empty( $general_data['discard'] ) ? '1' : '' ?>" <?php echo !empty( $general_data['discard'] ) ? "checked" : ''; ?>>
                                <label for="discard"><?php echo __('Discard', 'cart-lift'); ?></label>
                            </li>
                            <li class="cl-checkbox">
                                <input type="checkbox" class="cl-toggle-option" id="lost" name="lost" value="<?php echo !empty( $general_data['lost'] ) ? '1' : '' ?>" <?php echo !empty( $general_data['lost'] ) ? "checked" : ''; ?>>
                                <label for="lost"><?php echo __('Lost', 'cart-lift'); ?></label>
                            </li>
                        </ul>

                        <span class="hints cart-expiration-hints-text">
                            <?php echo __('<b>Hints:</b> It will delete orders with the selected status from the database.', 'cart-lift'); ?>
                        </span>
                    </div>
                </div>

            </div>

            <?php } ?>

        </div>
        
        <div class="cl-form-group cl-cut-time-area">
            <div class="cl-cut-off-time">
                <span class="title"><?php echo __('Cart abandoned cut-off time:', 'cart-lift'); ?></span>
                <input class="cart_expiration_time" type="number" name="cart_abandonment_time" id="cl-abandonment-time" value="<?php echo (int)$general_data['cart_abandonment_time']; ?>">
                <div class="cl-cut-off-time-tooltip">
                    <span class="hits"><?php echo __('Minutes', 'cart-lift'); ?></span>

                    <div class="tooltip">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                <defs>
                                    <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                        <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                    </clipPath>
                                </defs>
                                <style>
                                    tspan {
                                        white-space: pre
                                    }

                                    .shp0 {
                                        fill: #6e42d3
                                    }
                                </style>
                                <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                    <g id="name">
                                        <g id="question">
                                            <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                            <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                            <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                        <p><?php echo __('Minimum time to consider a cart as abandoned. Minimum time limit 15 minutes.', 'cart-lift'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="cl-form-group cl-select-area">

            <div class="cl-select-content">
                <span class="title"><?php echo __('Enable exclude products from recovery:', 'cart-lift'); ?></span>
                <span class="cl-switcher">
                    <input class="cl-toggle-option" type="checkbox" id="enable_cl_exclude_products" name="enable_cl_exclude_products" data-status="<?php echo $enable_cl_exclude_products_status; ?>" value="<?php echo $enable_cl_exclude_products; ?>" <?php checked('1', $enable_cl_exclude_products); ?>/>
                    <label for="enable_cl_exclude_products"></label>
                </span>
                <div class="tooltip">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                            <defs>
                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                    <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                </clipPath>
                            </defs>
                            <style>
                                tspan {
                                    white-space: pre
                                }

                                .shp0 {
                                    fill: #6e42d3
                                }
                            </style>
                            <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                <g id="name">
                                    <g id="question">
                                        <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                        <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                        <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>
                    <p><?php echo __('Add products that, if found in the cart, will prevent it from being tracked as abandoned.', 'cart-lift'); ?></p>
                </div>
            </div>

            <?php
            if ($enable_cl_exclude_products_status == 'yes' && $enable_cl_exclude_products == '1') {
                ?>
                <div id="enable_excluded_products_section">
                    <div class="cl-form-select-product">
                        <span class="title"><?php echo __('Choose Products:', 'cart-lift'); ?></span>
                        <select class="cl-exclude-select2" id="cl_excluded_products" name="cl_excluded_products[]" multiple="multiple">
                            <?php
                            if( $selected_excluded_products ) {
                                foreach ( $selected_excluded_products as $product_id ) {
                                    if( function_exists( 'cl_is_wc_active' ) && cl_is_wc_active() ) {
                                        $product = wc_get_product( $product_id );
                                        if ( is_object( $product ) ) {
                                            $product_name = $product->get_formatted_name();
                                        }
                                    }
                                    if( function_exists( 'cl_is_edd_active' ) && cl_is_edd_active() ) {
                                        $product = new EDD_Download( $product_id );
                                        if ( $product->get_ID() ) {
                                            $product_name = $product->get_name();
                                        }
                                    }
                                    if ( !empty( $product ) && is_object( $product ) ) {
                                        echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product_name ) . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                        <div class="tooltip">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                    <defs>
                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                            <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                        </clipPath>
                                    </defs>
                                    <style>
                                        tspan {
                                            white-space: pre
                                        }

                                        .shp0 {
                                            fill: #6e42d3
                                        }
                                    </style>
                                    <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                        <g id="name">
                                            <g id="question">
                                                <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <p><?php echo __('Select products to exclude from abandoned cart recovery', 'cart-lift'); ?></p>
                        </div>
                    </div>
                    <p id="excluded-products-notice" class="cl-weekly-report-notice" style="margin-left:5px; display:none;"></p>
                </div>
                <?php
            } else {
                ?>
                <div id="enable_excluded_products_section" style="display:none;">
                    <div class="cl-form-select-product">
                        <span class="title"><?php echo __('Choose Products:', 'cart-lift'); ?></span>
                        <select class="cl-exclude-select2" id="cl_excluded_products" name="cl_excluded_products[]" multiple="multiple">
                            <?php
                            if( $selected_excluded_products ) {
                                foreach ( $selected_excluded_products as $product_id ) {
                                    if( function_exists( 'cl_is_wc_active' ) && cl_is_wc_active() ) {
                                        $product = wc_get_product( $product_id );
                                        if ( is_object( $product ) ) {
                                            $product_name = $product->get_formatted_name();
                                        }
                                    }
                                    if( function_exists( 'cl_is_edd_active' ) && cl_is_edd_active() ) {
                                        $product = new EDD_Download( $product_id );
                                        if ( $product->get_ID() ) {
                                            $product_name = $product->get_name();
                                        }
                                    }
                                    if ( !empty( $product ) && is_object( $product ) ) {
                                        echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product_name ) . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                        <div class="tooltip">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                    <defs>
                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                            <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                        </clipPath>
                                    </defs>
                                    <style>
                                        tspan {
                                            white-space: pre
                                        }

                                        .shp0 {
                                            fill: #6e42d3
                                        }
                                    </style>
                                    <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                        <g id="name">
                                            <g id="question">
                                                <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <p><?php echo __('Select products to exclude from abandoned cart recovery', 'cart-lift'); ?></p>
                        </div>
                    </div>
                    <p id="excluded-products-notice" class="cl-weekly-report-notice" style="margin-left:5px; display:none;"></p>
                </div>
                <?php
            }
            ?>
        </div>


        <div class="cl-form-group cl-select-area">
            <div class="cl-select-content">
                <span class="title"><?php echo __('Enable exclude categories from recovery:', 'cart-lift'); ?></span>
                <span class="cl-switcher">
                    <input class="cl-toggle-option" type="checkbox" id="enable_cl_exclude_categories" name="enable_cl_exclude_categories" data-status="<?php echo $enable_cl_exclude_categories_status; ?>" value="<?php echo $enable_cl_exclude_categories; ?>" <?php checked('1', $enable_cl_exclude_categories); ?>/>
                    <label for="enable_cl_exclude_categories"></label>
                </span>
                <div class="tooltip">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                            <defs>
                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                    <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                </clipPath>
                            </defs>
                            <style>
                                tspan {
                                    white-space: pre
                                }

                                .shp0 {
                                    fill: #6e42d3
                                }
                            </style>
                            <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                <g id="name">
                                    <g id="question">
                                        <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                        <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                        <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>
                    <p><?php echo __('Allows you to add categories to exclude carts from being tracked as abandoned if the products in those categories are found in the cart.', 'cart-lift'); ?></p>
                </div>
            </div>

            <?php
            if ($enable_cl_exclude_categories_status == 'yes' && $enable_cl_exclude_categories == '1') {
                ?>
                <div id="enable_excluded_categories_section">
                    <div class="cl-form-select-product">
                        <span class="title"><?php echo __('Choose Categories:', 'cart-lift'); ?></span>
                        <select class="cl-exclude-select2" id="cl_excluded_categories" name="cl_excluded_categories[]" multiple="multiple">
                            <?php
                            if( $selected_excluded_categories ) {
                                foreach ( $selected_excluded_categories as $category_id ) {
                                    $category_name = '';

                                    if( function_exists( 'cl_is_wc_active' ) && cl_is_wc_active() ) {
                                        $category = get_term( $category_id, 'product_cat' );
                                        if ( is_object( $category ) ) {
                                            $category_name = $category->name;
                                        }
                                    }

                                    if( function_exists( 'cl_is_edd_active' ) && cl_is_edd_active() ) {
                                        $category = get_term( $category_id, 'download_category' );
                                        if ( is_object( $category ) ) {
                                            $category_name = $category->name;
                                        }
                                    }

                                    if ( !empty( $category_name ) ) {
                                        echo '<option value="' . esc_attr( $category_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $category_name ) . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                        <div class="tooltip">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                    <defs>
                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                            <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                        </clipPath>
                                    </defs>
                                    <style>
                                        tspan {
                                            white-space: pre
                                        }

                                        .shp0 {
                                            fill: #6e42d3
                                        }
                                    </style>
                                    <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                        <g id="name">
                                            <g id="question">
                                                <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <p><?php echo __('Select categories to exclude from abandoned cart recovery', 'cart-lift'); ?></p>
                        </div>
                    </div>
                    <p id="excluded-categories-notice" class="cl-weekly-report-notice" style="margin-left:5px; display:none;"></p>
                </div>
                <?php
            } else {
                ?>
                <div id="enable_excluded_categories_section" style="display:none;">
                    <div class="cl-form-select-product">
                        <span class="title"><?php echo __('Choose Categories:', 'cart-lift'); ?></span>
                        <select class="cl-exclude-select2" id="cl_excluded_categories" name="cl_excluded_categories[]" multiple="multiple">
                            <?php
                            if( $selected_excluded_categories ) {
                                foreach ( $selected_excluded_categories as $category_id ) {
                                    $category_name = '';

                                    if( function_exists( 'cl_is_wc_active' ) && cl_is_wc_active() ) {
                                        $category = get_term( $category_id, 'product_cat' );
                                        if ( is_object( $category ) ) {
                                            $category_name = $category->name;
                                        }
                                    }

                                    if( function_exists( 'cl_is_edd_active' ) && cl_is_edd_active() ) {
                                        $category = get_term( $category_id, 'download_category' );
                                        if ( is_object( $category ) ) {
                                            $category_name = $category->name;
                                        }
                                    }

                                    if ( !empty( $category_name ) ) {
                                        echo '<option value="' . esc_attr( $category_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $category_name ) . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                        <div class="tooltip">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                    <defs>
                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                            <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                        </clipPath>
                                    </defs>
                                    <style>
                                        tspan {
                                            white-space: pre
                                        }

                                        .shp0 {
                                            fill: #6e42d3
                                        }
                                    </style>
                                    <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                        <g id="name">
                                            <g id="question">
                                                <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <p><?php echo __('Select categories to exclude from abandoned cart recovery', 'cart-lift'); ?></p>
                        </div>
                    </div>
                    <p id="excluded-categories-notice" class="cl-weekly-report-notice" style="margin-left:5px; display:none;"></p>
                </div>
                <?php
            }
            ?>
        </div>


        <div class="cl-form-group <?php if ($cl_pro_tag) echo 'cl-pro' ?> cl-select-area">
            <div class="cl-select-content">
                <span class="title"><?php echo __('Enable exclude countries from recovery:', 'cart-lift'); ?></span>
                <span class="cl-switcher">
                    <?php
                    $pro_url = add_query_arg('cl-dashboard', '1', 'https://rextheme.com/cart-lift');
                    ?>
                    <?php
                    if ($cl_pro_tag) {
                        $enable_cl_exclude_countries = 0;
                        ?>
                        <a href="<?php echo $pro_url; ?>" target="_blank" title="<?php _e('Click to Upgrade Pro', 'cart-lift'); ?>" class="pro-tag"><?php echo __('pro', 'cart-lift'); ?></a>
                    <?php } ?>
                    <input class="cl-toggle-option" type="checkbox" id="enable_cl_exclude_countries" name="enable_cl_exclude_countries" data-status="<?php echo $enable_cl_exclude_countries_status; ?>" value="<?php echo $enable_cl_exclude_countries; ?>" <?php checked('1', $enable_cl_exclude_countries); ?> <?php if ($cl_pro_tag) echo 'disabled' ?>/>
                    <label for="enable_cl_exclude_countries"></label>
                </span>
                <div class="tooltip">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                            <defs>
                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                    <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                </clipPath>
                            </defs>
                            <style>
                                tspan {
                                    white-space: pre
                                }

                                .shp0 {
                                    fill: #6e42d3
                                }
                            </style>
                            <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                <g id="name">
                                    <g id="question">
                                        <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                        <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                        <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>
                    <p><?php echo __('Allows you to add countries to exclude carts from being tracked as abandoned.', 'cart-lift'); ?></p>
                </div>
            </div>

            <?php
            if ($enable_cl_exclude_countries_status == 'yes' && $enable_cl_exclude_countries == '1') {
                ?>
                <div id="enable_excluded_countries_section">
                    <div class="cl-form-select-product">
                        <span class="title"><?php echo __('Choose Countries:', 'cart-lift'); ?></span>
                        <select class="cl-exclude-select2" id="cl_excluded_countries" name="cl_excluded_countries[]" multiple="multiple">
                            <?php foreach ( $get_wc_and_edd_countries as $key => $wc_and_edd_country_title ) { ?>
                                <option value="<?php echo esc_attr($key); ?>" <?php echo (isset($key) && in_array($key, $selected_excluded_countries)) ? 'selected' : ''; ?>>
                                    <?php echo esc_html($wc_and_edd_country_title); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <div class="tooltip">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                    <defs>
                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                            <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                        </clipPath>
                                    </defs>
                                    <style>
                                        tspan {
                                            white-space: pre
                                        }

                                        .shp0 {
                                            fill: #6e42d3
                                        }
                                    </style>
                                    <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                        <g id="name">
                                            <g id="question">
                                                <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <p><?php echo __('Select countries to exclude from abandoned cart recovery', 'cart-lift'); ?></p>
                        </div>
                    </div>
                    <p id="excluded-countries-notice" class="cl-weekly-report-notice" style="margin-left:5px; display:none;"></p>
                </div>
                <?php
            } else {
                ?>
                <div id="enable_excluded_countries_section" style="display:none;">
                    <div class="cl-form-select-product">
                        <span class="title"><?php echo __('Choose Countries:', 'cart-lift'); ?></span>
                        <select class="cl-exclude-select2" id="cl_excluded_countries" name="cl_excluded_countries[]" multiple="multiple">
                            <?php foreach ( $get_wc_and_edd_countries as $key => $wc_and_edd_country_title ) { ?>
                                <option value="<?php echo esc_attr($key); ?>" <?php echo (isset($key) && in_array($key, $selected_excluded_countries)) ? 'selected' : ''; ?>>
                                    <?php echo esc_html($wc_and_edd_country_title); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <div class="tooltip">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                    <defs>
                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                            <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                        </clipPath>
                                    </defs>
                                    <style>
                                        tspan {
                                            white-space: pre
                                        }

                                        .shp0 {
                                            fill: #6e42d3
                                        }
                                    </style>
                                    <g id="Final Create New Abandoned Cart Campaign " clip-path="url(#cp1)">
                                        <g id="name">
                                            <g id="question">
                                                <path id="Shape" fill-rule="evenodd" class="shp0" d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z" />
                                                <path id="Path" class="shp0" d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z" />
                                                <path id="Path" class="shp0" d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <p><?php echo __('Select countries to exclude from abandoned cart recovery', 'cart-lift'); ?></p>
                        </div>
                    </div>
                    <p id="excluded-countries-notice" class="cl-weekly-report-notice" style="margin-left:5px; display:none;"></p>
                </div>
                <?php
            }
            ?>

        </div>

        <div class="cl-form-group tracking">
            <span class="title"><?php echo __('Disable Tracking For:', 'cart-lift'); ?></span>
            <div class="cl-tracking-checkbox">
                <ul>
                    <?php

                    foreach ($all_roles as $role_key => $role_value) {
                        if (isset($general_data[$role_key])) {
                            if ($general_data[$role_key] == 1) {
                    ?>
                                <li class="cl-checkbox">
                                    <input type="checkbox" class="cl-toggle-option" id="<?php echo $role_key; ?>" name="<?php echo $role_key; ?>" data-status="" value="1" checked />
                                    <label for="<?php echo $role_key; ?>"><?php echo $role_value['name']; ?></label>
                                </li>
                            <?php
                            } else {
                            ?>
                                <li class="cl-checkbox">
                                    <input type="checkbox" class="cl-toggle-option" id="<?php echo $role_key; ?>" name="<?php echo $role_key; ?>" data-status="" value="" />
                                    <label for="<?php echo $role_key; ?>"><?php echo $role_value['name']; ?></label>
                                </li>
                            <?php
                            }
                        } else {
                            ?>
                            <li class="cl-checkbox">
                                <input type="checkbox" class="cl-toggle-option" id="<?php echo $role_key; ?>" name="<?php echo $role_key; ?>" data-status="" value="" />
                                <label for="<?php echo $role_key; ?>"><?php echo $role_value['name']; ?></label>
                            </li>
                    <?php
                        }
                    }
                    ?>
                </ul>

                <span class="hints"><?php echo __('<b>Hints:</b> It will ignore selected user roles from abandonment process when they logged in, so they will not receive mail for cart abandonment.', 'cart-lift'); ?></span>
            </div>
        </div>

    </div>

    <div class="btn-area">
        <button class="cl-btn" id="cl-general-info-save"><?php echo __('Save Settings', 'cart-lift'); ?></button>
        <p id="general_settings_notice" class="cl-notice" style="display:none"></p>
    </div>
</form>
