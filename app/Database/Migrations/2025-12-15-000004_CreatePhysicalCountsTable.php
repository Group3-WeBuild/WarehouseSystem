<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * =====================================================
 * CREATE PHYSICAL COUNTS TABLE
 * =====================================================
 * 
 * Purpose: Tracks physical inventory count sessions
 * Supports reconciliation between physical and system counts
 * 
 * Critical for:
 * - Inventory audits
 * - Discrepancy identification
 * - System accuracy verification
 * - Audit trail documentation
 * 
 * RUBRIC: Inventory Auditor Module (Midterm)
 * "Inventory Auditor: Conducts regular checks and 
 *  reconciliations of physical vs. system records"
 * =====================================================
 */
class CreatePhysicalCountsTable extends Migration
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
            'count_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
                'comment' => 'Reference number for this count'
            ],
            'warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Warehouse being counted'
            ],
            'initiated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'User who initiated the count'
            ],
            'count_type' => [
                'type' => 'ENUM',
                'constraint' => ['Full Count', 'Partial Count', 'Cycle Count', 'Recount'],
                'default' => 'Full Count',
                'comment' => 'Type of inventory count'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['In Progress', 'Completed', 'Discrepancies', 'Verified', 'Closed'],
                'default' => 'In Progress',
                'comment' => 'Current status of count'
            ],
            'count_start_date' => [
                'type' => 'DATETIME',
                'comment' => 'When count started'
            ],
            'count_end_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When count completed'
            ],
            'total_items_counted' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Total number of item lines counted'
            ],
            'total_discrepancies' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Number of items with discrepancies'
            ],
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Supervisor/Manager who approved results'
            ],
            'approved_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When count was approved'
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'General notes about the count'
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
        $this->forge->addKey('count_number');
        $this->forge->addKey('warehouse_id');
        $this->forge->addKey('status');
        $this->forge->addKey('count_start_date');
        
        // Foreign keys
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('initiated_by', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('physical_counts');
    }

    public function down()
    {
        $this->forge->dropTable('physical_counts');
    }
}
