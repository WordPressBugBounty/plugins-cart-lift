    <h6 class="settings-tab-heading">
        <?php echo __( 'SMS', 'cart-lift' ); ?>
    </h6>

	<?php
	if ( !apply_filters( 'is_cl_premium', false ) ) {
		$pro_url = add_query_arg( 'cl-dashboard', '1', 'https://rextheme.com/cart-lift' );
		?>

        <div class="cl-form-group">
            <div class="cl-global-tooltip-area">
                <span class="title">
                    <?php echo __( 'Enable Twilio SMS Notification:', 'cart-lift' ); ?>
                </span>

                <div class="tooltip">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <rect x="1" y="1.1925" width="14" height="14" rx="7" stroke="#535963"/>
                            <path d="M6 5.69543C6.0741 4.80132 6.85381 4.13315 7.74894 4.19668H8.24864C9.14377 4.13315 9.92347 4.80132 9.99758 5.69543C10.0354 6.36175 9.62793 6.97278 8.99818 7.19418C8.30536 7.60992 7.91577 8.38893 7.99879 9.1925" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.5 12.19V12.195" stroke="#535963" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <p><?php echo __( 'This will enable twilio sms notification.', 'cart-lift' ); ?></p>
                </div>
            </div>

            <span class="cl-switcher">
                <span class="pro-tag">
                    <?php echo __( 'pro', 'cart-lift' ); ?>
                </span>
                <input class="cl-toggle-option" type="checkbox" id="twilio_sms" name="twilio_sms" disabled/>
                <label for="twilio_sms"></label>
            </span>
          
        </div>

		<?php
	}
	else {
		do_action( 'cl_twilio_sms' );
	}
	?>
