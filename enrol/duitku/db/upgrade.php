<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Upgrade script for enrol_duitku
 * @package   enrol_duitku
 * @copyright 2025 IPARI <admin@example.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade function for the duitku enrolment plugin.
 *
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_enrol_duitku_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2025051400) {
        // Define table enrol_duitku_membership.
        $table = new xmldb_table('enrol_duitku_membership');

        // Adding fields to table enrol_duitku_membership.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('payment_type', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('payment_status', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('purchase_time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('expiry_time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('processed', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table enrol_duitku_membership.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Adding indexes to table enrol_duitku_membership.
        $table->add_index('userid-expiry', XMLDB_INDEX_NOTUNIQUE, ['userid', 'expiry_time']);

        // Conditionally launch create table for enrol_duitku_membership.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table enrol_duitku_transactions.
        $table = new xmldb_table('enrol_duitku_transactions');

        // Adding fields to table enrol_duitku_transactions.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('reference', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('payment_type', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('payment_status', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('amount', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('payment_time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table enrol_duitku_transactions.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Adding indexes to table enrol_duitku_transactions.
        $table->add_index('reference', XMLDB_INDEX_UNIQUE, ['reference']);

        // Conditionally launch create table for enrol_duitku_transactions.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Add membership price setting
        $setting = new stdClass();
        $setting->plugin = 'enrol_duitku';
        $setting->name = 'membership_price';
        $setting->value = '200000';
        
        if (!$DB->record_exists('config_plugins', ['plugin' => 'enrol_duitku', 'name' => 'membership_price'])) {
            $DB->insert_record('config_plugins', $setting);
        }

        // Duitku savepoint reached.
        upgrade_plugin_savepoint(true, 2025051400, 'enrol', 'duitku');
    }

    if ($oldversion < 2025051500) {
        // Define field merchant_order_id for table enrol_duitku_transactions
        $table = new xmldb_table('enrol_duitku_transactions');
        $field = new xmldb_field('merchant_order_id', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        
        // Conditionally add field merchant_order_id
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Add index for merchant_order_id
        $index = new xmldb_index('merchant_order_id', XMLDB_INDEX_NOTUNIQUE, ['merchant_order_id']);
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }
        
        // Define table enrol_duitku_log
        $table = new xmldb_table('enrol_duitku_log');
        
        // Add fields to table enrol_duitku_log
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('timestamp', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('log_type', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
        $table->add_field('data', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('status', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        
        // Adding keys to table enrol_duitku_log
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        
        // Adding indexes to table enrol_duitku_log
        $table->add_index('timestamp', XMLDB_INDEX_NOTUNIQUE, ['timestamp']);
        $table->add_index('log_type', XMLDB_INDEX_NOTUNIQUE, ['log_type']);
        
        // Conditionally launch create table for enrol_duitku_log
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        
        // Duitku savepoint reached
        upgrade_plugin_savepoint(true, 2025051500, 'enrol', 'duitku');
    }

    return true;
}
