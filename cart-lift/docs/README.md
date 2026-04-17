# Cart Lift Documentation

This directory contains internal documentation for developers, contributors, and AI agents working on the Cart Lift plugin.

## Documentation Structure

### Architecture

- **[Plugin Architecture](architecture/plugin-architecture.md)** - Overview of the plugin's structure, core components, and module interactions
- **[Database Architecture](architecture/database-architecture.md)** - Custom tables, schema definitions, relationships, and data flow

### Standards

- **[PHP Coding Standards](standards/php-coding-standards.md)** - Code style conventions and WordPress/PHP-FIG compliance
- **[Security Guidelines](standards/security-guidelines.md)** - WordPress security best practices, nonces, permissions, and escaping
- **[Data Sanitization & Validation](standards/data-sanitization-validation.md)** - Input/output sanitization and validation practices

### Design

- **[Design System](design/design-system.md)** - UI/UX guidelines, component library, and styling conventions

## Plugin Overview

Cart Lift is an abandoned cart recovery plugin for WooCommerce and Easy Digital Downloads (EDD):

- **Cart Tracking** - Monitors abandoned checkout sessions
- **Email Campaigns** - Automated recovery email sequences
- **Analytics** - Tracks recovery rates and revenue
- **WooCommerce & EDD Support** - Works with both platforms
- **GDPR Compliant** - Privacy-focused cart tracking

## For Contributors

All code contributions should adhere to the standards outlined in this documentation. Special attention should be paid to privacy and security when handling customer cart data.

## For AI Agents

This documentation provides comprehensive context for understanding and working with the Cart Lift codebase. Refer to these documents when making architectural decisions or implementing new features, especially those involving personal data handling.

## Key Features to Understand

- Abandoned cart detection logic
- Email campaign automation
- Customer data privacy
- WooCommerce integration
- EDD integration
- Cart recovery tracking
- GDPR compliance
