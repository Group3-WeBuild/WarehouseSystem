<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * =====================================================
 * VENDOR SEEDER
 * =====================================================
 * 
 * Purpose: Seeds vendor/supplier data
 * =====================================================
 */
class VendorSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'vendor_name' => 'Manila Steel Trading Corporation',
                'contact_person' => 'Roberto Santos',
                'email' => 'sales@manilasteel.com.ph',
                'phone' => '+63 2 8123 4567',
                'address' => '456 Steel Avenue, Binondo, Manila, Philippines',
                'payment_terms' => 'Net 30',
                'tax_id' => '123-456-789-000',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'vendor_name' => 'Philippine Cement Industries Inc.',
                'contact_person' => 'Maria dela Cruz',
                'email' => 'orders@phcement.com',
                'phone' => '+63 2 8234 5678',
                'address' => '789 Industrial Road, Caloocan City, Philippines',
                'payment_terms' => 'Net 45',
                'tax_id' => '234-567-890-000',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'vendor_name' => 'Pacific Lumber & Hardware Supply',
                'contact_person' => 'Juan Reyes',
                'email' => 'sales@paclumber.ph',
                'phone' => '+63 2 8345 6789',
                'address' => '321 Commerce Street, Quezon City, Philippines',
                'payment_terms' => 'Net 30',
                'tax_id' => '345-678-901-000',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'vendor_name' => 'Metro Paint Manufacturing',
                'contact_person' => 'Lisa Tan',
                'email' => 'info@metropaint.com.ph',
                'phone' => '+63 2 8456 7890',
                'address' => '654 Paint Boulevard, Pasig City, Philippines',
                'payment_terms' => 'Net 15',
                'tax_id' => '456-789-012-000',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'vendor_name' => 'Global Electrical Supplies Inc.',
                'contact_person' => 'Eddie Lim',
                'email' => 'sales@globalelectric.ph',
                'phone' => '+63 2 8567 8901',
                'address' => '987 Electric Avenue, Makati City, Philippines',
                'payment_terms' => 'Net 30',
                'tax_id' => '567-890-123-000',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'vendor_name' => 'ABC Plumbing Supplies',
                'contact_person' => 'Carlos Mendoza',
                'email' => 'orders@abcplumbing.ph',
                'phone' => '+63 2 8678 9012',
                'address' => '147 Pipe Street, Mandaluyong City, Philippines',
                'payment_terms' => 'Net 30',
                'tax_id' => '678-901-234-000',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'vendor_name' => 'Safety First Equipment Trading',
                'contact_person' => 'Anna Garcia',
                'email' => 'info@safetyfirst.ph',
                'phone' => '+63 2 8789 0123',
                'address' => '258 Safety Avenue, Taguig City, Philippines',
                'payment_terms' => 'Net 15',
                'tax_id' => '789-012-345-000',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'vendor_name' => 'Premium Tools & Equipment Corp.',
                'contact_person' => 'Miguel Cruz',
                'email' => 'sales@premiumtools.ph',
                'phone' => '+63 2 8890 1234',
                'address' => '369 Tool Street, Pasay City, Philippines',
                'payment_terms' => 'COD',
                'tax_id' => '890-123-456-000',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        $builder = $this->db->table('vendors');
        
        foreach ($data as $vendor) {
            $existing = $builder->where('email', $vendor['email'])->get()->getRow();
            if (!$existing) {
                $builder->insert($vendor);
            }
        }

        echo "Vendors seeded successfully.\n";
    }
}
