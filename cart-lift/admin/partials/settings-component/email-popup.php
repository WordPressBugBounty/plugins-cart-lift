<h4 class="settings-tab-heading">
    <?php echo __( 'Popup Editor', 'cart-lift' ); ?>
</h4>
<div class="inner-wrapper">
	<?php
	if ( !apply_filters( 'is_cl_premium', false ) ) {
		$pro_url = add_query_arg( 'cl-dashboard', '1', 'https://rextheme.com/cart-lift' );
		?>

            <div class="cl-form-group">
                <span class="title"><?php echo __( 'Enable Cart Lift Popup:', 'cart-lift' ); ?></span>
                <span class="cl-switcher">
                    <a href="https://rextheme.com/cart-lift?cl-dashboard=1" style="right: 48px" target="_blank"
                        title="<?php
                        _e( 'Click to Upgrade Pro', 'cart-lift' ); ?>" class="pro-tag"><?php
                        echo __( 'pro', 'cart-lift' ); ?></a>
                    <input class="cl-toggle-option" type="checkbox" id="email_popup" name="email_popup" disabled/>
                    <label for="email_popup"></label>
                </span>

                <div class="tooltip">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 19" width="18" height="19">
                            <defs>
                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                    <path d="M-941 -385L379 -385L379 866L-941 866Z"/>
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
                                        <path id="Shape" fill-rule="evenodd" class="shp0"
                                                d="M18 10C18 14.97 13.97 19 9 19C4.03 19 0 14.97 0 10C0 5.03 4.03 1 9 1C13.97 1 18 5.03 18 10ZM16.8 10C16.8 5.7 13.3 2.2 9 2.2C4.7 2.2 1.2 5.7 1.2 10C1.2 14.3 4.7 17.8 9 17.8C13.3 17.8 16.8 14.3 16.8 10Z"/>
                                        <path id="Path" class="shp0"
                                                d="M8.71 11.69C8.25 11.69 7.87 12.07 7.87 12.53C7.87 12.98 8.24 13.37 8.71 13.37C9.19 13.37 9.56 12.98 9.56 12.53C9.56 12.07 9.18 11.69 8.71 11.69Z"/>
                                        <path id="Path" class="shp0"
                                                d="M8.64 6.06C7.35 6.06 6.75 6.85 6.75 7.38C6.75 7.77 7.07 7.94 7.33 7.94C7.84 7.94 7.63 7.19 8.61 7.19C9.09 7.19 9.48 7.4 9.48 7.86C9.48 8.39 8.94 8.69 8.62 8.97C8.34 9.21 7.98 9.62 7.98 10.47C7.98 10.98 8.11 11.12 8.51 11.12C8.98 11.12 9.07 10.91 9.07 10.72C9.07 10.21 9.08 9.91 9.61 9.49C9.87 9.28 10.69 8.61 10.69 7.69C10.69 6.76 9.87 6.06 8.64 6.06Z"/>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>
                    <p><?php echo __( 'This will initiate a pop-up and prompt users to provide email addresses when they intend to leave from the cart page.', 'cart-lift' ); ?></p>
                </div>
            </div>
		<?php
	}
	else {
		do_action( 'cl_pop_up_email' );
	}
	?>

</div>
