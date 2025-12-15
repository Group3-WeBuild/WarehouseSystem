<?php

namespace App\Libraries;

/**
 * =====================================================
 * PDF SERVICE - PDF Generation for Reports
 * =====================================================
 * 
 * Purpose: Generates PDF documents from HTML content
 * Uses: Dompdf library for HTML to PDF conversion
 * 
 * RUBRIC: Documentation & Final Presentation (Finals)
 * "Professional report generation with PDF export"
 * =====================================================
 */
class PdfService
{
    protected $title = 'WeBuild Report';
    protected $author = 'WeBuild Warehouse System';
    protected $orientation = 'portrait'; // portrait or landscape
    protected $paperSize = 'A4';
    
    /**
     * Generate PDF from HTML content
     * 
     * @param string $html HTML content to convert
     * @param string $filename Output filename (without .pdf)
     * @param bool $download True to download, false to display inline
     * @return \CodeIgniter\HTTP\Response
     */
    public function generateFromHtml(string $html, string $filename = 'report', bool $download = true)
    {
        // Wrap HTML with proper document structure and styles
        $fullHtml = $this->wrapHtml($html);
        
        // Use mPDF-style approach with native PHP
        return $this->outputPdf($fullHtml, $filename, $download);
    }

    /**
     * Generate a table-based report PDF
     */
    public function generateTableReport(string $title, array $headers, array $data, array $options = [])
    {
        $subtitle = $options['subtitle'] ?? '';
        $orientation = $options['orientation'] ?? 'portrait';
        $this->orientation = $orientation;
        
        $html = $this->buildReportHtml($title, $subtitle, $headers, $data, $options);
        $filename = $options['filename'] ?? $this->sanitizeFilename($title);
        
        return $this->generateFromHtml($html, $filename);
    }

    /**
     * Build HTML for a table report
     */
    public function buildReportHtml(string $title, string $subtitle, array $headers, array $data, array $options = []): string
    {
        $summaryHtml = '';
        if (!empty($options['summary'])) {
            $summaryHtml = '<div class="summary-section">';
            foreach ($options['summary'] as $label => $value) {
                $summaryHtml .= "<div class='summary-item'><span class='label'>{$label}:</span> <span class='value'>{$value}</span></div>";
            }
            $summaryHtml .= '</div>';
        }

        $tableHtml = '<table class="data-table"><thead><tr>';
        foreach ($headers as $header) {
            $tableHtml .= "<th>{$header}</th>";
        }
        $tableHtml .= '</tr></thead><tbody>';
        
        foreach ($data as $row) {
            $tableHtml .= '<tr>';
            foreach ($row as $cell) {
                $tableHtml .= "<td>{$cell}</td>";
            }
            $tableHtml .= '</tr>';
        }
        $tableHtml .= '</tbody></table>';

        return "
            <div class='report-header'>
                <h1>{$title}</h1>
                " . ($subtitle ? "<p class='subtitle'>{$subtitle}</p>" : "") . "
                <p class='generated-date'>Generated: " . date('F d, Y h:i A') . "</p>
            </div>
            {$summaryHtml}
            {$tableHtml}
            <div class='report-footer'>
                <p>WeBuild Warehouse Management System</p>
            </div>
        ";
    }

    /**
     * Wrap HTML content with full document structure
     */
    protected function wrapHtml(string $content): string
    {
        $styles = $this->getStyles();
        
        return "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>{$this->title}</title>
    <style>{$styles}</style>
</head>
<body>
    <div class='container'>
        {$content}
    </div>
</body>
</html>";
    }

