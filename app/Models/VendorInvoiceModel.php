<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorInvoiceModel extends Model
{
    protected $table = 'vendor_invoices';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'vendor_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'amount',
        'description',
        'status',
        'approved_by',
        'approved_date',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get all invoices with vendor information
    public function getAllInvoicesWithVendors(): array
    {
        $args = func_get_args();
        
        return $this->select('vendor_invoices.*, vendors.vendor_name, vendors.contact_person')
                    ->join('vendors', 'vendors.id = vendor_invoices.vendor_id', 'left')
                    ->orderBy('vendor_invoices.created_at', 'DESC')
                    ->findAll();
    }

    // Get pending invoices
    public function getPendingInvoices(): array
    {
        $args = func_get_args();
        
        return $this->select('vendor_invoices.*, vendors.vendor_name')
                    ->join('vendors', 'vendors.id = vendor_invoices.vendor_id', 'left')
                    ->where('vendor_invoices.status', 'Pending')
                    ->orderBy('vendor_invoices.invoice_date', 'DESC')
                    ->findAll();
    }

    // Get approved invoices
    public function getApprovedInvoices(): array
    {
        $args = func_get_args();
        
        return $this->select('vendor_invoices.*, vendors.vendor_name')
                    ->join('vendors', 'vendors.id = vendor_invoices.vendor_id', 'left')
                    ->where('vendor_invoices.status', 'Approved')
                    ->orderBy('vendor_invoices.approved_date', 'DESC')
                    ->findAll();
    }

    // Get overdue invoices
    public function getOverdueInvoices(): array
    {
        $args = func_get_args();
        
        return $this->select('vendor_invoices.*, vendors.vendor_name, vendors.email')
                    ->join('vendors', 'vendors.id = vendor_invoices.vendor_id', 'left')
                    ->where('vendor_invoices.due_date <', date('Y-m-d'))
                    ->whereIn('vendor_invoices.status', ['Pending', 'Approved'])
                    ->orderBy('vendor_invoices.due_date', 'ASC')
                    ->findAll();
    }

    // Get total pending amount
    public function getTotalPending(): float
    {
        $args = func_get_args();
        
        $result = $this->selectSum('amount')
                       ->where('status', 'Pending')
                       ->get()
                       ->getRowArray();
        
        return (float)($result['amount'] ?? 0);
    }

    // Get total overdue amount
    public function getTotalOverdue(): float
    {
        $args = func_get_args();
        
        $result = $this->selectSum('amount')
                       ->where('due_date <', date('Y-m-d'))
                       ->whereIn('status', ['Pending', 'Approved'])
                       ->get()
                       ->getRowArray();
        
        return (float)($result['amount'] ?? 0);
    }

    // Get pending invoices count
    public function getPendingCount(): int
    {
        $args = func_get_args();
        
        return $this->where('status', 'Pending')->countAllResults();
    }

    // Get recent activities
    public function getRecentActivities(): array
    {
        $args = func_get_args();
        $limit = $args[0] ?? 10;
        
        return $this->select('vendor_invoices.*, vendors.vendor_name')
                    ->join('vendors', 'vendors.id = vendor_invoices.vendor_id', 'left')
                    ->orderBy('vendor_invoices.updated_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    // Generate invoice number
    public function generateInvoiceNumber(): string
    {
        $args = func_get_args();
        
        $year = date('Y');
        $month = date('m');
        
        $lastInvoice = $this->select('invoice_number')
                            ->like('invoice_number', "VINV-$year$month", 'after')
                            ->orderBy('id', 'DESC')
                            ->first();
        
        if ($lastInvoice) {
            $lastNumber = (int)substr($lastInvoice['invoice_number'], -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "VINV-$year$month-$newNumber";
    }
}
