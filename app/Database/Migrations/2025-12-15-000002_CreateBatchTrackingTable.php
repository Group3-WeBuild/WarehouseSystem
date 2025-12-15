<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * =====================================================
 * CREATE BATCH TRACKING TABLE
 * =====================================================
 * 
 * Purpose: Tracks inventory batches for quality control
 * Supports batch traceability and expiration management
 * 
 * Critical for:
 * - Expiration date tracking
 * - Batch recall procedures
 * - Quality control records
 * - Lot traceability
 * - Warehouse operations
 * 
 * RUBRIC: Batch and Lot Tracking (Midterm)
 * "Batch and lot tracking for quality control and 
 *  expiration-sensitive items"
 * =====================================================
 */
class CreateBatchTrackingTable extends Migration
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
            'inventory_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Reference to inventory item'
            ],
            'batch_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
                'comment' => 'Unique batch identifier from supplier'
            ],
            'reference_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'PO or receipt number'
            ],
            'manufacture_date' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Item manufacture/production date'
            ],
            'expiry_date' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Item expiration date (if applicable)'
            ],
            'days_until_expiry' => [
                'type' => 'INT',
                'null' => true,
                'comment' => 'Calculated days remaining until expiry'
            ],
            'quantity_received' => [
                'type' => 'INT',
                'comment' => 'Total quantity for this batch'
            ],
            'quantity_available' => [
                'type' => 'INT',
                'comment' => 'Quantity not yet used/sold'
            ],
            'quantity_used' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Quantity consumed/sold from batch'
            ],
            'supplier_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Vendor/supplier reference'
            ],
            'warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Primary storage warehouse'
            ],
            'quality_status' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Quarantine', 'Approved', 'Rejected', 'Expired', 'Damaged'],
                'default' => 'Active',
                'comment' => 'Quality control status'
            ],
            'quality_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Quality inspection notes'
            ],
            'inspected_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Auditor who inspected batch'
            ],
            'inspection_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When batch was inspected'
            ],
            'received_date' => [
                'type' => 'DATETIME',
                'comment' => 'When batch was received'
            ],
            'received_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'User who received batch'
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
        $this->forge->addKey('inventory_id');
        $this->forge->addKey('expiry_date');
        $this->forge->addKey('quality_status');
        $this->forge->addKey('warehouse_id');
        $this->forge->addKey('supplier_id');
        $this->forge->addKey('received_date');

        $this->forge->createTable('batch_tracking');
    }

    public function down()
    {
        $this->forge->dropTable('batch_tracking');
    }
}
