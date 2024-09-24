<?php

if( !function_exists( 'cart_lift_email_header' ) ) {
    function cart_lift_email_header( $header = 'Please Consider Your Cart', $color = '#6e42d3', $echo = true )
    {
        ob_start();
        include CART_LIFT_DIR . '/includes/emails/email-header.php';
        $output = ob_get_contents();
        ob_end_clean();
        if( $echo ) {
            echo $output;
        } else {
            return $output;
        }
    }
}
add_action( 'cart_lift_email_header', 'cart_lift_email_header' );

/**
 * cl_email_header_section
 * Design and content of email header
 * @param $header text of email header
 * @param $color background color of header
 */
if( !function_exists( 'cl_email_header_section' ) ) {
    function cl_email_header_section( $header, $color )
    {
        /**
         * Filters the background color
         *
         * @param string $background_color Default color #F7F7F7.
         *
         * @since 1.0.0
         */
        $background_color = apply_filters( 'cl_email_bg_color', '#F7F7F7' );

        /**
         * Filters and adds option to add more custom style for email body
         *
         * @param string $custom_style Custom styles. i.e. 'font-weight: bold; margin: auto'
         *
         * @since 3.1.7
         */
        $custom_style = apply_filters( 'cl_email_body_custom_style', '' );

        if( is_rtl() ) {
            $dir = 'rtl';
        } else {
            $dir = 'ltr';
        }
        $html = '';
        $html .= '<div id="wrapper" dir=' . $dir . ' style="background-color:' . $background_color . ';margin: 0;padding: 70px 0;width: 100%; ' . $custom_style . '">';
        $html .= '<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">';
        $html .= '<tr>';
        $html .= '<td align="center" valign="top">';
        $html .= '<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color: #ffffff;border: 1px solid #dedede;border-radius: 3px">';
        $html .= '<tr>';
        $html .= '<td align="center" valign="top">';
        $html .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' id='template_header' style='background-color:" . $color . "' color: #ffffff;border-bottom: 0;font-weight: bold;line-height: 100%;vertical-align: middle;font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;border-radius: 3px 3px 0 0><tr><td id='header_wrapper' style='padding: 36px 48px'><h1 style='color: #ffffff;border-bottom: 0;font-weight: bold;line-height: 100%;vertical-align: middle;font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;border-radius: 3px 3px 0 0'>" . $header . "</h1></td></tr> </table>";
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td align="center" valign="top">';
        $html .= '<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">';
        $html .= '<tr>';
        $html .= '<td valign="top" id="body_content">';
        $html .= '<table border="0" cellpadding="20" cellspacing="0" width="100%">';
        $html .= '<tr>';
        $html .= '<td valign="top">';
        $html .= '<div id="body_content_inner">';

        echo $html;
    }
}
add_action( 'cl_email_header_section', 'cl_email_header_section', 10, 2 );


/**
 * cl_email_footer_section
 * Design and content of email Footer
 */
if( !function_exists( 'cl_email_footer_section' ) ) {
    function cl_email_footer_section()
    {
        $disable_branding = cl_get_general_settings_data( 'disable_branding' );
        $blog_info        = get_bloginfo( 'name' ) . ' - ';
        $html             = '';
        $html             .= '</div>';
        $html             .= '</td>';
        $html             .= '</tr>';
        $html             .= '</table>';
        $html             .= '</td>';
        $html             .= '</tr>';
        $html             .= '</table>';
        $html             .= '</td>';
        $html             .= '</tr>';
        $html             .= '</table>';
        $html             .= '</td>';
        $html             .= '</tr>';
        $html             .= '<tr>';
        $html             .= '<td align="center" valign="top">';
        $html             .= '<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">';
        $html             .= '<tr>';
        $html             .= '<td valign="top">';
        $html             .= '<table border="0" cellpadding="10" cellspacing="0" width="100%">';
        $html             .= '<tr>';
        $html             .= '<td colspan="2" valign="middle" id="credit" style="border-radius: 6px;border: 0;color: #8a8a8a;font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;font-size: 12px;line-height: 150%;text-align: center;padding: 24px 0">';
        if( $disable_branding == 0 ) {

            $format_link = '<a href="https://rextheme.com/cart-lift" target="_blank" rel="noopener">CartLift</a>';
            $html        .= sprintf( __( '%s Powered by %s' ), $blog_info, $format_link );
        };
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</body>';
        $html .= '</html>';

        echo $html;


    }
}
add_action( 'cl_email_footer_section', 'cl_email_footer_section', 10 );


if( !function_exists( 'cart_lift_email_footer' ) ) {
    function cart_lift_email_footer( $echo = true )
    {
        ob_start();
        include CART_LIFT_DIR . '/includes/emails/email-footer.php';
        $output = ob_get_contents();
        ob_end_clean();
        if( $echo ) {
            echo $output;
        } else {
            return $output;
        }
    }
}
add_action( 'cart_lift_email_footer', 'cart_lift_email_footer' );


if( !function_exists( 'cl_trigger_abandon_cart_email_edd' ) ) {
    function cl_trigger_abandon_cart_email_edd( $cart_details )
    {

        $product_table = cl_get_email_product_table( $cart_details->cart_contents, $cart_details->cart_total, $cart_details->provider, false, false );
        $email_body    = '';
        $email_body    .= cart_lift_email_header( 'Cart Abandoned', false );
        $msg           = sprintf( esc_html__( 'Alas. Just to let you know &mdash; cart belonging to %1$s has been abandoned:', 'cart-lift' ), esc_html( $cart_details->email ) ) . "\n\n";

        $email_body .= $msg;
        $email_body .= '<br>';
        $email_body .= $product_table;
        $email_body .= cart_lift_email_footer( false );
        EDD()->emails->send( get_option( 'admin_email' ), 'Cart Abandoned', $email_body );
    }
}
add_action( 'cl_trigger_abandon_cart_email_edd', 'cl_trigger_abandon_cart_email_edd' );
