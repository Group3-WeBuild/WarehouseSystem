<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * PAYMENT MODEL - BACKEND DATABASE OPERATIONS
 * =====================================================
 * Handles all database operations for payments:
 * - Payment recording and tracking
 * - Monthly collections calculations
 * - Revenue reporting
 * - Payment method statistics
 * =====================================================
 */
class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'invoice_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference_number',
        'notes',
        'recorded_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'invoice_id' => 'required|integer',
        'payment_date' => 'required|valid_date',
        'amount' => 'required|decimal',
        'payment_method' => 'required|string|max_length[50]'
    ];

    protected $validationMessages = [
        'invoice_id' => [
            'required' => 'Invoice is required'
        ],
        'payment_date' => [
            'required' => 'Payment date is required'
        ],
        'amount' => [
            'required' => 'Amount is required'
        ],
        'payment_method' => [
            'required' => 'Payment method is required'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * =====================================================
     * BACKEND DATABASE QUERIES & CALCULATIONS
     * =====================================================
     */

    // Get monthly collections for current month
    public function getMonthlyCollections()
    {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        
        $result = $this->selectSum('amount')
                       ->where('payment_date >=', $startDate)
                       ->where('payment_date <=', $endDate)
                       ->get()
                       ->getRowArray();
        
        return $result['amount'] ?? 0;
    }

    // Get recent payments
    public function getRecentPayments(): array
    {
        $args = func_get_args();
        $limit = $args[0] ?? 20;
        
        return $this->select('payments.*, invoices.invoice_number, clients.client_name')
                    ->join('invoices', 'invoices.id = payments.invoice_id', 'left')
                    ->join('clients', 'clients.id = invoices.client_id', 'left')
                    ->orderBy('payments.payment_date', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    // Get total paid for an invoice
    public function getTotalPaidForInvoice(): float
    {
        $args = func_get_args();
        $invoiceId = $args[0] ?? 0;
        
        if ($invoiceId === 0) {
            return 0.0;
        }
        
        $result = $this->selectSum('amount')
                       ->where('invoice_id', $invoiceId)
                       ->get()
                       ->getRowArray();
        
        return (float)($result['amount'] ?? 0);
    }

    // Get monthly revenue for the past 12 months
    public function getMonthlyRevenue()
    {
        $startDate = date('Y-m-d', strtotime('-12 months'));
        
        return $this->select("DATE_FORMAT(payment_date, '%Y-%m') as month, 
                             DATE_FORMAT(payment_date, '%b %Y') as month_name, 
                             SUM(amount) as total")
                    ->where('payment_date >=', $startDate)
                    ->groupBy("DATE_FORMAT(payment_date, '%Y-%m')")
                    ->orderBy('month', 'ASC')
                    ->findAll();
    }

    // Get payment method statistics
    public function getPaymentMethodStats()
    {
        return $this->select('payment_method, COUNT(*) as count, SUM(amount) as total')
                    ->groupBy('payment_method')
                    ->findAll();
    }

    // Get payments by date range
    public function getPaymentsByDateRange(string $startDate = '', string $endDate = ''): array
    {
        if (empty($startDate) || empty($endDate)) {
            return [];
        }
        
        return $this->select('payments.*, invoices.invoice_number, clients.client_name')
                    ->join('invoices', 'invoices.id = payments.invoice_id', 'left')
                    ->join('clients', 'clients.id = invoices.client_id', 'left')
                    ->where('payment_date >=', $startDate)
                    ->where('payment_date <=', $endDate)
                    ->orderBy('payments.payment_date', 'DESC')
                    ->findAll();
    }
}
