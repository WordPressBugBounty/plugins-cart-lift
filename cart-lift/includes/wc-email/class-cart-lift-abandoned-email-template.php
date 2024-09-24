<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Email' ) ) {
    return;
}

/**
 * Class Cart_Lift_Admin_Abandoned_Email_Template
 */

class Cart_Lift_Admin_Abandoned_Email_Template extends WC_Email {

    /**
     * Cart_Lift_Admin_Abandoned_Email_Template constructor.
     *
     * @access public
     * @return void
     */
    public function __construct() {

        $this->id          = 'cl_admin_abandoned_cart';
        $this->title       = __( 'Abandoned cart to admin', 'cart-lift' );
        $this->description = __( 'An email sent to the admin when a cart is abandoned.', 'cart-lift' );

        $this->customer_email = false;
        $this->heading     = __( 'Cart Abandoned', 'cart-lift' );
        // translators: placeholder is {blogname}, a variable that will be substituted when email is sent out
        $this->subject     = sprintf( _x( '[%s] Cart abandoned', 'default email subject for abandoned emails sent to the admin', 'cart-lift' ), '{blogname}' );

        // Template paths.
        $this->template_html  = 'emails/cl-admin-abandoned-cart.php';
        $this->template_plain = 'emails/plain/cl-admin-abandoned-cart.php';
        $this->template_base  = CART_LIFT_DIR . 'includes/wc-email/templates/';

        $this->recipient = $this->get_option( 'recipient' );
        // if none was entered, just use the WP admin email as a fallback
        if ( ! $this->recipient )
            $this->recipient = get_option( 'admin_email' );

        add_action( 'cl_trigger_abandon_cart_email', array( $this, 'trigger' ));

        parent::__construct();
    }


    function trigger( $item ) {

        if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
            return;
        }
        $this->object = $item;
        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
    }


    public function get_content_html() {
        return wc_get_template_html( $this->template_html, array(
            'item'          => $this->object,
            'email_heading' => $this->get_heading(),
            'sent_to_admin' => false,
            'plain_text'    => false,
            'email'			=> $this
        ), '', $this->template_base );
    }

    /**
     * Get content plain.
     *
     * @return string
     */
    public function get_content_plain() {
        return wc_get_template_html( $this->template_plain, array(
            'item'          => $this->object,
            'email_heading' => $this->get_heading(),
            'sent_to_admin' => false,
            'plain_text'    => true,
            'email'			=> $this
        ), '', $this->template_base );
    }

    /**
     * Initialize Settings Form Fields
     *
     * @since 0.1
     */
    public function init_form_fields() {

        $this->form_fields = array(
            'enabled'    => array(
                'title'   => 'Enable/Disable',
                'type'    => 'checkbox',
                'label'   => 'Enable this email notification',
                'default' => 'yes'
            ),
            'recipient'  => array(
                'title'       => 'Recipient(s)',
                'type'        => 'text',
                'description' => sprintf( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', esc_attr( get_option( 'admin_email' ) ) ),
                'placeholder' => '',
                'default'     => ''
            ),
            'subject'    => array(
                'title'       => 'Subject',
                'type'        => 'text',
                'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject ),
                'placeholder' => '',
                'default'     => ''
            ),
            'heading'    => array(
                'title'       => 'Email Heading',
                'type'        => 'text',
                'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->heading ),
                'placeholder' => '',
                'default'     => ''
            ),
            'email_type' => array(
                'title'       => 'Email type',
                'type'        => 'select',
                'description' => 'Choose which format of email to send.',
                'default'     => 'html',
                'class'       => 'email_type',
                'options'     => array(
                    'plain'     => 'Plain text',
                    'html'      => 'HTML', 'woocommerce',
                    'multipart' => 'Multipart', 'woocommerce',
                )
            )
        );
    }
}