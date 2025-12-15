<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * =====================================================
 * CREATE ORDER ITEMS TABLE
 * =====================================================
 * 
 * Purpose: Normalized order line items (replaces JSON in orders table)
 * This ensures proper 3NF normalization
 * =====================================================
 */
class CreateOrderItemsTable extends Migration
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
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Reference to orders table'
            ],
            'inventory_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Reference to inventory table'
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Quantity ordered'
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'Price per unit at time of order'
            ],
            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'comment' => 'Line item total (quantity * unit_price)'
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Line item specific notes'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('order_id');
        $this->forge->addKey('inventory_id');
        
        $this->forge->createTable('order_items');
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
    }
}
