<?php

namespace App\Libraries;

use App\Models\InventoryModel;
use App\Models\StockMovementModel;
use App\Models\OrderModel;
use App\Models\WarehouseModel;

/**
 * =====================================================
 * ANALYTICS ENGINE - Forecasting & Business Intelligence
 * =====================================================
 * 
 * Purpose: Provides predictive analytics, KPI calculations,
 * and business intelligence for the warehouse system.
 * 
 * Features:
 * - Demand Forecasting (Moving Average, Exponential Smoothing)
 * - Inventory Turnover Analysis
 * - Reorder Point Calculation
 * - Sales Trend Analysis
 * - Warehouse Performance Metrics
 * - Financial KPIs
 * 
 * RUBRIC: Forecasting & Analytics (Finals)
 * "Accurate predictive analytics and KPI dashboards"
 * =====================================================
 */
class AnalyticsEngine
{
    protected $inventoryModel;
    protected $stockMovementModel;
    protected $orderModel;
    protected $warehouseModel;
    protected $db;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->stockMovementModel = new StockMovementModel();
        $this->orderModel = new OrderModel();
        $this->warehouseModel = new WarehouseModel();
        $this->db = \Config\Database::connect();
    }

    // =====================================================
    // DEMAND FORECASTING
    // =====================================================

    /**
     * Simple Moving Average Forecast
     * Predicts future demand based on historical average
     * 
     * @param int $itemId Inventory item ID
     * @param int $periods Number of periods to average
     * @param int $forecastPeriods Periods to forecast
     * @return array Forecast data
     */
    public function movingAverageForecast($itemId, $periods = 30, $forecastPeriods = 7)
    {
        // Get historical stock out movements
        $movements = $this->db->table('stock_movements')
            ->select('DATE(created_at) as date, SUM(ABS(quantity)) as daily_demand')
            ->where('item_id', $itemId)
            ->where('movement_type', 'Stock Out')
            ->where('created_at >=', date('Y-m-d', strtotime("-{$periods} days")))
            ->groupBy('DATE(created_at)')
            ->get()
            ->getResultArray();

        if (empty($movements)) {
            return [
                'method' => 'Moving Average',
                'historical_average' => 0,
                'forecast' => array_fill(0, $forecastPeriods, 0),
                'confidence' => 'low',
                'message' => 'Insufficient historical data'
            ];
        }

        // Calculate average demand
        $totalDemand = array_sum(array_column($movements, 'daily_demand'));
        $avgDemand = $totalDemand / count($movements);

        // Generate forecast
        $forecast = [];
        for ($i = 1; $i <= $forecastPeriods; $i++) {
            $forecast[] = [
                'date' => date('Y-m-d', strtotime("+{$i} days")),
                'predicted_demand' => round($avgDemand, 2),
                'lower_bound' => round($avgDemand * 0.8, 2),
                'upper_bound' => round($avgDemand * 1.2, 2)
            ];
        }

        return [
            'method' => 'Moving Average',
            'periods_analyzed' => count($movements),
            'historical_average' => round($avgDemand, 2),
            'forecast' => $forecast,
            'confidence' => count($movements) >= 14 ? 'high' : (count($movements) >= 7 ? 'medium' : 'low'),
            'total_forecasted_demand' => round($avgDemand * $forecastPeriods, 2)
        ];
    }

    /**
     * Exponential Smoothing Forecast
     * Gives more weight to recent observations
     * 
     * @param int $itemId Inventory item ID
     * @param float $alpha Smoothing factor (0-1)
     * @param int $forecastPeriods Periods to forecast
     * @return array Forecast data
     */
    public function exponentialSmoothingForecast($itemId, $alpha = 0.3, $forecastPeriods = 7)
    {
        $movements = $this->db->table('stock_movements')
            ->select('DATE(created_at) as date, SUM(ABS(quantity)) as daily_demand')
            ->where('item_id', $itemId)
            ->where('movement_type', 'Stock Out')
            ->where('created_at >=', date('Y-m-d', strtotime('-60 days')))
            ->groupBy('DATE(created_at)')
            ->orderBy('date', 'ASC')
            ->get()
            ->getResultArray();

        if (count($movements) < 3) {
            return $this->movingAverageForecast($itemId, 30, $forecastPeriods);
        }

        // Apply exponential smoothing
        $smoothed = floatval($movements[0]['daily_demand']);
        foreach ($movements as $movement) {
            $smoothed = $alpha * floatval($movement['daily_demand']) + (1 - $alpha) * $smoothed;
        }

        // Generate forecast
        $forecast = [];
        for ($i = 1; $i <= $forecastPeriods; $i++) {
            $forecast[] = [
                'date' => date('Y-m-d', strtotime("+{$i} days")),
                'predicted_demand' => round($smoothed, 2),
                'lower_bound' => round($smoothed * 0.85, 2),
                'upper_bound' => round($smoothed * 1.15, 2)
            ];
        }

        return [
            'method' => 'Exponential Smoothing',
            'alpha' => $alpha,
            'periods_analyzed' => count($movements),
            'smoothed_value' => round($smoothed, 2),
            'forecast' => $forecast,
            'confidence' => count($movements) >= 30 ? 'high' : 'medium',
            'total_forecasted_demand' => round($smoothed * $forecastPeriods, 2)
        ];
    }

    /**
     * Seasonal Demand Analysis
     * Identifies patterns by day of week/month
     */
    public function seasonalAnalysis($itemId, $periods = 90)
    {
        // Day of week analysis
        $dayOfWeekData = $this->db->table('stock_movements')
            ->select('DAYOFWEEK(created_at) as day_num, DAYNAME(created_at) as day_name, 
                     AVG(ABS(quantity)) as avg_demand, COUNT(*) as transactions')
            ->where('item_id', $itemId)
            ->where('movement_type', 'Stock Out')
            ->where('created_at >=', date('Y-m-d', strtotime("-{$periods} days")))
            ->groupBy('DAYOFWEEK(created_at), DAYNAME(created_at)')
            ->orderBy('day_num', 'ASC')
            ->get()
            ->getResultArray();

        // Monthly analysis
        $monthlyData = $this->db->table('stock_movements')
            ->select('MONTH(created_at) as month_num, MONTHNAME(created_at) as month_name,
                     SUM(ABS(quantity)) as total_demand, COUNT(*) as transactions')
            ->where('item_id', $itemId)
            ->where('movement_type', 'Stock Out')
            ->groupBy('MONTH(created_at), MONTHNAME(created_at)')
            ->orderBy('month_num', 'ASC')
            ->get()
            ->getResultArray();

        return [
            'day_of_week_pattern' => $dayOfWeekData,
            'monthly_pattern' => $monthlyData,
            'peak_day' => !empty($dayOfWeekData) ? 
                array_reduce($dayOfWeekData, fn($a, $b) => 
                    ($a['avg_demand'] ?? 0) > ($b['avg_demand'] ?? 0) ? $a : $b, ['avg_demand' => 0]) : null,
            'peak_month' => !empty($monthlyData) ? 
                array_reduce($monthlyData, fn($a, $b) => 
                    ($a['total_demand'] ?? 0) > ($b['total_demand'] ?? 0) ? $a : $b, ['total_demand' => 0]) : null
        ];
    }

    // =====================================================
    // INVENTORY KPIs
    // =====================================================

    /**
     * Calculate Inventory Turnover Ratio
     * Higher ratio = better inventory management
     */
    public function inventoryTurnoverRatio($itemId = null, $period = 365)
    {
        $query = $this->db->table('stock_movements sm')
            ->select('sm.item_id, i.product_name, i.sku,
                     SUM(CASE WHEN sm.movement_type = "Stock Out" THEN ABS(sm.quantity) * i.unit_price ELSE 0 END) as cogs,
                     AVG(i.quantity * i.unit_price) as avg_inventory_value')
            ->join('inventory i', 'i.id = sm.item_id')
            ->where('sm.created_at >=', date('Y-m-d', strtotime("-{$period} days")));

        if ($itemId) {
            $query->where('sm.item_id', $itemId);
        }

        $results = $query->groupBy('sm.item_id, i.product_name, i.sku')
            ->get()
            ->getResultArray();

        $kpis = [];
        foreach ($results as $row) {
            $avgInventory = floatval($row['avg_inventory_value']) ?: 1;
            $cogs = floatval($row['cogs']);
            $turnoverRatio = $cogs / $avgInventory;
            $daysToSell = $turnoverRatio > 0 ? $period / $turnoverRatio : 999;

            $kpis[] = [
                'item_id' => $row['item_id'],
                'product_name' => $row['product_name'],
                'sku' => $row['sku'],
                'cost_of_goods_sold' => round($cogs, 2),
                'avg_inventory_value' => round($avgInventory, 2),
                'turnover_ratio' => round($turnoverRatio, 2),
                'days_to_sell' => round($daysToSell, 0),
                'rating' => $turnoverRatio >= 6 ? 'Excellent' : 
                           ($turnoverRatio >= 4 ? 'Good' : 
                           ($turnoverRatio >= 2 ? 'Average' : 'Poor'))
            ];
        }

        return $kpis;
    }

    /**
     * Calculate Reorder Point for each item
     * ROP = (Average Daily Usage × Lead Time) + Safety Stock
     */
    public function calculateReorderPoints($leadTimeDays = 7, $safetyStockDays = 3)
    {
        $items = $this->inventoryModel->where('status', 'Active')->findAll();
        $reorderPoints = [];

        foreach ($items as $item) {
            // Calculate average daily usage
            $usage = $this->db->table('stock_movements')
                ->selectSum('ABS(quantity)', 'total_out')
                ->where('item_id', $item['id'])
                ->where('movement_type', 'Stock Out')
                ->where('created_at >=', date('Y-m-d', strtotime('-30 days')))
                ->get()
                ->getRow();

            $avgDailyUsage = ($usage && $usage->total_out) ? $usage->total_out / 30 : 0;
            $safetyStock = $avgDailyUsage * $safetyStockDays;
            $reorderPoint = ($avgDailyUsage * $leadTimeDays) + $safetyStock;

            $reorderPoints[] = [
                'item_id' => $item['id'],
                'product_name' => $item['product_name'],
                'sku' => $item['sku'],
                'current_quantity' => $item['quantity'],
                'avg_daily_usage' => round($avgDailyUsage, 2),
                'safety_stock' => round($safetyStock, 0),
                'calculated_reorder_point' => round($reorderPoint, 0),
                'current_reorder_level' => $item['reorder_level'],
                'days_of_stock' => $avgDailyUsage > 0 ? round($item['quantity'] / $avgDailyUsage, 0) : 999,
                'needs_reorder' => $item['quantity'] <= $reorderPoint,
                'stock_status' => $item['quantity'] <= 0 ? 'Out of Stock' :
                                 ($item['quantity'] <= $reorderPoint ? 'Reorder Now' :
                                 ($item['quantity'] <= $reorderPoint * 1.5 ? 'Low Stock' : 'Adequate'))
            ];
        }

        // Sort by urgency
        usort($reorderPoints, function($a, $b) {
            $statusOrder = ['Out of Stock' => 0, 'Reorder Now' => 1, 'Low Stock' => 2, 'Adequate' => 3];
            return $statusOrder[$a['stock_status']] - $statusOrder[$b['stock_status']];
        });

        return $reorderPoints;
    }

    /**
     * ABC Analysis - Classify items by importance
     * A = Top 20% by value (70-80% of total value)
     * B = Next 30% (15-25% of total value)
     * C = Remaining 50% (5-10% of total value)
     */
    public function abcAnalysis()
    {
        $items = $this->inventoryModel
            ->where('status', 'Active')
            ->orderBy('(quantity * unit_price)', 'DESC')
            ->findAll();

        $totalValue = 0;
        foreach ($items as $item) {
            $totalValue += $item['quantity'] * $item['unit_price'];
        }

        $cumulativeValue = 0;
        $classifiedItems = [];
        
        foreach ($items as $index => $item) {
            $itemValue = $item['quantity'] * $item['unit_price'];
            $cumulativeValue += $itemValue;
            $cumulativePercent = $totalValue > 0 ? ($cumulativeValue / $totalValue) * 100 : 0;
            $itemPercent = (($index + 1) / count($items)) * 100;

            // Classify based on cumulative value
            if ($cumulativePercent <= 70) {
                $class = 'A';
            } elseif ($cumulativePercent <= 90) {
                $class = 'B';
            } else {
                $class = 'C';
            }

            $classifiedItems[] = [
                'item_id' => $item['id'],
                'product_name' => $item['product_name'],
                'sku' => $item['sku'],
                'category' => $item['category'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_value' => round($itemValue, 2),
                'value_percent' => round(($itemValue / $totalValue) * 100, 2),
                'cumulative_percent' => round($cumulativePercent, 2),
                'abc_class' => $class,
                'recommendation' => $class === 'A' ? 'High priority - tight control, frequent review' :
                                   ($class === 'B' ? 'Medium priority - moderate control' :
                                   'Low priority - simple controls, bulk ordering')
            ];
        }

        // Summary
        $summary = [
            'A' => ['count' => 0, 'value' => 0],
            'B' => ['count' => 0, 'value' => 0],
            'C' => ['count' => 0, 'value' => 0]
        ];

        foreach ($classifiedItems as $item) {
            $summary[$item['abc_class']]['count']++;
            $summary[$item['abc_class']]['value'] += $item['total_value'];
        }

        return [
            'items' => $classifiedItems,
            'summary' => $summary,
            'total_value' => round($totalValue, 2),
            'total_items' => count($items)
        ];
    }

    // =====================================================
    // WAREHOUSE PERFORMANCE
    // =====================================================

    /**
     * Warehouse Efficiency Metrics
     */
    public function warehousePerformance($warehouseId = null)
    {
        $warehouses = $warehouseId ? 
            $this->warehouseModel->where('id', $warehouseId)->findAll() :
            $this->warehouseModel->findAll();

        $performance = [];
        foreach ($warehouses as $warehouse) {
            // Get warehouse inventory
            $inventory = $this->db->table('warehouse_inventory wi')
                ->select('SUM(wi.quantity) as total_units, 
                         SUM(wi.quantity * i.unit_price) as total_value,
                         COUNT(DISTINCT wi.inventory_id) as unique_items')
                ->join('inventory i', 'i.id = wi.inventory_id')
                ->where('wi.warehouse_id', $warehouse['id'])
                ->get()
                ->getRow();

            // Get movement activity (last 30 days)
            $movements = $this->db->table('stock_movements')
                ->select('COUNT(*) as total_movements,
                         SUM(CASE WHEN movement_type = "Stock In" THEN 1 ELSE 0 END) as inbound,
                         SUM(CASE WHEN movement_type = "Stock Out" THEN 1 ELSE 0 END) as outbound')
                ->where('warehouse_id', $warehouse['id'])
                ->where('created_at >=', date('Y-m-d', strtotime('-30 days')))
                ->get()
                ->getRow();

            $capacity = floatval($warehouse['capacity']) ?: 1;
            $currentUnits = $inventory ? floatval($inventory->total_units) : 0;
            $utilizationPercent = ($currentUnits / $capacity) * 100;

            $performance[] = [
                'warehouse_id' => $warehouse['id'],
                'warehouse_name' => $warehouse['warehouse_name'],
                'warehouse_code' => $warehouse['warehouse_code'],
                'location' => $warehouse['location'],
                'status' => $warehouse['status'],
                'capacity' => $capacity,
                'current_units' => $currentUnits,
                'utilization_percent' => round($utilizationPercent, 1),
                'total_value' => $inventory ? round($inventory->total_value, 2) : 0,
                'unique_items' => $inventory ? $inventory->unique_items : 0,
                'movements_30d' => $movements ? $movements->total_movements : 0,
                'inbound_30d' => $movements ? $movements->inbound : 0,
                'outbound_30d' => $movements ? $movements->outbound : 0,
                'efficiency_rating' => $utilizationPercent >= 80 ? 'Optimal' :
                                      ($utilizationPercent >= 50 ? 'Good' :
                                      ($utilizationPercent >= 20 ? 'Underutilized' : 'Critical'))
            ];
        }

        return $performance;
    }

    // =====================================================
    // FINANCIAL KPIs
    // =====================================================

    /**
     * Financial Dashboard Metrics
     */
    public function financialKPIs($period = 30): array
    {
        // Total inventory value
        $inventoryValue = $this->db->table('inventory')
            ->select('SUM(quantity * unit_price) as total_value')
            ->where('status', 'Active')
            ->get()
            ->getRow();

        // Orders summary
        $orders = $this->db->table('orders')
            ->select('status, COUNT(*) as count, SUM(total_amount) as total')
            ->where('created_at >=', date('Y-m-d', strtotime("-{$period} days")))
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $orderStats = [];
        foreach ($orders as $order) {
            $orderStats[$order['status']] = [
                'count' => $order['count'],
                'total' => round($order['total'], 2)
            ];
        }

        // Stock movement value
        $stockValue = $this->db->table('stock_movements sm')
            ->select('sm.movement_type,
                     SUM(ABS(sm.quantity) * i.unit_price) as value')
            ->join('inventory i', 'i.id = sm.item_id')
            ->where('sm.created_at >=', date('Y-m-d', strtotime("-{$period} days")))
            ->groupBy('sm.movement_type')
            ->get()
            ->getResultArray();

        $stockStats = [];
        foreach ($stockValue as $stat) {
            $stockStats[$stat['movement_type']] = round($stat['value'], 2);
        }

        return [
            'period_days' => $period,
            'inventory_value' => round($inventoryValue->total_value ?? 0, 2),
            'orders' => $orderStats,
            'stock_movements' => $stockStats,
            'total_completed_orders' => $orderStats['Completed']['total'] ?? 0,
            'average_order_value' => isset($orderStats['Completed']) && $orderStats['Completed']['count'] > 0 ?
                round($orderStats['Completed']['total'] / $orderStats['Completed']['count'], 2) : 0
        ];
    }

    // =====================================================
    // TREND ANALYSIS
    // =====================================================

    /**
     * Get historical trends for charts
     */
    public function getTrends($days = 30)
    {
        $trends = [
            'dates' => [],
            'inventory_value' => [],
            'orders_count' => [],
            'movements_count' => []
        ];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $trends['dates'][] = date('M d', strtotime($date));

            // Orders count per day
            $orderCount = $this->db->table('orders')
                ->where('DATE(created_at)', $date)
                ->countAllResults();
            $trends['orders_count'][] = $orderCount;

            // Movements count per day
            $movementCount = $this->db->table('stock_movements')
                ->where('DATE(created_at)', $date)
                ->countAllResults();
            $trends['movements_count'][] = $movementCount;
        }

        // Current inventory value trend (simplified - same for all days in example)
        $currentValue = $this->db->table('inventory')
            ->select('SUM(quantity * unit_price) as value')
            ->where('status', 'Active')
            ->get()
            ->getRow();

        $trends['current_inventory_value'] = round($currentValue->value ?? 0, 2);

        return $trends;
    }

    /**
     * Get comprehensive analytics summary
     */
    public function getAnalyticsSummary()
    {
        return [
            'generated_at' => date('Y-m-d H:i:s'),
            'financial_kpis' => $this->financialKPIs(30),
            'warehouse_performance' => $this->warehousePerformance(),
            'reorder_alerts' => array_filter(
                $this->calculateReorderPoints(),
                fn($item) => $item['needs_reorder']
            ),
            'abc_summary' => $this->abcAnalysis()['summary'],
            'trends' => $this->getTrends(14)
        ];
    }
}
