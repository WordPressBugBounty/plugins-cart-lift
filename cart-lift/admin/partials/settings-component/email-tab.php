<?php
    //---smtp switcher data------
    $smtp_switch = get_option( 'enable_cl_smtp' );
    $smtp_switch_value = get_option( 'enable_cl_smtp' );
    if ($smtp_switch == '1') {
      $smtp_switch = 'block';
      $smtp_switch_status = 'yes';
    }
    else {
      $smtp_switch = 'none';
      $smtp_switch_status = 'no';
    }
?>

<div id="smtp-tabs" class="smtp-tabs">
    <h4 class="settings-tab-heading"><?php echo __( 'Email', 'cart-lift' ); ?></h4>
    <div class="inner-wrapper">
        <div class="cl-form-group">
            <span class="title"><?php echo __( 'Enable Cart Lift SMTP for this site:', 'cart-lift' ); ?></span>
            <span class="cl-switcher">
                <input class="cl-toggle-option-smtp" type="checkbox" id="enable_cl_smtp" name="enable_smtp" data-status="<?php echo $smtp_switch_status; ?>" value="<?php echo $smtp_switch_value; ?>" <?php checked( '1', $smtp_switch_value ); ?> />
                <label for="enable_cl_smtp"></label>
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
                <p><?php echo __( 'Allows you to connect SMTP using Cart Lift.', 'cart-lift' ); ?></p>
            </div>
        </div>
    </div>

    <ul id="smtp-switch" style="display:<?php echo $smtp_switch; ?>;">
        <!-- <li>
            <a href="#sendgrid">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 22" width="20" height="22">
                    <defs>
                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                            <path d="M-60 -241L1260 -241L1260 486L-60 486Z" />
                        </clipPath>
                    </defs>
                    <style>
                        .sendgrid-icon { fill: none;stroke: #a8a7be;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.5 }
                    </style>
                    <g id="SMTP" clip-path="url(#cp1)">
                        <g id="1copy">
                            <g id="Group6">
                                <g id="1">
                                    <g id="Group19">
                                        <g id="Group17">
                                            <path id="Stroke1" class="sendgrid-icon tab-icon" d="M3.87 11.37L3.87 3" />
                                            <path id="Stroke3" class="sendgrid-icon tab-icon" d="M3.87 19L3.87 14.23" />
                                            <path id="Stroke5" class="sendgrid-icon tab-icon" d="M16.02 12.6L16.02 3" />
                                            <path id="Stroke7" class="sendgrid-icon tab-icon" d="M16.02 19L16.02 15.45" />
                                            <path id="Stroke9" class="sendgrid-icon tab-icon" d="M9.94 4.6L9.94 3" />
                                            <path id="Stroke11" class="sendgrid-icon tab-icon" d="M9.94 19L9.94 7.58" />
                                            <path id="Stroke13" class="sendgrid-icon tab-icon" d="M2.21 11.37L5.29 11.37" />
                                            <path id="Stroke15" class="sendgrid-icon tab-icon" d="M14.48 12.61L17.56 12.61" />
                                        </g>
                                        <path id="Stroke18" class="sendgrid-icon tab-icon" d="M8.41 7.18L11.48 7.18" />
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
                <?php echo __( 'Sendgrid', 'cart-lift' ); ?>
            </a>
        </li>
        <li>
            <a href="#mailchimp">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 21" width="21" height="21">
                    <defs>
                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                            <path d="M-231 -242L1089 -242L1089 485L-231 485Z" />
                        </clipPath>
                    </defs>
                    <style>
                        .mailchimp-icon { fill: none;stroke: #a8a7be;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.5 }
                    </style>
                    <g id="SMTP" clip-path="url(#cp1)">
                        <g id="1 copy">
                            <g id="Group 6">
                                <g id="1 copy 4">
                                    <g id="Group 9">
                                        <path id="Stroke 1" class="mailchimp-icon tab-icon" d="M17.45 8.04L10 13.12L2.58 8.04" />
                                        <path id="Stroke 3" class="mailchimp-icon tab-icon" d="M2.58 17.52L6.39 14.91" />
                                        <path id="Stroke 5" class="mailchimp-icon tab-icon" d="M17.45 17.52L13.52 14.84" />
                                        <path id="Stroke 7" class="mailchimp-icon tab-icon" d="M17.62 7.3L10 2L2.38 7.3C2.14 7.46 2 7.73 2 8.02L2 16.22C2 17.2 2.8 18 3.78 18L16.22 18C17.2 18 18 17.2 18 16.22L18 8.02C18 7.73 17.86 7.46 17.62 7.3Z" />
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
                <?php echo __( 'Mailchimp', 'cart-lift' ); ?>
            </a>
        </li>
        <li>
            <a href="#ses">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 21" width="21" height="21">
                    <defs>
                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                            <path d="M-420 -241L900 -241L900 486L-420 486Z" />
                        </clipPath>
                    </defs>
                    <style>
                        .ses-icon { fill: none;stroke: #a8a7be;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.5 }
                    </style>
                    <g id="SMTP" clip-path="url(#cp1)">
                        <g id="1 copy">
                            <g id="Group 6">
                                <g id="1 copy 5">
                                    <g id="Group 10">
                                        <path id="Stroke 1" class="ses-icon tab-icon" d="M3 8.64L8.64 8.64L8.64 3L3 3L3 8.64Z" />
                                        <path id="Stroke 3" class="ses-icon tab-icon" d="M3 19L8.64 19L8.64 13.35L3 13.35L3 19Z" />
                                        <path id="Stroke 4" class="ses-icon tab-icon" d="M3 8.64L8.64 8.64L8.64 3L3 3L3 8.64Z" />
                                        <path id="Stroke 5" class="ses-icon tab-icon" d="M3 19L8.64 19L8.64 13.35L3 13.35L3 19Z" />
                                        <path id="Stroke 6" class="ses-icon tab-icon" d="M13.35 8.64L19 8.64L19 3L13.35 3L13.35 8.64Z" />
                                        <path id="Stroke 7" class="ses-icon tab-icon" d="M13.35 19L19 19L19 13.35L13.35 13.35L13.35 19Z" />
                                        <path id="Stroke 8" class="ses-icon tab-icon" d="M13.35 8.64L19 8.64L19 3L13.35 3L13.35 8.64Z" />
                                        <path id="Stroke 9" class="ses-icon tab-icon" d="M13.35 19L19 19L19 13.35L13.35 13.35L13.35 19Z" />
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
                <?php echo __( 'SES', 'cart-lift' ); ?>
            </a>
        </li> -->
        <li>
            <a href="#other-smtp">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 21" width="20" height="21">
                    <defs>
                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                            <path d="M-549 -240L771 -240L771 487L-549 487Z" />
                        </clipPath>
                    </defs>
                    <style>
                        .other-smtp-icon { fill: none;stroke: #a8a7be;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.5 }
                    </style>
                    <g id="SMTP" clip-path="url(#cp1)">
                        <g id="1 copy">
                            <g id="Group 6">
                                <g id="1 copy 6">
                                    <g id="Group 6">
                                        <path id="Stroke 1" class="other-smtp-icon tab-icon" d="M2 8.64L18 8.64L18 3L2 3L2 8.64Z" />
                                        <path id="Stroke 3" class="other-smtp-icon tab-icon" d="M2 19L18 19L18 13.35L2 13.35L2 19Z" />
                                        <path id="Stroke 4" class="other-smtp-icon tab-icon" d="M2 8.64L18 8.64L18 3L2 3L2 8.64Z" />
                                        <path id="Stroke 5" class="other-smtp-icon tab-icon" d="M2 19L18 19L18 13.35L2 13.35L2 19Z" />
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
                <?php echo __( 'Other SMTP', 'cart-lift' ); ?>
            </a>
        </li>
    </ul>

    <!-- <div id="sendgrid" class="smtp-tab-content sendgrid">
        <?php require_once CART_LIFT_DIR . '/admin/partials/settings-component/sendgrid-tab.php'; ?>
    </div>

    <div id="mailchimp" class="smtp-tab-content mailchimp">
        <?php require_once CART_LIFT_DIR . '/admin/partials/settings-component/mailchimp-tab.php'; ?>
    </div>

    <div id="ses" class="smtp-tab-content ses">
        <?php require_once CART_LIFT_DIR . '/admin/partials/settings-component/ses-tab.php'; ?>
    </div> -->
    
    <div id="other-smtp" class="smtp-tab-content other-smtp">
        <?php require_once CART_LIFT_DIR . '/admin/partials/settings-component/smtp-tab.php'; ?>
    </div>
</div>

