<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * =====================================================
 * CREATE AUDIT TRAIL TABLE
 * =====================================================
 * 
 * Purpose: Comprehensive logging of all system changes
 * Tracks who did what, when, and why
 * 
 * Critical for:
 * - Compliance and regulatory requirements
 * - Security auditing
 * - Change tracking and accountability
 * - Dispute resolution
 * - System integrity verification
 * 
 * RUBRIC: Audit Trail for all transactions (Midterm)
 * "Audit trail for all transactions to ensure 
 *  accountability"
 * =====================================================
 */
class CreateAuditTrailTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'User who performed the action'
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'comment' => 'Username for quick reference'
            ],
            'module' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'comment' => 'Module affected (inventory, orders, payments, etc)'
            ],
            'controller' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'comment' => 'Controller name'
            ],
            'action' => [
                'type' => 'ENUM',
                'constraint' => ['CREATE', 'READ', 'UPDATE', 'DELETE', 'APPROVE', 'REJECT', 'TRANSFER', 'LOGIN', 'LOGOUT', 'EXPORT', 'IMPORT', 'RECONCILE', 'VERIFY', 'AUTHORIZE'],
                'comment' => 'Type of action performed'
            ],
            'table_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'comment' => 'Database table affected'
            ],
            'record_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'ID of record affected'
            ],
            'old_values' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Original values before change'
            ],
            'new_values' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'New values after change'
            ],
            'changes_summary' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Human-readable summary of changes'
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Additional context or description'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Success', 'Failure', 'Pending', 'Cancelled'],
                'default' => 'Success',
                'comment' => 'Result of the action'
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'IP address of request'
            ],
            'user_agent' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Browser/client user agent'
            ],
            'request_method' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'comment' => 'HTTP method (GET, POST, etc)'
            ],
            'endpoint' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'API endpoint or page accessed'
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Error message if action failed'
            ],
            'execution_time_ms' => [
                'type' => 'INT',
                'null' => true,
                'comment' => 'Action execution time in milliseconds'
            ],
            'timestamp' => [
                'type' => 'DATETIME',
                'comment' => 'When action occurred'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('module');
        $this->forge->addKey('action');
        $this->forge->addKey('table_name');
        $this->forge->addKey('record_id');
        $this->forge->addKey('status');
        $this->forge->addKey('timestamp');
        $this->forge->addKey('ip_address');
        $this->forge->addKey(['timestamp', 'user_id'], false, false, 'idx_timestamp_user');
        
        // Foreign key
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('audit_trail');
    }

    public function down()
    {
        $this->forge->dropTable('audit_trail');
    }
}
