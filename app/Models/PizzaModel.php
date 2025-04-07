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
        'name' => 'required|min_length[3]|max_length[100]|is_unique[pizzas.name,id,{id}]',
        'description' => 'permit_empty',
        'price' => 'required|numeric|greater_than[0]',
        'is_available' => 'required|in_list[0,1]'
    ];
    protected $validationMessages   = [
        'price' => [
            'greater_than' => 'Price must be greater than 0'
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


    public function getPizzasWithCategory($categoryId = null, $onlyAvailable = true): array
    {
        $builder = $this->db->table('pizzas p');
        $builder->select('p.*, c.name as category_name');
        $builder->join('categories c', 'c.id = p.category_id');

        if ($categoryId) {
            $builder->where('p.category_id', $categoryId);
        }

        if ($onlyAvailable) {
            $builder->where('p.is_available', 1);
        }

        return $builder->get()->getResultArray();
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
        if (!$pizza) {
            return false;
        }

        // Delete old image if exists
        if (!empty($pizza['image']) && file_exists(ROOTPATH . 'public/' . $pizza['image'])) {
            unlink(ROOTPATH . 'public/' . $pizza['image']);
        }

        // Generate new filename
        $newName = $imageFile->getRandomName();
        $imagePath = 'uploads/pizzas/' . $newName;

        // Move uploaded file
        if ($imageFile->move(ROOTPATH . 'public/uploads/pizzas', $newName)) {
            return $this->update($pizzaId, ['image' => $imagePath]);
        }

        return false;
    }

    public function deleteImage($pizzaId): bool
    {
        $pizza = $this->find($pizzaId);
        if (!$pizza || empty($pizza['image'])) {
            return false;
        }

        if (file_exists(ROOTPATH . 'public/' . $pizza['image'])) {
            unlink(ROOTPATH . 'public/' . $pizza['image']);
        }

        return $this->update($pizzaId, ['image' => null]);
    }


    public function search($term, $onlyAvailable = true): array
    {
        $builder = $this->like('name', $term)
            ->orLike('description', $term);

        if ($onlyAvailable) {
            $builder->where('is_available', 1);
        }

        return $builder->findAll();
    }
}
