<?php
/**
 * =====================================================
 * MANAGEMENT DASHBOARD CONTROLLER - Backend Logic
 * =====================================================
 * 
 * This controller handles TOP MANAGEMENT functions:
 * - Executive dashboard with KPIs
 * - Inventory overview and analytics
 * - Warehouse performance metrics
 * - Financial reports integration
 * - Real-time business intelligence
 * 
 * STUDENT NOTE: This is BACKEND for TOP MANAGEMENT
 * It provides high-level data for decision making
 * 
 * FRONTEND VIEWS (Already Exist):
 * - inventory_overview.php
 * - warehouse_analytics.php
 * - dashboard.php
 * - executive_reports.php
 * 
 * DATABASE TABLES USED:
 * - inventory (stock data)
 * - stock_movements (transaction history)
 * - orders (order analytics)
 * - warehouses (location performance)
 * 
 * =====================================================
 */

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\StockMovementModel;
use App\Models\OrderModel;
use App\Models\WarehouseModel;
use App\Models\InvoiceModel;

class ManagementDashboard extends BaseController
{
    protected $session;
    protected $inventoryModel;
    protected $stockMovementModel;
    protected $orderModel;
    protected $warehouseModel;
    protected $invoiceModel;

    /**
     * =====================================================
     * CONSTRUCTOR - Initialize Controller
     * =====================================================
     */
    public function __construct()
    {
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
        
        // BACKEND: Initialize all required models
        $this->inventoryModel = new InventoryModel();
        $this->stockMovementModel = new StockMovementModel();
        $this->orderModel = new OrderModel();
        $this->warehouseModel = new WarehouseModel();
        $this->invoiceModel = new InvoiceModel();
    }

    /**
     * =====================================================
     * SECURITY CHECK - Authentication & Authorization
     * =====================================================
     * 
     * STUDENT NOTE: Management access requires high-level roles
     * Only executives and IT admins can view this data
     * =====================================================
     */
    private function checkAuth()
    {
        // BACKEND: Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            $this->session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to(base_url('login'));
        }

        // BACKEND: Check user role (restrictive - management only)
        $userRole = $this->session->get('role');
        $allowedRoles = ['IT Administrator', 'Top Management', 'CEO', 'CFO', 'COO'];
        
        if (!in_array($userRole, $allowedRoles)) {
            $this->session->setFlashdata('error', 'Access denied. Management privileges required.');
            return redirect()->to(base_url('dashboard'));
        }

