<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\UserModel;
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

    protected $userModel;

    protected $session;

    public function __construct()
    {
        $this->pizzaModel = model(PizzaModel::class);
        $this->cartModel = model(CartModel::class);
        $this->cartItemModel = model(CartItemModel::class);
        $this->orderModel = model(OrderModel::class);
        $this->orderItemModel = model(OrderModel::class);
        $this->userModel = model(UserModel::class);
        $this->session = \Config\Services::session();
    }

    public function index(): RedirectResponse|string
    {
        $userId = $this->session->get('id');
        $orders = $this->orderModel->getOrdersByUserId($userId);

        $data = [
            'title' => 'My Orders',
            'orders' => $orders,
        ];

        return view('templates/customer/header', $data)
            . view('customer/orders/index', $data)
            . view('templates/customer/footer');
    }

    public function checkout(): RedirectResponse|string
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

    public function store(): RedirectResponse
    {
        $rules = [
            'notes' => 'permit_empty|string|max_length[255]',
            'address' => 'required|string|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('validation', $this->validator)
                ->with('error', 'Failed to create order. Please try again.');
        }

        $userId = $this->session->get('id');

        if ($this->request->getPost('save_info')) {
            $this->userModel->update($userId, [
                'address' => $this->request->getPost('address'),
            ]);

            $this->session->set([
                'address' => $this->request->getPost('address'),
            ]);
        }

        $phone = $this->session->get('phone');
        $cart = $this->cartModel->getOrCreateCart($userId);
        $cartItems = $this->cartItemModel->getItems($cart['id']);
        $total = $this->cartItemModel->getCartTotal($cart['id']);

        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $orderData = [
            'user_id' => $userId,
            'total_amount' => $total,
            'delivery_address' => $this->request->getPost('address'),
            'contact_number' => $phone,
            'notes' => $this->request->getPost('notes'),
        ];

        $orderId = $this->orderModel->createOrder($orderData, $cartItems);

        if ($orderId) {
            $this->cartItemModel->clearCart($cart['id']);

            return redirect()
                ->route('orders.show', [$orderId])
                ->with('success', 'Order placed successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function show(int $orderId): RedirectResponse|string
    {
        $order = $this->orderModel->getOrderWithDetails($orderId);
        $pizzas = $this->pizzaModel->getPizzasWithCategory(onlyAvailable: true);

        if (! $order) {
            return redirect()->back()->with('error', 'Order not found.');
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
            'title' => 'Order Details',
            'order' => $order,
            'pizzas' => $pizzas,
            'cartItems' => $cartItems,
            'cartCount' => count($cartItems),
            'total' => $total,
        ];

        return view('templates/customer/header', $data)
            . view('customer/cart/index', $data)
            . view('customer/orders/show', $data)
            . view('templates/customer/footer');
    }
}
