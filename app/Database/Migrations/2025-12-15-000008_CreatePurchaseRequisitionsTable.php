<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * =====================================================
 * CREATE PURCHASE REQUISITIONS TABLE
 * =====================================================
 * 
 * Purpose: Tracks purchase requisition requests
 * Part of the procurement workflow
 * 
 * RUBRIC: Procurement Officer Module
 * "Orders materials, ensures suppliers deliver on time,
 *  and coordinates with accounts payable"
 * =====================================================
 */
class CreatePurchaseRequisitionsTable extends Migration
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
            'requisition_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
                'comment' => 'Unique requisition number'
            ],
            'requested_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'User who created requisition'
            ],
            'warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'Target warehouse for items'
            ],
            'priority' => [
                'type' => 'ENUM',
                'constraint' => ['Low', 'Medium', 'High', 'Urgent'],
                'default' => 'Medium'
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Justification for requisition'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Draft', 'Submitted', 'Approved', 'Rejected', 'Ordered', 'Received', 'Cancelled'],
                'default' => 'Draft'
            ],
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'approved_date' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'required_date' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Date items are needed by'
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true
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
        $this->forge->addKey('requisition_number');
        $this->forge->addKey('status');
        $this->forge->addKey('requested_by');
        $this->forge->addKey('warehouse_id');
        
        $this->forge->addForeignKey('requested_by', 'users', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('purchase_requisitions');
    }

    public function down()
    {
        $this->forge->dropTable('purchase_requisitions');
    }
}
