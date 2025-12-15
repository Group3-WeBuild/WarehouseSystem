<?php
/**
 * =====================================================
 * INVENTORY AUDITOR CONTROLLER
 * =====================================================
 * 
 * Purpose: Physical inventory count and reconciliation
 * 
 * RUBRIC: Inventory Auditor Module (Midterm)
 * "Inventory Auditor: Conducts regular checks and
 *  reconciliations of physical vs. system records"
 * 
 * Key Functions:
 * - Start/manage physical count sessions
 * - Record item counts
 * - Identify discrepancies
 * - Reconciliation workflow
 * - Generate audit reports
 * =====================================================
 */

namespace App\Controllers;

use App\Models\PhysicalCountModel;
use App\Models\CountDetailsModel;
use App\Models\InventoryModel;
use App\Models\WarehouseModel;
use App\Models\AuditTrailModel;

class InventoryAuditor extends BaseController
{
    protected $session;
    protected $physicalCountModel;
    protected $countDetailsModel;
    protected $inventoryModel;
    protected $warehouseModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
        
        $this->physicalCountModel = new PhysicalCountModel();
        $this->countDetailsModel = new CountDetailsModel();
        $this->inventoryModel = new InventoryModel();
        $this->warehouseModel = new WarehouseModel();
    }

    /**
     * =====================================================
     * SECURITY CHECK
     * =====================================================
     */
    private function checkAuth()
    {
        if (!$this->session->get('isLoggedIn')) {
            $this->session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to(base_url('login'));
        }

        $userRole = $this->session->get('role');
        $allowedRoles = ['Inventory Auditor', 'Warehouse Manager', 'IT Administrator', 'Top Management'];
        
        if (!in_array($userRole, $allowedRoles)) {
            $this->session->setFlashdata('error', 'You do not have permission to access this page.');
            return redirect()->to(base_url('dashboard'));
        }

        return null;
    }

    /**
     * =====================================================
     * VIEW: Auditor Dashboard
     * =====================================================
     */
    public function dashboard()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $stats = $this->physicalCountModel->getCountStatistics();
        $inProgressCounts = $this->physicalCountModel->getCountsInProgress();
        $discrepancyCounts = $this->physicalCountModel->getCountsWithDiscrepancies();
        $recentCounts = $this->physicalCountModel->getAllCountsWithDetails();
        
        $data = [
            'title' => 'Inventory Auditor Dashboard',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'stats' => $stats,
            'inProgressCounts' => $inProgressCounts,
            'discrepancyCounts' => $discrepancyCounts,
            'recentCounts' => array_slice($recentCounts, 0, 10)
        ];

        return view('inventory_auditor/dashboard', $data);
    }

    /**
     * =====================================================
     * VIEW: All Count Sessions
     * =====================================================
     */
    public function countSessions()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $counts = $this->physicalCountModel->getAllCountsWithDetails();
        $warehouses = $this->warehouseModel->findAll();
        
        $data = [
            'title' => 'Physical Count Sessions',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'counts' => $counts,
            'warehouses' => $warehouses
        ];

        return view('inventory_auditor/count_sessions', $data);
    }

    /**
     * =====================================================
     * VIEW: Active Count Session
     * =====================================================
     */
    public function activeCount($countId)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $count = $this->physicalCountModel->find($countId);
        if (!$count) {
            $this->session->setFlashdata('error', 'Count session not found.');
            return redirect()->to(base_url('inventory-auditor/count-sessions'));
        }

        $countDetails = $this->countDetailsModel->getDetailsForCount($countId);
        $discrepancyStats = $this->countDetailsModel->getDiscrepancyStats($countId);
        
        // Get items not yet counted for this warehouse
        $inventoryItems = $this->inventoryModel
            ->where('status', 'Active')
            ->findAll();

        $countedItemIds = array_column($countDetails, 'inventory_id');
        $uncountedItems = array_filter($inventoryItems, function($item) use ($countedItemIds) {
            return !in_array($item['id'], $countedItemIds);
        });
        
        $data = [
            'title' => 'Physical Count: ' . $count['count_number'],
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'count' => $count,
            'countDetails' => $countDetails,
            'discrepancyStats' => $discrepancyStats,
            'uncountedItems' => $uncountedItems
        ];

        return view('inventory_auditor/active_count', $data);
    }

    /**
     * =====================================================
     * VIEW: Discrepancy Review
     * =====================================================
     */
    public function discrepancyReview()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $countsWithDiscrepancies = $this->physicalCountModel->getCountsWithDiscrepancies();
        
        $allDiscrepancies = [];
        foreach ($countsWithDiscrepancies as $count) {
            $discrepancies = $this->countDetailsModel->getDiscrepanciesForCount($count['id']);
            foreach ($discrepancies as $d) {
                $d['count_number'] = $count['count_number'];
                $allDiscrepancies[] = $d;
            }
        }
        
        $data = [
            'title' => 'Discrepancy Review',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'discrepancies' => $allDiscrepancies,
            'pendingCounts' => $countsWithDiscrepancies
        ];

        return view('inventory_auditor/discrepancy_review', $data);
    }

    /**
     * =====================================================
     * AJAX: Start New Count Session
     * =====================================================
     */
    public function startCount()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $warehouseId = $this->request->getPost('warehouse_id');
        $countType = $this->request->getPost('count_type') ?? 'Full Count';
        $userId = $this->session->get('user_id');
        
        if (!$warehouseId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please select a warehouse'
            ]);
        }
        
        $countId = $this->physicalCountModel->startCountSession($warehouseId, $userId, $countType);
        
        if ($countId) {
            // Log audit
            AuditTrailModel::logAction([
                'user_id' => $userId,
                'username' => $this->session->get('username'),
                'module' => 'Inventory Audit',
                'controller' => 'InventoryAuditor',
                'action' => 'CREATE',
                'table_name' => 'physical_counts',
                'record_id' => $countId,
                'description' => 'Started new physical count session',
                'status' => 'Success',
                'endpoint' => '/inventory-auditor/start-count'
            ]);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Count session started successfully',
                'count_id' => $countId,
                'redirect' => base_url("inventory-auditor/active-count/{$countId}")
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to start count session'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Record Item Count
     * =====================================================
     */
    public function recordItemCount()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $countId = $this->request->getPost('count_id');
        $inventoryId = $this->request->getPost('inventory_id');
        $physicalQuantity = $this->request->getPost('physical_quantity');
        $userId = $this->session->get('user_id');
        
        // Validate
        if (!$countId || !$inventoryId || $physicalQuantity === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Missing required fields'
            ]);
        }
        
        $result = $this->countDetailsModel->recordCount(
            $countId,
            $inventoryId,
            (int)$physicalQuantity,
            $userId
        );
        
        if ($result) {
            // Get updated stats
            $stats = $this->countDetailsModel->getDiscrepancyStats($countId);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Count recorded successfully',
                'stats' => $stats
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to record count'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Complete Count Session
     * =====================================================
     */
    public function completeCount()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $countId = $this->request->getPost('count_id');
        $stats = $this->countDetailsModel->getDiscrepancyStats($countId);
        
        $result = $this->physicalCountModel->completeCountSession(
            $countId,
            $stats['total_counted'],
            $stats['shortages'] + $stats['overages']
        );
        
        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Count session completed',
                'has_discrepancies' => ($stats['shortages'] + $stats['overages']) > 0
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to complete count session'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Verify Count Detail
     * =====================================================
     */
    public function verifyCount()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $detailId = $this->request->getPost('detail_id');
        $userId = $this->session->get('user_id');
        
        $result = $this->countDetailsModel->verifyCount($detailId, $userId);
        
        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'Count verified' : 'Verification failed'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Resolve Discrepancy
     * =====================================================
     */
    public function resolveDiscrepancy()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $detailId = $this->request->getPost('detail_id');
        $action = $this->request->getPost('action');
        $notes = $this->request->getPost('notes') ?? '';
        $adjustInventory = $this->request->getPost('adjust_inventory');
        
        // Resolve discrepancy
        $result = $this->countDetailsModel->resolveDiscrepancy($detailId, $action, $notes);
        
        // Optionally adjust system inventory to match physical count
        if ($adjustInventory === 'yes') {
            $detail = $this->countDetailsModel->find($detailId);
            if ($detail) {
                $this->inventoryModel->update($detail['inventory_id'], [
                    'quantity' => $detail['physical_quantity']
                ]);
                
                // Log the adjustment
                $stockMovementModel = new \App\Models\StockMovementModel();
                $stockMovementModel->insert([
                    'item_id' => $detail['inventory_id'],
                    'movement_type' => 'Adjustment',
                    'quantity' => $detail['discrepancy'],
                    'reference_number' => 'AUDIT-' . date('YmdHis'),
                    'user_id' => $this->session->get('user_id'),
                    'notes' => "Physical count adjustment: {$notes}",
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'Discrepancy resolved' : 'Resolution failed'
        ]);
    }

    /**
     * =====================================================
     * AJAX: Approve Count Session
     * =====================================================
     */
    public function approveCount()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $countId = $this->request->getPost('count_id');
        $userId = $this->session->get('user_id');
        
        $result = $this->physicalCountModel->approveCountSession($countId, $userId);
        
        return $this->response->setJSON([
            'success' => $result,
            'message' => $result ? 'Count approved successfully' : 'Approval failed'
        ]);
    }

    /**
     * =====================================================
     * VIEW: Audit Reports
     * =====================================================
     */
    public function reports()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $stats = $this->physicalCountModel->getCountStatistics();
        
        // Get accuracy trend (last 6 months)
        $recentCounts = $this->physicalCountModel->orderBy('count_start_date', 'DESC')
                                                 ->limit(20)
                                                 ->findAll();
        
        $accuracyTrend = [];
        foreach ($recentCounts as $count) {
            $detailStats = $this->countDetailsModel->getDiscrepancyStats($count['id']);
            $accuracyTrend[] = [
                'count_number' => $count['count_number'],
                'date' => $count['count_start_date'],
                'accuracy' => $detailStats['accuracy_rate']
            ];
        }
        
        $data = [
            'title' => 'Audit Reports',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'role' => $this->session->get('role')
            ],
            'stats' => $stats,
            'accuracyTrend' => array_reverse($accuracyTrend)
        ];

        return view('inventory_auditor/reports', $data);
    }

    /**
     * =====================================================
     * PRINT/EXPORT PDF REPORTS
     * =====================================================
     */

    /**
     * Print Inventory Audit Report as PDF
     */
    public function printAuditReport()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // Get filter parameters
        $status = $this->request->getGet('status');
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d', strtotime('-90 days'));
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $builder = $this->physicalCountModel->builder();
        $builder->select('physical_counts.*, warehouses.name as warehouse_name');
        $builder->join('warehouses', 'warehouses.id = physical_counts.warehouse_id', 'left');
        $builder->where('DATE(physical_counts.count_start_date) >=', $startDate);
        $builder->where('DATE(physical_counts.count_start_date) <=', $endDate);
        
        if ($status) {
            $builder->where('physical_counts.status', $status);
        }
        
        $builder->orderBy('physical_counts.count_start_date', 'DESC');
        $counts = $builder->get()->getResultArray();

        // Enrich with accuracy data
        foreach ($counts as &$count) {
            $detailStats = $this->countDetailsModel->getDiscrepancyStats($count['id']);
            $count['total_items'] = $detailStats['total_items'] ?? 0;
            $count['accuracy_rate'] = $detailStats['accuracy_rate'] ?? 0;
        }

        $stats = $this->physicalCountModel->getCountStatistics();

        $pdfService = new \App\Libraries\PdfService();
        $html = $pdfService->inventoryAuditReport($counts, [
            'Completed' => $stats['completed'] ?? 0,
            'In Progress' => $stats['in_progress'] ?? 0,
            'Avg. Accuracy' => number_format($stats['avg_accuracy'] ?? 0, 1) . '%'
        ]);

        return $pdfService->generateFromHtml($html, 'inventory_audit_report_' . date('Y-m-d'), false);
    }

    /**
     * Print Discrepancy Report as PDF
     */
    public function printDiscrepancyReport()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $countId = $this->request->getGet('count_id');
        $status = $this->request->getGet('status');

        $builder = $this->countDetailsModel->builder();
        $builder->select('physical_count_details.*, inventory_items.item_code, inventory_items.name as item_name');
        $builder->join('inventory_items', 'inventory_items.id = physical_count_details.item_id', 'left');
        
        // Only get items with discrepancies
        $builder->where('physical_count_details.system_quantity != physical_count_details.counted_quantity', null, false);
        
        if ($countId) {
            $builder->where('physical_count_details.physical_count_id', $countId);
        }
        
        if ($status) {
            $builder->where('physical_count_details.status', $status);
        }
        
        $builder->orderBy('ABS(physical_count_details.counted_quantity - physical_count_details.system_quantity)', 'DESC');
        $discrepancies = $builder->get()->getResultArray();

        $pdfService = new \App\Libraries\PdfService();
        $html = $pdfService->discrepancyReport($discrepancies);

        return $pdfService->generateFromHtml($html, 'discrepancy_report_' . date('Y-m-d'), false);
    }
}
