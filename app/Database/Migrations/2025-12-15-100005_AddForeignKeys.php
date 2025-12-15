<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * =====================================================
 * ADD FOREIGN KEY CONSTRAINTS
 * =====================================================
 * 
 * Purpose: Adds proper foreign key relationships to ensure referential integrity
 * This migration should run AFTER all table creation migrations
 * =====================================================
 */
class AddForeignKeys extends Migration
{
    public function up()
    {
        // Add foreign keys for stock_movements
        if (!$this->db->DBDriver === 'MySQLi') {
            return; // Only for MySQL/MariaDB
        }

        // Stock Movements -> Inventory
        $this->forge->addForeignKey(
            'item_id',
            'inventory',
            'id',
            'CASCADE',
            'CASCADE',
            'stock_movements'
        );

        // Stock Movements -> Users
        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'SET NULL',
            'CASCADE',
            'stock_movements'
        );

        // Stock Movements -> Warehouses
        $this->forge->addForeignKey(
            'warehouse_id',
            'warehouses',
            'id',
            'SET NULL',
            'CASCADE',
            'stock_movements'
        );

        // Order Items -> Orders
        $this->forge->addForeignKey(
            'order_id',
            'orders',
            'id',
            'CASCADE',
            'CASCADE',
            'order_items'
        );

        // Order Items -> Inventory
        $this->forge->addForeignKey(
            'inventory_id',
            'inventory',
            'id',
            'RESTRICT',
            'CASCADE',
            'order_items'
        );

        // Orders -> Users (processed_by)
        $this->forge->addForeignKey(
            'processed_by',
            'users',
            'id',
            'SET NULL',
            'CASCADE',
            'orders'
        );

        // Warehouses -> Users (manager_id)
        $this->forge->addForeignKey(
            'manager_id',
            'users',
            'id',
            'SET NULL',
            'CASCADE',
            'warehouses'
        );

        // Warehouse Inventory -> Warehouses
        $this->forge->addForeignKey(
            'warehouse_id',
            'warehouses',
            'id',
            'CASCADE',
            'CASCADE',
            'warehouse_inventory'
        );

        // Warehouse Inventory -> Inventory
        $this->forge->addForeignKey(
            'inventory_id',
            'inventory',
            'id',
            'CASCADE',
            'CASCADE',
            'warehouse_inventory'
        );

        // Purchase Requisitions -> Users (requested_by)
        $this->forge->addForeignKey(
            'requested_by',
            'users',
            'id',
            'RESTRICT',
            'CASCADE',
            'purchase_requisitions'
        );

        // Purchase Requisitions -> Warehouses
        $this->forge->addForeignKey(
            'warehouse_id',
            'warehouses',
            'id',
            'RESTRICT',
            'CASCADE',
            'purchase_requisitions'
        );

        // Requisition Items -> Purchase Requisitions
        $this->forge->addForeignKey(
            'requisition_id',
            'purchase_requisitions',
            'id',
            'CASCADE',
            'CASCADE',
            'requisition_items'
        );

        // Requisition Items -> Inventory
        $this->forge->addForeignKey(
            'inventory_id',
            'inventory',
            'id',
            'RESTRICT',
            'CASCADE',
            'requisition_items'
        );

        // Purchase Orders -> Vendors
        $this->forge->addForeignKey(
            'vendor_id',
            'vendors',
            'id',
            'RESTRICT',
            'CASCADE',
            'purchase_orders'
        );

        // Purchase Orders -> Warehouses
        $this->forge->addForeignKey(
            'warehouse_id',
            'warehouses',
            'id',
            'RESTRICT',
            'CASCADE',
            'purchase_orders'
        );

        // Purchase Orders -> Users (created_by)
        $this->forge->addForeignKey(
            'created_by',
            'users',
            'id',
            'RESTRICT',
            'CASCADE',
            'purchase_orders'
        );

        // Purchase Orders -> Purchase Requisitions (optional)
        $this->forge->addForeignKey(
            'requisition_id',
            'purchase_requisitions',
            'id',
            'SET NULL',
            'CASCADE',
            'purchase_orders'
        );

        // Purchase Order Items -> Purchase Orders
        $this->forge->addForeignKey(
            'po_id',
            'purchase_orders',
            'id',
            'CASCADE',
            'CASCADE',
            'purchase_order_items'
        );

        // Purchase Order Items -> Inventory
        $this->forge->addForeignKey(
            'inventory_id',
            'inventory',
            'id',
            'RESTRICT',
            'CASCADE',
            'purchase_order_items'
        );

