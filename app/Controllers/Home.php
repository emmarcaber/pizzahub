<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\PizzaModel;
use App\Models\CartItemModel;
use CodeIgniter\HTTP\RedirectResponse;

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

    public function index(): RedirectResponse|string
    {
        try {
            $keyword = $this->request->getGet('search');

            if (!empty($keyword)) {
                $pizzas = $this->pizzaModel->searchPizzas($keyword, true);
            } else {
                $pizzas = $this->pizzaModel->getPizzasWithCategory(onlyAvailable: true);
            }

            if ($this->session->get('isLoggedIn')) {
                $userId = $this->session->get('id');
                $cart = $this->cartModel->getOrCreateCart($userId);
                $cartItems = $this->cartItemModel->getItems($cart['id']);
                $total = $this->cartItemModel->getCartTotal($cart['id']);
            } else {
                $cartItems = [];
                $total = 0;
            }

            $data = [
                'title' => 'Home',
                'pizzas' => $pizzas,
                'cartItems' => $cartItems,
                'cartCount' => count($cartItems),
                'total' => $total,
                'keyword' => $keyword,
            ];

            return view('templates/customer/header', $data)
                . view('customer/cart/index', $data)
                . view('templates/customer/hero')
                . view('customer/pizzas/index')
                . view('templates/customer/footer');
        } catch (\Exception $e) {
            log_message('error', 'Error fetching pizzas: ' . $e->getMessage());
            return redirect()->route('auth.login')->with('error', 'An error occurred while processing your request. Please try again later.');
        }
    }
}
