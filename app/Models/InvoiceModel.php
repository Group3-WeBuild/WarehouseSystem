<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * INVOICE MODEL - BACKEND DATABASE OPERATIONS
 * =====================================================
 * Handles all database operations for invoices:
 * - Invoice CRUD operations
 * - Outstanding and overdue calculations
 * - Aging report generation
 * - Automatic status updates
 * =====================================================
 */
class InvoiceModel extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'client_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'amount',
        'status',
        'description',
        'created_by',
        'updated_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'client_id' => 'required|integer',
        'invoice_number' => 'required|string|max_length[50]|is_unique[invoices.invoice_number,id,{id}]',
        'issue_date' => 'required|valid_date',
        'due_date' => 'required|valid_date',
        'amount' => 'required|decimal'
    ];

    protected $validationMessages = [
        'client_id' => [
            'required' => 'Client is required'
        ],
        'invoice_number' => [
            'required' => 'Invoice number is required',
            'is_unique' => 'This invoice number already exists'
        ],
        'amount' => [
            'required' => 'Amount is required'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * =====================================================
     * BACKEND DATABASE QUERIES & BUSINESS LOGIC
     * =====================================================
     */

    // Get all invoices with client information
    public function getAllInvoicesWithClients()
    {
        return $this->select('invoices.*, clients.client_name, clients.email, clients.phone')
                    ->join('clients', 'clients.id = invoices.client_id', 'left')
                    ->orderBy('invoices.created_at', 'DESC')
                    ->findAll();
    }

    // Get total outstanding amount
    public function getTotalOutstanding()
    {
        $result = $this->selectSum('amount')
                       ->where('status', 'Pending')
                       ->orWhere('status', 'Overdue')
                       ->get()
                       ->getRowArray();
        
        return $result['amount'] ?? 0;
    }

    // Get overdue amount
    public function getOverdueAmount()
    {
        $result = $this->selectSum('amount')
                       ->where('status', 'Overdue')
                       ->orWhere('due_date <', date('Y-m-d'))
                       ->where('status', 'Pending')
                       ->get()
                       ->getRowArray();
        
        // Update overdue invoices
        $this->updateOverdueInvoices();
        
        return $result['amount'] ?? 0;
    }

    // Get pending invoices count
    public function getPendingInvoicesCount()
    {
        return $this->where('status', 'Pending')->countAllResults();
    }

    // Get recent activities
    public function getRecentActivities(): array
    {
        $args = func_get_args();
        $limit = $args[0] ?? 10;
        
        return $this->select('invoices.*, clients.client_name')
                    ->join('clients', 'clients.id = invoices.client_id', 'left')
                    ->orderBy('invoices.updated_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    // Get pending invoices
    public function getPendingInvoices()
    {
        return $this->select('invoices.*, clients.client_name')
                    ->join('clients', 'clients.id = invoices.client_id', 'left')
                    ->whereIn('invoices.status', ['Pending', 'Overdue'])
                    ->orderBy('invoices.due_date', 'ASC')
                    ->findAll();
    }

    // Get overdue invoices
    public function getOverdueInvoices()
    {
        $this->updateOverdueInvoices();
        
        return $this->select('invoices.*, clients.client_name, clients.email, clients.phone, clients.contact_person')
                    ->join('clients', 'clients.id = invoices.client_id', 'left')
                    ->where('invoices.status', 'Overdue')
                    ->orderBy('invoices.due_date', 'ASC')
                    ->findAll();
    }

    // Update overdue invoices
    public function updateOverdueInvoices()
    {
        $this->where('due_date <', date('Y-m-d'))
             ->where('status', 'Pending')
             ->set(['status' => 'Overdue'])
             ->update();
    }

    // Get aging report
    public function getAgingReport()
    {
        return $this->select('clients.client_name, invoices.invoice_number, invoices.issue_date, 
                             invoices.due_date, invoices.amount, invoices.status,
                             DATEDIFF(CURDATE(), invoices.due_date) as days_overdue')
                    ->join('clients', 'clients.id = invoices.client_id', 'left')
                    ->whereIn('invoices.status', ['Pending', 'Overdue'])
                    ->orderBy('invoices.due_date', 'ASC')
                    ->findAll();
    }

    // Get aging summary
    public function getAgingSummary()
    {
        $result = $this->selectSum('amount')
                       ->whereIn('status', ['Pending', 'Overdue'])
                       ->get()
                       ->getRowArray();
        
        return $result ?? ['amount' => 0];
    }
}
