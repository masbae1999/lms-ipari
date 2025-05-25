# Duitku Plugin Security Fix Documentation

## Critical Security Vulnerability: Guest Access to Paid Courses

### Issue Summary

A critical security vulnerability was discovered in the Duitku enrollment plugin allowing guest users to access paid courses without authentication or payment. This poses significant risks including:

1. Unauthorized access to premium content
2. Revenue loss from bypassed payment requirements
3. Compromise of course enrollment integrity
4. Potential data privacy concerns

### Root Causes

1. Missing authentication validation in enrollment process
2. Improper session handling
3. Insufficient context validation
4. Guest users incorrectly being added to enrollment tables

## Implemented Security Fixes

### 1. Guest Access Blocking in Enrollment Logic

The core of the fix involves modifying the `allow_enrol()` method in the Duitku plugin to properly block guest users while allowing legitimate payment callbacks:

```php
public function allow_enrol(stdClass $instance) {
    global $USER;

    // Skip the guest check for special cases
    if (defined('CLI_SCRIPT') && CLI_SCRIPT) {
        return parent::allow_enrol($instance);
    }

    // Block guest users
    if (isguestuser() || !isloggedin()) {
        // Special exception for payment callbacks
        if (isset($_SERVER['HTTP_X_DUITKU_CALLBACK']) ||
            strpos($_SERVER['PHP_SELF'], 'membership_callback.php') !== false) {
            return parent::allow_enrol($instance);
        }
        throw new moodle_exception('noguestaccess', 'enrol_duitku');
    }

    return parent::allow_enrol($instance);
}
```

### 2. User Access Validation

Added a new validation method to verify user authentication status:

```php
public static function validate_access() {
    global $USER, $PAGE;

    if (isguestuser() || !isloggedin()) {
        // Redirect to login with session termination
        require_logout();
        redirect(new \moodle_url('/login/index.php'));
    }

    // Only validate course context when we're in a course
    if (isset($PAGE->context) && $PAGE->context->contextlevel == CONTEXT_COURSE) {
        return true;
    }

    return true;
}
```

### 3. Course Access Control

Created a robust course access control function:

```php
function check_course_access($courseid) {
    global $USER, $DB;

    if (isguestuser() || !isloggedin()) {
        send_forbidden_response();
        return false;
    }

    // Check for admin/teacher access
    if (is_siteadmin() || has_capability('moodle/course:manageactivities',
        context_course::instance($courseid))) {
        return true;
    }

    // Check membership with error handling
    try {
        // Membership validation logic with fallback
        // ...
    } catch (Exception $e) {
        // Safe error handling
        // ...
    }

    return is_enrolled(context_course::instance($courseid));
}
```

### 4. Database Cleanup

Created a utility script (`cleanup_guests.php`) to remove guest users from enrollment tables:

```sql
-- Remove guest users from membership table
DELETE FROM enrol_duitku_membership
WHERE userid IN (SELECT id FROM user WHERE username = 'guest');

-- Remove guest enrollments in paid courses
DELETE FROM user_enrolments
WHERE enrolid IN (SELECT id FROM enrol WHERE enrol = 'duitku')
AND userid IN (SELECT id FROM user WHERE username = 'guest');
```

### 5. Callback Security

Modified payment callback handler to allow external payment processors to communicate with the system without requiring a logged-in session.

## Installation & Verification

1. **Update Plugin Files**:
   Replace the following files with the fixed versions:

    - `lib.php`
    - `classes/duitku_helper.php`
    - `membership_callback.php`
    - `locallib.php`

2. **Database Cleanup**:
   Run the cleanup script to remove existing guest user enrollments:

    ```bash
    php enrol/duitku/cli/cleanup_guests.php --verbose
    ```

3. **Verify Security**:
   Run the verification script to ensure all security fixes are properly implemented:

    ```bash
    php enrol/duitku/cli/verify_security.php --verbose
    ```

4. **Purge Caches**:
    ```bash
    php admin/cli/purge_caches.php
    ```

## Troubleshooting

If you encounter HTTP 500 errors:

1. **Check Logs**:

    ```bash
    tail -f /var/log/apache2/error.log
    ```

2. **Enable Debugging**:
   In config.php:

    ```php
    $CFG->debug = E_ALL;
    $CFG->debugdisplay = 1;
    ```

3. **Common Solutions**:
    - If admin pages fail: The context validation may be too strict
    - If payment callbacks fail: External services cannot authenticate
    - If auto-enrollment fails: There may be issues with the user detection

## Testing Scenarios

1. **Guest Access Test**:

    - Log out completely
    - Try to access a paid course URL directly
    - Verify redirect to login page

2. **Payment Callback Test**:

    - Use a tool like Postman to simulate a payment callback
    - Verify the callback is processed without session errors

3. **Admin Access Test**:
    - Log in as admin
    - Verify access to all courses and admin pages

## Security Recommendations

1. **Regular Monitoring**:

    - Monitor logs for unauthorized access attempts
    - Regularly check for guest enrollments in paid courses

2. **Additional Hardening**:
    - Consider implementing IP-based restrictions for payment callbacks
    - Add rate limiting for enrollment attempts
    - Implement additional logging for security events

---

IPARI Security Team
May 25, 2025
