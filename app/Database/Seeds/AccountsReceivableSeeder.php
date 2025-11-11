<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ClientModel;
use App\Models\InvoiceModel;
use App\Models\PaymentModel;

class AccountsReceivableSeeder extends Seeder
{
    public function run()
    {
        $clientModel = new ClientModel();
        $invoiceModel = new InvoiceModel();
        $paymentModel = new PaymentModel();
        // Insert sample clients
        $clients = [
            [
                'client_name' => 'ABC Corporation',
                'contact_person' => 'John Doe',
                'email' => 'john@abccorp.com',
                'phone' => '+1-234-567-8900',
                'address' => '123 Business Street, Metro Manila',
                'status' => 'Active',
                'created_by' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_name' => 'XYZ Industries',
                'contact_person' => 'Jane Smith',
                'email' => 'jane@xyzind.com',
                'phone' => '+1-234-567-8901',
                'address' => '456 Commerce Avenue, Makati City',
                'status' => 'Active',
                'created_by' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_name' => 'Tech Solutions Inc.',
                'contact_person' => 'Bob Johnson',
                'email' => 'bob@techsolutions.com',
                'phone' => '+1-234-567-8902',
                'address' => '789 Technology Park, Quezon City',
                'status' => 'Active',
                'created_by' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_name' => 'Global Trading Co.',
                'contact_person' => 'Alice Brown',
                'email' => 'alice@globaltrading.com',
                'phone' => '+1-234-567-8903',
                'address' => '321 Export Street, Pasig City',
                'status' => 'Active',
                'created_by' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_name' => 'Premier Services Ltd.',
                'contact_person' => 'Charlie Wilson',
                'email' => 'charlie@premierservices.com',
                'phone' => '+1-234-567-8904',
                'address' => '654 Service Road, Mandaluyong',
                'status' => 'Active',
                'created_by' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert clients and get their IDs
        $clientIds = [];
        foreach ($clients as $client) {
            $id = $clientModel->insert($client);
            $clientIds[] = ['id' => $id];
        }
        
        // Insert sample invoices
        $invoices = [
            [
                'client_id' => $clientIds[0]['id'],
                'invoice_number' => 'INV-' . date('Ym') . '-0001',
                'issue_date' => date('Y-m-d', strtotime('-30 days')),
                'due_date' => date('Y-m-d', strtotime('-15 days')),
                'amount' => 50000.00,
                'status' => 'Overdue',
                'description' => 'Warehouse storage services - October 2024',
                'created_by' => 'admin',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_id' => $clientIds[1]['id'],
                'invoice_number' => 'INV-' . date('Ym') . '-0002',
                'issue_date' => date('Y-m-d', strtotime('-20 days')),
                'due_date' => date('Y-m-d', strtotime('+10 days')),
                'amount' => 75000.00,
                'status' => 'Pending',
                'description' => 'Logistics and transportation services',
                'created_by' => 'admin',
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_id' => $clientIds[2]['id'],
                'invoice_number' => 'INV-' . date('Ym') . '-0003',
                'issue_date' => date('Y-m-d', strtotime('-10 days')),
                'due_date' => date('Y-m-d', strtotime('+20 days')),
                'amount' => 120000.00,
                'status' => 'Pending',
                'description' => 'IT equipment storage and handling',
                'created_by' => 'admin',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_id' => $clientIds[3]['id'],
                'invoice_number' => 'INV-' . date('Ym') . '-0004',
                'issue_date' => date('Y-m-d', strtotime('-45 days')),
                'due_date' => date('Y-m-d', strtotime('-30 days')),
                'amount' => 85000.00,
                'status' => 'Paid',
                'description' => 'Import/Export handling services',
                'created_by' => 'admin',
                'created_at' => date('Y-m-d H:i:s', strtotime('-45 days')),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_id' => $clientIds[4]['id'],
                'invoice_number' => 'INV-' . date('Ym') . '-0005',
                'issue_date' => date('Y-m-d', strtotime('-5 days')),
                'due_date' => date('Y-m-d', strtotime('+25 days')),
                'amount' => 95000.00,
                'status' => 'Pending',
                'description' => 'Premium warehouse services package',
                'created_by' => 'admin',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_id' => $clientIds[0]['id'],
                'invoice_number' => 'INV-' . date('Ym') . '-0006',
                'issue_date' => date('Y-m-d', strtotime('-60 days')),
                'due_date' => date('Y-m-d', strtotime('-45 days')),
                'amount' => 60000.00,
                'status' => 'Paid',
                'description' => 'Monthly storage fees - September 2024',
                'created_by' => 'admin',
                'created_at' => date('Y-m-d H:i:s', strtotime('-60 days')),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert invoices
        foreach ($invoices as $invoice) {
            $invoiceModel->insert($invoice);
        }

        // Get paid invoices
        $paidInvoices = $invoiceModel->where('status', 'Paid')->findAll();

        // Insert sample payments for paid invoices
        $payments = [];
        foreach ($paidInvoices as $invoice) {
            $payments[] = [
                'invoice_id' => $invoice['id'],
                'payment_date' => date('Y-m-d', strtotime('-35 days')),
                'amount' => $invoice['amount'],
                'payment_method' => 'Bank Transfer',
                'reference_number' => 'REF-' . rand(100000, 999999),
                'notes' => 'Full payment received',
                'recorded_by' => 'admin',
                'created_at' => date('Y-m-d H:i:s', strtotime('-35 days')),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        // Insert payments
        foreach ($payments as $payment) {
            $paymentModel->insert($payment);
        }

        echo "Sample data created successfully!\n";
        echo "- " . count($clients) . " clients\n";
        echo "- " . count($invoices) . " invoices\n";
        echo "- " . count($payments) . " payments\n";
    }
}
