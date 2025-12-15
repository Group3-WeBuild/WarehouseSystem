<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * =====================================================
 * CREATE COUNT DETAILS TABLE
 * =====================================================
 * 
 * Purpose: Individual item records for physical counts
 * Records physical count vs system count for each item
 * 
 * Stores:
 * - Each item counted in the session
 * - Physical quantity counted
 * - System quantity
 * - Discrepancy details
 * - Verification status
 * 
 * Linked to: physical_counts table
 * =====================================================
 */
class CreateCountDetailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'count_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Reference to physical_counts'
            ],
            'inventory_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Item being counted'
            ],
            'system_quantity' => [
                'type' => 'INT',
                'comment' => 'Quantity recorded in system'
            ],
            'physical_quantity' => [
                'type' => 'INT',
                'comment' => 'Actual quantity physically counted'
            ],
            'discrepancy' => [
                'type' => 'INT',
                'comment' => 'Difference (positive or negative)'
            ],
            'discrepancy_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => [5, 2],
                'null' => true,
                'comment' => 'Percentage difference'
            ],
            'has_discrepancy' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'Flag if discrepancy exists'
            ],
            'discrepancy_type' => [
                'type' => 'ENUM',
                'constraint' => ['Overage', 'Shortage', 'None'],
                'default' => 'None',
                'comment' => 'Type of discrepancy'
            ],
            'counted_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Staff member who counted'
            ],
            'verified_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Auditor who verified count'
            ],
            'verification_status' => [
                'type' => 'ENUM',
                'constraint' => ['Pending', 'Verified', 'Approved', 'Rejected'],
                'default' => 'Pending',
                'comment' => 'Verification status'
            ],
            'resolution_status' => [
                'type' => 'ENUM',
                'constraint' => ['Not Required', 'Pending', 'In Progress', 'Resolved', 'Waived'],
                'default' => 'Not Required',
                'comment' => 'Status of discrepancy resolution'
            ],
            'investigation_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Notes on discrepancy investigation'
            ],
            'resolution_action' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Action taken to resolve discrepancy'
            ],
            'resolved_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When discrepancy was resolved'
            ],
            'count_timestamp' => [
                'type' => 'DATETIME',
                'comment' => 'When this item was counted'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('count_id');
        $this->forge->addKey('inventory_id');
        $this->forge->addKey('has_discrepancy');
        $this->forge->addKey('verification_status');
        $this->forge->addKey('resolution_status');
        
        // Foreign keys
        $this->forge->addForeignKey('count_id', 'physical_counts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('inventory_id', 'inventory', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('counted_by', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('verified_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('count_details');
    }

    public function down()
    {
        $this->forge->dropTable('count_details');
    }
}
