<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\UserModel;
use App\Types\StatusType;
use App\Models\OrderModel;
use App\Models\PizzaModel;
use App\Models\CartItemModel;
use App\Models\OrderItemModel;
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
        $this->orderItemModel = model(OrderItemModel::class);
        $this->userModel = model(UserModel::class);
        $this->session = \Config\Services::session();
    }

    /**
     * Display list of user orders
     */
    public function index(): RedirectResponse|string
    {
        $userId = $this->session->get('id');
        $keyword = $this->request->getGet('search');

        if (!empty($keyword)) {
            $orders = $this->orderModel->searchOrdersByUser($userId, $keyword);
        } else {
            $orders = $this->orderModel->getOrdersByUser($userId);
        }

        $data = $this->prepareCommonData('My Orders');
        $data['orders'] = $orders;
        $data['keyword'] = $keyword;

        return $this->renderCustomerView('customer/orders/index', $data, 'customer/cart/index');
    }

    /**
     * Display checkout page
     */
    public function checkout(): RedirectResponse|string
    {
        try {
            $data = $this->prepareCommonData('Checkout');

            return $this->renderCustomerView('customer/orders/checkout', $data, 'customer/cart/index');
        } catch (\Exception $e) {
            log_message('error', 'Error in checkout: ' . $e->getMessage());
            return redirect()->to('/auth/login')->with('error', 'An error occurred. Please try again later.');
        }
    }

    /**
     * Process order creation
     */
    public function store(): RedirectResponse
    {
        if (!$this->validateOrderData()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('validation', $this->validator)
                ->with('error', 'Failed to create order. Please try again.');
        }

        $userId = $this->session->get('id');

        // Save user address if requested
        $this->saveUserAddressIfRequested($userId);

        // Get cart data
        $cart = $this->cartModel->getOrCreateCart($userId);
        $cartItems = $this->cartItemModel->getItems($cart['id']);
        $total = $this->cartItemModel->getCartTotal($cart['id']);

        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Create the order
        $orderData = $this->prepareOrderData($userId, $total);
        $orderId = $this->orderModel->createOrder($orderData, $cartItems);

        if ($orderId) {
            $this->cartItemModel->clearCart($cart['id']);
            return redirect()->to("/orders/$orderId")
                ->with('success', 'Order placed successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    /**
     * Display order details
     */
    public function show(int $orderId): RedirectResponse|string
    {
        $order = $this->orderModel->getOrderWithDetails($orderId);

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        $data = $this->prepareCommonData('Order Details');
        $data['order'] = $order;
        $data['orderItemsCount'] = $this->orderModel->getOrderItemTotalQuantity($orderId);
        $data['isOrderCancellable'] = $order['status'] === strtolower(StatusType::PENDING->name);

        return $this->renderCustomerView('customer/orders/show', $data, 'customer/cart/index');
    }

    /**
     * Cancel an order
     */
    public function cancel(int $orderId): RedirectResponse
    {
        $order = $this->orderModel->find($orderId);

        if (! $order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        $result = $this->orderModel->cancelOrder($orderId);

        return $result
            ? redirect()->back()->with('success', 'Order cancelled successfully.')
            : redirect()->back()->with('error', 'Failed to cancel order. Please try again.');
    }

    /**
     * Prepare common data needed for most views
     */
    private function prepareCommonData(string $title): array
    {
        $pizzas = $this->pizzaModel->getPizzasWithCategory(onlyAvailable: true);
        $cartItems = [];
        $total = 0;

        if ($this->session->get('isLoggedIn')) {
            $userId = $this->session->get('id');
            $cart = $this->cartModel->getOrCreateCart($userId);
            $cartItems = $this->cartItemModel->getItems($cart['id']);
            $total = $this->cartItemModel->getCartTotal($cart['id']);
        }

        return [
            'title' => $title,
            'pizzas' => $pizzas,
            'cartItems' => $cartItems,
            'cartCount' => count($cartItems),
            'total' => $total,
        ];
    }

    /**
     * Render a view with customer template
     */
    private function renderCustomerView(string $view, array $data, ?string $extraView = null): string
    {
        $output = view('templates/customer/header', $data);

        if ($extraView) {
            $output .= view($extraView, $data);
        }

        $output .= view($view, $data);
        $output .= view('templates/customer/footer');

        return $output;
    }

    /**
     * Validate order form data
     */
    private function validateOrderData(): bool
    {
        $rules = [
            'notes' => 'permit_empty|string|max_length[255]',
            'address' => 'required|string|max_length[255]',
        ];

        return $this->validate($rules);
    }

    /**
     * Save user address if requested
     */
    private function saveUserAddressIfRequested(int $userId): void
    {
        if ($this->request->getPost('save_info')) {
            $address = $this->request->getPost('address');

            $this->userModel->update($userId, [
                'address' => $address,
            ]);

            $this->session->set([
                'address' => $address,
            ]);
        }
    }

    /**
     * Prepare order data for creation
     */
    private function prepareOrderData(int $userId, float $total): array
    {
        return [
            'user_id' => $userId,
            'total_amount' => $total,
            'delivery_address' => $this->request->getPost('address'),
            'contact_number' => $this->session->get('phone'),
            'notes' => $this->request->getPost('notes'),
        ];
    }
}
