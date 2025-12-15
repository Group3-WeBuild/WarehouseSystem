<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * =====================================================
 * ADD BARCODE AND QR CODE FIELDS TO INVENTORY
 * =====================================================
 * 
 * Purpose: Add barcode/QR code scanning capability
 * Supports fast and error-free material logging
 * 
 * RUBRIC: Barcode/QR Code Functionality (Midterm)
 * "Barcode or QR code scanning for fast and 
 *  error-free logging of materials"
 * =====================================================
 */
class AddBarcodeFieldsToInventory extends Migration
{
    public function up()
    {
        // Add barcode-related fields to inventory table
        $this->forge->addColumn('inventory', [
            'barcode_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'unique' => true,
                'after' => 'sku',
                'comment' => 'Barcode number (EAN/UPC)'
            ],
            'qr_code_data' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
                'after' => 'barcode_number',
                'comment' => 'QR code encoded data'
            ],
            'barcode_generated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'qr_code_data',
                'comment' => 'When barcode was generated'
            ],
            'barcode_enabled' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'after' => 'barcode_generated_at',
                'comment' => 'Whether barcode scanning is enabled'
            ],
        ]);

        // Add index for barcode lookup
        $this->forge->addKey('barcode_number', false, false, 'idx_barcode', 'inventory');
    }

    public function down()
    {
        // Remove the added columns
        $this->forge->dropColumn('inventory', ['barcode_number', 'qr_code_data', 'barcode_generated_at', 'barcode_enabled']);
    }
}
