<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\PizzaModel;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'description'
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
        'name' => 'required|min_length[3]|max_length[50]',
        'description' => 'required|max_length[500]'
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Category name is required',
            'min_length' => 'Category name must be at least 3 characters',
            'max_length' => 'Category name cannot exceed 50 characters',
            'is_unique' => 'This category name already exists'
        ],
        'description' => [
            'required' => 'Description is required',
            'max_length' => 'Description cannot exceed 500 characters'
        ]
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

    public function getCreateValidatonRules(): array
    {
        $validationRules = $this->validationRules;
        $validationRules['name'] = 'required|min_length[3]|max_length[50]|is_unique[categories.name]';

        return $validationRules;
    }

    public function getUpdateValidationRules(int $id): array
    {
        $validationRules = $this->validationRules;
        $validationRules['name'] = "required|min_length[3]|max_length[50]|is_unique[categories.name,id,$id]";

        return $validationRules;
    }

    public function canDelete(int $categoryId): bool
    {
        $pizzaModel = new PizzaModel();
        $pizzaCount = $pizzaModel->where('category_id', $categoryId)->countAllResults();

        return $pizzaCount === 0;
    }

    public function getCategoriesWithCount(): array
    {
        $builder = $this->db->table('categories c');
        $builder->select('c.*, COUNT(p.id) as pizza_count');
        $builder->join('pizzas p', 'p.category_id = c.id', 'left');
        $builder->groupBy('c.id');

        return $builder->get()->getResultArray();
    }

    public function getCategoryByName(string $name): array|object|null
    {
        return $this->where('name', $name)->first();
    }

    public function getActiveCategories(): array
    {
        return $this->orderBy('name', 'ASC')->findAll();
    }

    public function getPizzasByCategory(int $categoryId): array
    {
        $pizzaModel = model(PizzaModel::class);
        $pizzas = $pizzaModel->select('pizzas.*, categories.name as category_name')
            ->join('categories', 'categories.id = pizzas.category_id')
            ->where('pizzas.category_id', $categoryId)
            ->orderBy('pizzas.id', 'DESC')
            ->findAll();

        return $pizzas;
    }

    public function search(string $term): array
    {
        return $this->like('name', $term)
            ->orLike('description', $term)
            ->findAll();
    }
}
