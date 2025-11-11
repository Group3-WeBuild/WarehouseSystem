<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * CLIENT MODEL - BACKEND DATABASE OPERATIONS
 * =====================================================
 * Handles all database operations for clients:
 * - CRUD operations
 * - Client statistics and analytics
 * - Active/Inactive client filtering
 * =====================================================
 */
class ClientModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'client_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'status',
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
        'client_name' => 'required|string|max_length[255]',
        'email' => 'required|valid_email|max_length[255]'
    ];

    protected $validationMessages = [
        'client_name' => [
            'required' => 'Client name is required'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please provide a valid email address'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * =====================================================
     * BACKEND DATABASE QUERIES
     * =====================================================
     */

    // Get all clients with their statistics
    public function getAllClientsWithStats()
    {
        return $this->select('clients.*, 
                             COUNT(invoices.id) as total_invoices,
                             SUM(CASE WHEN invoices.status = "Pending" THEN invoices.amount ELSE 0 END) as outstanding_balance')
                    ->join('invoices', 'invoices.client_id = clients.id', 'left')
                    ->groupBy('clients.id')
                    ->findAll();
    }

    // Get client statistics
    public function getClientStatistics()
    {
        $totalClients = $this->countAll();
        $activeClients = $this->where('status', 'Active')->countAllResults(false);
        $inactiveClients = $this->where('status', 'Inactive')->countAllResults();
        
        return [
            'total_clients' => $totalClients,
            'active_clients' => $activeClients,
            'inactive_clients' => $inactiveClients
        ];
    }

    // Get active clients
    public function getActiveClients()
    {
        return $this->where('status', 'Active')->findAll();
    }
}
