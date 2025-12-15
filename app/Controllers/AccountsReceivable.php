<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\ClientModel;
use App\Models\PaymentModel;

/**
 * =====================================================
 * ACCOUNTS RECEIVABLE CONTROLLER - BACKEND LOGIC
 * =====================================================
 * This controller handles all backend operations for
 * the Accounts Receivable module including:
 * - Dashboard statistics and data
 * - Invoice management (CRUD operations)
 * - Payment processing and recording
 * - Client management
 * - Reports and analytics
 * =====================================================
 */
class AccountsReceivable extends BaseController
{
    protected $session;
    protected $invoiceModel;
    protected $clientModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
        $this->invoiceModel = new InvoiceModel();
        $this->clientModel = new ClientModel();
        $this->paymentModel = new PaymentModel();
    }

    /**
     * =====================================================
     * AUTHENTICATION & AUTHORIZATION - BACKEND
     * =====================================================
     */
    private function checkAuth()
    {
        if (!$this->session->get('isLoggedIn')) {
            $this->session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to(base_url('login'));
        }

        $userRole = $this->session->get('role');
        $allowedRoles = ['Accounts Receivable Clerk', 'admin', 'Top Management'];
        
        if (!in_array($userRole, $allowedRoles)) {
            $this->session->setFlashdata('error', 'You do not have permission to access this page.');
            return redirect()->to(base_url('dashboard'));
        }

        return null;
    }

    /**
     * =====================================================
     * DASHBOARD - BACKEND DATA PROCESSING
     * =====================================================
     * Fetches and calculates dashboard statistics:
     * - Total outstanding amount
     * - Overdue amount
     * - Pending invoices count
     * - Monthly collections
     * - Recent activities
     * =====================================================
     */
    public function dashboard()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // Calculate statistics
        $totalOutstanding = $this->invoiceModel->getTotalOutstanding();
        $overdueAmount = $this->invoiceModel->getOverdueAmount();
        $pendingInvoices = $this->invoiceModel->getPendingInvoicesCount();
        $monthlyCollections = $this->paymentModel->getMonthlyCollections();
        $recentActivities = $this->invoiceModel->getRecentActivities(10);

        $data = [
            'title' => 'Accounts Receivable Dashboard',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'stats' => [
                'totalOutstanding' => $totalOutstanding,
                'overdueAmount' => $overdueAmount,
                'pendingInvoices' => $pendingInvoices,
                'monthlyCollections' => $monthlyCollections
            ],
            'recentActivities' => $recentActivities
        ];

        return view('account_receivable/dashboard', $data);
    }

    /**
     * =====================================================
     * INVOICE MANAGEMENT - BACKEND OPERATIONS
     * =====================================================
     */
    
    // Display all invoices with client information
    public function manageInvoices()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $invoices = $this->invoiceModel->getAllInvoicesWithClients();
        $clients = $this->clientModel->findAll();

        $data = [
            'title' => 'Manage Invoices',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'invoices' => $invoices,
            'clients' => $clients
        ];

        return view('account_receivable/manage_invoices', $data);
    }

    // BACKEND: Create new invoice with validation
    public function createInvoice()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'client_id' => 'required|integer',
                'issue_date' => 'required|valid_date',
                'due_date' => 'required|valid_date',
                'amount' => 'required|decimal',
                'description' => 'permit_empty|string'
            ];

            if ($this->validate($rules)) {
                $invoiceData = [
                    'client_id' => $this->request->getPost('client_id'),
                    'invoice_number' => $this->generateInvoiceNumber(),
                    'issue_date' => $this->request->getPost('issue_date'),
                    'due_date' => $this->request->getPost('due_date'),
                    'amount' => $this->request->getPost('amount'),
                    'description' => $this->request->getPost('description'),
                    'status' => 'Pending',
                    'created_by' => $this->session->get('username')
                ];

                if ($this->invoiceModel->insert($invoiceData)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Invoice created successfully'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to create invoice'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
        }
    }

    // BACKEND: Update existing invoice
    public function updateInvoice($id = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // Get ID from parameter or request
        $invoiceId = $id ?? $this->request->getUri()->getSegment(3);

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'client_id' => 'required|integer',
                'issue_date' => 'required|valid_date',
                'due_date' => 'required|valid_date',
                'amount' => 'required|decimal',
                'status' => 'required|in_list[Pending,Paid,Overdue,Cancelled]',
                'description' => 'permit_empty|string'
            ];

            if ($this->validate($rules)) {
                $invoiceData = [
                    'client_id' => $this->request->getPost('client_id'),
                    'issue_date' => $this->request->getPost('issue_date'),
                    'due_date' => $this->request->getPost('due_date'),
                    'amount' => $this->request->getPost('amount'),
                    'status' => $this->request->getPost('status'),
                    'description' => $this->request->getPost('description'),
                    'updated_by' => $this->session->get('username')
                ];

                if ($this->invoiceModel->update($invoiceId, $invoiceData)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Invoice updated successfully'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to update invoice'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
        }
    }

    // BACKEND: Delete invoice
    public function deleteInvoice($id = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // Get ID from parameter or request
        $invoiceId = $id ?? $this->request->getUri()->getSegment(3);

        if ($this->invoiceModel->delete($invoiceId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Invoice deleted successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete invoice'
            ]);
        }
    }

    /**
     * =====================================================
     * PAYMENT PROCESSING - BACKEND OPERATIONS
     * =====================================================
     */
    
    // Display payment recording interface
    public function recordPayments()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $pendingInvoices = $this->invoiceModel->getPendingInvoices();
        $recentPayments = $this->paymentModel->getRecentPayments(20);

        $data = [
            'title' => 'Record Payments',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'pendingInvoices' => $pendingInvoices,
            'recentPayments' => $recentPayments
        ];

        return view('account_receivable/record_payments', $data);
    }

    // BACKEND: Create payment record and update invoice status
    public function createPayment()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'invoice_id' => 'required|integer',
                'payment_date' => 'required|valid_date',
                'amount' => 'required|decimal',
                'payment_method' => 'required|in_list[Cash,Check,Bank Transfer,Credit Card,Other]',
                'reference_number' => 'permit_empty|string'
            ];

            if ($this->validate($rules)) {
                $invoiceId = $this->request->getPost('invoice_id');
                $paymentAmount = $this->request->getPost('amount');

                // Get invoice details
                $invoice = $this->invoiceModel->find($invoiceId);
                if (!$invoice) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Invoice not found'
                    ]);
                }

                // Record payment
                $paymentData = [
                    'invoice_id' => $invoiceId,
                    'payment_date' => $this->request->getPost('payment_date'),
                    'amount' => $paymentAmount,
                    'payment_method' => $this->request->getPost('payment_method'),
                    'reference_number' => $this->request->getPost('reference_number'),
                    'notes' => $this->request->getPost('notes'),
                    'recorded_by' => $this->session->get('username')
                ];

                if ($this->paymentModel->insert($paymentData)) {
                    // Update invoice status
                    $totalPaid = $this->paymentModel->getTotalPaidForInvoice($invoiceId);
                    if ($totalPaid >= $invoice['amount']) {
                        $this->invoiceModel->update($invoiceId, ['status' => 'Paid']);
                    }

                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Payment recorded successfully'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to record payment'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
        }
    }

    /**
     * =====================================================
     * CLIENT MANAGEMENT - BACKEND OPERATIONS
     * =====================================================
     */
    
    // Display all clients with statistics
    public function clientManagement()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $clients = $this->clientModel->getAllClientsWithStats();

        $data = [
            'title' => 'Client Management',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'clients' => $clients
        ];

        return view('account_receivable/client_management', $data);
    }

    // BACKEND: Create new client with validation
    public function createClient()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'client_name' => 'required|string|max_length[255]',
                'contact_person' => 'permit_empty|string|max_length[255]',
                'email' => 'required|valid_email',
                'phone' => 'permit_empty|string|max_length[50]',
                'address' => 'permit_empty|string'
            ];

            if ($this->validate($rules)) {
                $clientData = [
                    'client_name' => $this->request->getPost('client_name'),
                    'contact_person' => $this->request->getPost('contact_person'),
                    'email' => $this->request->getPost('email'),
                    'phone' => $this->request->getPost('phone'),
                    'address' => $this->request->getPost('address'),
                    'status' => 'Active',
                    'created_by' => $this->session->get('username')
                ];

                if ($this->clientModel->insert($clientData)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Client created successfully'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to create client'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
        }
    }

    // BACKEND: Update client information
    public function updateClient($id = null)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        // Get ID from parameter or request
        $clientId = $id ?? $this->request->getUri()->getSegment(3);

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'client_name' => 'required|string|max_length[255]',
                'contact_person' => 'permit_empty|string|max_length[255]',
                'email' => 'required|valid_email',
                'phone' => 'permit_empty|string|max_length[50]',
                'address' => 'permit_empty|string',
                'status' => 'required|in_list[Active,Inactive]'
            ];

            if ($this->validate($rules)) {
                $clientData = [
                    'client_name' => $this->request->getPost('client_name'),
                    'contact_person' => $this->request->getPost('contact_person'),
                    'email' => $this->request->getPost('email'),
                    'phone' => $this->request->getPost('phone'),
                    'address' => $this->request->getPost('address'),
                    'status' => $this->request->getPost('status'),
                    'updated_by' => $this->session->get('username')
                ];

                if ($this->clientModel->update($clientId, $clientData)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Client updated successfully'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to update client'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
        }
    }

    /**
     * =====================================================
     * REPORTS & ANALYTICS - BACKEND DATA PROCESSING
     * =====================================================
     */
    
    // Display overdue invoices for follow-up
    public function overdueFollowups()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $overdueInvoices = $this->invoiceModel->getOverdueInvoices();

        $data = [
            'title' => 'Overdue Follow-ups',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'overdueInvoices' => $overdueInvoices
        ];

        return view('account_receivable/overdue_followups', $data);
    }

    // BACKEND: Generate aging report data
    public function agingReport()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $agingData = $this->invoiceModel->getAgingReport();

        $data = [
            'title' => 'Aging Report',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'agingData' => $agingData
        ];

        return view('account_receivable/aging_report', $data);
    }

    // BACKEND: Compile analytics and statistics
    public function reportsAnalytics()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $monthlyRevenue = $this->paymentModel->getMonthlyRevenue();
        $clientStats = $this->clientModel->getClientStatistics();
        $paymentMethodStats = $this->paymentModel->getPaymentMethodStats();

        $data = [
            'title' => 'Reports & Analytics',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'monthlyRevenue' => $monthlyRevenue,
            'clientStats' => $clientStats,
            'paymentMethodStats' => $paymentMethodStats
        ];

        return view('account_receivable/reports_analytics', $data);
    }

    public function settings()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'title' => 'Settings',
            'user' => [
                'username' => $this->session->get('username'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ]
        ];

        return view('account_receivable/settings', $data);
    }

    /**
     * =====================================================
     * UTILITY FUNCTIONS - BACKEND HELPERS
     * =====================================================
     */
    
    // BACKEND: Generate unique invoice number
    private function generateInvoiceNumber()
    {
        $year = date('Y');
        $month = date('m');
        $count = $this->invoiceModel->where('YEAR(created_at)', $year)
                                     ->where('MONTH(created_at)', $month)
                                     ->countAllResults();
        
        return 'INV-' . $year . $month . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * =====================================================
     * PRINT/EXPORT PDF REPORTS
     * =====================================================
     */
    
    public function printInvoiceReport()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $pdf = new \App\Libraries\PdfService();
        
        // Get invoices with client names
        $db = \Config\Database::connect();
        $invoices = $db->query("
            SELECT i.*, c.company_name as client_name
            FROM invoices i
            LEFT JOIN clients c ON i.client_id = c.id
            ORDER BY i.created_at DESC
        ")->getResultArray();
        
        $html = $pdf->arInvoicesReport($invoices);
        
        return $pdf->generateFromHtml($html, 'accounts_receivable_report_' . date('Y-m-d'));
    }
    
    public function printAgingReport()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $pdf = new \App\Libraries\PdfService();
        
        // Get aging data
        $db = \Config\Database::connect();
        $invoices = $db->query("
            SELECT i.*, c.company_name as client_name,
                   DATEDIFF(CURDATE(), i.due_date) as days_overdue
            FROM invoices i
            LEFT JOIN clients c ON i.client_id = c.id
            WHERE i.status != 'Paid'
            ORDER BY i.due_date ASC
        ")->getResultArray();
        
        // Categorize by aging buckets
        $current = [];
        $days30 = [];
        $days60 = [];
        $days90 = [];
        $over90 = [];
        
        foreach ($invoices as $inv) {
            $daysOverdue = $inv['days_overdue'] ?? 0;
            if ($daysOverdue <= 0) {
                $current[] = $inv;
            } elseif ($daysOverdue <= 30) {
                $days30[] = $inv;
            } elseif ($daysOverdue <= 60) {
                $days60[] = $inv;
            } elseif ($daysOverdue <= 90) {
                $days90[] = $inv;
            } else {
                $over90[] = $inv;
            }
        }
        
        $headers = ['Invoice #', 'Client', 'Amount', 'Due Date', 'Days Overdue', 'Status'];
        $data = [];
        
        foreach ($invoices as $inv) {
            $balance = ($inv['total_amount'] ?? 0) - ($inv['paid_amount'] ?? 0);
            $daysOverdue = max(0, $inv['days_overdue'] ?? 0);
            
            $data[] = [
                esc($inv['invoice_number'] ?? 'N/A'),
                esc($inv['client_name'] ?? 'N/A'),
                '₱' . number_format($balance, 2),
                date('M d, Y', strtotime($inv['due_date'] ?? 'now')),
                $daysOverdue > 0 ? "<span class='text-danger'>{$daysOverdue} days</span>" : 'Current',
                esc($inv['status'] ?? 'Pending')
            ];
        }
        
        $html = $pdf->setTitle('AR Aging Report')->buildReportHtml(
            'Accounts Receivable Aging Report',
            'Outstanding customer balances by aging bucket',
            $headers,
            $data,
            [
                'summary' => [
                    'Current' => count($current),
                    '1-30 Days' => count($days30),
                    '31-60 Days' => count($days60),
                    '61-90 Days' => count($days90),
                    'Over 90 Days' => count($over90)
                ]
            ]
        );
        
        return $pdf->generateFromHtml($html, 'ar_aging_report_' . date('Y-m-d'));
    }
}
