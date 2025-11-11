<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorPaymentModel extends Model
{
    protected $table = 'vendor_payments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'invoice_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference_number',
        'notes',
        'processed_by',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get recent payments
    public function getRecentPayments(): array
    {
        $args = func_get_args();
        $limit = $args[0] ?? 20;
        
        return $this->select('vendor_payments.*, vendor_invoices.invoice_number, vendors.vendor_name')
                    ->join('vendor_invoices', 'vendor_invoices.id = vendor_payments.invoice_id', 'left')
                    ->join('vendors', 'vendors.id = vendor_invoices.vendor_id', 'left')
                    ->orderBy('vendor_payments.payment_date', 'DESC')
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

    // Get monthly payments
    public function getMonthlyPayments(): float
    {
        $args = func_get_args();
        
        $result = $this->selectSum('amount')
                       ->where('MONTH(payment_date)', date('m'))
                       ->where('YEAR(payment_date)', date('Y'))
                       ->get()
                       ->getRowArray();
        
        return (float)($result['amount'] ?? 0);
    }

    // Get payment method statistics
    public function getPaymentMethodStats(): array
    {
        $args = func_get_args();
        
        return $this->select('payment_method, COUNT(*) as count, SUM(amount) as total')
                    ->groupBy('payment_method')
                    ->findAll();
    }

    // Get monthly spending report
    public function getMonthlySpending(): array
    {
        $args = func_get_args();
        $months = $args[0] ?? 6;
        
        return $this->select('DATE_FORMAT(payment_date, "%Y-%m") as month, SUM(amount) as total')
                    ->where('payment_date >=', date('Y-m-d', strtotime("-$months months")))
                    ->groupBy('month')
                    ->orderBy('month', 'DESC')
                    ->findAll();
    }
}
