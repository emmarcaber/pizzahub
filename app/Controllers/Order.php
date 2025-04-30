<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\PizzaModel;
use App\Models\CartItemModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;

class Order extends BaseController
{
    protected $helpers = ['form', 'url'];

    protected $pizzaModel;

    protected $cartModel;

    protected $cartItemModel;

    protected $orderModel;

    protected $orderItemModel;

    protected $session;

    public function __construct()
    {
        $this->pizzaModel = model(PizzaModel::class);
        $this->cartModel = model(CartModel::class);
        $this->cartItemModel = model(CartItemModel::class);
        $this->orderModel = model(OrderModel::class);
        $this->orderItemModel = model(OrderModel::class);
        $this->session = \Config\Services::session();
    }

    public function index(): RedirectResponse|string
    {
        try {
            $pizzas = $this->pizzaModel->getPizzasWithCategory(onlyAvailable: true);

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
                'title' => 'Checkout',
                'pizzas' => $pizzas,
                'cartItems' => $cartItems,
                'cartCount' => count($cartItems),
                'total' => $total,
            ];

            return view('templates/customer/header', $data)
                . view('customer/cart/index', $data)
                . view('customer/orders/checkout', $data)
                . view('templates/customer/footer');
        } catch (\Exception $e) {
            log_message('error', 'Error fetching pizzas: ' . $e->getMessage());
            return redirect()->route('auth.login')->with('error', 'An error occurred while processing your request. Please try again later.');
        }
    }
}
