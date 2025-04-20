<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\PizzaModel;
use App\Models\CartItemModel;

class Home extends BaseController
{
    protected $helpers = ['form', 'url'];

    protected $pizzaModel;

    protected $cartModel;

    protected $cartItemModel;

    protected $session;

    public function __construct()
    {
        $this->pizzaModel = model(PizzaModel::class);
        $this->cartModel = model(CartModel::class);
        $this->cartItemModel = model(CartItemModel::class);
        $this->session = \Config\Services::session();
    }

    public function index(): string
    {
        $pizzas = $this->pizzaModel->getPizzasWithCategory(onlyAvailable: true);

        if ($this->session->get('isLoggedIn')) {
            $userId = $this->session->get('id');
            $cart = $this->cartModel->getOrCreateCart($userId);
            $cartItems = $this->cartItemModel->getItems($cart['id']);
        } else {
            $cartItems = [];
        }

        $data = [
            'title' => 'Home',
            'pizzas' => $pizzas,
            'cartItems' => $cartItems,
            'cartCount' => count($cartItems),
            'total' => $this->cartItemModel->getCartTotal($cart['id'])
        ];

        return view('templates/customer/header', $data)
            . view('customer/cart/index', $data)
            . view('templates/customer/hero')
            . view('customer/pizzas/index')
            . view('templates/customer/footer'); 
    }
}
