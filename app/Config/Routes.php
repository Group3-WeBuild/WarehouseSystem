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
    
    // AJAX ENDPOINTS - Process form data and return JSON
    $routes->post('add-item', 'WarehouseManager::addItem');
    $routes->post('update-item/(:num)', 'WarehouseManager::updateItem/$1');
    $routes->post('delete-item/(:num)', 'WarehouseManager::deleteItem/$1');
    $routes->post('adjust-stock', 'WarehouseManager::adjustStock');
    $routes->post('process-order/(:num)', 'WarehouseManager::processOrder/$1');
    $routes->post('complete-order/(:num)', 'WarehouseManager::completeOrder/$1');
    $routes->get('low-stock-alerts', 'WarehouseManager::getLowStockAlerts');
});

// Warehouse routes (legacy - keeping for compatibility)
$routes->group('warehouse', function($routes) {
    $routes->get('dashboard', 'Warehouse::dashboard');
    // Add more routes as needed
});

// Inventory routes
$routes->group('inventory', function($routes) {
    $routes->get('dashboard', 'Inventory::dashboard');
    // Add more routes as needed
});

// Procurement routes
$routes->group('procurement', function($routes) {
    $routes->get('dashboard', 'Procurement::dashboard');
    // Add more routes as needed
});

// Management routes
$routes->group('management', function($routes) {
    $routes->get('dashboard', 'Management::dashboard');
    // Add more routes as needed
});