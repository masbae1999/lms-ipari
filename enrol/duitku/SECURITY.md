# Duitku Plugin Security Documentation

## Security Protocols

### 1. Guest Access Handling

-   All requests from guest users are immediately terminated
-   Guest user sessions are destroyed before redirecting to login
-   No enrollment process is allowed for guest users
-   Guest users cannot access paid course content

### 2. Context Validation

-   Double validation of course context in all endpoints
-   Uses CONTEXT_COURSE for all course-related operations
-   Ensures proper context before allowing any access

### 3. Session Management

-   All payment callbacks validate user session
-   Guest and non-logged-in users are blocked from making payments
-   Session tokens are validated for all course access attempts

### 4. Database Security

-   Regular cleanup of guest user records
-   Purging of expired memberships
-   Validation of user role before auto-enrollment

## Security Implementation

### Files Modified

1. `lib.php` - Added guest access blocking in `allow_enrol`
2. `duitku_helper.php` - Added `validate_access` method
3. `membership_callback.php` - Added session validation
4. `auto_enroll_members.php` - Fixed guest user check
5. `locallib.php` - Added course access control

### New Files

1. `cli/cleanup_guests.php` - Database cleanup script
2. `tests/security_test.php` - Security test script

## Testing Scenarios

### 1. Guest Access Test

-   Access paid course via URL → Should redirect to login
-   Try to enroll via API → Should return error 403
-   Access resources directly → Should return error 403

### 2. Penetration Test

-   Use Burp Suite to intercept requests
-   Try to bypass redirect with manual URL entry
-   Verify response headers include:
    -   `X-Content-Type-Options: nosniff`
    -   `Strict-Transport-Security: max-age=63072000`

## How to Use This Documentation

### For Administrators

1. Run the cleanup script to remove existing guest user records:

    ```
    php enrol/duitku/cli/cleanup_guests.php --verbose
    ```

2. Run the security test script to verify all fixes are working:

    ```
    php enrol/duitku/tests/security_test.php
    ```

3. Monitor the system logs for any unauthorized access attempts

### For Developers

1. Always use `duitku_helper::validate_access()` in any new file that handles course access
2. Check `isguestuser()` before allowing any enrollment or payment actions
3. Use the `locallib.php` `check_course_access()` function for course content access control

## Database Migration

The following SQL was executed to remove guest users from enrollment records:

```sql
-- Delete guest user from enrol_duitku_membership
DELETE FROM enrol_duitku_membership
WHERE userid IN (SELECT id FROM mdl_user WHERE username = 'guest');

-- Delete guest enrollments in Duitku courses
DELETE FROM user_enrolments
WHERE enrolid IN (
    SELECT id FROM enrol WHERE enrol = 'duitku'
)
AND userid IN (SELECT id FROM user WHERE username = 'guest');

-- Delete guest role assignments in Duitku courses
DELETE FROM role_assignments
WHERE userid IN (SELECT id FROM user WHERE username = 'guest')
AND contextid IN (
    SELECT ctx.id
    FROM context ctx
    JOIN course c ON c.id = ctx.instanceid AND ctx.contextlevel = 50
    JOIN enrol e ON e.courseid = c.id AND e.enrol = 'duitku'
);
```
