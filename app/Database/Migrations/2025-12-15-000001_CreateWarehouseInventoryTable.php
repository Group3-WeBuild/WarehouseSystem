<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * =====================================================
 * CREATE WAREHOUSE INVENTORY TABLE
 * =====================================================
 * 
 * Purpose: Links inventory items to specific warehouses
 * Tracks quantity of each item per warehouse location
 * 
 * This table is CRITICAL for:
 * - Multi-warehouse inventory tracking
 * - Warehouse-specific stock levels
 * - Inventory transfer operations
 * - Warehouse capacity management
 * - Stock location reporting
 * 
 * RUBRIC: Multi-Warehouse Management (Midterm)
 * "Users can view/manage stock by warehouse"
 * =====================================================
 */
class CreateWarehouseInventoryTable extends Migration
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
            'warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Reference to warehouses table'
            ],
            'inventory_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Reference to inventory table'
            ],
            'quantity' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Current quantity at this warehouse'
            ],
            'rack_location' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Specific rack/location within warehouse'
            ],
            'bin_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Bin/shelf identifier'
            ],
            'reserved_quantity' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Quantity reserved for pending orders'
            ],
            'available_quantity' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Quantity available for new orders (quantity - reserved)'
            ],
            'last_counted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Last physical inventory count date'
            ],
            'last_adjusted_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'User who last adjusted quantity'
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
        $this->forge->addUniqueKey(['warehouse_id', 'inventory_id'], 'unique_warehouse_inventory');
        $this->forge->addKey('warehouse_id');
        $this->forge->addKey('inventory_id');
        $this->forge->addKey('quantity');
        $this->forge->addKey('available_quantity');
        $this->forge->addKey('last_counted_at');

        $this->forge->createTable('warehouse_inventory');
    }

    public function down()
    {
        $this->forge->dropTable('warehouse_inventory');
    }
}
