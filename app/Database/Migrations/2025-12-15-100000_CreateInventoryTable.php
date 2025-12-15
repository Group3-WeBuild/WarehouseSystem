<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'product_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Product name'
            ],
            'sku' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
                'comment' => 'Stock Keeping Unit'
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'comment' => 'Product category'
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Product description'
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Current stock quantity'
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'Unit of measurement (pcs, kg, liters, etc.)'
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '0.00',
                'comment' => 'Price per unit'
            ],
            'reorder_level' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Minimum quantity before reorder alert'
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Storage location/warehouse'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Inactive', 'Discontinued'],
                'default' => 'Active',
                'comment' => 'Item status'
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
        $this->forge->addKey('category');
        $this->forge->addKey('status');
        $this->forge->addKey('location');
        $this->forge->createTable('inventory');
    }

    public function down()
    {
        $this->forge->dropTable('inventory');
    }
}
