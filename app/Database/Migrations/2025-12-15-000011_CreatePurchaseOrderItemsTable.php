<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * =====================================================
 * CREATE PURCHASE ORDER ITEMS TABLE
 * =====================================================
 * 
 * Purpose: Line items for purchase orders
 * =====================================================
 */
class CreatePurchaseOrderItemsTable extends Migration
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
            'po_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'inventory_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'quantity_ordered' => [
                'type' => 'INT',
            ],
            'quantity_received' => [
                'type' => 'INT',
                'default' => 0
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => [10, 2],
            ],
            'total_price' => [
                'type' => 'DECIMAL',
                'constraint' => [12, 2],
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Pending', 'Partially Received', 'Received'],
                'default' => 'Pending'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('po_id');
        $this->forge->addKey('inventory_id');
        
        $this->forge->addForeignKey('po_id', 'purchase_orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('inventory_id', 'inventory', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('purchase_order_items');
    }

    public function down()
    {
        $this->forge->dropTable('purchase_order_items');
    }
}
