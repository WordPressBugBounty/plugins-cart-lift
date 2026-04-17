# Data Sanitization & Validation (Cart Lift)

## Overview

Cart Lift handles sensitive customer data requiring thorough sanitization and validation.

> **See:** [Best WooCommerce Feed Data Sanitization](../../best-woocommerce-feed/docs/standards/data-sanitization-validation.md) for complete practices.

## Cart-Specific Data Types

### Email Addresses

Always validate and sanitize customer emails:

```php
// Validation
$email = sanitize_email( $_POST['email'] );
if ( ! is_email( $email ) ) {
    return new WP_Error( 'invalid_email', __( 'Valid email required.', 'cart-lift' ) );
}

// Check for injection attempts
if ( preg_match( '/[\r\n]/', $email ) ) {
    return new WP_Error( 'invalid_email', __( 'Invalid email format.', 'cart-lift' ) );
}
```

### Cart Contents

Sanitize before storage:

```php
// Sanitize cart data
$cart_data = array(
    'items' => array_map( function( $item ) {
        return array(
            'product_id' => absint( $item['product_id'] ),
            'quantity' => absint( $item['quantity'] ),
            'price' => floatval( $item['price'] ),
            'name' => sanitize_text_field( $item['name'] ),
        );
    }, $_POST['items'] ),
    'total' => floatval( $_POST['total'] ),
    'currency' => sanitize_text_field( $_POST['currency'] ),
);
```

### Recovery Tokens

```php
// Generate secure token
$token = wp_generate_password( 32, false );

// Validate token input
$input_token = sanitize_text_field( $_GET['token'] );
if ( ! preg_match( '/^[a-zA-Z0-9]{32}$/', $input_token ) ) {
    wp_die( __( 'Invalid recovery token.', 'cart-lift' ) );
}
```

### Email Templates

```php
// Sanitize template content
$template = wp_kses_post( $_POST['email_template'] );

// Validate template variables
$allowed_variables = array( '{{customer_name}}', '{{cart_total}}', '{{recovery_link}}' );
// Ensure only allowed variables used
```

## Output Escaping

### Email Content

```php
// Plain text emails
$message = esc_html( $cart->customer_name ) . "\n\n";
$message .= esc_html__( 'Your cart total:', 'cart-lift' ) . ' ' . esc_html( $cart->total );

// HTML emails
$html = '<p>' . esc_html( $cart->customer_name ) . '</p>';
$html .= '<a href="' . esc_url( $recovery_link ) . '">' . esc_html__( 'Complete Purchase', 'cart-lift' ) . '</a>';
```

### Admin Display

```php
<div class="cart-info">
    <h3><?php echo esc_html( $cart->customer_name ); ?></h3>
    <p><?php echo esc_html( $cart->email ); ?></p>
    <p><?php echo wc_price( $cart->total ); ?></p>
</div>
```

## Privacy-Focused Sanitization

### Anonymization

```php
/**
 * Anonymize email for display
 *
 * @param string $email Full email
 * @return string Partial email
 */
function cl_anonymize_email_display( $email ) {
    $parts = explode( '@', $email );
    if ( count( $parts ) !== 2 ) {
        return '***@***.***';
    }

    $local = substr( $parts[0], 0, 2 ) . '***';
    $domain_parts = explode( '.', $parts[1] );
    $domain = $domain_parts[0][0] . '***.' . end( $domain_parts );

    return esc_html( $local . '@' . $domain );
}
```

## Best Practices

1. **Validate emails strictly** - Prevent injection
2. **Sanitize cart data** - Before database storage
3. **Escape email content** - Prevent XSS in HTML emails
4. **Secure tokens** - Cryptographic generation
5. **Anonymize for display** - Protect customer privacy
6. **Validate template variables** - Only allow safe placeholders
7. **Rate limit data collection** - Prevent abuse
8. **Clean old data** - Regular database cleanup
9. **Consent validation** - Check before tracking
10. **Audit sensitive operations** - Log data access

## Quick Reference

| Data Type      | Sanitize                | Validate                       | Escape Output       |
| -------------- | ----------------------- | ------------------------------ | ------------------- |
| Email          | `sanitize_email()`      | `is_email()` + injection check | `esc_html()`        |
| Cart contents  | Type-specific           | Structure + types              | Context-specific    |
| Recovery token | `sanitize_text_field()` | Regex pattern                  | `esc_attr()`        |
| Customer name  | `sanitize_text_field()` | Length check                   | `esc_html()`        |
| Cart total     | `floatval()`            | Range check                    | `wc_price()`        |
| Email template | `wp_kses_post()`        | Variables check                | N/A (pre-sanitized) |
