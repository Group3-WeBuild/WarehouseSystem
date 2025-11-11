<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\VendorModel;
use App\Models\VendorInvoiceModel;
use App\Models\VendorPaymentModel;

class AccountsPayableSeeder extends Seeder
{
    public function run()
    {
        $vendorModel = new VendorModel();
        $invoiceModel = new VendorInvoiceModel();
        $paymentModel = new VendorPaymentModel();

        // Disable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        // Clear existing data
        /** @var \CodeIgniter\Database\BaseBuilder $builder */
        $builder = $this->db->table('vendor_payments');
        $builder->truncate();
        
        /** @var \CodeIgniter\Database\BaseBuilder $builder */
        $builder = $this->db->table('vendor_invoices');
        $builder->truncate();
        
        /** @var \CodeIgniter\Database\BaseBuilder $builder */
        $builder = $this->db->table('vendors');
        $builder->truncate();

        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');

        // Sample Vendors
        $vendors = [
            [
                'vendor_name' => 'ABC Construction Supplies',
                'contact_person' => 'John Martinez',
                'email' => 'john@abcconstruction.com',
                'phone' => '02-1234-5678',
                'address' => '123 Builder St., Makati City',
                'tax_id' => 'TIN-123-456-789',
                'payment_terms' => 'Net 30',
                'status' => 'Active'
            ],
            [
                'vendor_name' => 'XYZ Hardware Corp',
                'contact_person' => 'Maria Santos',
                'email' => 'maria@xyzhardware.com',
                'phone' => '02-9876-5432',
                'address' => '456 Industrial Ave., Quezon City',
                'tax_id' => 'TIN-987-654-321',
                'payment_terms' => 'Net 45',
                'status' => 'Active'
            ],
            [
                'vendor_name' => 'Global Tools & Equipment',
                'contact_person' => 'Roberto Cruz',
                'email' => 'roberto@globaltools.com',
                'phone' => '02-5555-1234',
                'address' => '789 Equipment Rd., Pasig City',
                'tax_id' => 'TIN-555-111-222',
                'payment_terms' => 'Net 30',
                'status' => 'Active'
            ],
            [
                'vendor_name' => 'Prime Steel Supplies',
                'contact_person' => 'Ana Reyes',
                'email' => 'ana@primesteel.com',
                'phone' => '02-7777-9999',
                'address' => '321 Steel Plaza, Mandaluyong City',
                'tax_id' => 'TIN-777-888-999',
                'payment_terms' => 'Net 60',
                'status' => 'Active'
            ],
            [
                'vendor_name' => 'BuildPro Materials Inc',
                'contact_person' => 'Carlos Mendoza',
                'email' => 'carlos@buildpro.com',
                'phone' => '02-3333-4444',
                'address' => '567 Materials Way, Taguig City',
                'tax_id' => 'TIN-333-444-555',
                'payment_terms' => 'Net 30',
                'status' => 'Active'
            ]
        ];

        foreach ($vendors as $vendor) {
            $vendorModel->insert($vendor);
        }

        // Sample Vendor Invoices
        $invoices = [
            [
                'vendor_id' => 1,
                'invoice_number' => 'VINV-202511-0001',
                'invoice_date' => date('Y-m-d', strtotime('-10 days')),
                'due_date' => date('Y-m-d', strtotime('+20 days')),
                'amount' => 45500.00,
                'description' => 'Construction materials - cement, steel bars, and gravel',
                'status' => 'Pending'
            ],
            [
                'vendor_id' => 2,
                'invoice_number' => 'VINV-202511-0002',
                'invoice_date' => date('Y-m-d', strtotime('-8 days')),
                'due_date' => date('Y-m-d', strtotime('+37 days')),
                'amount' => 32800.00,
                'description' => 'Hardware supplies - screws, nails, and power tools',
                'status' => 'Approved',
                'approved_by' => 'accounts_payable',
                'approved_date' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'vendor_id' => 3,
                'invoice_number' => 'VINV-202511-0003',
                'invoice_date' => date('Y-m-d', strtotime('-15 days')),
                'due_date' => date('Y-m-d', strtotime('-5 days')),
                'amount' => 67200.00,
                'description' => 'Heavy equipment rental for 3 months',
                'status' => 'Approved',
                'approved_by' => 'admin',
                'approved_date' => date('Y-m-d H:i:s', strtotime('-10 days'))
            ],
            [
                'vendor_id' => 4,
                'invoice_number' => 'VINV-202511-0004',
                'invoice_date' => date('Y-m-d', strtotime('-5 days')),
                'due_date' => date('Y-m-d', strtotime('+55 days')),
                'amount' => 125000.00,
                'description' => 'Premium steel beams and columns',
                'status' => 'Pending'
            ],
            [
                'vendor_id' => 5,
                'invoice_number' => 'VINV-202511-0005',
                'invoice_date' => date('Y-m-d', strtotime('-3 days')),
                'due_date' => date('Y-m-d', strtotime('+27 days')),
                'amount' => 28900.00,
                'description' => 'Plumbing and electrical materials',
                'status' => 'Approved',
                'approved_by' => 'accounts_payable',
                'approved_date' => date('Y-m-d H:i:s', strtotime('-1 days'))
            ],
            [
                'vendor_id' => 1,
                'invoice_number' => 'VINV-202511-0006',
                'invoice_date' => date('Y-m-d', strtotime('-20 days')),
                'due_date' => date('Y-m-d', strtotime('-10 days')),
                'amount' => 52000.00,
                'description' => 'Concrete mix and aggregates',
                'status' => 'Paid',
                'approved_by' => 'admin',
                'approved_date' => date('Y-m-d H:i:s', strtotime('-18 days'))
            ]
        ];

        foreach ($invoices as $invoice) {
            $invoiceModel->insert($invoice);
        }

        // Sample Payments (for paid invoices)
        $payments = [
            [
                'invoice_id' => 6,
                'payment_date' => date('Y-m-d', strtotime('-8 days')),
                'amount' => 52000.00,
                'payment_method' => 'Bank Transfer',
                'reference_number' => 'BT-2025110301',
                'notes' => 'Full payment via BDO',
                'processed_by' => 'accounts_payable'
            ]
        ];

        foreach ($payments as $payment) {
            $paymentModel->insert($payment);
        }

        echo "Accounts Payable seeder completed successfully!\n";
        echo "Created 5 vendors, 6 invoices, and 1 payment record.\n";
    }
}
