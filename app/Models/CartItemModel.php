<?php

namespace App\Models;

use CodeIgniter\Model;

class CartItemModel extends Model
{
    protected $table = 'cart_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['cart_id', 'pizza_id', 'quantity'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get items for a specific cart
    public function getItems(int $cartId)
    {
        return $this->where('cart_id', $cartId)
            ->join('pizzas', 'pizzas.id = cart_items.pizza_id')
            ->select('cart_items.*, pizzas.name, pizzas.price, pizzas.image')
            ->findAll();
    }

    // Add or update item in cart
    public function addItem(int $cartId, int $pizzaId, int $quantity = 1)
    {
        $existingItem = $this->where('cart_id', $cartId)
            ->where('pizza_id', $pizzaId)
            ->first();

        if ($existingItem) {
            // Update quantity if item already exists
            return $this->update($existingItem['id'], [
                'quantity' => $existingItem['quantity'] + $quantity
            ]);
        } else {
            // Add new item
            return $this->insert([
                'cart_id' => $cartId,
                'pizza_id' => $pizzaId,
                'quantity' => $quantity
            ]);
        }
    }

    public function updateQuantity(int $itemId, int $quantity)
    {
        return $this->update($itemId, ['quantity' => $quantity]);
    }

    // Remove item from cart
    public function removeItem(int $itemId)
    {
        return $this->delete($itemId);
    }

    // Clear all items from cart
    public function clearCart(int $cartId)
    {
        return $this->where('cart_id', $cartId)->delete();
    }

    // Get cart total
    public function getCartTotal(int $cartId)
    {
        $items = $this->where('cart_id', $cartId)
            ->join('pizzas', 'pizzas.id = cart_items.pizza_id')
            ->select('cart_items.quantity, pizzas.price')
            ->findAll();

        $total = 0;
        foreach ($items as $item) {
            $total += $item['quantity'] * $item['price'];
        }

        return $total;
    }
}
