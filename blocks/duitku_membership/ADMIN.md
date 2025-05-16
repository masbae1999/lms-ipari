# ADMINISTRATOR GUIDE - DUITKU MEMBERSHIP PLUGIN

## Overview
The Duitku Membership plugin extends your Moodle site with an annual membership system integrated with the Duitku payment gateway. This guide explains how to install, configure, and manage the membership system.

## Installation

### Prerequisites
- Moodle 4.0 or higher
- PHP 8.2 or higher
- Duitku payment gateway merchant account
- Database with sufficient privileges

### Installation Steps
1. **Download the Plugins**
   - Download both the `block_duitku_membership` and `enrol_duitku` plugins
   - If `enrol_duitku` is already installed, ensure it's up to date

2. **Install via Moodle Admin Interface**
   - Log in as an administrator
   - Go to Site administration > Plugins > Install plugins
   - Upload the ZIP files for both plugins
   - Follow the installation prompts

3. **Alternative Installation (Manual)**
   - Extract the plugin files
   - Place `block_duitku_membership` into the `/blocks/` directory
   - Place `enrol_duitku` into the `/enrol/` directory
   - Visit Site administration > Notifications to complete installation

4. **Post-Installation**
   - Configure the Duitku payment gateway settings
   - Configure membership settings
   - Add the membership block to your site

## Configuration

### Duitku Payment Gateway Setup
1. Go to **Site administration > Plugins > Enrolments > Duitku**
2. Configure the following settings:
   - **Merchant Code**: Your Duitku merchant code
   - **API Key**: Your Duitku API key
   - **Environment**: Select "Sandbox" for testing or "Production" for live payments
   - **Expiry Period**: Transaction expiry time in minutes (recommended: 60-1440)

### Membership Settings
1. Within the Duitku enrollment settings, locate the "Membership Settings" section
2. Configure:
   - **Membership Price**: Annual price in IDR
   - **Auto-enroll members**: Enable/disable automatic enrollment in paid courses
   - **Email notifications**: Configure email templates for membership events

### Block Placement
1. Go to your site homepage or dashboard
2. Turn editing on
3. Click "Add a block" and select "IPARI Membership"
4. Configure the block:
   - Set visibility and location preferences
   - Customize the title (optional)
   - Save changes

## Managing Memberships

### Viewing Active Memberships
1. Go to **Site administration > Plugins > Enrolments > Duitku > Membership Dashboard**
2. The dashboard displays:
   - Total active memberships
   - Memberships expiring soon
   - New memberships this month
   - Total revenue from memberships

### Member Management
1. Navigate to the "Members" tab in the Membership Dashboard
2. Here you can:
   - View all members and their expiry dates
   - Filter by status (active, expired)
   - Search for specific members
   - Export the list as CSV

### Extending Memberships
1. Find the member in the member list
2. Click the "Extend" button next to their name
3. Enter the number of days to extend (default: 365)
4. Click "Confirm"
5. The system will update the expiry date and maintain the member role

### Revoking Memberships
1. Find the member in the member list
2. Click the "Revoke" button
3. Confirm the action
4. The system will:
   - Mark the membership as expired
   - Remove the membership role
   - Log the action

## Monitoring and Reporting

### Transaction Logs
1. Go to **Site administration > Plugins > Enrolments > Duitku > Transactions**
2. Filter by:
   - Transaction type: Select "Membership"
   - Date range
   - Payment status
3. Review transaction details including:
   - User information
   - Payment amount and status
   - Transaction references
   - Timestamps

### Membership Reports
1. Go to the "Reports" tab in the Membership Dashboard
2. Available reports include:
   - New memberships over time
   - Renewals vs. new subscriptions
   - Revenue breakdown by period
   - Membership expiration forecast

### System Logs
For more detailed troubleshooting:
1. Go to **Site administration > Reports > Logs**
2. Filter for "enrol_duitku" or "block_duitku_membership" components
3. Review system interactions with the plugins

## Configuring Notifications

### Email Notifications
1. Go to **Site administration > Plugins > Enrolments > Duitku**
2. Scroll to the email template sections:
   - **Admin Email**: Notification when users subscribe
   - **Student Email**: Welcome/confirmation email to new members
3. Customize templates using available variables:
   - `$studentUsername`: Student's username
   - `$amount`: Payment amount
   - `$adminUsername`: Admin username

### Expiry Notifications
1. Set up automatic notifications for users with expiring memberships:
   - Go to **Site administration > Plugins > Enrolments > Duitku > Membership Settings**
   - Configure "Expiry notification period" (days before expiry)
   - Enable or disable notifications

## Troubleshooting

### Common Issues and Solutions

#### Payment Issues
- **Issue**: Payments not being processed
  - **Solution**: Verify Duitku API credentials and environment settings
  - **Solution**: Check API connectivity by reviewing transaction logs

#### Enrollment Problems
- **Issue**: Members not auto-enrolled in courses
  - **Solution**: Check if auto-enrollment is enabled
  - **Solution**: Run manual sync via "Sync All Members" button
  - **Solution**: Verify course costs are set properly

#### Role Assignment
- **Issue**: Members don't have proper permissions
  - **Solution**: Verify the "Penyuluh Agama" role exists and has proper capabilities
  - **Solution**: Check role assignment in the user's profile

#### Display Issues
- **Issue**: Block not showing correct information
  - **Solution**: Clear Moodle caches
  - **Solution**: Check language settings and ensure language files are complete
  - **Solution**: Verify block visibility settings

### Getting Support
If problems persist:
1. Check the detailed logs in **Site administration > Reports > Logs**
2. Consult the developer documentation for additional details
3. Contact the plugin developer or Duitku support for payment-specific issues

## Best Practices

### Pricing Strategy
- Set membership price appropriately considering:
  - Individual course prices
  - Value of full access
  - Market positioning
  - Competitor pricing

### Membership Management
- Regularly review expiring memberships
- Send reminders to members before expiry
- Consider promotional campaigns for renewals
- Monitor auto-enrollment to ensure it's working correctly

### Security
- Regularly update the plugin to the latest version
- Use HTTPS for all payment-related communications
- Review transaction logs for any suspicious activity
- Maintain secure API keys and credentials

## Customizing the Membership Experience

### Custom Branding
1. The membership block and pages can be customized through:
   - Site themes
   - Custom CSS
   - Modifying language strings

### Adding Member Benefits
1. Update language strings in `lang/en/block_duitku_membership.php`
2. Modify the `membership_benefits` section to reflect your specific offerings
3. Consider creating a dedicated membership benefits page and linking to it

### Restricted Content
Use Moodle's native restrictions and the membership role to:
1. Create members-only course categories
2. Add members-only resources within courses
3. Create member discussion forums

---
*This documentation is for administrators managing the Duitku Membership plugin. For developer or user documentation, please refer to the respective guides.*
