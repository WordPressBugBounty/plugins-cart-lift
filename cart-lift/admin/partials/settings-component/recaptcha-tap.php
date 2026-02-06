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
            <div class="cl-global-tooltip-area">
                <span class="title cl-recaptcha-v3"><?php echo __('Enable reCAPTCHA V3:', 'cart-lift'); ?></span>
                <div class="tooltip">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <rect x="1" y="1.1925" width="14" height="14" rx="7" stroke="#535963"/>
                            <path d="M6 5.69543C6.0741 4.80132 6.85381 4.13315 7.74894 4.19668H8.24864C9.14377 4.13315 9.92347 4.80132 9.99758 5.69543C10.0354 6.36175 9.62793 6.97278 8.99818 7.19418C8.30536 7.60992 7.91577 8.38893 7.99879 9.1925" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.5 12.19V12.195" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <p><?php echo __('Protect forms automatically with reCAPTCHA V3.', 'cart-lift'); ?></p>
                </div>
            </div>

            <span class="cl-switcher">
                <input class="cl-toggle-option" type="checkbox" id="enable_recaptcha_v3" name="enable_recaptcha_v3" data-status="<?php echo $recaptcha_v3_status; ?>" value="<?php echo $enable_recaptcha_v3; ?>" <?php checked('1', $enable_recaptcha_v3); ?>/>
                <label for="enable_recaptcha_v3"></label>
            </span>
       
    </div>

    <?php
    if ($recaptcha_v3_status == 'yes' && $enable_recaptcha_v3 == '1') {
        ?>

        <div id="cl_recaptcha_v3">

            <div class="cl-form-group">
                <div class="cl-global-tooltip-area">
                    <span class="title"><?php echo __('Site Key:', 'cart-lift'); ?></span>

                    <div class="tooltip">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <rect x="1" y="1.1925" width="14" height="14" rx="7" stroke="#535963"/>
                            <path d="M6 5.69543C6.0741 4.80132 6.85381 4.13315 7.74894 4.19668H8.24864C9.14377 4.13315 9.92347 4.80132 9.99758 5.69543C10.0354 6.36175 9.62793 6.97278 8.99818 7.19418C8.30536 7.60992 7.91577 8.38893 7.99879 9.1925" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.5 12.19V12.195" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <p><?php echo __('Enter the reCAPTCHA site key for verification.', 'cart-lift'); ?></p>
                    </div>
                </div>

                <input type="text" id="recaptcha-v3-site-key" name="recaptcha_v3_site_key" class="cl-recaptcha-input" value="<?php echo $recaptcha_v3_setting['recaptcha_site_key'] ?? ''; ?>">
                
            </div>

            <span id="cl-recaptcha-error-message-site-key" class="cl-recaptcha-error-messge"><?php echo __('Please enter site key', 'cart-lift'); ?></span>

            <div class="cl-form-group">
                <div class="cl-global-tooltip-area">
                    <span class="title"><?php echo __('Secret Key:', 'cart-lift'); ?></span>

                    <div class="tooltip">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <rect x="1" y="1.1925" width="14" height="14" rx="7" stroke="#535963"/>
                                <path d="M6 5.69543C6.0741 4.80132 6.85381 4.13315 7.74894 4.19668H8.24864C9.14377 4.13315 9.92347 4.80132 9.99758 5.69543C10.0354 6.36175 9.62793 6.97278 8.99818 7.19418C8.30536 7.60992 7.91577 8.38893 7.99879 9.1925" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.5 12.19V12.195" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <p><?php echo __('Enter the reCAPTCHA secret key for validation.', 'cart-lift'); ?></p>
                    </div>
                    
                </div>

                <input type="text" id="recaptcha-v3-secret-key" name="recaptcha_v3_secret_key" class="cl-recaptcha-input"  value="<?php echo $recaptcha_v3_setting['recaptcha_secret_key'] ?? '';  ?>">
               
            </div>

            <span id="cl-recaptcha-error-message-secret-key" class="cl-recaptcha-error-messge"><?php echo __('Please enter secret key', 'cart-lift'); ?></span>
                <div class="cl-form-group">

                    <div class="cl-global-tooltip-area">
                        <span class="title"><?php echo __('Score:', 'cart-lift'); ?></span>

                        <div class="tooltip">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <rect x="1" y="1.1925" width="14" height="14" rx="7" stroke="#535963"/>
                                    <path d="M6 5.69543C6.0741 4.80132 6.85381 4.13315 7.74894 4.19668H8.24864C9.14377 4.13315 9.92347 4.80132 9.99758 5.69543C10.0354 6.36175 9.62793 6.97278 8.99818 7.19418C8.30536 7.60992 7.91577 8.38893 7.99879 9.1925" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.5 12.19V12.195" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <p><?php echo __('Define the reCAPTCHA score threshold for suspicious activity.', 'cart-lift'); ?></p>
                        </div>
                    </div>

                    <input type="number" step=".1" id="recaptcha-v3-score" name="recaptcha_v3_score" class="cl-recaptcha-input"  value="<?php echo $recaptcha_v3_setting['recaptcha_score'] ?? '0.5';?>" max="1" min="0">
                    
                </div>
            <span id="cl-recaptcha-error-message-score" class="cl-recaptcha-error-messge"><?php echo __('Please enter score', 'cart-lift') ?></span>
        </div>

        <?php
    } else {
        ?>
        <div id="cl_recaptcha_v3" style="display:none;">
            <div class="cl-form-group">
                <div class="cl-global-tooltip-area">
                    <span class="title"><?php echo __('Site Key:', 'cart-lift'); ?></span>

                    <div class="tooltip">
                        <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <rect x="1" y="1.1925" width="14" height="14" rx="7" stroke="#535963"/>
                            <path d="M6 5.69543C6.0741 4.80132 6.85381 4.13315 7.74894 4.19668H8.24864C9.14377 4.13315 9.92347 4.80132 9.99758 5.69543C10.0354 6.36175 9.62793 6.97278 8.99818 7.19418C8.30536 7.60992 7.91577 8.38893 7.99879 9.1925" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.5 12.19V12.195" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <p><?php echo __('Enter the reCAPTCHA site key for verification.', 'cart-lift'); ?></p>
                    </div>
                </div>

                <input type="text" id="recaptcha-v3-site-key" name="recaptcha_v3_site_key" >
              
            </div>
            <span id="cl-recaptcha-error-message-site-key" class="cl-recaptcha-error-messge">
                <?php echo __('Please enter site key', 'cart-lift'); ?>
            </span>

            <div class="cl-form-group">
                <div class="cl-global-tooltip-area">
                    <span class="title"><?php echo __('Secret Key:', 'cart-lift'); ?></span>

                    <div class="tooltip">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <rect x="1" y="1.1925" width="14" height="14" rx="7" stroke="#535963"/>
                            <path d="M6 5.69543C6.0741 4.80132 6.85381 4.13315 7.74894 4.19668H8.24864C9.14377 4.13315 9.92347 4.80132 9.99758 5.69543C10.0354 6.36175 9.62793 6.97278 8.99818 7.19418C8.30536 7.60992 7.91577 8.38893 7.99879 9.1925" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.5 12.19V12.195" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <p><?php echo __('Enter the reCAPTCHA secret key for validation.', 'cart-lift'); ?></p>
                    </div>

                </div>

                <input type="text" id="recaptcha-v3-secret-key" name="recaptcha_v3_secret_key" >
                
            </div>

            <span id="cl-recaptcha-error-message-secret-key" class="cl-recaptcha-error-messge">
                <?php echo __('Please enter secret key', 'cart-lift'); ?>
            </span>

            <div class="cl-form-group">
            <div class="cl-global-tooltip-area">

                <span class="title"><?php echo __('Score:', 'cart-lift'); ?></span>

                    <div class="tooltip">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <rect x="1" y="1.1925" width="14" height="14" rx="7" stroke="#535963"/>
                                <path d="M6 5.69543C6.0741 4.80132 6.85381 4.13315 7.74894 4.19668H8.24864C9.14377 4.13315 9.92347 4.80132 9.99758 5.69543C10.0354 6.36175 9.62793 6.97278 8.99818 7.19418C8.30536 7.60992 7.91577 8.38893 7.99879 9.1925" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.5 12.19V12.195" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                        </span>
                        <p><?php echo __('Define the reCAPTCHA score threshold for suspicious activity.', 'cart-lift'); ?></p>
                    </div>
                </div>

                <input type="number" step=".1" id="recaptcha-v3-score" name="recaptcha_v3_score" max="1" min="0">
               
            </div>
            <span id="cl-recaptcha-error-message-score" class="cl-recaptcha-error-messge"><?php echo __('Please enter score', 'cart-lift'); ?></span>

            <!-- ----------Recaptcha end--------->
        </div>
        <?php
    }
    ?>
    <div class="btn-area">
        <button class="cl-btn" id="cl_recaptcha_v3_btn" <?php echo ($enable_recaptcha_v3 == '1') ? '' : 'disabled style="pointer-events: none; opacity: 0.5;"'; ?>>
            <?php echo __('Save Settings', 'cart-lift'); ?>
        </button>
        <p id="recaptcha_v3_settings_notice" class="cl-notice" style="display:none"></p>
    </div>
</div>




