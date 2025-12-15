<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * =====================================================
 * CREATE PURCHASE ORDERS TABLE
 * =====================================================
 * 
 * Purpose: Tracks purchase orders to vendors
 * Links requisitions to vendor invoices
 * =====================================================
 */
class CreatePurchaseOrdersTable extends Migration
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
            'po_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
                'comment' => 'Unique purchase order number'
            ],
            'requisition_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Source requisition'
            ],
            'vendor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Supplier'
            ],
            'warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Delivery warehouse'
            ],
            'order_date' => [
                'type' => 'DATE',
                'comment' => 'Date PO was placed'
            ],
            'expected_delivery_date' => [
                'type' => 'DATE',
                'null' => true
            ],
            'actual_delivery_date' => [
                'type' => 'DATE',
                'null' => true
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => [12, 2],
                'default' => 0
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Draft', 'Sent', 'Confirmed', 'Shipped', 'Partially Received', 'Received', 'Cancelled'],
                'default' => 'Draft'
            ],
            'payment_terms' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'shipping_address' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->addKey('po_number');
        $this->forge->addKey('vendor_id');
        $this->forge->addKey('status');
        $this->forge->addKey('order_date');
        
        $this->forge->addForeignKey('requisition_id', 'purchase_requisitions', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('vendor_id', 'vendors', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'RESTRICT', 'CASCADE');

        $this->forge->createTable('purchase_orders');
    }

    public function down()
    {
        $this->forge->dropTable('purchase_orders');
    }
}
