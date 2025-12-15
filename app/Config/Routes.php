<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Auth::login');

// Auth routes
$routes->group('auth', function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::login');
    $routes->get('logout', 'Auth::logout');
    $routes->get('forgotPassword', 'Auth::forgotPassword');
    $routes->post('forgotPassword', 'Auth::forgotPassword');
    $routes->get('profile', 'Auth::profile');
    $routes->post('profile', 'Auth::profile');
});

// Simple routes for backward compatibility
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Role-based dashboard routes
$routes->get('/', 'Home::index');

/**
 * =====================================================
 * STUDENT GUIDE: Understanding Routes
 * =====================================================
 * 
 * What are Routes?
 * Routes connect URLs to Controller functions
 * 
 * Example:
 * URL: http://localhost/admin/dashboard
 * Route: $routes->get('admin/dashboard', 'Admin::dashboard')
 * Calls: Admin controller -> dashboard() function
 * 
 * Route Types:
 * - GET routes: Load pages (views)
 *   Example: $routes->get('admin/dashboard', ...)
 * 
 * - POST routes: Handle form submissions (AJAX)
 *   Example: $routes->post('admin/create-user', ...)
 * 
 * Route Parameters:
 * (:num) = Number parameter
 * Example: admin/delete-user/5
 * The "5" is passed to the function as $id
 * 
 * =====================================================
 */

// Admin routes
// BACKEND: All admin dashboard pages and AJAX endpoints
$routes->group('admin', function($routes) {
    // VIEW ROUTES - Load HTML pages
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('user-accounts', 'Admin::userAccounts');
    $routes->get('roles-permissions', 'Admin::rolesPermissions');
    $routes->get('active-sessions', 'Admin::activeSessions');
    $routes->get('security-policies', 'Admin::securityPolicies');
    $routes->get('audit-logs', 'Admin::auditLogs');
    $routes->get('system-health', 'Admin::systemHealth');
    $routes->get('database-management', 'Admin::databaseManagement');
    $routes->get('backup-recovery', 'Admin::backupRecovery');
    $routes->get('settings', 'Admin::settings');
    
    // AJAX ENDPOINTS - Process form data and return JSON
    $routes->post('create-user', 'Admin::createUser');
    $routes->post('update-user/(:num)', 'Admin::updateUser/$1');
    $routes->post('delete-user/(:num)', 'Admin::deleteUser/$1');
    $routes->post('toggle-user-status/(:num)', 'Admin::toggleUserStatus/$1');
    
    // NEW ROUTES - Backup & Recovery (WeBuild Requirements)
    $routes->post('backup-database', 'Admin::backupDatabase');
    $routes->get('list-backups', 'Admin::listBackups');
    $routes->post('restore-database', 'Admin::restoreDatabase');
    $routes->get('download-backup/(:any)', 'Admin::downloadBackup/$1');
    $routes->post('delete-backup', 'Admin::deleteBackup');
    $routes->post('schedule-backup', 'Admin::scheduleBackup');
});

// Accounts Payable routes
$routes->group('accounts-payable', function($routes) {
    $routes->get('dashboard', 'AccountsPayable::dashboard');
    $routes->get('pending-invoices', 'AccountsPayable::pendingInvoices');
    $routes->get('approved-invoices', 'AccountsPayable::approvedInvoices');
    $routes->get('payment-processing', 'AccountsPayable::paymentProcessing');
    $routes->get('vendor-management', 'AccountsPayable::vendorManagement');
    $routes->get('payment-reports', 'AccountsPayable::paymentReports');
    $routes->get('overdue-payments', 'AccountsPayable::overduePayments');
    $routes->get('audit-trail', 'AccountsPayable::auditTrail');
    // AJAX endpoints
    $routes->post('create-invoice', 'AccountsPayable::createInvoice');
    $routes->post('approve-invoice/(:num)', 'AccountsPayable::approveInvoice/$1');
    $routes->post('reject-invoice/(:num)', 'AccountsPayable::rejectInvoice/$1');
    $routes->post('process-payment', 'AccountsPayable::processPayment');
    $routes->post('create-vendor', 'AccountsPayable::createVendor');
});