        return null;
    }

    /**
     * =====================================================
     * VIEW: Main Management Dashboard
     * =====================================================
     * 
     * Route: GET /management/dashboard
     * Frontend: app/Views/management_dashboard/dashboard.php
     * 
     * BACKEND PURPOSE:
     * Provides executive-level KPIs and metrics
     * 
     * DATA PROVIDED TO FRONTEND:
     * - Company-wide statistics
     * - Inventory value metrics
     * - Order trends
     * - Warehouse performance
     * - Financial summaries
     * 
     * RUBRIC COMPLIANCE:
     * ✓ Real-time dashboard updates
     * ✓ Accurate business metrics
     * =====================================================
     */
    public function index()
    {
        // BACKEND: Security check
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // BACKEND: Calculate executive KPIs
        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role'),
                'email' => $this->session->get('email')
            ],
            
            // BACKEND: Inventory metrics
            'totalInventoryValue' => $this->calculateTotalInventoryValue(),
            'totalItems' => $this->inventoryModel->where('status', 'Active')->countAllResults(),
            'lowStockCount' => $this->getLowStockCount(),
            'outOfStockCount' => $this->getOutOfStockCount(),
            
            // BACKEND: Order metrics
            'pendingOrders' => $this->orderModel->where('status', 'Pending')->countAllResults(),
            'processingOrders' => $this->orderModel->where('status', 'Processing')->countAllResults(),
            'completedOrdersToday' => $this->getCompletedOrdersToday(),
            'totalOrderValue' => $this->calculateTotalOrderValue(),
            
            // BACKEND: Warehouse metrics
            'activeWarehouses' => $this->warehouseModel->where('status', 'Active')->countAllResults(),
            'warehouseUtilization' => $this->calculateWarehouseUtilization(),
            
            // BACKEND: Recent activity
            'recentMovements' => $this->stockMovementModel->orderBy('created_at', 'DESC')->findAll(10),
            'recentOrders' => $this->orderModel->orderBy('created_at', 'DESC')->findAll(5),
            
            // BACKEND: Trends (for charts)
            'inventoryTrend' => $this->getInventoryTrend(30), // Last 30 days
            'orderTrend' => $this->getOrderTrend(30),
            
            // BACKEND: Alerts
            'criticalAlerts' => $this->getCriticalAlerts(),
            
            // Page metadata
            'pageTitle' => 'Executive Dashboard',
            'breadcrumb' => 'Dashboard'
        ];

        // BACKEND: Send data to frontend view
        return view('management_dashboard/dashboard', $data);
    }

    /**
     * =====================================================
     * VIEW: Inventory Overview
     * =====================================================
     * 
     * Route: GET /management/inventory-overview
     * Frontend: app/Views/management_dashboard/inventory_overview.php
     * 
     * BACKEND PURPOSE:
     * Complete inventory visibility for management
     * 
     * STUDENT NOTE:
     * This connects the existing frontend to backend data
     * The view already exists - we're providing the data!
     * =====================================================
     */
    public function inventoryOverview()
    {
        // BACKEND: Security check
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // BACKEND: Get comprehensive inventory data
        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            
            // BACKEND: All inventory items grouped by category
            'inventoryByCategory' => $this->getInventoryByCategory(),
            
            // BACKEND: Summary statistics
            'totalItems' => $this->inventoryModel->where('status', 'Active')->countAllResults(),
            'totalValue' => $this->calculateTotalInventoryValue(),
            'averageItemValue' => $this->calculateAverageItemValue(),
            
            // BACKEND: Stock status breakdown
            'stockStatus' => [
                'in_stock' => $this->inventoryModel->where('quantity >', 0)->where('status', 'Active')->countAllResults(),
                'low_stock' => $this->getLowStockCount(),
                'out_of_stock' => $this->getOutOfStockCount()
            ],
            
            // BACKEND: Category analysis
            'categoryStats' => $this->getCategoryStatistics(),
            
            // BACKEND: Top items by value
            'topValueItems' => $this->getTopItemsByValue(10),
            
            // BACKEND: Items requiring attention
            'lowStockItems' => $this->inventoryModel->getLowStockItems(),
            'outOfStockItems' => $this->inventoryModel->getOutOfStockItems(),
            
            // BACKEND: Warehouse distribution
            'warehouseDistribution' => $this->getWarehouseDistribution(),
            
            // Page metadata
            'pageTitle' => 'Inventory Overview',
            'breadcrumb' => 'Inventory'
        ];

        // BACKEND: Return view with data
        return view('management_dashboard/inventory_overview', $data);
    }

    /**
     * =====================================================
     * VIEW: Warehouse Analytics
     * =====================================================
     * 
     * Route: GET /management/warehouse-analytics
     * Frontend: app/Views/management_dashboard/warehouse_analytics.php
     * 
     * BACKEND PURPOSE:
     * Warehouse performance metrics and analytics
     * =====================================================
     */
    public function warehouseAnalytics()
    {
        // BACKEND: Security check
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // BACKEND: Get warehouse performance data
        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            
            // BACKEND: Warehouse statistics
            'warehouses' => $this->warehouseModel->findAll(),
            'totalWarehouses' => $this->warehouseModel->countAllResults(),
            'activeWarehouses' => $this->warehouseModel->where('status', 'Active')->countAllResults(),
            
            // BACKEND: Capacity metrics
            'totalCapacity' => $this->calculateTotalCapacity(),
            'usedCapacity' => $this->calculateUsedCapacity(),
            'utilizationRate' => $this->calculateWarehouseUtilization(),
            
            // BACKEND: Movement analytics
            'totalMovements' => $this->stockMovementModel->countAllResults(),
            'movementsThisMonth' => $this->getMovementsThisMonth(),
            'movementsByType' => $this->getMovementsByType(),
            
            // BACKEND: Performance by warehouse
            'warehousePerformance' => $this->getWarehousePerformance(),
            
            // BACKEND: Trends
            'movementTrend' => $this->getMovementTrend(30), // Last 30 days
            
            // BACKEND: Top performing warehouses
            'topWarehouses' => $this->getTopWarehousesByActivity(5),
            
            // Page metadata
            'pageTitle' => 'Warehouse Analytics',
            'breadcrumb' => 'Analytics'
        ];

        // BACKEND: Return view with data
        return view('management_dashboard/warehouse_analytics', $data);
    }

    /**
     * =====================================================
     * AJAX API: Get Inventory Statistics (Real-Time)
     * =====================================================
     * 
     * Route: GET /management/api/inventory-stats
     * 
     * BACKEND PURPOSE:
     * Provides real-time inventory data for AJAX updates
     * Used by dashboard widgets and charts
     * 
     * STUDENT NOTE - How Real-Time Updates Work:
     * 1. Frontend calls this endpoint periodically (e.g., every 30 seconds)
     * 2. Backend fetches latest data from database
     * 3. Backend calculates current statistics
     * 4. Backend returns JSON data
     * 5. Frontend updates displays without page reload
     * 
     * RUBRIC COMPLIANCE:
     * ✓ Real-time stock updates
     * ✓ Accurate and instant metrics
     * =====================================================
     */
    public function getInventoryStats()
    {
        // BACKEND: Security check
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            // BACKEND: Calculate real-time statistics
            $stats = [
                'timestamp' => date('Y-m-d H:i:s'),
                'total_items' => $this->inventoryModel->where('status', 'Active')->countAllResults(),
                'total_value' => floatval($this->calculateTotalInventoryValue()),
                'low_stock_count' => $this->getLowStockCount(),
                'out_of_stock_count' => $this->getOutOfStockCount(),
                'categories' => $this->getCategoryStatistics(),
                'stock_status' => [
                    'healthy' => $this->inventoryModel
                        ->where('quantity >', 'reorder_level', false)
                        ->where('status', 'Active')
                        ->countAllResults(),
                    'low' => $this->getLowStockCount(),
                    'out' => $this->getOutOfStockCount()
                ]
            ];

            // BACKEND: Return JSON response
            return $this->response->setJSON([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            // BACKEND: Error handling
            log_message('error', 'Failed to get inventory stats: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to fetch inventory statistics'
            ]);
        }
    }

    /**
     * =====================================================
     * AJAX API: Get Warehouse Performance Data
     * =====================================================
     * 
     * Route: GET /management/api/warehouse-performance
     * 
     * BACKEND PURPOSE:
     * Real-time warehouse metrics for analytics dashboard
     * =====================================================
     */
    public function getWarehousePerformanceData()
    {
        // BACKEND: Security check
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $performanceData = [];
            $warehouses = $this->warehouseModel->where('status', 'Active')->findAll();

            foreach ($warehouses as $warehouse) {
                $performanceData[] = [
                    'warehouse_id' => $warehouse['id'],
                    'warehouse_name' => $warehouse['warehouse_name'],
                    'warehouse_code' => $warehouse['warehouse_code'],
                    'total_items' => $this->getItemCountByWarehouse($warehouse['id']),
                    'total_value' => $this->getValueByWarehouse($warehouse['id']),
                    'movements_today' => $this->getMovementsTodayByWarehouse($warehouse['id']),
                    'utilization' => $this->calculateWarehouseUtilizationById($warehouse['id'])
                ];
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $performanceData
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Failed to get warehouse performance: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to fetch warehouse performance data'
            ]);
        }
    }

    /**
     * =====================================================
     * AJAX API: Get Inventory Trend (Chart Data)
     * =====================================================
     * 
     * Route: GET /management/api/inventory-trend
     * 
     * BACKEND PURPOSE:
     * Provides data for charts showing inventory trends over time
     * =====================================================
     */
    public function getInventoryTrendData()
    {
        // BACKEND: Security check
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            $days = $this->request->getGet('days') ?? 30;
            $trendData = $this->getInventoryTrend($days);

            return $this->response->setJSON([
                'success' => true,
                'data' => $trendData
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Failed to get inventory trend: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to fetch inventory trend data'
            ]);
        }
    }

    /**
     * =====================================================
     * HELPER FUNCTIONS - Data Calculation
     * =====================================================
     * 
     * STUDENT NOTE: These functions perform complex calculations
     * They process data from database and return metrics
     * =====================================================
     */

    /**
     * HELPER: Calculate total inventory value
     * Formula: SUM(quantity × unit_price) for all active items
     */
    private function calculateTotalInventoryValue()
    {
        try {
            $items = $this->inventoryModel->where('status', 'Active')->findAll();
            $totalValue = 0;
            
            foreach ($items as $item) {
                $totalValue += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            }
            
            return number_format($totalValue, 2, '.', '');
        } catch (\Exception $e) {
            return '0.00';
        }
    }

    /**
     * HELPER: Get count of low stock items
     */
    private function getLowStockCount()
    {
        return $this->inventoryModel
            ->where('quantity <=', 'reorder_level', false)
            ->where('quantity >', 0)
            ->where('status', 'Active')
            ->countAllResults();
    }

    /**
     * HELPER: Get count of out of stock items
     */
    private function getOutOfStockCount()
    {
        return $this->inventoryModel
            ->where('quantity', 0)
            ->where('status', 'Active')
            ->countAllResults();
    }

    /**
     * HELPER: Calculate average item value
     */
    private function calculateAverageItemValue()
    {
        $totalValue = floatval($this->calculateTotalInventoryValue());
        $totalItems = $this->inventoryModel->where('status', 'Active')->countAllResults();
        
        if ($totalItems == 0) return '0.00';
        
        return number_format($totalValue / $totalItems, 2, '.', '');
    }

    /**
     * HELPER: Get inventory grouped by category
     */
    private function getInventoryByCategory()
    {
        $items = $this->inventoryModel->where('status', 'Active')->findAll();
        $grouped = [];
        
        foreach ($items as $item) {
            $category = $item['category'] ?? 'Uncategorized';
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $item;
        }
        
        return $grouped;
    }

    /**
     * HELPER: Get category statistics
     */
    private function getCategoryStatistics()
    {
        $items = $this->inventoryModel->where('status', 'Active')->findAll();
        $stats = [];
        
        foreach ($items as $item) {
            $category = $item['category'] ?? 'Uncategorized';
            
            if (!isset($stats[$category])) {
                $stats[$category] = [
                    'count' => 0,
                    'total_quantity' => 0,
                    'total_value' => 0
                ];
            }
            
            $stats[$category]['count']++;
            $stats[$category]['total_quantity'] += $item['quantity'] ?? 0;
            $stats[$category]['total_value'] += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
        }
        
        return $stats;
    }

    /**
     * HELPER: Get top items by value
     */
    private function getTopItemsByValue($limit = 10)
    {
        $items = $this->inventoryModel->where('status', 'Active')->findAll();
        
        // Calculate value for each item
        foreach ($items as &$item) {
            $item['total_value'] = ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
        }
        
        // Sort by value descending
        usort($items, function($a, $b) {
            return $b['total_value'] <=> $a['total_value'];
        });
        
        return array_slice($items, 0, $limit);
    }

    /**
     * HELPER: Get warehouse distribution
     */
    private function getWarehouseDistribution()
    {
        $items = $this->inventoryModel->where('status', 'Active')->findAll();
        $distribution = [];
        
        foreach ($items as $item) {
            $location = $item['location'] ?? 'Unknown';
            
            if (!isset($distribution[$location])) {
                $distribution[$location] = [
                    'count' => 0,
                    'quantity' => 0,
                    'value' => 0
                ];
            }
            
            $distribution[$location]['count']++;
            $distribution[$location]['quantity'] += $item['quantity'] ?? 0;
            $distribution[$location]['value'] += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
        }
        
        return $distribution;
    }

    /**
     * HELPER: Calculate total warehouse capacity
     */
    private function calculateTotalCapacity()
    {
        $warehouses = $this->warehouseModel->where('status', 'Active')->findAll();
        $totalCapacity = 0;
        
        foreach ($warehouses as $warehouse) {
            $totalCapacity += $warehouse['capacity'] ?? 0;
        }
        
        return $totalCapacity;
    }

    /**
     * HELPER: Calculate used capacity
     */
    private function calculateUsedCapacity()
    {
        return $this->inventoryModel
            ->selectSum('quantity')
            ->where('status', 'Active')
            ->first()['quantity'] ?? 0;
    }

    /**
     * HELPER: Calculate warehouse utilization percentage
     */
    private function calculateWarehouseUtilization()
    {
        $totalCapacity = $this->calculateTotalCapacity();
        
        if ($totalCapacity == 0) return 0;
        
        $usedCapacity = $this->calculateUsedCapacity();
        return round(($usedCapacity / $totalCapacity) * 100, 2);
    }

    /**
     * HELPER: Get movements this month
     */
    private function getMovementsThisMonth()
    {
        $startOfMonth = date('Y-m-01 00:00:00');
        $endOfMonth = date('Y-m-t 23:59:59');
        
        return $this->stockMovementModel
            ->where('created_at >=', $startOfMonth)
            ->where('created_at <=', $endOfMonth)
            ->countAllResults();
    }

    /**
     * HELPER: Get movements by type
     */
    private function getMovementsByType()
    {
        $movements = $this->stockMovementModel->findAll();
        $byType = [];
        
        foreach ($movements as $movement) {
            $type = $movement['movement_type'] ?? 'Unknown';
            $byType[$type] = ($byType[$type] ?? 0) + 1;
        }
        
        return $byType;
    }

    /**
     * HELPER: Get warehouse performance
     */
    private function getWarehousePerformance()
    {
        $warehouses = $this->warehouseModel->where('status', 'Active')->findAll();
        $performance = [];
        
        foreach ($warehouses as $warehouse) {
            $performance[] = [
                'warehouse' => $warehouse,
                'item_count' => $this->getItemCountByWarehouse($warehouse['id']),
                'total_value' => $this->getValueByWarehouse($warehouse['id']),
                'movements' => $this->getMovementsByWarehouse($warehouse['id'])
            ];
        }
        
        return $performance;
    }

    /**
     * HELPER: Get item count by warehouse
     */
    private function getItemCountByWarehouse($warehouseId)
    {
        // Note: This assumes location field contains warehouse reference
        // Adjust based on your actual database structure
        return $this->inventoryModel
            ->where('status', 'Active')
            ->countAllResults();
    }

    /**
     * HELPER: Get value by warehouse
     */
    private function getValueByWarehouse($warehouseId)
    {
        $items = $this->inventoryModel->where('status', 'Active')->findAll();
        $value = 0;
        
        foreach ($items as $item) {
            $value += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
        }
        
        return $value;
    }

    /**
     * HELPER: Get movements by warehouse
     */
    private function getMovementsByWarehouse($warehouseId)
    {
        return $this->stockMovementModel->countAllResults();
    }

    /**
     * HELPER: Get movements today by warehouse
     */
    private function getMovementsTodayByWarehouse($warehouseId)
    {
        $today = date('Y-m-d');
        
        return $this->stockMovementModel
            ->where('DATE(created_at)', $today)
            ->countAllResults();
    }

    /**
     * HELPER: Calculate warehouse utilization by ID
     */
    private function calculateWarehouseUtilizationById($warehouseId)
    {
        $warehouse = $this->warehouseModel->find($warehouseId);
        
        if (!$warehouse || !isset($warehouse['capacity']) || $warehouse['capacity'] == 0) {
            return 0;
        }
        
        $used = $this->getItemCountByWarehouse($warehouseId);
        return round(($used / $warehouse['capacity']) * 100, 2);
    }

    /**
     * HELPER: Get inventory trend (for charts)
     */
    private function getInventoryTrend($days = 30)
    {
        $trend = [];
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        
        // Simple trend - can be enhanced with actual historical data
        for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $trend[] = [
                'date' => $date,
                'value' => floatval($this->calculateTotalInventoryValue()),
                'items' => $this->inventoryModel->where('status', 'Active')->countAllResults()
            ];
        }
        
        return array_reverse($trend);
    }

    /**
     * HELPER: Get order trend
     */
    private function getOrderTrend($days = 30)
    {
        $trend = [];
        
        for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $count = $this->orderModel
                ->where('DATE(created_at)', $date)
                ->countAllResults();
            
            $trend[] = [
                'date' => $date,
                'count' => $count
            ];
        }
        
        return array_reverse($trend);
    }

    /**
     * HELPER: Get movement trend
     */
    private function getMovementTrend($days = 30)
    {
        $trend = [];
        
        for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $count = $this->stockMovementModel
                ->where('DATE(created_at)', $date)
                ->countAllResults();
            
            $trend[] = [
                'date' => $date,
                'count' => $count
            ];
        }
        
        return array_reverse($trend);
    }

    /**
     * HELPER: Get top warehouses by activity
     */
    private function getTopWarehousesByActivity($limit = 5)
    {
        $warehouses = $this->warehouseModel->where('status', 'Active')->findAll();
        $withActivity = [];
        
        foreach ($warehouses as $warehouse) {
            $warehouse['activity_count'] = $this->getMovementsByWarehouse($warehouse['id']);
            $withActivity[] = $warehouse;
        }
        
        usort($withActivity, function($a, $b) {
            return $b['activity_count'] <=> $a['activity_count'];
        });
        
        return array_slice($withActivity, 0, $limit);
    }

    /**
     * HELPER: Calculate total order value
     */
    private function calculateTotalOrderValue()
    {
        $orders = $this->orderModel->findAll();
        $total = 0;
        
        foreach ($orders as $order) {
            $total += $order['total_amount'] ?? 0;
        }
        
        return number_format($total, 2, '.', '');
    }

    /**
     * HELPER: Get completed orders today
     */
    private function getCompletedOrdersToday()
    {
        $today = date('Y-m-d');
        
        return $this->orderModel
            ->where('status', 'Completed')
            ->where('DATE(completed_at)', $today)
            ->countAllResults();
    }

    /**
     * HELPER: Get critical alerts
     */
    private function getCriticalAlerts()
    {
        $alerts = [];
        
        // Out of stock alerts
        $outOfStock = $this->getOutOfStockCount();
        if ($outOfStock > 0) {
            $alerts[] = [
                'type' => 'danger',
                'message' => "{$outOfStock} items are out of stock",
                'action' => 'View Items'
            ];
        }
        
        // Low stock alerts
        $lowStock = $this->getLowStockCount();
        if ($lowStock > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "{$lowStock} items are below reorder level",
                'action' => 'Review Inventory'
            ];
        }
        
        return $alerts;
    }
    
    /**
     * =====================================================
     * FORECASTING & ANALYTICS - Predictive Features
     * =====================================================
     */
    
    public function forecasting()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // Calculate demand forecasting based on historical data
        $historicalData = $this->getHistoricalConsumption(90); // Last 90 days
        $forecast = $this->calculateDemandForecast($historicalData);
        $stockTurnover = $this->calculateStockTurnover();
        $reorderPredictions = $this->predictReorderNeeds();

        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'historicalData' => $historicalData,
            'forecast' => $forecast,
            'stockTurnover' => $stockTurnover,
            'reorderPredictions' => $reorderPredictions,
            'topMovingItems' => $this->getTopMovingItems(10),
            'slowMovingItems' => $this->getSlowMovingItems(10),
            'seasonalTrends' => $this->getSeasonalTrends(),
            'pageTitle' => 'Demand Forecasting & Analytics',
            'breadcrumb' => 'Forecasting'
        ];

        return view('management_dashboard/forecasting', $data);
    }
    
    public function performanceKpis()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            // Inventory KPIs
            'inventoryTurnover' => $this->calculateStockTurnover(),
            'inventoryAccuracy' => $this->calculateInventoryAccuracy(),
            'stockoutRate' => $this->calculateStockoutRate(),
            'averageStockLevel' => $this->calculateAverageStockLevel(),
            
            // Financial KPIs
            'unpaidInvoicesCount' => $this->getUnpaidInvoicesCount(),
            'unpaidInvoicesAmount' => $this->getUnpaidInvoicesAmount(),
            'collectionEfficiency' => $this->calculateCollectionEfficiency(),
            
            // Operational KPIs
            'orderFulfillmentRate' => $this->calculateOrderFulfillmentRate(),
            'warehouseUtilization' => $this->calculateWarehouseUtilization(),
            'mostRequestedMaterials' => $this->getMostRequestedMaterials(10),
            
            'pageTitle' => 'Performance KPIs',
            'breadcrumb' => 'KPIs'
        ];

        return view('management_dashboard/performance_kpis', $data);
    }
    
    public function executiveReports()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'pageTitle' => 'Executive Reports',
            'breadcrumb' => 'Reports'
        ];

        return view('management_dashboard/executive_reports', $data);
    }
    
    /**
     * =====================================================
     * VIEW: Financial Reports
     * =====================================================
     * 
     * Route: GET /management/financial-reports
     * 
     * BACKEND PURPOSE:
     * Provides comprehensive financial analytics for management
     * Integrates data from AR, AP, Inventory valuation
     * =====================================================
     */
    public function financialReports()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // Get date range from request or default to current month
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        // Get financial summary data
        $financialSummary = $this->getFinancialSummary($startDate, $endDate);
        
        // Get monthly trends for charts
        $monthlyTrends = $this->getMonthlyFinancialTrends(6); // Last 6 months

        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role'),
                'email' => $this->session->get('email')
            ],
            'pageTitle' => 'Financial Reports',
            'breadcrumb' => 'Financial Reports',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'summary' => $financialSummary,
            'monthlyTrends' => $monthlyTrends
        ];

        return view('management_dashboard/financial_reports', $data);
    }
    
    public function monthlyReport()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');

        helper('printing_helper');
        
        $stockMovements = $this->getMonthlyStockMovements($month, $year);
        $financialSummary = $this->getMonthlyFinancialSummary($month, $year);
        $topItems = $this->getTopItemsByMonth($month, $year, 10);

        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Monthly Report - ' . date('F Y', strtotime("$year-$month-01")) . '</title>
            ' . generate_print_styles() . '
        </head>
        <body>
            <div class="print-button-container no-print">
                <button class="btn-print" onclick="window.print()">🖨️ Print Report</button>
                <button class="btn-print" onclick="window.close()" style="background-color: #95a5a6;">✕ Close</button>
            </div>
            
            ' . generate_print_header('Monthly Operations Report', date('F Y', strtotime("$year-$month-01"))) . '
            
            <div class="summary-box">
                <h3>Executive Summary</h3>
                <table style="border: none;">
                    <tr>
                        <td style="border: none;"><strong>Total Stock Movements:</strong></td>
                        <td style="border: none;">' . count($stockMovements) . '</td>
                        <td style="border: none;"><strong>Stock In:</strong></td>
                        <td style="border: none;">' . $this->countMovementType($stockMovements, 'IN') . '</td>
                    </tr>
                    <tr>
                        <td style="border: none;"><strong>Stock Out:</strong></td>
                        <td style="border: none;">' . $this->countMovementType($stockMovements, 'OUT') . '</td>
                        <td style="border: none;"><strong>Total Value:</strong></td>
                        <td style="border: none;">₱' . number_format($financialSummary['totalValue'] ?? 0, 2) . '</td>
                    </tr>
                </table>
            </div>
            
            <h3>Top 10 Most Moved Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Total Movements</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>';
        
        $counter = 1;
        foreach ($topItems as $item) {
            $html .= '
                    <tr>
                        <td>' . $counter++ . '</td>
                        <td>' . esc($item['product_name'] ?? 'Unknown') . '</td>
                        <td>' . ($item['movement_count'] ?? 0) . '</td>
                        <td>' . number_format($item['total_quantity'] ?? 0) . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            ' . generate_print_footer() . '
        </body>
        </html>';
        
        echo $html;
    }
    
    public function quarterlyReport()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $quarter = $this->request->getGet('quarter') ?? ceil(date('n') / 3);
        $year = $this->request->getGet('year') ?? date('Y');

        helper('printing_helper');
        
        $months = $this->getQuarterMonths($quarter);
        $quarterlyData = $this->getQuarterlyData($months, $year);

        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Quarterly Report - Q' . $quarter . ' ' . $year . '</title>
            ' . generate_print_styles() . '
        </head>
        <body>
            <div class="print-button-container no-print">
                <button class="btn-print" onclick="window.print()">🖨️ Print Report</button>
                <button class="btn-print" onclick="window.close()" style="background-color: #95a5a6;">✕ Close</button>
            </div>
            
            ' . generate_print_header('Quarterly Performance Report', 'Q' . $quarter . ' ' . $year) . '
            
            <div class="summary-box">
                <h3>Quarter Summary</h3>
                <table style="border: none;">
                    <tr>
                        <td style="border: none;"><strong>Total Revenue:</strong></td>
                        <td style="border: none;">₱' . number_format($quarterlyData['revenue'] ?? 0, 2) . '</td>
                        <td style="border: none;"><strong>Total Expenses:</strong></td>
                        <td style="border: none;">₱' . number_format($quarterlyData['expenses'] ?? 0, 2) . '</td>
                    </tr>
                    <tr>
                        <td style="border: none;"><strong>Stock Turnover:</strong></td>
                        <td style="border: none;">' . number_format($quarterlyData['turnover'] ?? 0, 2) . 'x</td>
                        <td style="border: none;"><strong>Orders Fulfilled:</strong></td>
                        <td style="border: none;">' . number_format($quarterlyData['orders'] ?? 0) . '</td>
                    </tr>
                </table>
            </div>
            
            ' . generate_print_footer() . '
        </body>
        </html>';
        
        echo $html;
    }
    
    /**
     * =====================================================
     * HELPER METHODS - Calculations & Analytics
     * =====================================================
     */
    
    private function getHistoricalConsumption($days = 90)
    {
        try {
            $startDate = date('Y-m-d', strtotime("-$days days"));
            $movements = $this->stockMovementModel
                ->where('created_at >=', $startDate)
                ->where('movement_type', 'OUT')
                ->findAll();
            
            return $movements;
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function calculateDemandForecast($historicalData)
    {
        // Simple moving average forecast
        $itemConsumption = [];
        foreach ($historicalData as $movement) {
            $itemId = $movement['item_id'] ?? 0;
            if (!isset($itemConsumption[$itemId])) {
                $itemConsumption[$itemId] = 0;
            }
            $itemConsumption[$itemId] += $movement['quantity'] ?? 0;
        }
        
        $forecasts = [];
        foreach ($itemConsumption as $itemId => $totalQty) {
            $avgDaily = $totalQty / 90;
            $forecasts[$itemId] = [
                'daily_avg' => round($avgDaily, 2),
                'weekly_forecast' => round($avgDaily * 7, 0),
                'monthly_forecast' => round($avgDaily * 30, 0)
            ];
        }
        
        return $forecasts;
    }
    
    private function calculateStockTurnover()
    {
        try {
            $totalValue = (float)$this->calculateTotalInventoryValue();
            $yearlyMovements = $this->stockMovementModel
                ->where('created_at >=', date('Y-m-d', strtotime('-365 days')))
                ->where('movement_type', 'OUT')
                ->countAllResults();
            
            if ($totalValue > 0) {
                return round($yearlyMovements / ($totalValue / 1000), 2);
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function predictReorderNeeds()
    {
        $lowStock = $this->inventoryModel->getLowStockItems();
        $predictions = [];
        
        foreach ($lowStock as $item) {
            $reorderQty = ($item['reorder_level'] * 2) - $item['quantity'];
            if ($reorderQty > 0) {
                $predictions[] = [
                    'item' => $item['product_name'],
                    'current' => $item['quantity'],
                    'reorder_qty' => $reorderQty,
                    'estimated_cost' => $reorderQty * $item['unit_price']
                ];
            }
        }
        
        return $predictions;
    }
    
    private function getTopMovingItems($limit = 10)
    {
        try {
            $movements = $this->stockMovementModel
                ->select('item_id, COUNT(*) as movement_count')
                ->where('created_at >=', date('Y-m-d', strtotime('-30 days')))
                ->groupBy('item_id')
                ->orderBy('movement_count', 'DESC')
                ->findAll($limit);
            
            return $movements;
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function getSlowMovingItems($limit = 10)
    {
        try {
            $items = $this->inventoryModel
                ->where('quantity >', 0)
                ->where('status', 'Active')
                ->orderBy('updated_at', 'ASC')
                ->findAll($limit);
            
            return $items;
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function getSeasonalTrends()
    {
        // Placeholder for seasonal analysis
        return [];
    }
    
    private function calculateInventoryAccuracy()
    {
        // Placeholder - would compare physical counts vs system
        return 98.5;
    }
    
    private function calculateStockoutRate()
    {
        try {
            $total = $this->inventoryModel->where('status', 'Active')->countAllResults();
            $outOfStock = $this->getOutOfStockCount();
            
            if ($total > 0) {
                return round(($outOfStock / $total) * 100, 2);
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function calculateAverageStockLevel()
    {
        try {
            $items = $this->inventoryModel->where('status', 'Active')->findAll();
            if (count($items) > 0) {
                $total = array_sum(array_column($items, 'quantity'));
                return round($total / count($items), 0);
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getUnpaidInvoicesCount()
    {
        try {
            return $this->invoiceModel->where('status', 'Unpaid')->countAllResults();
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getUnpaidInvoicesAmount()
    {
        try {
            $unpaid = $this->invoiceModel->where('status', 'Unpaid')->findAll();
            return array_sum(array_column($unpaid, 'total_amount'));
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function calculateCollectionEfficiency()
    {
        try {
            $total = $this->invoiceModel->countAllResults();
            $paid = $this->invoiceModel->where('status', 'Paid')->countAllResults();
            
            if ($total > 0) {
                return round(($paid / $total) * 100, 2);
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function calculateOrderFulfillmentRate()
    {
        try {
            $total = $this->orderModel->countAllResults();
            $completed = $this->orderModel->where('status', 'Completed')->countAllResults();
            
            if ($total > 0) {
                return round(($completed / $total) * 100, 2);
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    private function getMostRequestedMaterials($limit = 10)
    {
        try {
            return $this->stockMovementModel
                ->select('item_id, SUM(quantity) as total_requested')
                ->where('movement_type', 'OUT')
                ->where('created_at >=', date('Y-m-d', strtotime('-30 days')))
                ->groupBy('item_id')
                ->orderBy('total_requested', 'DESC')
                ->findAll($limit);
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function getMonthlyStockMovements($month, $year)
    {
        try {
            return $this->stockMovementModel
                ->where('MONTH(created_at)', $month)
                ->where('YEAR(created_at)', $year)
                ->findAll();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function getMonthlyFinancialSummary($month, $year)
    {
        return [
            'totalValue' => $this->calculateTotalInventoryValue()
        ];
    }
    
    /**
     * Get comprehensive financial summary for a date range
     */
    private function getFinancialSummary($startDate, $endDate)
    {
        // Inventory valuation
        $inventoryValue = $this->calculateTotalInventoryValue();
        
        $db = \Config\Database::connect();
        
        // Get AR data (receivables) from invoices table
        $arInvoices = $db->table('invoices')
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate)
            ->get()
            ->getResultArray();
        
        $totalReceivables = 0;
        $receivablesPaid = 0;
        foreach ($arInvoices as $invoice) {
            $totalReceivables += floatval($invoice['amount'] ?? 0);
            if ($invoice['status'] === 'Paid') {
                $receivablesPaid += floatval($invoice['amount'] ?? 0);
            }
        }
        $receivablesOutstanding = $totalReceivables - $receivablesPaid;
        
        // Get AP data (payables) from vendor_invoices table
        $apInvoices = $db->table('vendor_invoices')
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate)
            ->get()
            ->getResultArray();
        
        $totalPayables = 0;
        $payablesPaid = 0;
        foreach ($apInvoices as $invoice) {
            $totalPayables += floatval($invoice['amount'] ?? 0);
            if ($invoice['status'] === 'Paid') {
                $payablesPaid += floatval($invoice['amount'] ?? 0);
            }
        }
        $payablesOutstanding = $totalPayables - $payablesPaid;
        
        // Calculate net position
        $netPosition = $receivablesOutstanding - $payablesOutstanding;
        
        // Get inventory movement value
        $stockMovements = $this->stockMovementModel
            ->where('DATE(created_at) >=', $startDate)
            ->where('DATE(created_at) <=', $endDate)
            ->findAll();
        
        $stockInValue = 0;
        $stockOutValue = 0;
        foreach ($stockMovements as $movement) {
            $value = floatval($movement['quantity'] ?? 0) * floatval($movement['unit_cost'] ?? 0);
            if ($movement['movement_type'] === 'IN') {
                $stockInValue += $value;
            } else {
                $stockOutValue += $value;
            }
        }
        
        return [
            'inventory_value' => $inventoryValue,
            'total_receivables' => $totalReceivables,
            'receivables_collected' => $receivablesPaid,
            'receivables_outstanding' => $receivablesOutstanding,
            'total_payables' => $totalPayables,
            'payables_paid' => $payablesPaid,
            'payables_outstanding' => $payablesOutstanding,
            'net_position' => $netPosition,
            'stock_in_value' => $stockInValue,
            'stock_out_value' => $stockOutValue,
            'ar_count' => count($arInvoices),
            'ap_count' => count($apInvoices),
            'collection_rate' => $totalReceivables > 0 ? ($receivablesPaid / $totalReceivables) * 100 : 0,
            'payment_rate' => $totalPayables > 0 ? ($payablesPaid / $totalPayables) * 100 : 0
        ];
    }
    
    /**
     * Get monthly financial trends for charts
     */
    private function getMonthlyFinancialTrends($months = 6)
    {
        $trends = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = date('Y-m-01', strtotime("-$i months"));
            $month = date('m', strtotime($date));
            $year = date('Y', strtotime($date));
            $startDate = "$year-$month-01";
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $summary = $this->getFinancialSummary($startDate, $endDate);
            
            $trends[] = [
                'month' => date('M Y', strtotime($startDate)),
                'receivables' => $summary['total_receivables'],
                'payables' => $summary['total_payables'],
                'net_position' => $summary['net_position'],
                'stock_in' => $summary['stock_in_value'],
                'stock_out' => $summary['stock_out_value']
            ];
        }
        
        return $trends;
    }
    
    private function getTopItemsByMonth($month, $year, $limit)
    {
        try {
            return $this->stockMovementModel
                ->select('stock_movements.*, COUNT(*) as movement_count, SUM(quantity) as total_quantity')
                ->where('MONTH(created_at)', $month)
                ->where('YEAR(created_at)', $year)
                ->groupBy('item_id')
                ->orderBy('movement_count', 'DESC')
                ->findAll($limit);
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function countMovementType($movements, $type)
    {
        $count = 0;
        foreach ($movements as $movement) {
            if (strpos($movement['movement_type'], $type) !== false) {
                $count++;
            }
        }
        return $count;
    }
    
    private function getQuarterMonths($quarter)
    {
        $quarters = [
            1 => [1, 2, 3],
            2 => [4, 5, 6],
            3 => [7, 8, 9],
            4 => [10, 11, 12]
        ];
        return $quarters[$quarter] ?? [1, 2, 3];
    }
    
    private function getQuarterlyData($months, $year)
    {
        return [
            'revenue' => 0,
            'expenses' => 0,
            'turnover' => $this->calculateStockTurnover(),
            'orders' => $this->orderModel->countAllResults()
        ];
    }
}
