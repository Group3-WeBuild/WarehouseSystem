<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * =====================================================
 * CREATE LOT TRACKING TABLE
 * =====================================================
 * 
 * Purpose: Tracks individual lots within batches
 * For fine-grained inventory control and traceability
 * 
 * Relationship:
 * Batch (many items) → Lot (specific unit/subgroup)
 * 
 * Use Cases:
 * - Serial number tracking
 * - Split batch allocation to warehouses
 * - Partial lot transfers
 * - Item-level quality tracking
 * 
 * RUBRIC: Batch and Lot Tracking (Midterm)
 * =====================================================
 */
class CreateLotTrackingTable extends Migration
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
            'batch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Reference to batch_tracking'
            ],
            'lot_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'comment' => 'Unique lot number/serial within batch'
            ],
            'serial_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'unique' => true,
                'comment' => 'Item serial number (if applicable)'
            ],
            'quantity' => [
                'type' => 'INT',
                'default' => 1,
                'comment' => 'Quantity in this lot'
            ],
            'warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Current warehouse location'
            ],
            'rack_location' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Specific location in warehouse'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Available', 'Reserved', 'Sold', 'Transferred', 'Expired', 'Damaged'],
                'default' => 'Available',
                'comment' => 'Current lot status'
            ],
            'is_allocated' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'Whether lot is allocated to an order'
            ],
            'allocated_to_order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Order ID if allocated'
            ],
            'allocated_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When lot was allocated'
            ],
            'shipped_date' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When lot was shipped'
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Additional notes'
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
        $this->forge->addKey('batch_id');
        $this->forge->addKey('lot_number');
        $this->forge->addKey('warehouse_id');
        $this->forge->addKey('status');
        $this->forge->addKey('allocated_to_order_id');
        $this->forge->addKey('created_at');

        $this->forge->createTable('lot_tracking');
    }

    public function down()
    {
        $this->forge->dropTable('lot_tracking');
    }
}
