# DEVELOPER DOCUMENTATION - DUITKU MEMBERSHIP PLUGIN

## Overview
The Duitku Membership plugin extends Moodle's functionality by introducing an annual membership system integrated with the Duitku payment gateway. This document provides technical information for developers to understand, maintain, and extend the plugin.

## Code Structure

### Plugin Components
1. **Block Plugin**: `blocks/duitku_membership`
   - Displays membership information and subscription buttons
   - Uses templates to render UI

2. **Enrol Plugin Extension**: `enrol/duitku`
   - Contains membership processing logic
   - Handles payment transactions
   - Manages user role assignments
   - Contains database tables for membership data

### Key Files

#### Block Plugin
- `block_duitku_membership.php`: Main block class
- `templates/membership_info.mustache`: Basic membership information template
- `templates/membership_info_enhanced.mustache`: Enhanced membership information template with progress bar
- `lang/en/block_duitku_membership.php`: English language strings
- `lang/id/block_duitku_membership.php`: Indonesian language strings

#### Enrol Plugin (Membership Components)
- `membership.php`: Handles membership subscription process
- `membership_return.php`: Processes return from payment gateway
- `membership_callback.php`: Processes payment gateway callbacks
- `classes/duitku_membership.php`: Core membership class with helper methods
- `classes/duitku_helper.php`: Payment gateway integration helpers
- `classes/task/auto_enroll_members.php`: Scheduled task for auto-enrolling members
- `classes/task/payment_expirations.php`: Checks for expired memberships

## Database Schema

### Key Tables

1. `enrol_duitku_membership`
   - `id`: Primary key
   - `userid`: ID of the subscribed user
   - `payment_type`: Type of payment (membership)
   - `payment_status`: Payment status (success, pending, etc.)
   - `purchase_time`: Timestamp of purchase
   - `expiry_time`: Timestamp of membership expiry

2. `enrol_duitku_transactions`
   - `id`: Primary key
   - `userid`: User ID
   - `reference`: Payment reference from Duitku
   - `payment_type`: Payment type (course/membership)
   - `payment_status`: Payment status
   - `amount`: Payment amount
   - `payment_time`: Timestamp of payment
   - `merchant_order_id`: Merchant order ID sent to Duitku

3. `enrol_duitku_log`
   - `id`: Primary key
   - `timestamp`: Log entry timestamp
   - `log_type`: Type of log entry
   - `data`: Log data (JSON or text)
   - `status`: Status (success/error)

## Business Logic

### Membership Flow
1. User clicks "Subscribe Now" in the membership block
2. System checks if user already has an active membership
3. If not, system generates a Duitku payment transaction
4. User is redirected to Duitku payment page
5. After payment, user is returned to membership_return.php
6. System verifies payment status via Duitku API
7. On successful payment, membership is activated:
   - Record is added to `enrol_duitku_membership`
   - User is assigned the 'penyuluhagama' role
   - User is auto-enrolled in all applicable courses

### Auto-enrollment System
1. Scheduled task `auto_enroll_members` runs periodically
2. Identifies all active members and relevant courses
3. Enrolls members in courses they're not already enrolled in
4. Updates enrollment records

## API and Hooks

### Key Classes and Methods

#### duitku_membership class
- `has_active_membership($userid)`: Checks if user has active membership
- `get_membership_expiry($userid)`: Gets expiry date of membership
- `assign_membership_role($userid, $contextid)`: Assigns membership role
- `create_or_extend_membership($userid, $expiryperiod)`: Creates or extends membership
- `process_membership_payment($userid, $reference, $amount)`: Processes payment
- `get_statistics()`: Gets membership statistics

#### duitku_helper class
- `create_transaction($paramsstring, $timestamp, $context)`: Creates payment transaction
- `check_transaction($context)`: Checks transaction status
- `log_request($eventarray)`: Logs requests and responses

### Event Observers
The plugin includes event observers to handle:
- Payment completion events
- Membership expiration events
- Course creation events (for auto-enrollment)

## Integration Points

### Integration with Moodle Core
- Uses Moodle's role system for membership privileges
- Integrates with enrollment system for auto-enrollment
- Uses Moodle's context system for permission checks
- Uses Moodle's language strings system for localization

### Integration with Duitku Payment Gateway
- Uses Duitku API for payment processing
- Implements callbacks for payment status updates
- Handles signature verification for secure transactions

## Extending the Plugin

### Adding New Features
To extend the plugin's functionality:
1. Understand the existing code structure
2. Identify appropriate integration points
3. Follow Moodle coding standards
4. Update language files for any new strings
5. Add appropriate database schema updates if required

### Common Extension Points
- Add new membership levels by extending the `duitku_membership` class
- Customize the block display by modifying templates
- Add new payment options by extending `duitku_helper`
- Add new auto-enrollment logic in the task classes

## Troubleshooting

### Common Issues
1. Payment not processing
   - Check Duitku API credentials
   - Verify correct endpoint URLs
   - Check logs for API response errors

2. Membership role not assigned
   - Verify role exists in Moodle
   - Check context used for role assignment
   - Check user capabilities

3. Auto-enrollment not working
   - Verify scheduled task is running
   - Check course enrollment criteria
   - Check enrollment method priorities

### Debugging
- Enable Moodle developer mode for detailed errors
- Check `enrol_duitku_log` table for transaction logs
- Use Moodle's debugging tools for additional information

## Performance Considerations
- Membership checks are cached where possible
- Batch processing is used for auto-enrollment
- Database queries are optimized to reduce load

## Security Considerations
- All user inputs are validated and sanitized
- Payment signatures are verified
- Proper capability checks are implemented
- Context validation is performed for all operations

## Future Development
- Support for multiple membership tiers
- Integration with additional payment gateways
- Enhanced reporting and analytics
- Discount and promotion system
- Group membership management

---
*This documentation is for developers working with the Duitku Membership plugin. For administrator or user documentation, please refer to the respective guides.*
