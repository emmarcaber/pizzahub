<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\PizzaModel;

class OrderItemModel extends Model
{
    protected $table            = 'orderitems';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'pizza_id',
        'quantity',
        'price',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'order_id' => 'required|numeric',
        'pizza_id' => 'required|numeric',
        'quantity' => 'required|numeric|greater_than[0]',
        'price' => 'required|numeric|greater_than[0]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getItemsWithDetails(int $orderId): array
    {
        $items = $this->where('order_id', $orderId)->findAll();

        if (empty($items)) {
            return [];
        }

        $pizzaModel = new PizzaModel();

        foreach ($items as &$item) {
            $item['pizza'] = $pizzaModel->find($item['pizza_id']);

            // Get toppings if any
            $item['toppings'] = $this->getItemToppings($item['id']);
        }

        return $items;
    }
}
