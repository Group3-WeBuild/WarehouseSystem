<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWarehousesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'warehouse_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Name of the warehouse'
            ],
            'warehouse_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
                'comment' => 'Unique warehouse code'
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'City/Region location'
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Full address'
            ],
            'capacity' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Warehouse capacity in square meters'
            ],
            'manager_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Warehouse manager user ID'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Inactive', 'Maintenance'],
                'default' => 'Active',
                'comment' => 'Warehouse operational status'
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
        $this->forge->addKey('status');
        $this->forge->createTable('warehouses');
    }

    public function down()
    {
        $this->forge->dropTable('warehouses');
    }
}
