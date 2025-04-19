<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'carts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get cart by user ID
    public function getCartByUser($userId)
    {
        return $this->where('user_id', $userId)->first();
    }

    // Create a new cart for user if doesn't exist
    public function getOrCreateCart($userId)
    {
        $cart = $this->getCartByUser($userId);
        
        if (!$cart) {
            $this->insert(['user_id' => $userId]);
            return $this->find($this->insertID());
        }
        
        return $cart;
    }
}