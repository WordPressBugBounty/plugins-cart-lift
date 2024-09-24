<div id="cl-loader" class="cl-loader">
    <div class="ring"></div>
</div>

<?php
    // Access the global wp_roles object
    global $wp_roles;
    
    // Retrieve all roles available in the WordPress site
    $all_roles = $wp_roles->roles;
?>

<!-- Block: cl-settings -->
<section id="cl-settings" class="cl-settings" role="tabpanel" aria-labelledby="cl-settings-tabs">

    <div class="cl-settings__header">
		<h2 class="cl-settings__title"><?php echo esc_html__('Settings', 'cart-lift'); ?></h2>
	</div>

    <div id="cl-settings-tabs" class="cl-settings__tab-wrapper">

        <nav class="cl-settings__nav-items" role="tablist" aria-label="Settings Tabs">
            <!-- Element: cl-settings__tabs -->
            <ul class="cl-settings__tabs">
                <li role="presentation">
                    <a href="#general" id="tab-general" role="tab" aria-controls="general" aria-selected="true">
                        <?php
                            $file_path = CART_LIFT_DIR . 'admin/icon/icon-svg/general.php';

                            if (file_exists($file_path)) {
                                include $file_path;
                            } else {
                                // Handle the case where the file doesn't exist
                                error_log("Warning: The file $file_path does not exist.");
                                // Optionally, you can display a message to the user or take alternative action
                                // echo "Sorry, the required file could not be found.";
                            }
                        ?>
                        <?php echo __( 'General', 'cart-lift' ); ?>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#email-popup" id="tab-email-popup" role="tab" aria-controls="email-popup" aria-selected="false">
                        <?php
                            $file_path = CART_LIFT_DIR . 'admin/icon/icon-svg/popup-editor.php';

                            if (file_exists($file_path)) {
                                include $file_path;
                            } else {
                                // Handle the case where the file doesn't exist
                                error_log("Warning: The file $file_path does not exist.");
                                // Optionally, you can display a message to the user or take alternative action
                                // echo "Sorry, the required file could not be found.";
                            }
                        ?>
                        <?php echo __( 'Popup Editor', 'cart-lift' ); ?>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#twilio-sms" id="tab-twilio-sms" role="tab" aria-controls="twilio-sms" aria-selected="false">
                        <?php
                            $file_path = CART_LIFT_DIR . 'admin/icon/icon-svg/sms.php';

                            if (file_exists($file_path)) {
                                include $file_path;
                            } else {
                                // Handle the case where the file doesn't exist
                                error_log("Warning: The file $file_path does not exist.");
                                // Optionally, you can display a message to the user or take alternative action
                                // echo "Sorry, the required file could not be found.";
                            }
                        ?>
                        <?php echo __( 'Integration', 'cart-lift' ); ?>
                    </a>
                </li>
            </ul>
        </nav> <!-- End of .cl-settings__tabs -->

        <!-- Element: cl-settings__content-wrapper -->
        <div class="cl-settings__content-wrapper">
            <!-- general tab -->

            <!-- Modifier: cl-settings__content--general -->
            <section id="general" class="cl-settings__content cl-settings__content--general" role="tabpanel" >

                <?php
                    $file_path = CART_LIFT_DIR . 'admin/partials/settings-component/general-tab.php';

                    if (file_exists($file_path)) {
                        require_once $file_path;
                    } else {
                        // Handle the case where the file doesn't exist
                        error_log("Warning: The file $file_path does not exist.");
                        // Optionally, you can display a message to the user or take alternative action
                        // echo "Sorry, the required file could not be found.";
                    }
                ?>
            </section>

            <!-- Modifier: cl-settings__content--email-popup -->
            <section id="email-popup" class="cl-settings__content cl-settings__content--email-popup" role="tabpanel" aria-labelledby="tab-email-popup">
                <?php
                    $file_path = CART_LIFT_DIR . 'admin/partials/settings-component/email-popup.php';

                    if (file_exists($file_path)) {
                        require_once $file_path;
                    } else {
                        // Handle the case where the file doesn't exist
                        error_log("Warning: The file $file_path does not exist.");
                        // Optionally, you can display a message to the user or take alternative action
                        // echo "Sorry, the required file could not be found.";
                    }
                ?>
            </section>

            <!-- Modifier: cl-settings__content--twilio-sms -->
            <section id="twilio-sms" class="cl-settings__content">

                <h4 class="settings-tab-heading">
                    <?php echo __( 'Integration', 'cart-lift' ); ?>
                </h4>

                <div class="inner-wrapper cl-integrations-area">
                    <section  class="cl-settings__content--twilio-sms" role="tabpanel" aria-labelledby="tab-twilio-sms">
                            <?php
                                $file_path = CART_LIFT_DIR . 'admin/partials/settings-component/twilio-sms.php';

                                if (file_exists($file_path)) {
                                    require_once $file_path;
                                } else {
                                    // Handle the case where the file doesn't exist
                                    error_log("Warning: The file $file_path does not exist.");
                                    // Optionally, you can display a message to the user or take alternative action
                                    // echo "Sorry, the required file could not be found.";
                                }
                            ?>
                    </section>

                    <section id="recaptcha-v3" class="cl-settings__content--recaptcha-v3" role="tabpanel" aria-labelledby="tab-recaptcha-v3">
                        <?php
                            $file_path = CART_LIFT_DIR . 'admin/partials/settings-component/recaptcha-tap.php';

                            if (file_exists($file_path)) {
                                require_once $file_path;
                            } else {
                                // Handle the case where the file doesn't exist
                                error_log("Warning: The file $file_path does not exist.");
                                // Optionally, you can display a message to the user or take alternative action
                                // echo "Sorry, the required file could not be found.";
                            }
                        ?>
                    </section>
                </div>
                
            </section>

        </div> <!-- End of .cl-settings__content-wrapper -->

    </div>

</section> <!-- End of .cl-settings -->