// Accounts Receivable routes
$routes->group('accounts-receivable', function($routes) {
    $routes->get('dashboard', 'AccountsReceivable::dashboard');
    $routes->get('manage-invoices', 'AccountsReceivable::manageInvoices');
    $routes->post('create-invoice', 'AccountsReceivable::createInvoice');
    $routes->post('update-invoice/(:num)', 'AccountsReceivable::updateInvoice/$1');
    $routes->post('delete-invoice/(:num)', 'AccountsReceivable::deleteInvoice/$1');
    $routes->get('record-payments', 'AccountsReceivable::recordPayments');
    $routes->post('create-payment', 'AccountsReceivable::createPayment');
    $routes->get('client-management', 'AccountsReceivable::clientManagement');
    $routes->post('create-client', 'AccountsReceivable::createClient');
    $routes->post('update-client/(:num)', 'AccountsReceivable::updateClient/$1');
    $routes->get('overdue-followups', 'AccountsReceivable::overdueFollowups');
    $routes->get('aging-report', 'AccountsReceivable::agingReport');
    $routes->get('reports-analytics', 'AccountsReceivable::reportsAnalytics');
    $routes->get('settings', 'AccountsReceivable::settings');
});

// Warehouse Manager routes
// BACKEND: All warehouse management pages and AJAX endpoints
$routes->group('warehouse-manager', function($routes) {
    // VIEW ROUTES - Load HTML pages
    $routes->get('dashboard', 'WarehouseManager::dashboard');
    $routes->get('inventory', 'WarehouseManager::inventory');
    $routes->get('stock-movements', 'WarehouseManager::stockMovements');
    $routes->get('orders', 'WarehouseManager::orders');
    $routes->get('reports', 'WarehouseManager::reports');
    
    // Multi-Warehouse Views (NEW)
    $routes->get('warehouse-inventory/(:num)', 'WarehouseManager::warehouseInventory/$1');
    $routes->get('transfer-inventory', 'WarehouseManager::transferInventoryView');
    $routes->get('batch-tracking', 'WarehouseManager::batchTracking');
    
    // AJAX ENDPOINTS - Process form data and return JSON
    $routes->post('add-item', 'WarehouseManager::addItem');
    $routes->post('update-item/(:num)', 'WarehouseManager::updateItem/$1');
    $routes->post('delete-item/(:num)', 'WarehouseManager::deleteItem/$1');
    $routes->post('adjust-stock', 'WarehouseManager::adjustStock');
    $routes->post('process-order/(:num)', 'WarehouseManager::processOrder/$1');
    $routes->post('complete-order/(:num)', 'WarehouseManager::completeOrder/$1');
    $routes->get('low-stock-alerts', 'WarehouseManager::getLowStockAlerts');
    
    // Multi-Warehouse AJAX Endpoints (NEW)
    $routes->post('transfer-inventory', 'WarehouseManager::transferInventory');
    $routes->get('inventory-by-warehouse/(:num)', 'WarehouseManager::getInventoryByWarehouse/$1');
    $routes->get('warehouse-capacity/(:num)', 'WarehouseManager::getWarehouseCapacity/$1');
    
    // Batch Tracking Endpoints (NEW)
    $routes->post('create-batch', 'WarehouseManager::createBatch');
    $routes->post('approve-batch/(:num)', 'WarehouseManager::approveBatch/$1');
    $routes->post('reject-batch/(:num)', 'WarehouseManager::rejectBatch/$1');
    $routes->get('batches-expiring', 'WarehouseManager::getBatchesExpiring');
});

