<?php

namespace App\Controllers;

use App\Libraries\AnalyticsEngine;
use App\Libraries\AuditService;

/**
 * =====================================================
 * ANALYTICS CONTROLLER - Forecasting & Business Intelligence
 * =====================================================
 * 
 * Purpose: Provides analytics, forecasting, and KPI endpoints
 * for the warehouse management system.
 * 
 * RUBRIC: Forecasting & Analytics (Finals)
 * "Accurate predictive analytics and KPI dashboards"
 * =====================================================
 */
class Analytics extends BaseController
{
    protected $session;
    protected $analytics;
    protected $audit;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
        $this->analytics = new AnalyticsEngine();
        $this->audit = new AuditService();
    }

    /**
     * Check authentication
     */
    private function checkAuth()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Please login to access analytics.');
        }

        $userRole = $this->session->get('role');
        $allowedRoles = [
            'Warehouse Manager', 
            'Inventory Auditor', 
            'Procurement Officer',
            'IT Administrator', 
            'Top Management'
        ];
        
        if (!in_array($userRole, $allowedRoles)) {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Access denied. Insufficient permissions.');
        }

        return null;
    }

    /**
     * Main Analytics Dashboard
     */
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'summary' => $this->analytics->getAnalyticsSummary(),
            'pageTitle' => 'Analytics Dashboard',
            'breadcrumb' => 'Analytics'
        ];

        $this->audit->logAction('Analytics', 'view', 'Accessed analytics dashboard');

        return view('analytics/dashboard', $data);
    }

    /**
     * Demand Forecasting
     */
    public function forecasting($itemId = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $inventoryModel = new \App\Models\InventoryModel();
        
        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'items' => $inventoryModel->where('status', 'Active')->findAll(),
            'pageTitle' => 'Demand Forecasting',
            'breadcrumb' => 'Analytics > Forecasting'
        ];

        if ($itemId) {
            $item = $inventoryModel->find($itemId);
            if ($item) {
                $data['selectedItem'] = $item;
                $data['movingAverage'] = $this->analytics->movingAverageForecast($itemId, 30, 14);
                $data['exponentialSmoothing'] = $this->analytics->exponentialSmoothingForecast($itemId, 0.3, 14);
                $data['seasonalAnalysis'] = $this->analytics->seasonalAnalysis($itemId, 90);
                
                $this->audit->logAction('Analytics', 'forecast', 
                    "Generated demand forecast for item: {$item['product_name']}");
            }
        }

        return view('analytics/forecasting', $data);
    }

    /**
     * API: Get forecast data (JSON)
     */
    public function getForecast($itemId)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $movingAvg = $this->analytics->movingAverageForecast($itemId, 30, 14);
        $expSmooth = $this->analytics->exponentialSmoothingForecast($itemId, 0.3, 14);
        $seasonal = $this->analytics->seasonalAnalysis($itemId, 90);

        return $this->response->setJSON([
            'success' => true,
            'item_id' => $itemId,
            'forecasts' => [
                'moving_average' => $movingAvg,
                'exponential_smoothing' => $expSmooth,
                'seasonal_analysis' => $seasonal
            ]
        ]);
    }

    /**
     * Inventory KPIs
     */
    public function inventoryKpis()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'turnoverRatios' => $this->analytics->inventoryTurnoverRatio(),
            'reorderPoints' => $this->analytics->calculateReorderPoints(),
            'abcAnalysis' => $this->analytics->abcAnalysis(),
            'pageTitle' => 'Inventory KPIs',
            'breadcrumb' => 'Analytics > Inventory KPIs'
        ];

        $this->audit->logAction('Analytics', 'view', 'Viewed inventory KPIs dashboard');

        return view('analytics/inventory_kpis', $data);
    }

    /**
     * API: Get ABC Analysis (JSON)
     */
    public function getAbcAnalysis()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $this->analytics->abcAnalysis()
        ]);
    }

    /**
     * Warehouse Performance
     */
    public function warehousePerformance($warehouseId = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'performance' => $this->analytics->warehousePerformance($warehouseId),
            'pageTitle' => 'Warehouse Performance',
            'breadcrumb' => 'Analytics > Warehouse Performance'
        ];

        $this->audit->logAction('Analytics', 'view', 'Viewed warehouse performance metrics');

        return view('analytics/warehouse_performance', $data);
    }

    /**
     * API: Get Warehouse Performance (JSON)
     */
    public function getWarehousePerformance($warehouseId = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $this->analytics->warehousePerformance($warehouseId)
        ]);
    }

    /**
     * Financial KPIs
     */
    public function financialKpis()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'kpis30' => $this->analytics->financialKPIs(30),
            'kpis90' => $this->analytics->financialKPIs(90),
            'kpis365' => $this->analytics->financialKPIs(365),
            'trends' => $this->analytics->getTrends(30),
            'pageTitle' => 'Financial KPIs',
            'breadcrumb' => 'Analytics > Financial KPIs'
        ];

        $this->audit->logAction('Analytics', 'view', 'Viewed financial KPIs dashboard');

        return view('analytics/financial_kpis', $data);
    }

    /**
     * API: Get Financial KPIs (JSON)
     */
    public function getFinancialKpis($period = 30)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        return $this->response->setJSON([
            'success' => true,
            'period' => $period,
            'data' => $this->analytics->financialKPIs($period)
        ]);
    }

    /**
     * Trends Dashboard
     */
    public function trends()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'trends7' => $this->analytics->getTrends(7),
            'trends30' => $this->analytics->getTrends(30),
            'trends90' => $this->analytics->getTrends(90),
            'pageTitle' => 'Trend Analysis',
            'breadcrumb' => 'Analytics > Trends'
        ];

        return view('analytics/trends', $data);
    }

    /**
     * API: Get Trends (JSON)
     */
    public function getTrends($days = 30)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        return $this->response->setJSON([
            'success' => true,
            'days' => $days,
            'data' => $this->analytics->getTrends($days)
        ]);
    }

    /**
     * Reorder Recommendations
     */
    public function reorderRecommendations()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $reorderPoints = $this->analytics->calculateReorderPoints();
        
        $data = [
            'user' => [
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'allItems' => $reorderPoints,
            'needsReorder' => array_filter($reorderPoints, fn($item) => $item['needs_reorder']),
            'pageTitle' => 'Reorder Recommendations',
            'breadcrumb' => 'Analytics > Reorder'
        ];

        $this->audit->logAction('Analytics', 'view', 'Viewed reorder recommendations');

        return view('analytics/reorder_recommendations', $data);
    }

    /**
     * Export Analytics Report as PDF
     */
    public function exportReport($type = 'summary')
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $pdf = new \App\Libraries\PdfService();
        
        switch ($type) {
            case 'inventory':
                $inventoryModel = new \App\Models\InventoryModel();
                $inventory = $inventoryModel->where('status', 'Active')->findAll();
                $html = $pdf->inventoryReport($inventory);
                break;
                
            case 'inventory_kpis':
                $data = $this->analytics->inventoryTurnoverRatio();
                $headers = ['Product', 'SKU', 'Turnover Ratio', 'Days of Supply', 'Status'];
                $rows = [];
                foreach ($data as $item) {
                    $status = $item['turnover_ratio'] >= 6 ? 'Fast Moving' : ($item['turnover_ratio'] >= 2 ? 'Normal' : 'Slow Moving');
                    $rows[] = [
                        esc($item['product_name']),
                        esc($item['sku']),
                        number_format($item['turnover_ratio'], 2),
                        $item['days_of_supply'] == 999 ? '∞' : number_format($item['days_of_supply'], 0),
                        $status
                    ];
                }
                $html = $pdf->setTitle('Inventory KPIs Report')->buildReportHtml(
                    'Inventory Turnover Analysis',
                    'KPI metrics for inventory performance',
                    $headers, $rows, []
                );
                break;
                
            case 'low_stock':
            case 'reorder':
                $data = $this->analytics->calculateReorderPoints();
                $lowStock = array_filter($data, fn($item) => $item['needs_reorder']);
                $html = $pdf->lowStockReport($lowStock);
                break;
                
            case 'warehouse':
                $data = $this->analytics->warehousePerformance();
                $headers = ['Warehouse', 'Location', 'Items', 'Utilization %', 'Total Value'];
                $rows = [];
                foreach ($data['warehouses'] ?? [] as $wh) {
                    $rows[] = [
                        esc($wh['name']),
                        esc($wh['location']),
                        number_format($wh['item_count']),
                        number_format($wh['utilization'], 1) . '%',
                        '₱' . number_format($wh['total_value'], 2)
                    ];
                }
                $html = $pdf->setTitle('Warehouse Performance Report')->buildReportHtml(
                    'Warehouse Performance Report',
                    'Performance metrics by warehouse',
                    $headers, $rows, 
                    ['summary' => ['Total Warehouses' => count($data['warehouses'] ?? [])]]
                );
                break;
                
            case 'financial':
                $data = $this->analytics->financialKPIs(30);
                $html = $pdf->analyticsReport($data);
                break;
                
            default: // summary
                $data = $this->analytics->getAnalyticsSummary();
                $html = $pdf->analyticsReport($data);
                break;
        }

        $this->audit->logReportGeneration("Analytics Report: {$type}", 'PDF');

        return $pdf->generateFromHtml($html, "analytics_{$type}_" . date('Y-m-d'));
    }
}
