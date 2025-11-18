-- =====================================================
-- WAREHOUSE INVENTORY MANAGEMENT SYSTEM
-- DATABASE SCHEMA - PRELIM PHASE (40-50% Completion)
-- =====================================================
-- 
-- This SQL file creates all required tables for the
-- Prelim phase of the Warehouse System
--
-- Database: G3Web
-- Date: November 18, 2025
-- Phase: Prelim
-- =====================================================

-- Use the G3Web database
USE G3Web;

-- =====================================================
-- TABLE 1: INVENTORY
-- =====================================================
-- Purpose: Stores all inventory items in the warehouse
-- CRUD Operations: Implemented in WarehouseManager.php
-- Backend Model: app/Models/InventoryModel.php
-- =====================================================

CREATE TABLE IF NOT EXISTS `inventory` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_name` VARCHAR(255) NOT NULL COMMENT 'Name of the product',
  `sku` VARCHAR(100) NOT NULL COMMENT 'Stock Keeping Unit - Unique identifier',
  `category` VARCHAR(100) NOT NULL COMMENT 'Product category (e.g., Construction, Hardware)',
  `description` TEXT DEFAULT NULL COMMENT 'Detailed product description',
  `quantity` INT(11) NOT NULL DEFAULT 0 COMMENT 'Current stock quantity',
  `unit` VARCHAR(50) NOT NULL COMMENT 'Unit of measurement (pcs, kg, box, etc.)',
  `unit_price` DECIMAL(10,2) NOT NULL COMMENT 'Price per unit',
  `reorder_level` INT(11) NOT NULL COMMENT 'Minimum quantity before reorder alert',
  `location` VARCHAR(100) NOT NULL COMMENT 'Warehouse location (e.g., Rack A1)',
  `status` ENUM('Active','Inactive') NOT NULL DEFAULT 'Active' COMMENT 'Item status (soft delete)',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku_unique` (`sku`),
  INDEX `idx_category` (`category`),
  INDEX `idx_status` (`status`),
  INDEX `idx_quantity` (`quantity`),
  INDEX `idx_reorder` (`reorder_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Inventory items table';

-- =====================================================
-- TABLE 2: STOCK MOVEMENTS
-- =====================================================
-- Purpose: Audit trail for all stock changes (IN/OUT)
-- Real-time Updates: Logged automatically in adjustStock()
-- Backend Model: app/Models/StockMovementModel.php
-- =====================================================

CREATE TABLE IF NOT EXISTS `stock_movements` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `item_id` INT(11) NOT NULL COMMENT 'Reference to inventory item',
  `movement_type` ENUM('Stock In','Stock Out','Adjustment','Transfer','Return','Initial Stock') NOT NULL COMMENT 'Type of stock movement',
  `quantity` INT(11) NOT NULL COMMENT 'Quantity moved (positive or negative)',
  `reference_number` VARCHAR(100) DEFAULT NULL COMMENT 'Reference/batch number',
  `user_id` INT(11) DEFAULT NULL COMMENT 'User who made the change',
  `notes` TEXT DEFAULT NULL COMMENT 'Reason or additional details',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'When movement occurred',
  PRIMARY KEY (`id`),
  INDEX `idx_item_id` (`item_id`),
  INDEX `idx_movement_type` (`movement_type`),
  INDEX `idx_created_at` (`created_at`),
  INDEX `idx_user_id` (`user_id`),
  CONSTRAINT `fk_stock_item` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Stock movement audit trail';

-- =====================================================
-- TABLE 3: ORDERS
-- =====================================================
-- Purpose: Manages customer and supplier orders
-- Backend Model: app/Models/OrderModel.php
-- Processing: processOrder(), completeOrder() methods
-- =====================================================

CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_number` VARCHAR(100) NOT NULL COMMENT 'Unique order number (auto-generated)',
  `order_type` ENUM('Customer Order','Supplier Order','Internal Transfer') NOT NULL COMMENT 'Type of order',
  `customer_name` VARCHAR(255) DEFAULT NULL COMMENT 'Customer or supplier name',
  `customer_email` VARCHAR(255) DEFAULT NULL COMMENT 'Contact email',
  `customer_phone` VARCHAR(50) DEFAULT NULL COMMENT 'Contact phone',
  `items` TEXT DEFAULT NULL COMMENT 'JSON array of ordered items',
  `total_amount` DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Total order amount',
  `status` ENUM('Pending','Processing','Completed','Cancelled') NOT NULL DEFAULT 'Pending' COMMENT 'Order status',
  `priority` ENUM('Low','Medium','High') NOT NULL DEFAULT 'Medium' COMMENT 'Order priority',
  `delivery_address` TEXT DEFAULT NULL COMMENT 'Delivery address',
  `delivery_date` DATE DEFAULT NULL COMMENT 'Expected delivery date',
  `processed_by` INT(11) DEFAULT NULL COMMENT 'User who processed the order',
  `processed_at` DATETIME DEFAULT NULL COMMENT 'When order was processed',
  `completed_at` DATETIME DEFAULT NULL COMMENT 'When order was completed',
  `notes` TEXT DEFAULT NULL COMMENT 'Additional notes',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number_unique` (`order_number`),
  INDEX `idx_status` (`status`),
  INDEX `idx_priority` (`priority`),
  INDEX `idx_order_type` (`order_type`),
  INDEX `idx_delivery_date` (`delivery_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Customer and supplier orders';

-- =====================================================
-- TABLE 4: WAREHOUSES
-- =====================================================
-- Purpose: Manages warehouse locations and details
-- Backend Model: app/Models/WarehouseModel.php
-- =====================================================

CREATE TABLE IF NOT EXISTS `warehouses` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `warehouse_name` VARCHAR(255) NOT NULL COMMENT 'Name of the warehouse',
  `warehouse_code` VARCHAR(50) NOT NULL COMMENT 'Unique warehouse code',
  `location` VARCHAR(255) DEFAULT NULL COMMENT 'City/area location',
  `address` TEXT DEFAULT NULL COMMENT 'Full address',
  `capacity` INT(11) DEFAULT NULL COMMENT 'Storage capacity',
  `manager_id` INT(11) DEFAULT NULL COMMENT 'Warehouse manager user ID',
  `status` ENUM('Active','Inactive','Maintenance') NOT NULL DEFAULT 'Active' COMMENT 'Warehouse status',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `warehouse_code_unique` (`warehouse_code`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Warehouse locations';

-- =====================================================
-- SAMPLE DATA FOR TESTING
-- =====================================================
-- These are sample records for testing the backend logic
-- Delete or modify as needed for production
-- =====================================================

-- Sample Inventory Items
INSERT INTO `inventory` (`product_name`, `sku`, `category`, `description`, `quantity`, `unit`, `unit_price`, `reorder_level`, `location`, `status`) VALUES
('Steel Bars 10mm', 'STL-001', 'Construction', 'High-grade steel reinforcement bars', 500, 'pcs', 150.00, 100, 'Warehouse A - Rack 1', 'Active'),
('Cement Portland', 'CEM-001', 'Construction', 'Standard Portland cement 40kg bags', 200, 'bags', 250.00, 50, 'Warehouse A - Storage 2', 'Active'),
('Screws 2-inch', 'SCR-001', 'Hardware', 'Stainless steel screws 2-inch', 5000, 'pcs', 0.50, 500, 'Warehouse B - Bin 5', 'Active'),
('Paint White Latex', 'PNT-001', 'Paint', 'White latex paint 5 gallons', 75, 'gallons', 450.00, 20, 'Warehouse B - Shelf 3', 'Active'),
('Plywood 4x8 ft', 'PLY-001', 'Wood', 'Marine grade plywood 4x8 feet', 150, 'sheets', 800.00, 30, 'Warehouse A - Rack 5', 'Active');

-- Sample Warehouses
INSERT INTO `warehouses` (`warehouse_name`, `warehouse_code`, `location`, `address`, `capacity`, `status`) VALUES
('Main Warehouse', 'WH-MAIN', 'Manila', '123 Industrial Ave, Manila', 10000, 'Active'),
('Secondary Storage', 'WH-SEC', 'Quezon City', '456 Commerce St, QC', 5000, 'Active'),
('Overflow Facility', 'WH-OVER', 'Caloocan', '789 Storage Rd, Caloocan', 3000, 'Maintenance');

-- Sample Stock Movements (Initial Stock)
INSERT INTO `stock_movements` (`item_id`, `movement_type`, `quantity`, `reference_number`, `user_id`, `notes`) VALUES
(1, 'Initial Stock', 500, 'INIT-001', 1, 'Initial inventory count - Steel Bars'),
(2, 'Initial Stock', 200, 'INIT-002', 1, 'Initial inventory count - Cement'),
(3, 'Initial Stock', 5000, 'INIT-003', 1, 'Initial inventory count - Screws'),
(4, 'Initial Stock', 75, 'INIT-004', 1, 'Initial inventory count - Paint'),
(5, 'Initial Stock', 150, 'INIT-005', 1, 'Initial inventory count - Plywood');

-- Sample Orders
INSERT INTO `orders` (`order_number`, `order_type`, `customer_name`, `customer_email`, `customer_phone`, `items`, `total_amount`, `status`, `priority`, `delivery_address`, `delivery_date`, `notes`) VALUES
('ORD-2025-001', 'Customer Order', 'ABC Construction', 'abc@construction.com', '09171234567', '[{"item_id":1,"quantity":50,"price":150.00}]', 7500.00, 'Pending', 'High', 'Project Site A, Makati', '2025-11-25', 'Urgent delivery required'),
('ORD-2025-002', 'Customer Order', 'XYZ Builders', 'xyz@builders.com', '09181234567', '[{"item_id":2,"quantity":100,"price":250.00}]', 25000.00, 'Processing', 'Medium', 'Construction Site B, BGC', '2025-11-28', 'Regular delivery'),
('ORD-2025-003', 'Supplier Order', 'Steel Supplier Inc', 'supplier@steel.com', '09191234567', '[{"item_id":1,"quantity":1000,"price":140.00}]', 140000.00, 'Completed', 'Low', 'Main Warehouse', '2025-11-20', 'Bulk purchase - restocking');

-- =====================================================
-- VIEWS FOR REPORTING (Optional)
-- =====================================================

-- View: Low Stock Items Alert
CREATE OR REPLACE VIEW `view_low_stock_items` AS
SELECT 
    i.id,
    i.product_name,
    i.sku,
    i.category,
    i.quantity,
    i.reorder_level,
    i.unit,
    i.unit_price,
    (i.quantity * i.unit_price) AS stock_value,
    i.location
FROM inventory i
WHERE i.quantity <= i.reorder_level
  AND i.status = 'Active'
ORDER BY i.quantity ASC;

-- View: Inventory Summary by Category
CREATE OR REPLACE VIEW `view_inventory_summary` AS
SELECT 
    i.category,
    COUNT(*) AS total_items,
    SUM(i.quantity) AS total_quantity,
    SUM(i.quantity * i.unit_price) AS total_value,
    COUNT(CASE WHEN i.quantity <= i.reorder_level THEN 1 END) AS low_stock_count
FROM inventory i
WHERE i.status = 'Active'
GROUP BY i.category
ORDER BY total_value DESC;

-- View: Recent Stock Movements
CREATE OR REPLACE VIEW `view_recent_movements` AS
SELECT 
    sm.id,
    sm.movement_type,
    sm.quantity,
    sm.reference_number,
    sm.notes,
    sm.created_at,
    i.product_name,
    i.sku,
    i.unit
FROM stock_movements sm
INNER JOIN inventory i ON sm.item_id = i.id
ORDER BY sm.created_at DESC
LIMIT 100;

-- View: Pending Orders Summary
CREATE OR REPLACE VIEW `view_pending_orders` AS
SELECT 
    o.id,
    o.order_number,
    o.order_type,
    o.customer_name,
    o.total_amount,
    o.priority,
    o.delivery_date,
    o.created_at,
    DATEDIFF(o.delivery_date, CURDATE()) AS days_until_delivery
FROM orders o
WHERE o.status = 'Pending'
ORDER BY o.priority DESC, o.delivery_date ASC;

-- =====================================================
-- STORED PROCEDURES (Backend Logic Support)
-- =====================================================

-- Procedure: Get Inventory Value by Category
DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS `sp_get_inventory_value_by_category`(
    IN p_category VARCHAR(100)
)
BEGIN
    SELECT 
        category,
        COUNT(*) AS total_items,
        SUM(quantity) AS total_quantity,
        SUM(quantity * unit_price) AS total_value
    FROM inventory
    WHERE category = p_category
      AND status = 'Active'
    GROUP BY category;
END$$
DELIMITER ;

-- Procedure: Get Stock Movement History
DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS `sp_get_stock_history`(
    IN p_item_id INT,
    IN p_start_date DATE,
    IN p_end_date DATE
)
BEGIN
    SELECT 
        sm.id,
        sm.movement_type,
        sm.quantity,
        sm.reference_number,
        sm.notes,
        sm.created_at,
        i.product_name,
        i.sku
    FROM stock_movements sm
    INNER JOIN inventory i ON sm.item_id = i.id
    WHERE sm.item_id = p_item_id
      AND DATE(sm.created_at) BETWEEN p_start_date AND p_end_date
    ORDER BY sm.created_at DESC;
END$$
DELIMITER ;

-- Procedure: Calculate Total Inventory Value
DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS `sp_calculate_total_inventory_value`()
BEGIN
    SELECT 
        COUNT(*) AS total_items,
        SUM(quantity) AS total_units,
        SUM(quantity * unit_price) AS total_value,
        COUNT(CASE WHEN quantity <= reorder_level THEN 1 END) AS low_stock_count,
        COUNT(CASE WHEN quantity = 0 THEN 1 END) AS out_of_stock_count
    FROM inventory
    WHERE status = 'Active';
END$$
DELIMITER ;

-- =====================================================
-- TRIGGERS (Automatic Backend Logic)
-- =====================================================

-- Trigger: Prevent Negative Inventory
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS `trg_prevent_negative_inventory`
BEFORE UPDATE ON `inventory`
FOR EACH ROW
BEGIN
    IF NEW.quantity < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Inventory quantity cannot be negative';
    END IF;
END$$
DELIMITER ;

-- Trigger: Auto-generate Order Number
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS `trg_auto_order_number`
BEFORE INSERT ON `orders`
FOR EACH ROW
BEGIN
    IF NEW.order_number IS NULL OR NEW.order_number = '' THEN
        SET NEW.order_number = CONCAT('ORD-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD((SELECT COALESCE(MAX(id), 0) + 1 FROM orders), 4, '0'));
    END IF;
END$$
DELIMITER ;

-- Trigger: Log Inventory Changes
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS `trg_log_inventory_update`
AFTER UPDATE ON `inventory`
FOR EACH ROW
BEGIN
    IF OLD.quantity != NEW.quantity THEN
        INSERT INTO stock_movements (item_id, movement_type, quantity, reference_number, notes)
        VALUES (
            NEW.id,
            'Adjustment',
            NEW.quantity - OLD.quantity,
            CONCAT('AUTO-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s')),
            CONCAT('Quantity changed from ', OLD.quantity, ' to ', NEW.quantity)
        );
    END IF;
END$$
DELIMITER ;

-- =====================================================
-- INDEXES FOR PERFORMANCE OPTIMIZATION
-- =====================================================

-- Already created with tables above, but here's a summary:
-- inventory: sku (UNIQUE), category, status, quantity, reorder_level
-- stock_movements: item_id, movement_type, created_at, user_id
-- orders: order_number (UNIQUE), status, priority, order_type, delivery_date
-- warehouses: warehouse_code (UNIQUE), status

-- =====================================================
-- GRANT PERMISSIONS (Optional - for production)
-- =====================================================
-- Uncomment and modify for production deployment
-- 
-- CREATE USER IF NOT EXISTS 'warehouse_user'@'localhost' IDENTIFIED BY 'secure_password';
-- GRANT SELECT, INSERT, UPDATE ON G3Web.inventory TO 'warehouse_user'@'localhost';
-- GRANT SELECT, INSERT ON G3Web.stock_movements TO 'warehouse_user'@'localhost';
-- GRANT SELECT, UPDATE ON G3Web.orders TO 'warehouse_user'@'localhost';
-- GRANT SELECT ON G3Web.warehouses TO 'warehouse_user'@'localhost';
-- FLUSH PRIVILEGES;

-- =====================================================
-- DATABASE SETUP COMPLETE
-- =====================================================
-- 
-- Next Steps:
-- 1. Import this file into your G3Web database
-- 2. Test backend endpoints using the WarehouseManager controller
-- 3. Verify CRUD operations work correctly
-- 4. Test real-time stock updates with adjustStock()
-- 5. Check validation rules are working
-- 
-- Backend Files:
-- - app/Controllers/WarehouseManager.php
-- - app/Models/InventoryModel.php
-- - app/Models/StockMovementModel.php
-- - app/Models/OrderModel.php
-- - app/Models/WarehouseModel.php
-- 
-- Routes: app/Config/Routes.php (lines 118-136)
-- 
-- Documentation: PRELIM_BACKEND_IMPLEMENTATION.md
-- =====================================================

-- Success message
SELECT 'Database schema created successfully! Ready for Prelim phase testing.' AS Status;