// Warehouse routes (legacy - redirects to warehouse-manager)
$routes->group('warehouse', function($routes) {
    $routes->get('dashboard', 'WarehouseManager::dashboard');
    $routes->get('inventory', 'WarehouseManager::inventory');
    $routes->get('stock-movements', 'WarehouseManager::stockMovements');
    $routes->get('orders', 'WarehouseManager::orders');
    $routes->get('reports', 'WarehouseManager::reports');
    $routes->post('add-item', 'WarehouseManager::addItem');
    $routes->post('update-item/(:num)', 'WarehouseManager::updateItem/$1');
    $routes->post('delete-item/(:num)', 'WarehouseManager::deleteItem/$1');
    $routes->post('adjust-stock', 'WarehouseManager::adjustStock');
});

// Inventory routes (redirects to warehouse-manager for inventory auditor)
$routes->group('inventory', function($routes) {
    $routes->get('dashboard', 'InventoryAuditor::dashboard');
    $routes->get('count-sessions', 'InventoryAuditor::countSessions');
    $routes->get('active-count/(:num)', 'InventoryAuditor::activeCount/$1');
    $routes->get('discrepancy-review', 'InventoryAuditor::discrepancyReview');
    $routes->get('reports', 'InventoryAuditor::reports');
});

// Procurement routes
// BACKEND: Procurement Officer module for requisitions and purchase orders
$routes->group('procurement', function($routes) {
    // VIEW ROUTES - Load procurement pages
    $routes->get('dashboard', 'Procurement::dashboard');
    $routes->get('requisitions', 'Procurement::requisitions');
    $routes->get('purchase-orders', 'Procurement::purchaseOrders');
    $routes->get('delivery-tracking', 'Procurement::deliveryTracking');
    $routes->get('reports', 'Procurement::reports');
    
    // AJAX ENDPOINTS - Purchase Requisitions
    $routes->post('create-requisition', 'Procurement::createRequisition');
    $routes->post('submit-requisition', 'Procurement::submitRequisition');
    $routes->post('approve-requisition', 'Procurement::approveRequisition');
    $routes->post('reject-requisition', 'Procurement::rejectRequisition');
    
    // AJAX ENDPOINTS - Purchase Orders
    $routes->post('create-purchase-order', 'Procurement::createPurchaseOrder');
    $routes->post('send-po', 'Procurement::sendPOToVendor');
    $routes->post('receive-po', 'Procurement::receivePO');
});

// Inventory Auditor routes
// BACKEND: Physical inventory counts and reconciliation
$routes->group('inventory-auditor', function($routes) {
    // VIEW ROUTES - Load auditor pages
    $routes->get('dashboard', 'InventoryAuditor::dashboard');
    $routes->get('count-sessions', 'InventoryAuditor::countSessions');
    $routes->get('active-count/(:num)', 'InventoryAuditor::activeCount/$1');
    $routes->get('discrepancy-review', 'InventoryAuditor::discrepancyReview');
    $routes->get('reports', 'InventoryAuditor::reports');
    
    // AJAX ENDPOINTS - Process count data
    $routes->post('start-count', 'InventoryAuditor::startCount');
    $routes->post('record-item-count', 'InventoryAuditor::recordItemCount');
    $routes->post('complete-count', 'InventoryAuditor::completeCount');
    $routes->post('verify-count', 'InventoryAuditor::verifyCount');
    $routes->post('resolve-discrepancy', 'InventoryAuditor::resolveDiscrepancy');
    $routes->post('approve-count', 'InventoryAuditor::approveCount');
});

// Barcode/QR Scanning routes
// BACKEND: Barcode and QR code operations
$routes->group('barcode', function($routes) {
    // AJAX ENDPOINTS - Barcode operations
    $routes->post('scan', 'WarehouseManager::scanBarcode');
    $routes->post('generate', 'WarehouseManager::generateBarcode');
    $routes->post('stock-in-scan', 'WarehouseManager::stockInViaScan');
    $routes->post('stock-out-scan', 'WarehouseManager::stockOutViaScan');
    $routes->post('batch-generate', 'WarehouseManager::batchGenerateBarcodes');
});

