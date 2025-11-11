<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * =====================================================
 * WAREHOUSE MODEL - Database Interaction
 * =====================================================
 * 
 * This model handles warehouse locations data
 * 
 * DATABASE TABLE: warehouses
 * =====================================================
 */

class WarehouseModel extends Model
{
    protected $table = 'warehouses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'warehouse_name',
        'warehouse_code',
        'location',
        'address',
        'capacity',
        'manager_id',
        'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'warehouse_name' => 'required|min_length[3]|max_length[255]',
        'warehouse_code' => 'required|is_unique[warehouses.warehouse_code,id,{id}]',
        'location' => 'required'
    ];

    protected $validationMessages = [
        'warehouse_name' => [
            'required' => 'Warehouse name is required'
        ],
        'warehouse_code' => [
            'required' => 'Warehouse code is required',
            'is_unique' => 'This warehouse code already exists'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get active warehouses
     */
    public function getActiveWarehouses()
    {
        return $this->where('status', 'Active')->findAll();
    }

    /**
     * Get warehouse by code
     */
    public function getWarehouseByCode($code)
    {
        return $this->where('warehouse_code', $code)->first();
    }
}
