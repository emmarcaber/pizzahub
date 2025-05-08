<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'email',
        'password',
        'role',
        'address',
        'phone',
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
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[8]',
        'address' => 'permit_empty',
        'role' => 'required|in_list[admin,customer]',
        'phone' => 'permit_empty|regex_match[/^[0-9]{10}$/]'
    ];
    protected $validationMessages   = [
        'email' => [
            'is_unique' => 'This email is already registered.'
        ],
        'phone' => [
            'regex_match' => 'Phone number must be 10 digits.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function canDelete(int $userId): bool
    {
        $orderModel = model(OrderModel::class);
        $orderCount = $orderModel->where('user_id', $userId)->countAllResults();

        return $orderCount === 0;
    }

    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }

    public function verifyCredentials(string $email, string $password): mixed
    {
        $user = $this->getUserByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        return $user;
    }

    public function updateProfile(int $id, array $data)
    {
        // Remove password if present
        if (isset($data['password'])) {
            unset($data['password']);
        }

        return $this->update($id, $data);
    }

    public function changePassword(int $id, string $newPassword): bool
    {
        return $this->update($id, ['password' => $newPassword]);
    }

    public function getUserByEmail(string $email): array|object|null
    {
        return $this->where('email', $email)->first();
    }

    public function getAllCustomers(): array
    {
        return $this->where('role', 'customer')->findAll();
    }

    public function getAllAdmins(): array
    {
        return $this->where('role', 'admin')->findAll();
    }
}
