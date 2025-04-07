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
        'name' => 'required|min_length[3]|max_length[50]|is_unique[categories.name,id,{id}]',
        'description' => 'permit_empty|max_length[500]'
    ];
    protected $validationMessages   = [
        'name' => [
            'is_unique' => 'This category name already exists.'
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


    public function canDelete(int $categoryId): bool
    {
        $pizzaModel = new PizzaModel();
        $pizzaCount = $pizzaModel->where('category_id', $categoryId)->countAllResults();

        return $pizzaCount === 0;
    }

    public function search(string $term): array
    {
        return $this->like('name', $term)
            ->orLike('description', $term)
            ->findAll();
    }
}
