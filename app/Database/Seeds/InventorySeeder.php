<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * =====================================================
 * INVENTORY SEEDER
 * =====================================================
 * 
 * Purpose: Seeds inventory items with realistic construction materials
 * =====================================================
 */
class InventorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Construction Materials
            [
                'product_name' => 'Portland Cement Type 1',
                'sku' => 'CEM-001',
                'category' => 'Cement & Concrete',
                'description' => '40kg bag of Portland cement, Type 1 for general construction',
                'quantity' => 1500,
                'unit' => 'bags',
                'unit_price' => 250.00,
                'reorder_level' => 300,
                'location' => 'WH-MNL-001-A1',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Steel Rebar 10mm Grade 40',
                'sku' => 'STL-RB-010',
                'category' => 'Steel & Metal',
                'description' => '6-meter steel reinforcement bar, 10mm diameter, Grade 40',
                'quantity' => 2500,
                'unit' => 'pieces',
                'unit_price' => 180.00,
                'reorder_level' => 500,
                'location' => 'WH-MNL-001-B2',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Steel Rebar 12mm Grade 40',
                'sku' => 'STL-RB-012',
                'category' => 'Steel & Metal',
                'description' => '6-meter steel reinforcement bar, 12mm diameter, Grade 40',
                'quantity' => 2000,
                'unit' => 'pieces',
                'unit_price' => 220.00,
                'reorder_level' => 400,
                'location' => 'WH-MNL-001-B3',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Marine Plywood 4x8 18mm',
                'sku' => 'PLY-MAR-418',
                'category' => 'Wood & Plywood',
                'description' => '4x8 feet marine grade plywood, 18mm thickness',
                'quantity' => 800,
                'unit' => 'sheets',
                'unit_price' => 850.00,
                'reorder_level' => 150,
                'location' => 'WH-MNL-001-C1',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Ordinary Plywood 4x8 12mm',
                'sku' => 'PLY-ORD-412',
                'category' => 'Wood & Plywood',
                'description' => '4x8 feet ordinary plywood, 12mm thickness',
                'quantity' => 1200,
                'unit' => 'sheets',
                'unit_price' => 450.00,
                'reorder_level' => 200,
                'location' => 'WH-MNL-001-C2',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Paint & Finishing
            [
                'product_name' => 'Latex Paint White 4L',
                'sku' => 'PNT-LAT-WHT4',
                'category' => 'Paint & Coatings',
                'description' => '4-liter can of white latex paint, interior/exterior',
                'quantity' => 500,
                'unit' => 'cans',
                'unit_price' => 480.00,
                'reorder_level' => 100,
                'location' => 'WH-QC-002-A5',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Latex Paint Beige 4L',
                'sku' => 'PNT-LAT-BEI4',
                'category' => 'Paint & Coatings',
                'description' => '4-liter can of beige latex paint, interior/exterior',
                'quantity' => 300,
                'unit' => 'cans',
                'unit_price' => 480.00,
                'reorder_level' => 80,
                'location' => 'WH-QC-002-A5',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Enamel Paint Grey 1L',
                'sku' => 'PNT-ENM-GRY1',
                'category' => 'Paint & Coatings',
                'description' => '1-liter can of grey enamel paint, quick-dry',
                'quantity' => 400,
                'unit' => 'cans',
                'unit_price' => 180.00,
                'reorder_level' => 100,
                'location' => 'WH-QC-002-A6',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Hardware & Fasteners
            [
                'product_name' => 'Common Wire Nails 2-inch',
                'sku' => 'HW-NAIL-2IN',
                'category' => 'Hardware & Fasteners',
                'description' => '2-inch common wire nails, 1kg pack',
                'quantity' => 5000,
                'unit' => 'kg',
                'unit_price' => 85.00,
                'reorder_level' => 1000,
                'location' => 'WH-MAK-003-D1',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Concrete Screws 3-inch',
                'sku' => 'HW-SCR-CON3',
                'category' => 'Hardware & Fasteners',
                'description' => '3-inch concrete screws with anchors, box of 100',
                'quantity' => 800,
                'unit' => 'boxes',
                'unit_price' => 250.00,
                'reorder_level' => 150,
                'location' => 'WH-MAK-003-D2',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Wood Screws #8 2-inch',
                'sku' => 'HW-SCR-WD82',
                'category' => 'Hardware & Fasteners',
                'description' => '#8 x 2-inch wood screws, box of 100',
                'quantity' => 1200,
                'unit' => 'boxes',
                'unit_price' => 120.00,
                'reorder_level' => 250,
                'location' => 'WH-MAK-003-D2',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Electrical & Plumbing
            [
                'product_name' => 'PVC Pipe 1/2 inch 3m',
                'sku' => 'PLB-PVC-05-3M',
                'category' => 'Plumbing Supplies',
                'description' => '1/2 inch PVC pipe, 3 meters length, Schedule 40',
                'quantity' => 600,
                'unit' => 'pieces',
                'unit_price' => 95.00,
                'reorder_level' => 150,
                'location' => 'WH-QC-002-E1',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'PVC Pipe 3/4 inch 3m',
                'sku' => 'PLB-PVC-75-3M',
                'category' => 'Plumbing Supplies',
                'description' => '3/4 inch PVC pipe, 3 meters length, Schedule 40',
                'quantity' => 500,
                'unit' => 'pieces',
                'unit_price' => 125.00,
                'reorder_level' => 120,
                'location' => 'WH-QC-002-E1',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Electrical Wire THHN 2.0mm',
                'sku' => 'ELC-THHN-20',
                'category' => 'Electrical Supplies',
                'description' => 'THHN electrical wire 2.0mm, per meter',
                'quantity' => 10000,
                'unit' => 'meters',
                'unit_price' => 18.00,
                'reorder_level' => 2000,
                'location' => 'WH-QC-002-E5',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Circuit Breaker 20A 2-pole',
                'sku' => 'ELC-CB-20A2P',
                'category' => 'Electrical Supplies',
                'description' => '20 Ampere 2-pole circuit breaker',
                'quantity' => 150,
                'unit' => 'pieces',
                'unit_price' => 350.00,
                'reorder_level' => 30,
                'location' => 'WH-QC-002-E6',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Tools & Equipment
            [
                'product_name' => 'Power Drill 13mm Chuck',
                'sku' => 'TLS-DRL-13MM',
                'category' => 'Tools & Equipment',
                'description' => 'Variable speed power drill, 13mm chuck, 550W',
                'quantity' => 25,
                'unit' => 'pieces',
                'unit_price' => 1850.00,
                'reorder_level' => 5,
                'location' => 'WH-MAK-003-F1',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Measuring Tape 5m',
                'sku' => 'TLS-TAPE-5M',
                'category' => 'Tools & Equipment',
                'description' => '5-meter measuring tape with lock',
                'quantity' => 200,
                'unit' => 'pieces',
                'unit_price' => 120.00,
                'reorder_level' => 50,
                'location' => 'WH-MAK-003-F2',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Safety Equipment
            [
                'product_name' => 'Safety Helmet Hard Hat',
                'sku' => 'SFT-HLM-001',
                'category' => 'Safety Equipment',
                'description' => 'Safety hard hat helmet, adjustable, various colors',
                'quantity' => 150,
                'unit' => 'pieces',
                'unit_price' => 180.00,
                'reorder_level' => 30,
                'location' => 'WH-MNL-001-G1',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Safety Gloves Leather',
                'sku' => 'SFT-GLV-LTH',
                'category' => 'Safety Equipment',
                'description' => 'Leather work gloves, heavy duty, per pair',
                'quantity' => 300,
                'unit' => 'pairs',
                'unit_price' => 95.00,
                'reorder_level' => 80,
                'location' => 'WH-MNL-001-G1',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // Low stock items for testing
            [
                'product_name' => 'Concrete Sealer 4L',
                'sku' => 'PNT-SLR-CON4',
                'category' => 'Paint & Coatings',
                'description' => '4-liter concrete sealer, water-based',
                'quantity' => 25,
                'unit' => 'cans',
                'unit_price' => 580.00,
                'reorder_level' => 50,
                'location' => 'WH-QC-002-A7',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        $builder = $this->db->table('inventory');
        
        foreach ($data as $item) {
            $existing = $builder->where('sku', $item['sku'])->get()->getRow();
            if (!$existing) {
                $builder->insert($item);
            }
        }

        echo "Inventory items seeded successfully.\n";
    }
}
