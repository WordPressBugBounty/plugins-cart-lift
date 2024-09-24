<?php
$pro_url = add_query_arg( 'cl-dashboard', '1', 'https://rextheme.com/cart-lift' );
?>

<div id="cl-email-templates" class="cl-email-templates">
    <form method="post" action="<?php echo $form_action; ?>" id="cl-email-template-edit-form">
        <input type="hidden" name="id" value="<?php echo $template_id ;?>" />
        <input type="hidden" name="cl_form_action" value="<?php echo $action; ?>" />
        <?php wp_nonce_field( 'email_template_nonce', '_wpnonce' ); ?>
        <div id="poststuff" class="cl-add-campaign-form">
            <div class="form-header">
                <h3><?php esc_html_e($form_header, 'cart-lift');?></h3>
            </div>

            <div class="campaign-form">
                <?php
                $campaign_tab_url = add_query_arg( array(
                    'page' => 'cart_lift',
                    'action' => 'email_templates'
                ), admin_url( '/admin.php' ) );
                ?>
                <a href="<?php echo $campaign_tab_url; ?>" class="backto-campaign cl-btn"><?php echo __('Back to Campaign', 'cart-lift'); ?></a>

                <div class="campaign-notice">
                    <p><?php echo __("A campaign is simply an automated email. You give us the basic details for the email, tell us how long to wait before we send it, and we'll do the rest. You can change all these edit later if you need to.", 'cart-lift'); ?></p>
                </div>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php echo __('Template status', 'cart-lift'); ?></label>
                            </th>
                            <td>
                                <?php

                                    $checked = '';
                                    $status = 'off';

                                    if( $active == 1 ){
                                        $checked = 'checked';
                                        $status = 'on';
                                    }
                                ?>
                                <span class="cl-switcher">
                                    <?php print'<input class="cl-toggle-email-template-status" type="checkbox" id="cl-template-status" name="cl_email_template_status" data-status="'.$status.'" data-template-id="'.$template_id.'" value="'.$active.'" '.$checked.' /> ';?>
                                   <?php print'<label for="cl-template-status"></label> ';?>
                                </span>
                            </td>
                        </tr>

                        <tr valign="center" class="template-name">
                            <th scope="row" class="titledesc">
                                <label for="cl-template-name"><?php echo __('Template name', 'cart-lift'); ?></label>
                            </th>
                            <td>
                                <?php print'<input type="text" name="cl_template_name" id="cl-template-name" value="'.$template_name.'" class="cl-widefat" >';?>

                                <div class="tooltip">
                                    <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                            <defs>
                                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                    <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                                </clipPath>
                                            </defs>
                                            <style>
                                                tspan { white-space:pre }
                                                .shp0 { fill: #6e42d3 }
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
                                    <p><?php echo __('Name of the campaign.', 'cart-lift'); ?></p>
                                </div>
                            </td>
                        </tr>

                        <tr valign="center"  class="template-subject">
                            <th scope="row" class="titledesc">
                                <label for="cl-email-subject"><?php echo __('Email subject', 'cart-lift'); ?></label>
                            </th>
                            <td>
                                <?php print'<input type="text" name="cl_email_subject" id="cl-email-subject" value="'.$email_subject.'" class="cl-widefat" >';?>

                                <div class="tooltip">
                                    <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                            <defs>
                                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                    <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                                </clipPath>
                                            </defs>
                                            <style>
                                                tspan { white-space:pre }
                                                .shp0 { fill: #6e42d3 }
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
                                    <p><?php echo __('Use an engaging subject line that is personalized.', 'cart-lift'); ?></p>
                                </div>
                            </td>
                        </tr>



                        <tr valign="top" class="tinymce-editor">
                            <th scope="row" class="titledesc">
                                <label for="cl-email-body"><?php echo __('Email body', 'cart-lift'); ?></label>
                            </th>
                            <td>
                                <?php
                                    wp_editor(
                                        $email_body,
                                        'cl_email_body',
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
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Enable coupon?', 'cart-lift' ); ?></label>
                            </th>
                            <td class="cl-checkbox">
                                <?php
                                    $checked = '';
                                    if( $coupon == 1 ){
                                        $checked = 'checked';
                                    }
                                ?>
                                <input class="cl-toggle-campaign-coupon" id="cl-campaign-coupon" name="cl_campaign_coupon" type="checkbox" <?php echo $checked; ?> value="<?php echo $coupon; ?>" />
                                <label for="cl-campaign-coupon"><?php echo __('Allows you to send new coupon only for this template.', 'cart-lift'); ?></label>
                            </td>
                        </tr>

                        <tr class="coupon-fields">
                            <th>
                                <label for="cl-campaign-coupon-type"><?php esc_html_e( 'Coupon Type', 'cart-lift' ); ?></label>
                            </th>
                            <td>
                               <?php
                                   $coupon_options = array(
                                       'percent'    => 'Percentage',
                                       'flat_rate' => 'Flat rate',
                                   );

                                   echo '<select id="cl-campaign-coupon-type" name="cl_campaign_coupon_type">';
                                   foreach ( $coupon_options as $key => $value ) {
                                       $is_selected = $key === $coupon_type ? 'selected' : '';
                                       echo '<option ' . esc_attr( $is_selected ) . ' value=' . esc_attr( $key ) . '>' . esc_attr( $value ) . '</option>';
                                   }
                                   echo '</select>';
                               ?>
                            </td>
                        </tr>

                        <tr class="coupon-fields">
                            <th>
                                <label for="cl-campaign-coupon-amount"><?php esc_html_e( 'Enable conditional discount', 'cart-lift' ); ?></label>
                            </th>
                            <td>
                                <?php
                                    $checked = '';
                                    $status = 'off';
                                    $disabeld = 'disabled';
                                    $discount_pro = 'discount-pro';


                                    if( !$is_pro ) {
                                        $enable_coupon_condition = 0;
                                    }

                                    if( $enable_coupon_condition == 1 ){
                                        $checked = 'checked';
                                        $status = 'on';
                                    }
                                    if( $is_pro ) {
                                        $disabeld = '';
                                        $discount_pro = '';
                                    }

                                ?>
                                <span class="cl-switcher <?php echo $discount_pro; ?>">
                                    <?php
                                        if( !$is_pro ) {
                                            echo '<a href="'.$pro_url.'" target="_blank" title="Click to Upgrade Pro" class="pro-tag">pro</a>';
                                        }
                                    ?>

                                    <?php print'<input class="cl-enable-conditional-discount" type="checkbox" id="cl-conditional-discount" name="cl_campaign_conditional_discount" data-status="'.$status.'" data-template-id="'.$template_id.'" value="'.$enable_coupon_condition.'" '.$checked. ' '. $disabeld. '  /> ';?>
                                    <?php print'<label for="cl-conditional-discount"></label> ';?>
                                </span>
                            </td>
                        </tr>

                        <tr class="coupon-fields" id="cl-coupon-amount">
                            <th>
                                <label for="cl-campaign-coupon-amount"><?php esc_html_e( 'Coupon amount', 'cart-lift' ); ?></label>
                            </th>
                            <td>
                                <input id="cl-campaign-coupon-amount" name="cl_campaign_coupon_amount" type="number" value="<?php echo $coupon_amount; ?>" />
                            </td>
                        </tr>

                        <?php do_action('cl_after_discount_field', $coupon_included_products,  $coupon_included_products_amount, $coupon_included_categories, $coupon_included_categories_amount); ?>

                        <tr valign="top" class="coupon-fields">
                            <th scope="row" class="titledesc">
                                <label for="cl-campaign-coupon-frequency"><?php echo __('Coupon expiration frequency', 'cart-lift'); ?></label>
                            </th>
                            <td class="coupon-expire">
                                <?php print '<input type="number" name="cl_campaign_coupon_frequency" id="cl-campaign-coupon-frequency" value="' . esc_attr( $coupon_frequency ) . '">';
                                ?>
                                <select name="cl_campaign_coupon_unit" id="cl-campaign-coupon-unit">
                                    <?php
                                    $default_unit = array(
                                        'hour' => __('Hour (s)', 'cart-lift'),
                                        'day' => __('Day (s)', 'cart-lift'),
                                    );
                                    foreach ( $default_unit as $key => $value ) {
                                        printf( "<option %s value='%s'>%s</option>\n",
                                            selected( $key, $coupon_frequency_unit, false ),
                                            esc_attr( $key ),
                                            esc_attr( $value )
                                        );
                                    }
                                    ?>
                                </select>
                                <h4 id="coupon_expiration_frequency">Can not be less than 2 hours</h4>
                            </td>
                        </tr>

                        <tr valign="top" class="coupon-fields">
                            <?php
                                $checked = '';
                                $status = 'off';
                                $disabeld = 'disabled';
                                $discount_pro = 'discount-pro';
                                if( !$is_pro ) {
                                    $free_shipping = 0;
                                }

                                if( $free_shipping == 1 ){
                                    $checked = 'checked';
                                    $status = 'on';
                                }
                                if( $is_pro ) {
                                    $disabeld = '';
                                    $discount_pro = '';
                                }
                            ?>
                            <th>
                                <label><?php esc_html_e( 'Free Shipping', 'cart-lift' ); ?></label>
                            </th>
                            <td>
                                <div class="coupon-field-switcher <?php echo !$is_pro ? 'switcher-pro' : ''; ?>">
                                    <span class="cl-switcher <?php echo $discount_pro; ?>">
                                        <?php
                                            if( !$is_pro ) {
                                                echo '<a href="'.$pro_url.'" target="_blank" title="'.__('Click to Upgrade Pro', 'cart-lift').'" class="pro-tag">pro</a>';
                                            }
                                        ?>
                                        <?php print'<input class="cl-free-shipping" type="checkbox" id="cl-free-shipping" name="cl_campaign_free_shipping" data-status="'.$status.'" data-template-id="'.$template_id.'" value="'.$free_shipping.'" '.$checked. ' '. $disabeld. '  /> ';?>
                                        <?php print'<label for="cl-free-shipping"></label>';?>
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
                                                    tspan { white-space:pre }
                                                    .shp0 { fill: #6e42d3 }
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
                                        <p><?php echo __('Allows you to grant free shipping. A free shipping method must be enabled in your shipping zone and be set to require "a valid free shipping coupon"', 'cart-lift'); ?></p>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr valign="top" class="coupon-fields">
                            <?php
                            $checked = '';
                            $status = 'off';
                            $disabeld = 'disabled';
                            $discount_pro = 'discount-pro';
                            if( !$is_pro ) {
                                $individual_use = 0;
                            }

                            if( $individual_use == 1 ){
                                $checked = 'checked';
                                $status = 'on';
                            }
                            if( $is_pro ) {
                                $disabeld = '';
                                $discount_pro = '';
                            }
                            ?>
                            <th>
                                <label><?php esc_html_e( 'Individual use only', 'cart-lift' ); ?></label>
                            </th>
                            <td>
                                <div class="coupon-field-switcher <?php echo !$is_pro ? 'switcher-pro' : ''; ?>">
                                    <span class="cl-switcher <?php echo $discount_pro; ?>">
                                        <?php
                                            if( !$is_pro ) {
                                                echo '<a href="'.$pro_url.'" target="_blank" title="'.__('Click to Upgrade Pro', 'cart-lift').'" class="pro-tag">'.__('pro', 'cart-lift').'</a>';
                                            }
                                        ?>
                                        <?php print'<input class="cl-individual-use" type="checkbox" id="cl-individual-use" name="cl_campaign_individual_use" data-status="'.$status.'" data-template-id="'.$template_id.'" value="'.$individual_use.'" '.$checked. ' '. $disabeld. '  /> ';?>
                                        <label for="cl-individual-use"></label>
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
                                                    tspan { white-space:pre }
                                                    .shp0 { fill: #6e42d3 }
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
                                        <p><?php echo __('Check this box if the coupon cannot be used in conjunction with other coupons.', 'cart-lift'); ?></p>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr valign="top" class="coupon-fields">
                            <?php
                            $checked = '';
                            $status = 'off';
                            $disabeld = 'disabled';
                            $discount_pro = 'discount-pro';
                            if( !$is_pro ) {
                                $coupon_auto_apply = 0;
                            }

                            if( $coupon_auto_apply == 1 ){
                                $checked = 'checked';
                                $status = 'on';
                            }
                            if( $is_pro ) {
                                $disabeld = '';
                                $discount_pro = '';
                            }
                            ?>
                            <th>
                                <label><?php esc_html_e( 'Auto apply coupon', 'cart-lift' ); ?></label>
                            </th>
                            <td>
                                <div class="coupon-field-switcher <?php echo !$is_pro ? 'switcher-pro' : ''; ?>">
                                    <span class="cl-switcher <?php echo $discount_pro; ?>">
                                        <?php
                                            if( !$is_pro ) {
                                                echo '<a href="'.$pro_url.'" target="_blank" title="'.__('Click to Upgrade Pro', 'cart-lift').'" class="pro-tag">'.__('pro', 'cart-lift').'</a>';
                                            }
                                        ?>
                                        <?php print'<input class="cl-auto-apply" type="checkbox" id="cl-auto-apply" name="cl_campaign_auto_apply" data-status="'.$status.'" data-template-id="'.$template_id.'" value="'.$coupon_auto_apply.'" '.$checked. ' '. $disabeld. '  /> ';?>
                                        <label for="cl-auto-apply"></label>
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
                                                    tspan { white-space:pre }
                                                    .shp0 { fill: #6e42d3 }
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
                                        <p><?php echo __('Coupon code will be automatically applied to checkout.', 'cart-lift'); ?></p>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr valign="top" class="">
                            <th>
                                <label><?php esc_html_e( 'Enable Twilio SMS?', 'cart-lift' ); ?></label>
                            </th>
                            <td class="cl-checkbox">
		                        <?php
		                        $twilio_settings  = get_option( 'cl_twilio_settings' );
		                        $settings_enabler = isset( $twilio_settings[ 'enabler' ] ) ? $twilio_settings[ 'enabler' ] : '';
		                        $checked          = '';
		                        $disabled         = '';
		                        $hidden           = 'hidden';

		                        if ( $settings_enabler == 1 && $twilio_enabled == 1 && $is_pro ) {
			                        $checked = 'checked';
			                        $hidden  = '';
		                        }

		                        if ( !$is_pro || $settings_enabler != 1 ) {
			                        $disabled = 'disabled';
		                        }
		                        if ( $is_pro ): ?>
                                    <input class="cl-toggle-campaign-coupon" id="cl-campaign-enable-twilio" name="cl_campaign_enable_twilio" type="checkbox" <?php echo $checked; ?> value="<?php echo $twilio_enabled; ?>" <?php echo $disabled?> />
			                        <?php if ( $disabled === '' && $settings_enabler == 1 ): ?>
                                    <label for="cl-campaign-enable-twilio"><?php echo __('Allows you to send mobile SMS through Twilio.', 'cart-lift'); ?></label>
		                            <?php elseif ( $settings_enabler != 1 ): ?>
                                    <label for="cl-campaign-enable-twilio"><?php echo __('Please enable SMS notification from settings menu.', 'cart-lift'); ?></label>
		                            <?php endif;?>

                                <?php else: ?>
                                    <!--	                                    --><?php //print'<input class="cl-auto-apply" type="checkbox" id="cl_disabled_twilio_campaign" name="cl_disabled_twilio_campaign" data-status="'.$status.'" data-template-id="'.$template_id.'" value="'.$coupon_auto_apply.'" '.$checked. ' '. $disabeld. '  /> ';?>
<!--                                    <label for="cl_disabled_twilio_campaign"></label>-->
                                    <div class="twilio-pro-disabled-container">
                                        <p class="twilio-disabled"></p>
	                                    <?php echo '<a href="'.$pro_url.'" target="_blank" title="'.__('Click to Upgrade Pro', 'cart-lift').'" class="pro-tag">'.__('pro', 'cart-lift').'</a>'; ?>
                                        <div class="tooltip">
                                            <span class="icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                                                    <defs>
                                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                            <path d="M-941 -385L379 -385L379 866L-941 866Z" />
                                                        </clipPath>
                                                    </defs>
                                                    <style>
                                                        tspan { white-space:pre }
                                                        .shp0 { fill: #6e42d3 }
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
                                            <p><?php echo __('Allows you to send mobile SMS through Twilio.', 'cart-lift'); ?></p>
                                        </div>
                                    </div>
		                        <?php endif;?>
                            </td>
                        </tr>

                        <tr valign="top" class="tinymce-editor" id="cl-twilio-campaign-body" <?php echo $hidden; ?> >
                            <th scope="row" class="titledesc">
                                <label for="cl_twilio_sms_body"><?php echo __('Twilio SMS body', 'cart-lift'); ?></label>
                            </th>
                            <td>
		                        <?php
		                        wp_editor(
			                        $twilio_sms_body,
			                        'cl_twilio_sms_body',
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
                            </td>
                        </tr>

                        <tr valign="top">
                            <th>
                                <label for="cl-campaign-email-header"><?php esc_html_e( 'Email header text', 'cart-lift' ); ?></label>
                            </th>
                            <td>
                                <input id="cl-campaign-email-header" name="cl_campaign_email_header_text" class="cl-widefat" type="text" value="<?php echo $email_header_text; ?>" />
                            </td>
                        </tr>

                        <tr valign="top">
                            <th>
                                <label for="cl-campaign-email-header-color"><?php esc_html_e( 'Email header color', 'cart-lift' ); ?></label>
                            </th>
                            <td>
                                <input id="cl-campaign-email-header-color" name="cl_campaign_email_header_color" class="cl-widefat" type="text" value="<?php echo $email_header_color; ?>" />
                            </td>
                        </tr>

                        <tr valign="top">
                            <th>
                                <label for="cl-campaign-checkout-text"><?php esc_html_e( 'Email checkout button text', 'cart-lift' ); ?></label>
                            </th>
                            <td>
                                <input id="cl-campaign-checkout-text" name="cl_campaign_checkout_text" class="cl-widefat" type="text" value="<?php echo $email_checkout_text; ?>" />
                            </td>
                        </tr>

                        <tr valign="top">
                            <th>
                                <label for="cl-campaign-checkout-color"><?php esc_html_e( 'Email checkout button color', 'cart-lift' ); ?></label>
                            </th>
                            <td>
                                <input id="cl-campaign-checkout-color" name="cl_campaign_checkout_color" class="cl-widefat" type="text" value="<?php echo $email_checkout_color; ?>" />
                            </td>
                        </tr>


                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="cl-email-frequency"><?php echo __('Send this email', 'cart-lift'); ?></label>
                            </th>
                            <td class="send-email-time">
                                <?php print '<input type="number" name="cl_email_frequency" min="1" id="cl-email-frequency" value="' . esc_attr( $frequency ) . '">';
                                ?>
                                <select name="cl_email_unit" id="cl-email-unit">
                                    <?php
                                        $default_unit = array(
                                            'minute' => __('Minute(s)', 'cart-lift'),
                                            'hour' => __('Hour(s)', 'cart-lift'),
                                            'day' => __('Day(s)', 'cart-lift'),
                                        );
                                        foreach ( $default_unit as $key => $value ) {
                                            printf( "<option %s value='%s'>%s</option>\n",
                                                selected( $key, $unit, false ),
                                                esc_attr( $key ),
                                                esc_attr( $value )
                                            );
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
                                                tspan { white-space:pre }
                                                .shp0 { fill: #6e42d3 }
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
                                    <p><?php echo __('Minimum time limit 15 minutes.', 'cart-lift'); ?></p>
                                </div>
                                <span class="hints"><?php echo __('After cart is abandoned.', 'cart-lift'); ?></span>
                            </td>
                        </tr>

                        <tr valign="top">
                            <?php $current_user = wp_get_current_user(); ?>
                            <th scope="row">
                                <label for="cl-test-email"><?php echo __('Send Test Email To', 'cart-lift'); ?></label>
                            </th>
                            <td class="send-email">
                                <input type="email" name="cl_test_email" id="cl-test-email" value="<?php echo esc_attr( $current_user->user_email ); ?>" class="cl-widefat" >
                                <input class="button cl-send-test-email" type="button" value="<?php echo __('Send a test email', 'cart-lift'); ?>">
                                <div id="test_mail_response_msg" class="cl-notice"></div>
                            </td>
                        </tr>

                        <?php do_action( 'cl_after_test_email_field' );?>

                    </tbody>
                </table>
            </div>
        </div>
        <p class="submit">
            <input type="submit" name="Submit" class="button-primary cl-btn campaign-save-btn" value="<?php echo __($button_text, 'cart-lift'); ?>"/>
        </p>
    </form>
</div>