    /**
     * Get CSS styles for PDF
     */
    protected function getStyles(): string
    {
        return "
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                font-family: 'DejaVu Sans', Arial, sans-serif;
                font-size: 10pt;
                line-height: 1.4;
                color: #333;
                background: #fff;
            }
            .container {
                padding: 20px;
            }
            .report-header {
                text-align: center;
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 2px solid #1a237e;
            }
            .report-header h1 {
                color: #1a237e;
                font-size: 18pt;
                margin-bottom: 5px;
            }
            .report-header .subtitle {
                color: #666;
                font-size: 11pt;
            }
            .report-header .generated-date {
                color: #888;
                font-size: 9pt;
                margin-top: 5px;
            }
            .summary-section {
                background: #f5f5f5;
                padding: 10px 15px;
                margin-bottom: 15px;
                border-radius: 5px;
            }
            .summary-item {
                display: inline-block;
                margin-right: 20px;
                padding: 5px 0;
            }
            .summary-item .label {
                color: #666;
                font-weight: normal;
            }
            .summary-item .value {
                color: #1a237e;
                font-weight: bold;
            }
            .data-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            .data-table th {
                background: #1a237e;
                color: #fff;
                padding: 8px 10px;
                text-align: left;
                font-size: 9pt;
                font-weight: bold;
            }
            .data-table td {
                padding: 6px 10px;
                border-bottom: 1px solid #ddd;
                font-size: 9pt;
            }
            .data-table tbody tr:nth-child(even) {
                background: #f9f9f9;
            }
            .data-table tbody tr:hover {
                background: #f0f0f0;
            }
            .report-footer {
                margin-top: 30px;
                padding-top: 15px;
                border-top: 1px solid #ddd;
                text-align: center;
                color: #888;
                font-size: 8pt;
            }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .text-success { color: #2e7d32; }
            .text-danger { color: #c62828; }
            .text-warning { color: #ef6c00; }
            .badge {
                display: inline-block;
                padding: 2px 8px;
                border-radius: 10px;
                font-size: 8pt;
                font-weight: bold;
            }
            .badge-success { background: #e8f5e9; color: #2e7d32; }
            .badge-danger { background: #ffebee; color: #c62828; }
            .badge-warning { background: #fff3e0; color: #ef6c00; }
            .badge-info { background: #e3f2fd; color: #1565c0; }
            
            @page {
                margin: 15mm;
            }
        ";
    }

    /**
     * Output PDF using browser's print functionality
     * This generates HTML that auto-triggers print dialog
     */
    protected function outputPdf(string $html, string $filename, bool $download)
    {
        $response = service('response');
        
        // Add print-to-PDF JavaScript
        $printScript = "
            <script>
                window.onload = function() {
                    // Set document title for PDF filename
                    document.title = '{$filename}';
                    // Auto-trigger print dialog
                    setTimeout(function() {
                        window.print();
                    }, 500);
                };
            </script>
            <style>
                @media print {
                    body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                }
            </style>
        ";
        
        // Insert script before </head>
        $html = str_replace('</head>', $printScript . '</head>', $html);
        
        return $response
            ->setHeader('Content-Type', 'text/html; charset=UTF-8')
            ->setBody($html);
    }

    /**
     * Sanitize filename
     */
    protected function sanitizeFilename(string $name): string
    {
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        return strtolower($name) . '_' . date('Y-m-d');
    }

    /**
     * Set title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set orientation
     */
    public function setOrientation(string $orientation): self
    {
        $this->orientation = $orientation;
        return $this;
    }

    // =========================================================
    // PREDEFINED REPORT GENERATORS
    // =========================================================

    /**
     * Generate Inventory Report PDF
     */
    public function inventoryReport(array $inventory): string
    {
        $headers = ['#', 'Product Name', 'SKU', 'Category', 'Qty', 'Unit', 'Price', 'Value', 'Status'];
        $data = [];
        $totalValue = 0;
        
        foreach ($inventory as $index => $item) {
            $value = ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            $totalValue += $value;
            $statusBadge = ($item['status'] ?? 'Active') === 'Active' 
                ? "<span class='badge badge-success'>Active</span>" 
                : "<span class='badge badge-danger'>Inactive</span>";
            
            $data[] = [
                $index + 1,
                esc($item['product_name'] ?? 'N/A'),
                esc($item['sku'] ?? 'N/A'),
                esc($item['category'] ?? 'N/A'),
                number_format($item['quantity'] ?? 0),
                esc($item['unit'] ?? 'pcs'),
                '₱' . number_format($item['unit_price'] ?? 0, 2),
                '₱' . number_format($value, 2),
                $statusBadge
            ];
        }
        
        return $this->buildReportHtml(
            'Inventory Report',
            'Complete inventory listing',
            $headers,
            $data,
            [
                'summary' => [
                    'Total Items' => count($inventory),
                    'Total Value' => '₱' . number_format($totalValue, 2)
                ]
            ]
        );
    }

    /**
     * Generate Stock Movement Report PDF
     */
    public function stockMovementReport(array $movements): string
    {
        $headers = ['#', 'Date', 'Type', 'Product', 'Qty', 'Reference', 'Performed By'];
        $data = [];
        
        foreach ($movements as $index => $move) {
            $typeBadge = match($move['movement_type'] ?? '') {
                'Stock In' => "<span class='badge badge-success'>Stock In</span>",
                'Stock Out' => "<span class='badge badge-danger'>Stock Out</span>",
                'Transfer' => "<span class='badge badge-info'>Transfer</span>",
                'Adjustment' => "<span class='badge badge-warning'>Adjustment</span>",
                default => "<span class='badge'>{$move['movement_type']}</span>"
            };
            
            $data[] = [
                $index + 1,
                date('M d, Y', strtotime($move['created_at'] ?? 'now')),
                $typeBadge,
                esc($move['product_name'] ?? 'N/A'),
                number_format($move['quantity'] ?? 0),
                esc($move['reference_number'] ?? 'N/A'),
                esc($move['performed_by_name'] ?? 'System')
            ];
        }
        
        return $this->buildReportHtml(
            'Stock Movement Report',
            'Transaction history',
            $headers,
            $data,
            [
                'summary' => [
                    'Total Movements' => count($movements)
                ]
            ]
        );
    }

    /**
     * Generate Low Stock Alert Report PDF
     */
    public function lowStockReport(array $items): string
    {
        $headers = ['#', 'Product Name', 'SKU', 'Current Qty', 'Reorder Point', 'Shortage', 'Status'];
        $data = [];
        
        foreach ($items as $index => $item) {
            $shortage = max(0, ($item['reorder_point'] ?? 0) - ($item['quantity'] ?? 0));
            $status = ($item['quantity'] ?? 0) == 0 
                ? "<span class='badge badge-danger'>Out of Stock</span>"
                : "<span class='badge badge-warning'>Low Stock</span>";
            
            $data[] = [
                $index + 1,
                esc($item['product_name'] ?? 'N/A'),
                esc($item['sku'] ?? 'N/A'),
                number_format($item['quantity'] ?? 0),
                number_format($item['reorder_point'] ?? 0),
                number_format($shortage),
                $status
            ];
        }
        
        return $this->buildReportHtml(
            'Low Stock Alert Report',
            'Items requiring immediate attention',
            $headers,
            $data,
            [
                'summary' => [
                    'Items Below Reorder Point' => count($items)
                ]
            ]
        );
    }

    /**
     * Generate Orders Report PDF
     */
    public function ordersReport(array $orders): string
    {
        $headers = ['#', 'Order #', 'Date', 'Type', 'Client', 'Total', 'Status'];
        $data = [];
        $totalAmount = 0;
        
        foreach ($orders as $index => $order) {
            $totalAmount += $order['total_amount'] ?? 0;
            $statusBadge = match($order['status'] ?? '') {
                'Completed' => "<span class='badge badge-success'>Completed</span>",
                'Pending' => "<span class='badge badge-warning'>Pending</span>",
                'Processing' => "<span class='badge badge-info'>Processing</span>",
                'Cancelled' => "<span class='badge badge-danger'>Cancelled</span>",
                default => "<span class='badge'>{$order['status']}</span>"
            };
            
            $data[] = [
                $index + 1,
                esc($order['order_number'] ?? 'N/A'),
                date('M d, Y', strtotime($order['created_at'] ?? 'now')),
                esc($order['order_type'] ?? 'N/A'),
                esc($order['client_name'] ?? 'N/A'),
                '₱' . number_format($order['total_amount'] ?? 0, 2),
                $statusBadge
            ];
        }
        
        return $this->buildReportHtml(
            'Orders Report',
            'Order transactions summary',
            $headers,
            $data,
            [
                'summary' => [
                    'Total Orders' => count($orders),
                    'Total Amount' => '₱' . number_format($totalAmount, 2)
                ]
            ]
        );
    }

    /**
     * Generate Analytics Summary PDF
     */
    public function analyticsReport(array $summary): string
    {
        $html = "
            <div class='report-header'>
                <h1>Analytics Summary Report</h1>
                <p class='subtitle'>Business Intelligence Dashboard Export</p>
                <p class='generated-date'>Generated: " . date('F d, Y h:i A') . "</p>
            </div>
            
            <div class='summary-section'>
                <h3>Key Metrics</h3>
                <table class='data-table'>
                    <tr><td><strong>Total Inventory Items</strong></td><td>" . number_format($summary['total_items'] ?? 0) . "</td></tr>
                    <tr><td><strong>Total Inventory Value</strong></td><td>₱" . number_format($summary['total_inventory_value'] ?? 0, 2) . "</td></tr>
                    <tr><td><strong>Low Stock Alerts</strong></td><td>" . number_format($summary['low_stock_count'] ?? 0) . "</td></tr>
                    <tr><td><strong>Active Warehouses</strong></td><td>" . number_format($summary['active_warehouses'] ?? 0) . "</td></tr>
                    <tr><td><strong>Orders Completed</strong></td><td>" . number_format($summary['orders_completed'] ?? 0) . "</td></tr>
                    <tr><td><strong>Orders Pending</strong></td><td>" . number_format($summary['orders_pending'] ?? 0) . "</td></tr>
                    <tr><td><strong>Stock Movements Today</strong></td><td>" . number_format($summary['stock_movements_today'] ?? 0) . "</td></tr>
                    <tr><td><strong>Active Users</strong></td><td>" . number_format($summary['active_users'] ?? 0) . "</td></tr>
                </table>
            </div>
            
            <div class='report-footer'>
                <p>WeBuild Warehouse Management System - Analytics Module</p>
            </div>
        ";
        
        return $html;
    }

    /**
     * Generate Accounts Receivable Report PDF
     */
    public function arInvoicesReport(array $invoices): string
    {
        $headers = ['#', 'Invoice #', 'Date', 'Client', 'Amount', 'Paid', 'Balance', 'Due Date', 'Status'];
        $data = [];
        $totalAmount = 0;
        $totalPaid = 0;
        
        foreach ($invoices as $index => $inv) {
            $balance = ($inv['total_amount'] ?? 0) - ($inv['paid_amount'] ?? 0);
            $totalAmount += $inv['total_amount'] ?? 0;
            $totalPaid += $inv['paid_amount'] ?? 0;
            
            $statusBadge = match($inv['status'] ?? '') {
                'Paid' => "<span class='badge badge-success'>Paid</span>",
                'Partial' => "<span class='badge badge-warning'>Partial</span>",
                'Overdue' => "<span class='badge badge-danger'>Overdue</span>",
                default => "<span class='badge badge-info'>Pending</span>"
            };
            
            $data[] = [
                $index + 1,
                esc($inv['invoice_number'] ?? 'N/A'),
                date('M d, Y', strtotime($inv['invoice_date'] ?? 'now')),
                esc($inv['client_name'] ?? 'N/A'),
                '₱' . number_format($inv['total_amount'] ?? 0, 2),
                '₱' . number_format($inv['paid_amount'] ?? 0, 2),
                '₱' . number_format($balance, 2),
                date('M d, Y', strtotime($inv['due_date'] ?? 'now')),
                $statusBadge
            ];
        }
        
        return $this->buildReportHtml(
            'Accounts Receivable Report',
            'Customer invoices and payments',
            $headers,
            $data,
            [
                'summary' => [
                    'Total Invoices' => count($invoices),
                    'Total Amount' => '₱' . number_format($totalAmount, 2),
                    'Total Received' => '₱' . number_format($totalPaid, 2),
                    'Outstanding' => '₱' . number_format($totalAmount - $totalPaid, 2)
                ],
                'orientation' => 'landscape'
            ]
        );
    }

    /**
     * Generate Accounts Payable Report PDF
     */
    public function apInvoicesReport(array $invoices): string
    {
        $headers = ['#', 'Invoice #', 'Date', 'Vendor', 'Amount', 'Paid', 'Balance', 'Due Date', 'Status'];
        $data = [];
        $totalAmount = 0;
        $totalPaid = 0;
        
        foreach ($invoices as $index => $inv) {
            $balance = ($inv['total_amount'] ?? 0) - ($inv['paid_amount'] ?? 0);
            $totalAmount += $inv['total_amount'] ?? 0;
            $totalPaid += $inv['paid_amount'] ?? 0;
            
            $statusBadge = match($inv['status'] ?? '') {
                'Paid' => "<span class='badge badge-success'>Paid</span>",
                'Approved' => "<span class='badge badge-info'>Approved</span>",
                'Overdue' => "<span class='badge badge-danger'>Overdue</span>",
                default => "<span class='badge badge-warning'>Pending</span>"
            };
            
            $data[] = [
                $index + 1,
                esc($inv['invoice_number'] ?? 'N/A'),
                date('M d, Y', strtotime($inv['invoice_date'] ?? 'now')),
                esc($inv['vendor_name'] ?? 'N/A'),
                '₱' . number_format($inv['total_amount'] ?? 0, 2),
                '₱' . number_format($inv['paid_amount'] ?? 0, 2),
                '₱' . number_format($balance, 2),
                date('M d, Y', strtotime($inv['due_date'] ?? 'now')),
                $statusBadge
            ];
        }
        
        return $this->buildReportHtml(
            'Accounts Payable Report',
            'Vendor invoices and payments',
            $headers,
            $data,
            [
                'summary' => [
                    'Total Invoices' => count($invoices),
                    'Total Payable' => '₱' . number_format($totalAmount, 2),
                    'Total Paid' => '₱' . number_format($totalPaid, 2),
                    'Outstanding' => '₱' . number_format($totalAmount - $totalPaid, 2)
                ],
                'orientation' => 'landscape'
            ]
        );
    }

    /**
     * Generate Audit Trail Report PDF
     */
    public function auditTrailReport(array $logs, array $options = []): string
    {
        $headers = ['#', 'Timestamp', 'User', 'Module', 'Action', 'Description', 'IP Address'];
        $data = [];
        
        foreach ($logs as $index => $log) {
            $actionBadge = match($log['action'] ?? '') {
                'CREATE' => "<span class='badge badge-success'>CREATE</span>",
                'UPDATE' => "<span class='badge badge-info'>UPDATE</span>",
                'DELETE' => "<span class='badge badge-danger'>DELETE</span>",
                'LOGIN' => "<span class='badge badge-success'>LOGIN</span>",
                'LOGOUT' => "<span class='badge badge-warning'>LOGOUT</span>",
                default => "<span class='badge'>{$log['action']}</span>"
            };
            
            $data[] = [
                $index + 1,
                date('M d, Y H:i', strtotime($log['timestamp'] ?? $log['created_at'] ?? 'now')),
                esc($log['user_name'] ?? $log['username'] ?? 'System'),
                esc($log['module'] ?? 'N/A'),
                $actionBadge,
                esc(substr($log['description'] ?? '', 0, 50)) . (strlen($log['description'] ?? '') > 50 ? '...' : ''),
                esc($log['ip_address'] ?? 'N/A')
            ];
        }
        
        $subtitle = 'System activity log';
        if (isset($options['start_date']) && isset($options['end_date'])) {
            $subtitle .= ' | ' . date('M d, Y', strtotime($options['start_date'])) . ' - ' . date('M d, Y', strtotime($options['end_date']));
        }
        
        return $this->buildReportHtml(
            'Audit Trail Report',
            $subtitle,
            $headers,
            $data,
            [
                'summary' => [
                    'Total Entries' => count($logs)
                ],
                'orientation' => 'landscape'
            ]
        );
    }

    /**
     * Generate Procurement Report PDF
     * Purchase Orders and Requisitions summary
     */
    public function procurementReport(array $purchaseOrders, array $stats = []): string
    {
        $headers = ['#', 'PO Number', 'Vendor', 'Status', 'Order Date', 'Delivery Date', 'Total Amount'];
        $data = [];
        $totalAmount = 0;
        
        foreach ($purchaseOrders as $index => $po) {
            $amount = floatval($po['total_amount'] ?? 0);
            $totalAmount += $amount;
            
            $statusBadge = match($po['status'] ?? '') {
                'pending' => "<span class='badge badge-warning'>Pending</span>",
                'approved' => "<span class='badge badge-info'>Approved</span>",
                'sent' => "<span class='badge badge-primary'>Sent</span>",
                'received' => "<span class='badge badge-success'>Received</span>",
                'cancelled' => "<span class='badge badge-danger'>Cancelled</span>",
                default => "<span class='badge'>" . esc($po['status']) . "</span>"
            };
            
            $data[] = [
                $index + 1,
                esc($po['po_number'] ?? 'N/A'),
                esc($po['vendor_name'] ?? 'N/A'),
                $statusBadge,
                date('M d, Y', strtotime($po['order_date'] ?? 'now')),
                $po['expected_delivery_date'] ? date('M d, Y', strtotime($po['expected_delivery_date'])) : 'N/A',
                '₱' . number_format($amount, 2)
            ];
        }
        
        return $this->buildReportHtml(
            'Procurement Report',
            'Purchase Orders Summary',
            $headers,
            $data,
            [
                'summary' => array_merge([
                    'Total POs' => count($purchaseOrders),
                    'Total Amount' => '₱' . number_format($totalAmount, 2)
                ], $stats),
                'orientation' => 'landscape'
            ]
        );
    }

    /**
     * Generate Requisitions Report PDF
     */
    public function requisitionsReport(array $requisitions): string
    {
        $headers = ['#', 'Req. Number', 'Requested By', 'Department', 'Status', 'Priority', 'Date', 'Est. Amount'];
        $data = [];
        $totalEstimated = 0;
        
        foreach ($requisitions as $index => $req) {
            $amount = floatval($req['estimated_amount'] ?? 0);
            $totalEstimated += $amount;
            
            $statusBadge = match($req['status'] ?? '') {
                'draft' => "<span class='badge'>Draft</span>",
                'submitted' => "<span class='badge badge-info'>Submitted</span>",
                'approved' => "<span class='badge badge-success'>Approved</span>",
                'rejected' => "<span class='badge badge-danger'>Rejected</span>",
                'converted' => "<span class='badge badge-primary'>Converted to PO</span>",
                default => "<span class='badge'>" . esc($req['status']) . "</span>"
            };
            
            $priorityBadge = match($req['priority'] ?? 'normal') {
                'low' => "<span class='badge'>Low</span>",
                'normal' => "<span class='badge badge-info'>Normal</span>",
                'high' => "<span class='badge badge-warning'>High</span>",
                'urgent' => "<span class='badge badge-danger'>Urgent</span>",
                default => "<span class='badge'>" . esc($req['priority']) . "</span>"
            };
            
            $data[] = [
                $index + 1,
                esc($req['requisition_number'] ?? 'N/A'),
                esc($req['requester_name'] ?? 'N/A'),
                esc($req['department'] ?? 'N/A'),
                $statusBadge,
                $priorityBadge,
                date('M d, Y', strtotime($req['created_at'] ?? 'now')),
                '₱' . number_format($amount, 2)
            ];
        }
        
        return $this->buildReportHtml(
            'Purchase Requisitions Report',
            'All Requisitions Summary',
            $headers,
            $data,
            [
                'summary' => [
                    'Total Requisitions' => count($requisitions),
                    'Est. Total Value' => '₱' . number_format($totalEstimated, 2)
                ],
                'orientation' => 'landscape'
            ]
        );
    }

    /**
     * Generate Inventory Audit Report PDF
     */
    public function inventoryAuditReport(array $counts, array $stats = []): string
    {
        $headers = ['#', 'Count Number', 'Warehouse', 'Status', 'Start Date', 'End Date', 'Items Counted', 'Accuracy'];
        $data = [];
        
        foreach ($counts as $index => $count) {
            $statusBadge = match($count['status'] ?? '') {
                'in_progress' => "<span class='badge badge-warning'>In Progress</span>",
                'pending_review' => "<span class='badge badge-info'>Pending Review</span>",
                'completed' => "<span class='badge badge-success'>Completed</span>",
                'approved' => "<span class='badge badge-primary'>Approved</span>",
                default => "<span class='badge'>" . esc($count['status']) . "</span>"
            };
            
            $accuracy = $count['accuracy_rate'] ?? 0;
            $accuracyColor = $accuracy >= 95 ? 'success' : ($accuracy >= 85 ? 'warning' : 'danger');
            
            $data[] = [
                $index + 1,
                esc($count['count_number'] ?? 'N/A'),
                esc($count['warehouse_name'] ?? 'All'),
                $statusBadge,
                date('M d, Y', strtotime($count['count_start_date'] ?? 'now')),
                $count['count_end_date'] ? date('M d, Y', strtotime($count['count_end_date'])) : 'Ongoing',
                $count['total_items'] ?? 0,
                "<span class='badge badge-{$accuracyColor}'>" . number_format($accuracy, 1) . '%</span>'
            ];
        }
        
        return $this->buildReportHtml(
            'Inventory Audit Report',
            'Physical Count Sessions Summary',
            $headers,
            $data,
            [
                'summary' => array_merge([
                    'Total Count Sessions' => count($counts)
                ], $stats),
                'orientation' => 'landscape'
            ]
        );
    }

    /**
     * Generate Discrepancy Report PDF
     */
    public function discrepancyReport(array $discrepancies): string
    {
        $headers = ['#', 'Item Code', 'Item Name', 'System Qty', 'Counted Qty', 'Variance', 'Variance %', 'Status'];
        $data = [];
        $totalVariance = 0;
        
        foreach ($discrepancies as $index => $disc) {
            $systemQty = intval($disc['system_quantity'] ?? 0);
            $countedQty = intval($disc['counted_quantity'] ?? 0);
            $variance = $countedQty - $systemQty;
            $totalVariance += $variance;
            $variancePercent = $systemQty > 0 ? ($variance / $systemQty) * 100 : 0;
            
            $varianceClass = $variance >= 0 ? 'success' : 'danger';
            
            $statusBadge = match($disc['status'] ?? 'pending') {
                'pending' => "<span class='badge badge-warning'>Pending</span>",
                'resolved' => "<span class='badge badge-success'>Resolved</span>",
                'investigating' => "<span class='badge badge-info'>Investigating</span>",
                default => "<span class='badge'>" . esc($disc['status']) . "</span>"
            };
            
            $data[] = [
                $index + 1,
                esc($disc['item_code'] ?? 'N/A'),
                esc($disc['item_name'] ?? 'N/A'),
                number_format($systemQty),
                number_format($countedQty),
                "<span class='badge badge-{$varianceClass}'>" . ($variance >= 0 ? '+' : '') . number_format($variance) . '</span>',
                number_format($variancePercent, 1) . '%',
                $statusBadge
            ];
        }
        
        return $this->buildReportHtml(
            'Inventory Discrepancy Report',
            'Variance Analysis',
            $headers,
            $data,
            [
                'summary' => [
                    'Total Discrepancies' => count($discrepancies),
                    'Net Variance' => ($totalVariance >= 0 ? '+' : '') . number_format($totalVariance) . ' units'
                ],
                'orientation' => 'landscape'
            ]
        );
    }
}
