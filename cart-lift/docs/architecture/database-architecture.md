# Database Architecture

## Custom Tables

### Abandoned Carts Table

**Table:** `{$wpdb->prefix}cl_abandoned_carts`

Stores abandoned cart sessions.

**Schema:**

```sql
CREATE TABLE {$wpdb->prefix}cl_abandoned_carts (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    session_id VARCHAR(255) NOT NULL,
    user_id BIGINT UNSIGNED DEFAULT NULL,
    email VARCHAR(255) NOT NULL,
    cart_contents LONGTEXT NOT NULL,
    cart_total DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'USD',
    status VARCHAR(20) DEFAULT 'abandoned',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    recovered_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    KEY session_id (session_id),
    KEY email (email),
    KEY status (status),
    KEY created_at (created_at)
);
```

### Email Campaigns Table

**Table:** `{$wpdb->prefix}cl_email_campaigns`

Stores email recovery campaigns.

### Email Logs Table

**Table:** `{$wpdb->prefix}cl_email_logs`

Tracks sent emails and their status.

### Recovery Stats Table

**Table:** `{$wpdb->prefix}cl_recovery_stats`

Analytics and recovery metrics.

## WordPress Tables Used

- `wp_users` - Customer data
- `wp_options` - Plugin settings
- `wp_postmeta` - WooCommerce/EDD product data

## Data Relationships

```
cl_abandoned_carts
    ↓ (1:many)
cl_email_logs
    ↓ (many:1)
cl_email_campaigns

wp_users
    ↓ (1:many)
cl_abandoned_carts
```

## Privacy Considerations

- Cart data includes personal information
- Email addresses stored with consent
- Data retention configurable
- Automated data cleanup
- GDPR erasure support
