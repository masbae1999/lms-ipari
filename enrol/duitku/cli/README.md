# Duitku Membership CLI Tools

This directory contains command-line tools for managing the Duitku membership plugin.

## verify_membership_cli.php

A comprehensive tool to verify, report on, and fix membership status issues.

### Usage

```bash
php verify_membership_cli.php [options]
```

### Options

- `-h, --help`: Display help information
- `-l, --list`: List all members and their status
- `-v, --verify`: Verify membership status without fixing
- `-f, --fix`: Fix inconsistencies in membership status
- `-r, --reports`: Display membership reports
- `-u, --user=INT`: Target specific user ID
- `-V, --verbose`: Verbose output

### Examples

```bash
# Generate membership reports
php verify_membership_cli.php --reports

# List all members
php verify_membership_cli.php --list

# Verify all memberships with detailed output
php verify_membership_cli.php --verify --verbose

# Fix membership issues for a specific user
php verify_membership_cli.php --fix --user=123
```

### Common Use Cases

1. **Regular Maintenance**: Run `--verify` and `--reports` weekly to monitor the system.
2. **After Payment Issues**: Run `--fix` for specific users when they report membership problems.
3. **Bulk Fixes**: Run `--fix` on all users after system upgrades or migrations.

### Troubleshooting

If the script encounters errors:

1. Check PHP error logs
2. Verify that the database tables exist (enrol_duitku_membership, enrol_duitku_transactions, enrol_duitku_log)
3. Run with `--verbose` for detailed output

---

Document created: May 15, 2025
