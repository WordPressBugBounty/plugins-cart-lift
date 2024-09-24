<h6 class="settings-tab-heading">
    <?php echo __( 'reCAPTCHA V3 Settings', 'cart-lift' ); ?>
</h6>

<?php
    $recaptcha_v3_setting = get_option('cl_recaptcha_settings');
    $recaptcha_v3_status = 'no';
    $enable_recaptcha_v3 = isset($recaptcha_v3_setting['recaptcha_enable_status']) ? $recaptcha_v3_setting['recaptcha_enable_status'] : '0';
    if ($enable_recaptcha_v3) {
        $recaptcha_v3_status  = 'yes';
    }
?>

<div class="cl-recovery-loader">
    <div class="ring"></div>
</div>

<div class="cl-recaptcha-area">
    <!-- ----------Recaptcha start--------->
    <div class="cl-form-group">
            <span class="title cl-recaptcha-v3"><?php echo __('Enable reCAPTCHA V3:', 'cart-lift'); ?></span>
            <span class="cl-switcher">
                <input class="cl-toggle-option" type="checkbox" id="enable_recaptcha_v3" name="enable_recaptcha_v3" data-status="<?php echo $recaptcha_v3_status; ?>" value="<?php echo $enable_recaptcha_v3; ?>" <?php checked('1', $enable_recaptcha_v3); ?>/>
                <label for="enable_recaptcha_v3"></label>
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
            <p><?php echo __('reCAPTCHA V3 runs silently in the background to detect suspicious activity and verify user interactions without interrupting the user experience.', 'cart-lift'); ?></p>
        </div>
    </div>

    <?php
    if ($recaptcha_v3_status == 'yes' && $enable_recaptcha_v3 == '1') {
        ?>

        <div id="cl_recaptcha_v3">

            <div class="cl-form-group">
                <span class="title"><?php echo __('Site Key:', 'cart-lift'); ?></span>
                <input type="text" id="recaptcha-v3-site-key" name="recaptcha_v3_site_key" class="cl-recaptcha-input" value="<?php echo $recaptcha_v3_setting['recaptcha_site_key'] ?? ''; ?>">
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
                    <p><?php echo __('Enter your site key', 'cart-lift'); ?></p>
                </div>
            </div>

            <span id="cl-recaptcha-error-message-site-key" class="cl-recaptcha-error-messge"><?php echo __('Please enter site key', 'cart-lift'); ?></span>

            <div class="cl-form-group">
                <span class="title"><?php echo __('Secret Key:', 'cart-lift'); ?></span>
                <input type="text" id="recaptcha-v3-secret-key" name="recaptcha_v3_secret_key" class="cl-recaptcha-input"  value="<?php echo $recaptcha_v3_setting['recaptcha_secret_key'] ?? '';  ?>">
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
                        <p><?php echo __('Enter your secret key.', 'cart-lift'); ?></p>
                </div>
            </div>

            <span id="cl-recaptcha-error-message-secret-key" class="cl-recaptcha-error-messge"><?php echo __('Please enter secret key', 'cart-lift'); ?></span>
                <div class="cl-form-group">
                    <span class="title"><?php echo __('Score:', 'cart-lift'); ?></span>
                    <input type="number" step=".1" id="recaptcha-v3-score" name="recaptcha_v3_score" class="cl-recaptcha-input"  value="<?php echo $recaptcha_v3_setting['recaptcha_score'] ?? '0.5';?>" max="1" min="0">
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
                        <p><?php echo __('Enter score that will check with the reCAPTCHA score.', 'cart-lift'); ?></p>
                    </div>
                </div>
            <span id="cl-recaptcha-error-message-score" class="cl-recaptcha-error-messge"><?php echo __('Please enter score', 'cart-lift') ?></span>
        </div>

        <?php
    } else {
        ?>
        <div id="cl_recaptcha_v3" style="display:none;">
            <div class="cl-form-group">
                <span class="title"><?php echo __('Site Key:', 'cart-lift'); ?></span>
                <input type="text" id="recaptcha-v3-site-key" name="recaptcha_v3_site_key" >
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
                    <p><?php echo __('Enter your site key', 'cart-lift'); ?></p>
                </div>
            </div>
            <span id="cl-recaptcha-error-message-site-key" class="cl-recaptcha-error-messge">
                <?php echo __('Please enter site key', 'cart-lift'); ?>
            </span>

            <div class="cl-form-group">
                <span class="title"><?php echo __('Secret Key:', 'cart-lift'); ?></span>
                <input type="text" id="recaptcha-v3-secret-key" name="recaptcha_v3_secret_key" >
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
                    <p><?php echo __('Enter your secret key.', 'cart-lift'); ?></p>
                </div>
            </div>

            <span id="cl-recaptcha-error-message-secret-key" class="cl-recaptcha-error-messge">
                <?php echo __('Please enter secret key', 'cart-lift'); ?>
            </span>

            <div class="cl-form-group">
                <span class="title"><?php echo __('Score:', 'cart-lift'); ?></span>
                <input type="number" step=".1" id="recaptcha-v3-score" name="recaptcha_v3_score" max="1" min="0">
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
                    <p><?php echo __('Enter score that will check with the reCAPTCHA score.', 'cart-lift'); ?></p>
                </div>
            </div>
            <span id="cl-recaptcha-error-message-score" class="cl-recaptcha-error-messge"><?php echo __('Please enter score', 'cart-lift'); ?></span>

            <!-- ----------Recaptcha end--------->
            <div class="btn-area">
                <button class="cl-btn" id="cl_recaptcha_v3_btn"><?php echo __('Save Settings', 'cart-lift'); ?></button>
                <p id="recaptcha_v3_settings_notice" class="cl-notice" style="display:none"></p>
            </div>

        </div>
        <?php
    }
    ?>
    
</div>




