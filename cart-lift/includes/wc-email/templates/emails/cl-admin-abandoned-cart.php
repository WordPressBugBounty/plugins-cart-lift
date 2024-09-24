<?php
/**
 * Cancelled Order sent to Customer.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<h3><?php echo sprintf( esc_html__( 'Alas. Just to let you know &mdash; cart belonging to %1$s has been abandoned:', 'cart-lift' ), esc_html( $item->email ) ) . "\n\n";
?></h3>
<p><?php printf(__( 'Cart details: ', 'cart-lift' )); ?></p>


<?php

do_action( 'cart_lift_email_order_details', $item->cart_contents, $item->cart_total, 'wc' );

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );