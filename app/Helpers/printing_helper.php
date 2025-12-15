<?php

/**
 * =====================================================
 * PRINTING HELPER FUNCTIONS
 * =====================================================
 * 
 * These functions help generate printable reports
 * for all modules in the warehouse system
 * =====================================================
 */

if (!function_exists('generate_print_header')) {
    /**
     * Generate HTML header for printable reports
     */
    function generate_print_header($title, $subtitle = '', $logo = true)
    {
        $html = '
        <div class="print-header" style="text-align: center; border-bottom: 3px solid #2c3e50; padding-bottom: 20px; margin-bottom: 30px;">
            ' . ($logo ? '<div class="logo" style="font-size: 32px; font-weight: bold; color: #2c3e50; margin-bottom: 10px;">WITMS</div>' : '') . '
            <h1 style="margin: 10px 0; color: #34495e; font-size: 28px;">' . esc($title) . '</h1>
            ' . ($subtitle ? '<h3 style="margin: 5px 0; color: #7f8c8d; font-size: 18px;">' . esc($subtitle) . '</h3>' : '') . '
            <p style="margin: 10px 0; color: #95a5a6;">Generated on: ' . date('F d, Y h:i A') . '</p>
        </div>';
        
        return $html;
    }
}

if (!function_exists('generate_print_footer')) {
    /**
     * Generate HTML footer for printable reports
     */
    function generate_print_footer($page_number = null)
    {
        $html = '
        <div class="print-footer" style="text-align: center; border-top: 2px solid #ecf0f1; padding-top: 15px; margin-top: 30px; font-size: 12px; color: #7f8c8d;">
            <p>WeBuild Warehouse Inventory and Monitoring System (WITMS)</p>
            ' . ($page_number ? '<p>Page ' . $page_number . '</p>' : '') . '
            <p>&copy; ' . date('Y') . ' WITMS. All Rights Reserved.</p>
        </div>';
        
        return $html;
    }
}

if (!function_exists('generate_print_styles')) {
    /**
     * Generate CSS styles for printable reports
     */
    function generate_print_styles()
    {
        return '
        <style>
            @media print {
                @page {
                    size: A4;
                    margin: 15mm;
                }
                
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12pt;
                    color: #000;
                }
                
                .no-print {
                    display: none !important;
                }
                
                .print-break {
                    page-break-after: always;
                }
                
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                
                table th {
                    background-color: #34495e !important;
                    color: white !important;
                    padding: 10px;
                    text-align: left;
                    font-weight: bold;
                    border: 1px solid #2c3e50;
                }
                
                table td {
                    padding: 8px;
                    border: 1px solid #ddd;
                }
                
                table tr:nth-child(even) {
                    background-color: #f8f9fa;
                }
                
                .summary-box {
                    border: 2px solid #34495e;
                    padding: 15px;
                    margin: 20px 0;
                    background-color: #ecf0f1;
                }
                
                .signature-area {
                    margin-top: 50px;
                    display: flex;
                    justify-content: space-between;
                }
                
                .signature-line {
                    width: 40%;
                    border-top: 1px solid #000;
                    padding-top: 5px;
                    text-align: center;
                }
            }
            
            @media screen {
                body {
                    font-family: Arial, sans-serif;
                    max-width: 210mm;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: white;
                }
                
                .print-button-container {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 1000;
                }
                
                .btn-print {
                    background-color: #3498db;
                    color: white;
                    border: none;
                    padding: 12px 24px;
                    font-size: 16px;
                    border-radius: 5px;
                    cursor: pointer;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                }
                
                .btn-print:hover {
                    background-color: #2980b9;
                }
                
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                
                table th {
                    background-color: #34495e;
                    color: white;
                    padding: 12px;
                    text-align: left;
                }
                
                table td {
                    padding: 10px;
                    border: 1px solid #ddd;
                }
                
                table tr:nth-child(even) {
                    background-color: #f8f9fa;
                }
            }
        </style>';
    }
}

if (!function_exists('generate_inventory_report_html')) {
    /**
     * Generate HTML for inventory report
     */
    function generate_inventory_report_html($inventory_items, $stats = [])
    {
        helper('printing_helper');
        
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Inventory Report</title>
            ' . generate_print_styles() . '
        </head>
        <body>
            <div class="print-button-container no-print">
                <button class="btn-print" onclick="window.print()">🖨️ Print Report</button>
                <button class="btn-print" onclick="window.close()" style="background-color: #95a5a6;">✕ Close</button>
            </div>
            
            ' . generate_print_header('Inventory Report', 'Complete Stock Status') . '
            
            <div class="summary-box">
                <h3>Summary Statistics</h3>
                <table style="border: none;">
                    <tr>
                        <td style="border: none;"><strong>Total Items:</strong></td>
                        <td style="border: none;">' . ($stats['totalItems'] ?? count($inventory_items)) . '</td>
                        <td style="border: none;"><strong>Total Value:</strong></td>
                        <td style="border: none;">₱' . number_format($stats['totalValue'] ?? 0, 2) . '</td>
                    </tr>
                    <tr>
                        <td style="border: none;"><strong>In Stock:</strong></td>
                        <td style="border: none;">' . ($stats['inStock'] ?? 0) . '</td>
                        <td style="border: none;"><strong>Low Stock:</strong></td>
                        <td style="border: none;">' . ($stats['lowStock'] ?? 0) . '</td>
                    </tr>
                </table>
            </div>
            
            <h3>Inventory Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>SKU</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Value</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';
        
        $counter = 1;
        foreach ($inventory_items as $item) {
            $total_value = ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            $status = ($item['quantity'] ?? 0) == 0 ? 'Out' : (($item['quantity'] ?? 0) <= ($item['reorder_level'] ?? 0) ? 'Low' : 'OK');
            
            $html .= '
                    <tr>
                        <td>' . $counter++ . '</td>
                        <td>' . esc($item['sku'] ?? '') . '</td>
                        <td>' . esc($item['product_name'] ?? '') . '</td>
                        <td>' . esc($item['category'] ?? '') . '</td>
                        <td>' . number_format($item['quantity'] ?? 0) . '</td>
                        <td>₱' . number_format($item['unit_price'] ?? 0, 2) . '</td>
                        <td>₱' . number_format($total_value, 2) . '</td>
                        <td>' . $status . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="signature-area">
                <div class="signature-line">
                    <p>Prepared By</p>
                    <p style="margin-top: 30px;">_________________________</p>
                    <p><small>Warehouse Manager</small></p>
                </div>
                <div class="signature-line">
                    <p>Approved By</p>
                    <p style="margin-top: 30px;">_________________________</p>
                    <p><small>Operations Manager</small></p>
                </div>
            </div>
            
            ' . generate_print_footer() . '
        </body>
        </html>';
        
        return $html;
    }
}

if (!function_exists('generate_invoice_report_html')) {
    /**
     * Generate HTML for invoice report
     */
    function generate_invoice_report_html($invoices, $type = 'receivable')
    {
        helper('printing_helper');
        
        $title = $type == 'receivable' ? 'Accounts Receivable Report' : 'Accounts Payable Report';
        
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . $title . '</title>
            ' . generate_print_styles() . '
        </head>
        <body>
            <div class="print-button-container no-print">
                <button class="btn-print" onclick="window.print()">🖨️ Print Report</button>
                <button class="btn-print" onclick="window.close()" style="background-color: #95a5a6;">✕ Close</button>
            </div>
            
            ' . generate_print_header($title, 'Invoice Status Report') . '
            
            <h3>Invoice List</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice No.</th>
                        <th>Date</th>
                        <th>Due Date</th>
                        <th>' . ($type == 'receivable' ? 'Client' : 'Vendor') . '</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';
        
        $counter = 1;
        $total = 0;
        foreach ($invoices as $invoice) {
            $amount = $invoice['amount'] ?? 0;
            $total += $amount;
            
            $html .= '
                    <tr>
                        <td>' . $counter++ . '</td>
                        <td>' . esc($invoice['invoice_number'] ?? '') . '</td>
                        <td>' . date('M d, Y', strtotime($invoice['invoice_date'] ?? $invoice['issue_date'] ?? 'now')) . '</td>
                        <td>' . date('M d, Y', strtotime($invoice['due_date'] ?? 'now')) . '</td>
                        <td>' . esc($invoice['client_name'] ?? $invoice['vendor_name'] ?? '') . '</td>
                        <td>₱' . number_format($amount, 2) . '</td>
                        <td>' . esc($invoice['status'] ?? 'Pending') . '</td>
                    </tr>';
        }
        
        $html .= '
                    <tr style="font-weight: bold; background-color: #34495e; color: white;">
                        <td colspan="5" style="text-align: right;">TOTAL:</td>
                        <td colspan="2">₱' . number_format($total, 2) . '</td>
                    </tr>
                </tbody>
            </table>
            
            ' . generate_print_footer() . '
        </body>
        </html>';
        
        return $html;
    }
}
