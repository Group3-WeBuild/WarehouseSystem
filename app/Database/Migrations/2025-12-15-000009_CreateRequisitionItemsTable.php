<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * =====================================================
 * CREATE REQUISITION ITEMS TABLE
 * =====================================================
 * 
 * Purpose: Line items for purchase requisitions
 * =====================================================
 */
class CreateRequisitionItemsTable extends Migration
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
            'requisition_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'inventory_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'quantity_requested' => [
                'type' => 'INT',
                'comment' => 'Quantity needed'
            ],
            'quantity_approved' => [
                'type' => 'INT',
                'null' => true,
                'comment' => 'Quantity approved (may differ)'
            ],
            'estimated_unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => [10, 2],
                'null' => true
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('requisition_id');
        $this->forge->addKey('inventory_id');

        $this->forge->createTable('requisition_items');
    }

    public function down()
    {
        $this->forge->dropTable('requisition_items');
    }
}
