<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * =====================================================
 * CLIENT SEEDER
 * =====================================================
 * 
 * Purpose: Seeds client/customer data for accounts receivable
 * =====================================================
 */
class ClientSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'client_name' => 'ABC Construction & Development Corp.',
                'contact_person' => 'Fernando Pascual',
                'email' => 'fernando@abcconstruction.com',
                'phone' => '+63 917 123 4567',
                'address' => '123 Builder Street, Ortigas Center, Pasig City, Philippines',
                'status' => 'Active',
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_name' => 'XYZ Builders & Associates',
                'contact_person' => 'Maria Ramos',
                'email' => 'maria@xyzbuilders.ph',
                'phone' => '+63 917 234 5678',
                'address' => '456 Construction Ave, BGC, Taguig City, Philippines',
                'status' => 'Active',
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_name' => 'Metro Property Developers Inc.',
                'contact_person' => 'Jose Santos',
                'email' => 'jose@metroprop.com',
                'phone' => '+63 917 345 6789',
                'address' => '789 Development Road, Makati City, Philippines',
                'status' => 'Active',
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_name' => 'Prime Infrastructure Solutions',
                'contact_person' => 'Roberto Cruz',
                'email' => 'roberto@primeinfra.ph',
                'phone' => '+63 917 456 7890',
                'address' => '321 Infrastructure Blvd, Quezon City, Philippines',
                'status' => 'Active',
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_name' => 'Summit Engineering & Construction',
                'contact_person' => 'Patricia Lim',
                'email' => 'patricia@summiteng.com',
                'phone' => '+63 917 567 8901',
                'address' => '654 Summit Street, Mandaluyong City, Philippines',
                'status' => 'Active',
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_name' => 'Golden Gate Contracting Services',
                'contact_person' => 'David Tan',
                'email' => 'david@goldengatecontracting.ph',
                'phone' => '+63 917 678 9012',
                'address' => '987 Contract Avenue, Pasay City, Philippines',
                'status' => 'Active',
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_name' => 'Pacific Realty & Construction',
                'contact_person' => 'Sarah Villanueva',
                'email' => 'sarah@pacificrealty.com',
                'phone' => '+63 917 789 0123',
                'address' => '147 Pacific Road, Manila, Philippines',
                'status' => 'Active',
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_name' => 'Nationwide Builders Group',
                'contact_person' => 'Michael Reyes',
                'email' => 'michael@nationwidebuilders.ph',
                'phone' => '+63 917 890 1234',
                'address' => '258 Nationwide Street, Caloocan City, Philippines',
                'status' => 'Active',
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_name' => 'Innovative Housing Corporation',
                'contact_person' => 'Elizabeth Garcia',
                'email' => 'elizabeth@innovativehousing.com',
                'phone' => '+63 917 901 2345',
                'address' => '369 Housing Blvd, Muntinlupa City, Philippines',
                'status' => 'Active',
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'client_name' => 'Master Builders Association',
                'contact_person' => 'Anthony Lopez',
                'email' => 'anthony@masterbuilders.ph',
                'phone' => '+63 917 012 3456',
                'address' => '741 Master Street, Paranaque City, Philippines',
                'status' => 'Inactive',
                'created_by' => 'System',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        $builder = $this->db->table('clients');
        
        foreach ($data as $client) {
            $existing = $builder->where('email', $client['email'])->get()->getRow();
            if (!$existing) {
                $builder->insert($client);
            }
        }

        echo "Clients seeded successfully.\n";
    }
}
