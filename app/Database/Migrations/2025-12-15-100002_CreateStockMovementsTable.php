<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockMovementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Reference to inventory item'
            ],
            'warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Reference to warehouse'
            ],
            'movement_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'Type of movement: Stock In, Stock Out, Adjustment, Transfer, Return, Initial Stock'
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Quantity moved'
            ],
            'reference_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Reference document number'
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'User who performed the movement'
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('item_id');
        $this->forge->addKey('warehouse_id');
        $this->forge->addKey('movement_type');
        $this->forge->addKey('created_at');
        $this->forge->createTable('stock_movements');
    }

    public function down()
    {
        $this->forge->dropTable('stock_movements');
    }
}
