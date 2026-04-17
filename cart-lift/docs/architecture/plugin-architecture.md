# Plugin Architecture

## Overview

Cart Lift is a WordPress plugin that recovers abandoned carts for WooCommerce and Easy Digital Downloads by tracking checkout sessions and sending automated recovery emails.

## Directory Structure

```
cart-lift/
├── admin/              # Admin-facing functionality
├── includes/           # Core plugin classes
├── public/             # Public-facing functionality
├── vendor/             # Composer dependencies
├── languages/          # Translation files
└── cart-lift.php      # Main plugin file
```

## Core Components

### Main Plugin Class

**File:** `includes/class-cart-lift.php`

Orchestrates plugin lifecycle, cart tracking, and email campaigns.

### Cart Tracking

Monitors user checkout behavior:

- Session tracking
- Cart abandonment detection
- Customer information capture
- Privacy compliance

### Email Campaign System

Automated recovery emails:

- Customizable email templates
- Scheduled sending
- Multiple email sequences
- Dynamic coupon generation

### Analytics Engine

Tracks recovery performance:

- Abandoned cart metrics
- Recovery rates
- Revenue tracking
- Campaign effectiveness

## Integration Points

### WooCommerce

- Cart session hooks
- Checkout process monitoring
- Order completion tracking
- Product data retrieval

### Easy Digital Downloads (EDD)

- Downloads cart tracking
- Checkout monitoring
- Purchase completion
- Download product data

## Data Flow

1. **Cart Creation:** User adds items to cart
2. **Tracking:** Plugin captures cart session
3. **Abandonment Detection:** User leaves without purchasing
4. **Email Scheduling:** Recovery emails queued
5. **Email Sending:** Automated emails sent
6. **Recovery Tracking:** Monitors conversions
7. **Analytics:** Updates recovery statistics

## Privacy & GDPR

- Explicit consent collection
- Data retention policies
- Right to erasure
- Data export capabilities
- Anonymization options

## See Also

- [Database Architecture](database-architecture.md) - Cart and campaign data storage
- [Security Guidelines](../standards/security-guidelines.md) - Customer data security
- [Design System](../design/design-system.md) - Email and UI design
