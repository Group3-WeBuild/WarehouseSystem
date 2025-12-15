<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * =====================================================
 * WAREHOUSE SEEDER
 * =====================================================
 * 
 * Purpose: Seeds warehouse locations with test data
 * =====================================================
 */
class WarehouseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'warehouse_name' => 'Main Warehouse Manila',
                'warehouse_code' => 'WH-MNL-001',
                'location' => 'Manila',
                'address' => '1234 Industrial Avenue, Port Area, Manila, 1018 Philippines',
                'capacity' => 10000,
                'manager_id' => 3, // John Smith - Warehouse Manager
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'warehouse_name' => 'North Distribution Center',
                'warehouse_code' => 'WH-QC-002',
                'location' => 'Quezon City',
                'address' => '456 Commonwealth Avenue, Quezon City, 1121 Philippines',
                'capacity' => 8000,
                'manager_id' => 3,
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'warehouse_name' => 'South Logistics Hub',
                'warehouse_code' => 'WH-MAK-003',
                'location' => 'Makati',
                'address' => '789 EDSA, Makati City, 1200 Philippines',
                'capacity' => 6000,
                'manager_id' => 4, // Jane Doe - Warehouse Staff
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'warehouse_name' => 'East Storage Facility',
                'warehouse_code' => 'WH-PAS-004',
                'location' => 'Pasig',
                'address' => '321 C5 Road, Pasig City, 1600 Philippines',
                'capacity' => 5000,
                'manager_id' => null,
                'status' => 'Maintenance',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        $builder = $this->db->table('warehouses');
        
        foreach ($data as $warehouse) {
            $existing = $builder->where('warehouse_code', $warehouse['warehouse_code'])->get()->getRow();
            if (!$existing) {
                $builder->insert($warehouse);
            }
        }

        echo "Warehouses seeded successfully.\n";
    }
}
