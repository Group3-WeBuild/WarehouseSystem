<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'order_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
                'comment' => 'Unique order number'
            ],
            'order_type' => [
                'type' => 'ENUM',
                'constraint' => ['Customer', 'Supplier', 'Transfer', 'Internal'],
                'default' => 'Customer',
                'comment' => 'Type of order'
            ],
            'customer_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Customer or supplier name'
            ],
            'customer_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Contact email'
            ],
            'customer_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Contact phone'
            ],
            'items' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON array of order items'
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => '0.00',
                'comment' => 'Total order amount'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Pending', 'Processing', 'Completed', 'Cancelled', 'On Hold'],
                'default' => 'Pending',
                'comment' => 'Order status'
            ],
            'priority' => [
                'type' => 'ENUM',
                'constraint' => ['Low', 'Normal', 'High', 'Urgent'],
                'default' => 'Normal',
                'comment' => 'Order priority'
            ],
            'delivery_address' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Delivery address'
            ],
            'delivery_date' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Expected delivery date'
            ],
            'processed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'User who processed the order'
            ],
            'processed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When order was processed'
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When order was completed'
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Order notes'
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
        $this->forge->addKey('order_type');
        $this->forge->addKey('created_at');
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
