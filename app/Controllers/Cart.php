<?php
namespace App\Controllers;

use App\Models\CartModel;
use App\Models\CartItemModel;
use App\Models\PizzaModel;

class Cart extends BaseController
{
    protected $cartModel;
    protected $cartItemModel;
    protected $pizzaModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
        $this->pizzaModel = new PizzaModel();
    }

    // Add item to cart
    public function add($pizzaId)
    {
        // Check if user is logged in
        if (!session('isLoggedIn')) {
            return redirect()->to('auth.login')->with('error', 'Please login to add items to cart.');
        }

        $userId = session('id');
        
        // Get or create cart for user
        $cart = $this->cartModel->getOrCreateCart($userId);
        
        // Check if pizza exists
        $pizza = $this->pizzaModel->find($pizzaId);
        if (!$pizza) {
            return redirect()->back()->with('error', 'Pizza not found.');
        }

        // Add item to cart
        $this->cartItemModel->addItem($cart['id'], $pizzaId);
        
        return redirect()->back()->with('success', 'Pizza added to cart.');
    }

    // View cart
    public function view()
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('auth.login')->with('error', 'Please login to view your cart.');
        }

        $userId = session('id');
        $cart = $this->cartModel->getCartByUser($userId);
        
        $items = [];
        $total = 0;
        
        if ($cart) {
            $items = $this->cartItemModel->getItems($cart['id']);
            $total = $this->cartItemModel->getCartTotal($cart['id']);
        }

        return view('cart_view', [
            'items' => $items,
            'total' => $total
        ]);
    }

    // Remove item from cart
    public function remove($itemId)
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('auth.login');
        }

        $this->cartItemModel->removeItem($itemId);
        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    // Update item quantity
    public function update($itemId)
    {
        if (!session('isLoggedIn')) {
            return redirect()->to('auth.login');
        }

        $quantity = $this->request->getPost('quantity');
        $this->cartItemModel->updateQuantity($itemId, $quantity);
        return redirect()->route('home.index')->with('success', 'Cart updated.');
    }
}