<?php
    //===SMTP Data===//
    $other_mailer_settings = $settings['smtp']['other'];

    $username = '';
    if (isset($other_mailer_settings['username'])) {
      $username = $other_mailer_settings['username'];
    }

    $password = '';
    if (isset($other_mailer_settings['password'])) {
      $password = $other_mailer_settings['password'];
    }

    $from = '';
    if (isset($other_mailer_settings['from'])) {
      $from = $other_mailer_settings['from'];
    }

    $fromname = '';
    if (isset($other_mailer_settings['fromname'])) {
      $fromname = $other_mailer_settings['fromname'];
      $fromname = str_replace('+', ' ', $fromname);
    }

    $host = '';
    if (isset($other_mailer_settings['host'])) {
      $host = $other_mailer_settings['host'];
    }

    $port = '';
    if (isset($other_mailer_settings['port'])) {
      $port = $other_mailer_settings['port'];
    }

    $smtpsecure = 'none';
    if (isset($other_mailer_settings['smtpsecure'])) {
      $smtpsecure = $other_mailer_settings['smtpsecure'];
    }

    $smtpauth = 'no';
    if (isset($other_mailer_settings['smtpauth'])) {
      $smtpauth = $other_mailer_settings['smtpauth'];
    }

?>

<form action="" id="smtp-save-form" style="display:<?php echo $smtp_switch; ?>;">
    <div class="inner-wrapper">
        <div class="single-info form">

            <h4 class="single-info-heading"><?php echo __( 'from', 'cart-lift' ); ?></h4>
            <div class="form-wrapper">
                <input type="text" name="username" placeholder="User Name" value="<?php echo $username; ?>" required />
                <input type="password" name="password" placeholder="Password" value="<?php echo $password; ?>" required />

                <input type="text" name="from" value="<?php echo $from; ?>" placeholder="From" required />
                <input type="text" name="fromname" value="<?php echo $fromname; ?>" placeholder="From Name" required />
            </div>
        </div>

        <div class="single-info smtp-form">
            <h4 class="single-info-heading"><?php echo __( 'SMTP', 'cart-lift' ); ?></h4>
            <div class="form-wrapper">
                <input type="text" name="host" value="<?php echo $host; ?>" placeholder="SMTP Host" required />
                <input type="text" name="port" value="<?php echo $port; ?>" placeholder="SMTP Port" required />
            </div>
        </div>

        <div class="single-info security-form">
            <h4 class="single-info-heading"><?php echo __( 'security', 'cart-lift' ); ?></h4>
            <div class="form-wrapper">
                <div class="security">
                    <span class="title"><?php echo __( 'security:', 'cart-lift' ); ?> </span>
                    <span class="cl-radio-btn">
                        <input type="radio" name="smtpsecure" id="none" value="none" <?php checked( 'none', $smtpsecure ); ?> />
                        <label for="none"><?php echo __( 'None', 'cart-lift' ); ?></label>
                    </span>
                    <span class="cl-radio-btn">
                        <input type="radio" name="smtpsecure" id="ssl" value="ssl" <?php checked( 'ssl', $smtpsecure ); ?> />
                        <label for="ssl"><?php echo __( 'SSL', 'cart-lift' ); ?></label>
                    </span>
                    <span class="cl-radio-btn">
                        <input type="radio" name="smtpsecure" id="tls" value="tls" <?php checked( 'tls', $smtpsecure ); ?> />
                        <label for="tls"><?php echo __( 'TLS', 'cart-lift' ); ?></label>
                    </span>
                </div>

                <div>
                    <span class="title"><?php echo __( 'Authentication:', 'cart-lift' ); ?> </span>
                    <span class="cl-radio-btn">
                        <input type="radio" name="smtpauth" id="auth_no" value="no" <?php checked( 'no', $smtpauth ); ?> />
                        <label for="auth_no"><?php echo __( 'No', 'cart-lift' ); ?></label>
                    </span>
                    <span class="cl-radio-btn">
                        <input type="radio" name="smtpauth" id="auth_yes" value="yes" <?php checked( 'yes', $smtpauth ); ?> />
                        <label for="auth_yes"><?php echo __( 'Yes', 'cart-lift' ); ?></label>
                    </span>
                </div>
            </div>
        </div>

        <div class="btn-area">
            <button class="cl-btn" id="cl-smtp-save"><?php echo __( 'Save Settings', 'cart-lift' ); ?></button>
            <p id="smtp_notice" class="cl-notice" style="display:none"></p>
        </div>

    </div>

    <div class="test-email-form">
        <p id="smtp_test_notice" class="cl-notice"  style="display:none"></p>

        <div class="form-wrapper">
            <input type="email" name="cl_test_email" value="" placeholder="<?php _e( 'Enter Your Email', 'cart-lift' ); ?>" />
            <span class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 19" width="20" height="19">
                    <defs>
                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                            <path d="M-286 -550L1034 -550L1034 177L-286 177Z" />
                        </clipPath>
                    </defs>
                    <style>
                        .test-mail-icon { fill: none;stroke: #6e42d3;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.5 }
                    </style>
                    <g id="SMTP" clip-path="url(#cp1)">
                        <g id="1 copy">
                            <g id="Enter email">
                                <g id="Group 5">
                                    <path id="Stroke 1" class="test-mail-icon" d="M18 6.9L18 14.84C18 16.02 17.2 16.98 16.22 16.98L3.78 16.98C2.8 16.98 2 16.02 2 14.84L2 3.61C2 2.72 2.6 2 3.33 2L18 2L17.45 2.65" />
                                    <path id="Stroke 3" class="test-mail-icon" d="M17.45 2.69L10 9.98L2.58 2.69" />
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </span>
        </div>

        <button class="cl-btn" id="cl_send_test"><?php echo __( 'send a test email', 'cart-lift' ); ?></button>

    </div>

</form>