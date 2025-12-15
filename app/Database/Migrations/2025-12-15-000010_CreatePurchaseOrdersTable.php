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
                'null' => true,
                'comment' => 'Source requisition'
            ],
            'vendor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Supplier'
            ],
            'warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11,
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
        $this->forge->addKey('vendor_id');
        $this->forge->addKey('status');
        $this->forge->addKey('order_date');

        $this->forge->createTable('purchase_orders');
    }

    public function down()
    {
        $this->forge->dropTable('purchase_orders');
    }
}
