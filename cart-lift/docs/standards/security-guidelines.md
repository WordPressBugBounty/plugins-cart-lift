# Security Guidelines (Cart Lift)

## Overview

Cart Lift handles sensitive customer and cart data requiring strict security measures.

> **See:** [Best WooCommerce Feed Security Guidelines](../../best-woocommerce-feed/docs/standards/security-guidelines.md) for complete WordPress security practices.

## Cart Data Security

### Sensitive Data Protection

#### Cart Contents

```php
/**
 * Encrypt cart contents before storage
 *
 * @param array $cart_contents Cart items
 * @return string Encrypted cart data
 */
private function encrypt_cart_contents( $cart_contents ) {
    $serialized = maybe_serialize( $cart_contents );

    if ( function_exists( 'openssl_encrypt' ) ) {
        $key = wp_salt( 'secure_auth' );
        $iv = openssl_random_pseudo_bytes( 16 );
        $encrypted = openssl_encrypt( $serialized, 'AES-256-CBC', $key, 0, $iv );
        return base64_encode( $iv . $encrypted );
    }

    // Fallback: base64 (not secure)
    return base64_encode( $serialized );
}
```

### Recovery Link Security

#### Secure Token Generation

```php
/**
 * Generate secure recovery token
 *
 * @param int $cart_id Cart ID
 * @return string Recovery token
 */
public function generate_recovery_token( $cart_id ) {
    $random = wp_generate_password( 32, false );
    $token = hash_hmac( 'sha256', $cart_id . $random, wp_salt( 'auth' ) );

    // Store token with expiration
    $this->store_recovery_token( $cart_id, $token, DAY_IN_SECONDS );

    return $token;
}

/**
 * Validate recovery token
 *
 * @param string $token Recovery token
 * @return int|bool Cart ID or false
 */
public function validate_recovery_token( $token ) {
    // Sanitize token
    $token = sanitize_text_field( $token );

    global $wpdb;

    $cart_id = $wpdb->get_var( $wpdb->prepare(
        "SELECT cart_id FROM {$wpdb->prefix}cl_recovery_tokens
         WHERE token = %s AND expires_at > %s",
        $token,
        current_time( 'mysql' )
    ) );

    if ( $cart_id ) {
        // Delete token after use (one-time use)
        $this->delete_recovery_token( $token );
        return (int) $cart_id;
    }

    return false;
}
```

## Email Security

### Preventing Email Injection

```php
/**
 * Sanitize email recipient
 *
 * @param string $email Email address
 * @return string|false Sanitized email or false
 */
public function sanitize_email_recipient( $email ) {
    $email = sanitize_email( $email );

    if ( ! is_email( $email ) ) {
        return false;
    }

    // Additional checks for email injection
    if ( preg_match( '/[\r\n]/', $email ) ) {
        return false;
    }

    return $email;
}

/**
 * Send recovery email securely
 *
 * @param int $cart_id Cart ID
 * @return bool Success
 */
public function send_recovery_email( $cart_id ) {
    $cart = $this->get_cart( $cart_id );

    // Validate email
    $to = $this->sanitize_email_recipient( $cart->email );
    if ( ! $to ) {
        error_log( 'Invalid email for cart: ' . $cart_id );
        return false;
    }

    // Check unsubscribe status
    if ( $this->is_unsubscribed( $to ) ) {
        return false;
    }

    // Rate limiting
    if ( ! $this->check_email_rate_limit( $to ) ) {
        return false;
    }

    // Prepare email with escaping
    $subject = esc_html__( 'You left items in your cart', 'cart-lift' );
    $message = $this->get_email_template( $cart );
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );

    return wp_mail( $to, $subject, $message, $headers );
}
```

## Privacy & GDPR

### Consent Management

```php
/**
 * Record cart tracking consent
 *
 * @param string $email Email address
 * @param bool   $consented Consent status
 */
public function record_consent( $email, $consented ) {
    $email = sanitize_email( $email );

    if ( ! $email ) {
        return;
    }

    global $wpdb;

    $wpdb->replace(
        $wpdb->prefix . 'cl_consents',
        array(
            'email' => $email,
            'consented' => $consented ? 1 : 0,
            'consented_at' => current_time( 'mysql' ),
            'ip_address' => $this->get_user_ip(),
        ),
        array( '%s', '%d', '%s', '%s' )
    );
}
```

### Data Erasure

```php
/**
 * Erase customer data (GDPR right to erasure)
 *
 * @param string $email Email address
 * @return bool Success
 */
public function erase_customer_data( $email ) {
    $email = sanitize_email( $email );

    if ( ! $email ) {
        return false;
    }

    global $wpdb;

    // Delete abandoned carts
    $wpdb->delete(
        $wpdb->prefix . 'cl_abandoned_carts',
        array( 'email' => $email ),
        array( '%s' )
    );

    // Delete email logs
    $wpdb->delete(
        $wpdb->prefix . 'cl_email_logs',
        array( 'recipient_email' => $email ),
        array( '%s' )
    );

    // Delete consent records
    $wpdb->delete(
        $wpdb->prefix . 'cl_consents',
        array( 'email' => $email ),
        array( '%s' )
    );

    return true;
}
```

## Rate Limiting

```php
/**
 * Check email rate limit
 *
 * @param string $email Email address
 * @return bool Can send email
 */
private function check_email_rate_limit( $email ) {
    $transient_key = 'cl_email_limit_' . md5( $email );
    $count = get_transient( $transient_key );

    if ( false === $count ) {
        set_transient( $transient_key, 1, DAY_IN_SECONDS );
        return true;
    }

    // Max 3 emails per day per customer
    if ( $count >= 3 ) {
        return false;
    }

    set_transient( $transient_key, $count + 1, DAY_IN_SECONDS );
    return true;
}
```

## Security Checklist

- [ ] Cart data encrypted before storage
- [ ] Recovery tokens are cryptographically secure
- [ ] Recovery links expire after use
- [ ] Email addresses validated and sanitized
- [ ] Email injection prevented
- [ ] Rate limiting implemented
- [ ] Consent properly recorded
- [ ] GDPR erasure supported
- [ ] Unsubscribe functionality working
- [ ] Customer IP addresses anonymized in logs
- [ ] No sensitive data in error messages
- [ ] Cart access requires valid token

## Best Practices

1. **Encrypt cart contents** - Always encrypt sensitive data
2. **One-time recovery links** - Tokens expire after use
3. **Respect privacy laws** - GDPR, CCPA compliance
4. **Rate limit emails** - Prevent spam
5. **Secure token generation** - Use cryptographic functions
6. **Validate all email addresses** - Prevent injection
7. **Log security events** - Track suspicious activity
8. **Regular data cleanup** - Delete old carts and logs
9. **Audit trail** - Log consent and erasure requests
10. **Test security** - Regular penetration testing
