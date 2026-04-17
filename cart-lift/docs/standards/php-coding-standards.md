# PHP Coding Standards (Cart Lift)

## Overview

Cart Lift follows WordPress PHP Coding Standards with special attention to data privacy and email handling.

> **See:** [Best WooCommerce Feed PHP Standards](../../best-woocommerce-feed/docs/standards/php-coding-standards.md) for complete guidelines.

## Cart Lift Specific Conventions

### Naming

```php
// Prefix with 'cl_' or 'cart_lift_'
class Cart_Lift_Email_Campaign {}
function cl_get_abandoned_carts() {}
define( 'CART_LIFT_VERSION', '3.1.51' );
```

### Privacy-Focused Coding

#### Data Anonymization

```php
/**
 * Anonymize customer data
 *
 * @param string $email Customer email
 * @return string Anonymized email
 */
public function anonymize_email( $email ) {
    $parts = explode( '@', $email );
    if ( count( $parts ) !== 2 ) {
        return 'anonymous@example.com';
    }

    $local = substr( $parts[0], 0, 2 ) . '***';
    return $local . '@' . $parts[1];
}
```

#### Consent Checking

```php
/**
 * Check if user consented to cart tracking
 *
 * @param string $email Customer email
 * @return bool
 */
public function has_tracking_consent( $email ) {
    // Check consent meta or cookie
    $consent = get_user_meta_by_email( $email, 'cl_tracking_consent', true );
    return 'yes' === $consent;
}
```

### Email Handling

#### Template Processing

```php
/**
 * Process email template with variables
 *
 * @param string $template Email template content
 * @param array  $cart_data Cart data
 * @return string Processed template
 */
public function process_email_template( $template, $cart_data ) {
    $variables = array(
        '{{customer_name}}' => sanitize_text_field( $cart_data['name'] ),
        '{{cart_total}}'    => wc_price( $cart_data['total'] ),
        '{{products}}'      => $this->get_products_html( $cart_data['items'] ),
        '{{recovery_link}}' => esc_url( $this->get_recovery_link( $cart_data['id'] ) ),
    );

    return str_replace(
        array_keys( $variables ),
        array_values( $variables ),
        $template
    );
}
```

### Background Processing

#### Cron Jobs

```php
/**
 * Schedule cart abandonment check
 */
public function schedule_abandonment_check() {
    if ( ! wp_next_scheduled( 'cl_check_abandoned_carts' ) ) {
        wp_schedule_event( time(), 'hourly', 'cl_check_abandoned_carts' );
    }
}

/**
 * Check for abandoned carts (cron callback)
 */
public function check_abandoned_carts() {
    $threshold = apply_filters( 'cl_abandonment_threshold', 30 * MINUTE_IN_SECONDS );
    $carts = $this->get_potentially_abandoned_carts( $threshold );

    foreach ( $carts as $cart ) {
        $this->mark_as_abandoned( $cart->id );
        $this->schedule_recovery_email( $cart->id );
    }
}
```

## Best Practices

1. **Always check consent** - Before tracking or emailing
2. **Anonymize customer data** - In logs and exports
3. **Use WordPress email functions** - `wp_mail()` for emails
4. **Background processing** - Use WP-Cron for heavy operations
5. **Cart data security** - Encrypt sensitive cart contents
6. **Test email compatibility** - Across email clients
7. **Handle unsubscribes** - Respect opt-outs
8. **GDPR compliance** - Data export, erasure, consent
9. **Rate limiting** - Don't spam customers
10. **Error logging** - Track failed emails and recoveries
