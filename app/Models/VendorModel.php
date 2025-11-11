<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table = 'vendors';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'vendor_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'tax_id',
        'payment_terms',
        'status',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get all vendors with statistics
    public function getAllVendorsWithStats(): array
    {
        $args = func_get_args();
        
        return $this->select('vendors.*, 
                              COUNT(vendor_invoices.id) as total_invoices,
                              SUM(CASE WHEN vendor_invoices.status = "Pending" THEN vendor_invoices.amount ELSE 0 END) as pending_amount,
                              SUM(CASE WHEN vendor_invoices.status = "Paid" THEN vendor_invoices.amount ELSE 0 END) as paid_amount')
                    ->join('vendor_invoices', 'vendor_invoices.vendor_id = vendors.id', 'left')
                    ->groupBy('vendors.id')
                    ->findAll();
    }

    // Get vendor statistics
    public function getVendorStatistics(): array
    {
        $args = func_get_args();
        $vendorId = $args[0] ?? 0;
        
        return $this->select('vendors.*,
                              COUNT(vendor_invoices.id) as invoice_count,
                              SUM(vendor_invoices.amount) as total_amount,
                              SUM(CASE WHEN vendor_invoices.status = "Paid" THEN vendor_invoices.amount ELSE 0 END) as paid_amount')
                    ->join('vendor_invoices', 'vendor_invoices.vendor_id = vendors.id', 'left')
                    ->where('vendors.id', $vendorId)
                    ->groupBy('vendors.id')
                    ->first();
    }
}
