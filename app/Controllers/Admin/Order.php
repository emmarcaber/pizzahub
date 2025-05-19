<?php

namespace App\Controllers\Admin;

use App\Types\StatusType;
use App\Models\OrderModel;
use App\Models\PizzaModel;
use App\Models\OrderItemModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class Order extends BaseController
{

    protected $helpers = ['form', 'url'];

    protected $pizzaModel;

    protected $orderModel;

    protected $orderItemModel;


    public function __construct()
    {
        $this->pizzaModel = model(PizzaModel::class);
        $this->orderModel = model(OrderModel::class);
        $this->orderItemModel = model(OrderItemModel::class);
    }

    public function index()
    {
        $orders = $this->orderModel
            ->select('orders.*, users.name as customer, users.phone as customer_phone')
            ->join('users', 'users.id = orders.user_id')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Orders',
            'orders' => $orders,
            'statusOptions' => StatusType::optionsKeyValue(),
            'validation' => \Config\Services::validation(),
        ];

        return view('templates/admin/header', $data)
            . view('admin/orders/index', $data)
            . view('templates/admin/footer');
    }

    public function show(int $orderId)
    {
        $order = $this->orderModel->getOrderWithDetails($orderId);

        if (!$order) {
            return redirect()
                ->to(route_to('admin.orders.index'))
                ->with('error', 'Order not found.');
        }

        $data = [
            'title' => 'View Order',
            'order' => $order,
            'statusOptions' => StatusType::optionsKeyValue(),
        ];

        return view('templates/admin/header', $data)
            . view('admin/orders/show', $data)
            . view('templates/admin/footer');
    }

    public function updateStatus(int $orderId): RedirectResponse|string
    {
        $rules = [
            'status' => 'required|in_list[' . implode(',', StatusType::optionsLower()) . ']',
            'original_status' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('error', 'Invalid status selected.')
                ->withInput();
        }

        $newStatus = $this->request->getPost('status');
        $originalStatus = $this->request->getPost('original_status');

        if ($newStatus === $originalStatus) {
            return redirect()->back()
                ->with('error', 'No status change was made.');
        }

        try {
            $updated = $this->orderModel->updateStatus($orderId, $newStatus);

            if (!$updated) {
                return redirect()->back()
                    ->with('error', 'Failed to update order status.')
                    ->withInput();
            }

            return redirect()->back()
                ->with('success', "Order status updated to " . strtoupper($newStatus) . ".");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An unexpected error occurred while updating status.')
                ->withInput();
        }
    }
}
