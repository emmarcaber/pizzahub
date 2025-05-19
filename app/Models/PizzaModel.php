<?php

namespace App\Models;

use CodeIgniter\Model;

class PizzaModel extends Model
{
    protected $table            = 'pizzas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'is_available'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'category_id' => 'required|numeric',
        'name' => 'required|min_length[3]|max_length[100]',
        'description' => 'permit_empty',
        'price' => 'required|numeric|greater_than[0]',
    ];
    protected $validationMessages   = [
        'category_id' => [
            'required' => 'Please select a category',
            'numeric' => 'Invalid category selection'
        ],
        'name' => [
            'required' => 'Pizza name is required',
            'min_length' => 'Pizza name must be at least 3 characters',
            'max_length' => 'Pizza name cannot exceed 100 characters',
            'is_unique' => 'This pizza name already exists'
        ],
        'price' => [
            'required' => 'Price is required',
            'numeric' => 'Price must be a number',
            'greater_than' => 'Price must be greater than 0'
        ],
    ];
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

    public function getCreateValidationRules(): array
    {
        $validationRules = $this->validationRules;
        $validationRules['name'] = 'required|min_length[3]|max_length[100]|is_unique[pizzas.name]';

        return $validationRules;
    }

    public function getUpdateValidationRules(int $id): array
    {
        $validationRules = $this->validationRules;
        $validationRules['name'] = "required|min_length[3]|max_length[100]|is_unique[pizzas.name,id,$id]";

        return $validationRules;
    }

    public function canDelete(int $pizzaId): bool
    {
        $orderModel = model(OrderModel::class);
        $orderCount = $orderModel
            ->join('order_items', 'order_items.order_id = orders.id')
            ->where('orders.status !=', 'cancelled')
            ->where('orders.status !=', 'delivered')
            ->where('order_items.pizza_id', $pizzaId)->countAllResults();

        return $orderCount === 0;
    }

    public function getPizzasWithCategory($categoryId = null, $onlyAvailable = true): array
    {
        $builder = $this->db->table('pizzas p');
        $builder->select('p.*, c.name as category_name, c.description as category_description');
        $builder->join('categories c', 'c.id = p.category_id');

        if ($categoryId) {
            $builder->where('p.category_id', $categoryId);
        }

        if ($onlyAvailable) {
            $builder->where('p.is_available', 1);
        }

        return $builder->orderBy('id', 'DESC')->get()->getResultArray();
    }

    public function getAvailablePizzas(): array
    {
        return $this->where('is_available', 1)
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    public function getPizzaWithCategory($pizzaId): array|object|null
    {
        return $this->select('pizzas.*, categories.name as category_name')
            ->join('categories', 'categories.id = pizzas.category_id')
            ->find($pizzaId);
    }

    public function toggleAvailability($pizzaId): bool
    {
        $pizza = $this->find($pizzaId);
        if (!$pizza) {
            return false;
        }

        $newStatus = $pizza['is_available'] ? 0 : 1;
        return $this->update($pizzaId, ['is_available' => $newStatus]);
    }

    public function handleImageUpload($pizzaId, $imageFile): bool
    {
        $pizza = $this->find($pizzaId);
        if (! $pizza) {
            return false;
        }

        if (! $imageFile->isValid() || strpos($imageFile->getMimeType(), 'image/') !== 0) {
            return false;
        }

        if (! empty($pizza['image']) && file_exists(ROOTPATH . 'public/' . $pizza['image'])) {
            unlink(ROOTPATH . 'public/' . $pizza['image']);
        }

        // Generate new filename and path
        $newName = $imageFile->getRandomName();
        $imagePath = 'uploads/pizzas/' . $newName;

        // Move uploaded file and update database
        if ($imageFile->move(ROOTPATH . 'public/uploads/pizzas', $newName)) {
            return $this->update($pizzaId, ['image' => $imagePath]);
        }

        return false;
    }

    public function deleteImage(int $pizzaId): bool
    {
        /** @var mixed $pizza */
        $pizza = $this->find($pizzaId);
        if (! $pizza || empty($pizza['image'])) {
            return false;
        }

        if (file_exists(ROOTPATH . 'public/' . $pizza['image'])) {
            unlink(ROOTPATH . 'public/' . $pizza['image']);
        }

        return $this->update($pizzaId, ['image' => null]);
    }


    public function searchPizzas($keyword, $onlyAvailable = true): array
    {
        $builder = $this->db->table('pizzas')
            ->select('pizzas.*, categories.name as category_name, categories.description as category_description')
            ->join('categories', 'categories.id = pizzas.category_id', 'left');

        if ($onlyAvailable) {
            $builder->where('pizzas.is_available', true);
        }

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('pizzas.name', $keyword)
                ->orLike('pizzas.description', $keyword)
                ->orLike('categories.name', $keyword)
                ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    public function getTopSellingPizzas($limit = 5): array
    {
        return $this->db->query("
            SELECT 
                p.name,
                COUNT(oi.id) as order_count
            FROM order_items oi
            JOIN pizzas p ON p.id = oi.pizza_id
            GROUP BY oi.pizza_id
            ORDER BY order_count DESC
            LIMIT ?
        ", [$limit])->getResultArray();
    }
}