// Management Dashboard routes
// BACKEND: Top management executive dashboard and analytics
$routes->group('management', function($routes) {
    // VIEW ROUTES - Load management dashboard pages
    $routes->get('dashboard', 'ManagementDashboard::index');
    $routes->get('inventory-overview', 'ManagementDashboard::inventoryOverview');
    $routes->get('warehouse-analytics', 'ManagementDashboard::warehouseAnalytics');
    
    // NEW ROUTES - Forecasting & Analytics (WeBuild Requirements)
    $routes->get('forecasting', 'ManagementDashboard::forecasting');
    $routes->get('performance-kpis', 'ManagementDashboard::performanceKpis');
    $routes->get('executive-reports', 'ManagementDashboard::executiveReports');
    $routes->get('monthly-report', 'ManagementDashboard::monthlyReport');
    $routes->get('quarterly-report', 'ManagementDashboard::quarterlyReport');
    
    // AJAX API ENDPOINTS - Real-time data for charts and widgets
    $routes->get('api/inventory-stats', 'ManagementDashboard::getInventoryStats');
    $routes->get('api/warehouse-performance', 'ManagementDashboard::getWarehousePerformanceData');
    $routes->get('api/inventory-trend', 'ManagementDashboard::getInventoryTrendData');
});

// =====================================================
// ANALYTICS & FORECASTING ROUTES (Finals Phase)
// =====================================================
$routes->group('analytics', function($routes) {
    // Dashboard and Views
    $routes->get('/', 'Analytics::index');
    $routes->get('dashboard', 'Analytics::index');
    $routes->get('forecasting', 'Analytics::forecasting');
    $routes->get('forecasting/(:num)', 'Analytics::forecasting/$1');
    $routes->get('inventory-kpis', 'Analytics::inventoryKpis');
    $routes->get('warehouse-performance', 'Analytics::warehousePerformance');
    $routes->get('warehouse-performance/(:num)', 'Analytics::warehousePerformance/$1');
    $routes->get('financial-kpis', 'Analytics::financialKpis');
    $routes->get('trends', 'Analytics::trends');
    $routes->get('reorder-recommendations', 'Analytics::reorderRecommendations');
    
    // API Endpoints (JSON)
    $routes->get('api/forecast/(:num)', 'Analytics::getForecast/$1');
    $routes->get('api/abc-analysis', 'Analytics::getAbcAnalysis');
    $routes->get('api/warehouse-performance', 'Analytics::getWarehousePerformance');
    $routes->get('api/warehouse-performance/(:num)', 'Analytics::getWarehousePerformance/$1');
    $routes->get('api/financial-kpis', 'Analytics::getFinancialKpis');
    $routes->get('api/financial-kpis/(:num)', 'Analytics::getFinancialKpis/$1');
    $routes->get('api/trends', 'Analytics::getTrends');
    $routes->get('api/trends/(:num)', 'Analytics::getTrends/$1');
    
    // Export Reports
    $routes->get('export/(:alpha)', 'Analytics::exportReport/$1');
});

// Print Report Routes
$routes->group('print', function($routes) {
    // Warehouse Manager Reports
    $routes->get('inventory', 'WarehouseManager::printInventoryReport');
    $routes->get('stock-movements', 'WarehouseManager::printStockMovementReport');
    $routes->get('low-stock', 'WarehouseManager::printLowStockReport');
    
    // Accounts Receivable Reports
    $routes->get('ar-invoices', 'AccountsReceivable::printInvoiceReport');
    $routes->get('ar-aging', 'AccountsReceivable::printAgingReport');
    
    // Accounts Payable Reports
    $routes->get('ap-invoices', 'AccountsPayable::printInvoiceReport');
    $routes->get('ap-overdue', 'AccountsPayable::printOverdueReport');
});