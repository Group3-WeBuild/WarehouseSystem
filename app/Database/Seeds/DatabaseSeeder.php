<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * =====================================================
 * MASTER DATABASE SEEDER
 * =====================================================
 * 
 * Purpose: Runs all seeders in the correct order
 * Usage: php spark db:seed DatabaseSeeder
 * =====================================================
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "==============================================\n";
        echo "Starting Database Seeding Process\n";
        echo "==============================================\n\n";

        // Order is important due to foreign key constraints
        $seeders = [
            'UserSeeder',              // Must be first (users referenced by other tables)
            'WarehouseSeeder',         // Warehouses (references users)
            'InventorySeeder',         // Inventory items
            'VendorSeeder',            // Vendors/Suppliers
            'ClientSeeder',            // Clients/Customers
            'StockMovementSeeder',     // Stock movements (references inventory, warehouses, users)
            'OrderSeeder',             // Orders and order items (references inventory)
        ];

        foreach ($seeders as $seeder) {
            echo "Running $seeder...\n";
            $this->call($seeder);
            echo "\n";
        }

        echo "==============================================\n";
        echo "Database Seeding Complete!\n";
        echo "==============================================\n";
    }
}
