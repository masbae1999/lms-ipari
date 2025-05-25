# Duitku Security Fix Instructions

## 1. Background

We have fixed a critical security issue where guest users could access paid courses through the Duitku enrollment method. The issue allowed guests to:

-   Access paid courses via direct URL
-   Be registered as enrolled users in paid courses
-   Bypass the plugin's user validation

## 2. Implemented Fixes

The following security enhancements have been implemented:

1. **Enhanced Access Control**:

    - Added guest access prevention in the enrollment plugin
    - Fixed validation in course access methods
    - Added checks in auto-enrollment tasks

2. **Callback Security**:

    - Improved payment callback handling while maintaining compatibility with external services
    - Added proper HTTP response codes

3. **Database Cleanup**:
    - Created utility to remove guest users from enrollment tables
    - Fixed guest access to membership data

## 3. Testing the Fix

### 3.1 Running the Database Cleanup

```bash
# Run this from your Moodle root directory
php enrol/duitku/cli/cleanup_guests.php --verbose
```

This script:

-   Finds and removes guest users from the membership table
-   Removes guest enrollments in paid courses
-   Cleans up related role assignments

### 3.2 Testing Guest Access

1. Log out of your Moodle site
2. Try to access a paid course directly
3. You should be redirected to the login page

### 3.3 Testing Payment Callbacks

1. The payment callbacks should still work normally
2. The system now properly handles external requests

## 4. Troubleshooting

If you encounter HTTP 500 errors:

1. **Check your web server error logs**:

    ```
    tail -f /var/log/apache2/error.log
    ```

2. **Enable debugging in Moodle**:
   In config.php, set:

    ```php
    $CFG->debug = E_ALL;
    $CFG->debugdisplay = 1;
    ```

3. **Common issues and solutions**:

    a. If admin pages show 500 errors:

    - The context validation is too strict
    - We've fixed this by making `validate_access()` only check context when in a course

    b. If payment callbacks fail:

    - External services can't authenticate
    - We've fixed this by allowing callbacks to bypass login checks

## 5. Contact

If you encounter any issues with this security fix, please contact:

IPARI Technical Support
Email: support@ipari.org