        // Vendor Invoices -> Vendors
        $this->forge->addForeignKey(
            'vendor_id',
            'vendors',
            'id',
            'RESTRICT',
            'CASCADE',
            'vendor_invoices'
        );

        // Vendor Payments -> Vendor Invoices
        $this->forge->addForeignKey(
            'invoice_id',
            'vendor_invoices',
            'id',
            'CASCADE',
            'CASCADE',
            'vendor_payments'
        );

        // Vendor Payments -> Users (processed_by)
        $this->forge->addForeignKey(
            'processed_by',
            'users',
            'id',
            'SET NULL',
            'CASCADE',
            'vendor_payments'
        );

        // Invoices -> Clients
        $this->forge->addForeignKey(
            'client_id',
            'clients',
            'id',
            'RESTRICT',
            'CASCADE',
            'invoices'
        );

        // Payments -> Invoices
        $this->forge->addForeignKey(
            'invoice_id',
            'invoices',
            'id',
            'CASCADE',
            'CASCADE',
            'payments'
        );

        // Physical Counts -> Users (counted_by)
        $this->forge->addForeignKey(
            'counted_by',
            'users',
            'id',
            'RESTRICT',
            'CASCADE',
            'physical_counts'
        );

        // Physical Counts -> Warehouses
        $this->forge->addForeignKey(
            'warehouse_id',
            'warehouses',
            'id',
            'RESTRICT',
            'CASCADE',
            'physical_counts'
        );

        // Count Details -> Physical Counts
        $this->forge->addForeignKey(
            'count_id',
            'physical_counts',
            'id',
            'CASCADE',
            'CASCADE',
            'count_details'
        );

        // Count Details -> Inventory
        $this->forge->addForeignKey(
            'inventory_id',
            'inventory',
            'id',
            'RESTRICT',
            'CASCADE',
            'count_details'
        );

        // Batch Tracking -> Inventory
        $this->forge->addForeignKey(
            'inventory_id',
            'inventory',
            'id',
            'CASCADE',
            'CASCADE',
            'batch_tracking'
        );

        // Lot Tracking -> Inventory
        $this->forge->addForeignKey(
            'inventory_id',
            'inventory',
            'id',
            'CASCADE',
            'CASCADE',
            'lot_tracking'
        );
    }

    public function down()
    {
        // Drop all foreign keys (in reverse order)
        $tables = [
            'lot_tracking' => ['lot_tracking_inventory_id_foreign'],
            'batch_tracking' => ['batch_tracking_inventory_id_foreign'],
            'count_details' => ['count_details_inventory_id_foreign', 'count_details_count_id_foreign'],
            'physical_counts' => ['physical_counts_warehouse_id_foreign', 'physical_counts_counted_by_foreign'],
            'payments' => ['payments_invoice_id_foreign'],
            'invoices' => ['invoices_client_id_foreign'],
            'vendor_payments' => ['vendor_payments_processed_by_foreign', 'vendor_payments_invoice_id_foreign'],
            'vendor_invoices' => ['vendor_invoices_vendor_id_foreign'],
            'purchase_order_items' => ['purchase_order_items_inventory_id_foreign', 'purchase_order_items_po_id_foreign'],
            'purchase_orders' => ['purchase_orders_requisition_id_foreign', 'purchase_orders_created_by_foreign', 'purchase_orders_warehouse_id_foreign', 'purchase_orders_vendor_id_foreign'],
            'requisition_items' => ['requisition_items_inventory_id_foreign', 'requisition_items_requisition_id_foreign'],
            'purchase_requisitions' => ['purchase_requisitions_warehouse_id_foreign', 'purchase_requisitions_requested_by_foreign'],
            'warehouse_inventory' => ['warehouse_inventory_inventory_id_foreign', 'warehouse_inventory_warehouse_id_foreign'],
            'warehouses' => ['warehouses_manager_id_foreign'],
            'orders' => ['orders_processed_by_foreign'],
            'order_items' => ['order_items_inventory_id_foreign', 'order_items_order_id_foreign'],
            'stock_movements' => ['stock_movements_warehouse_id_foreign', 'stock_movements_user_id_foreign', 'stock_movements_item_id_foreign'],
        ];

        foreach ($tables as $table => $foreignKeys) {
            if ($this->db->DBDriver !== 'MySQLi') {
                continue;
            }
            
            foreach ($foreignKeys as $fk) {
                try {
                    $this->forge->dropForeignKey($table, $fk);
                } catch (\Exception $e) {
                    // Continue if foreign key doesn't exist
                }
            }
        }
    }
}
