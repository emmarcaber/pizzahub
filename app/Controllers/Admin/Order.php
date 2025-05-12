<?php

namespace App\Controllers\Admin;

use App\Models\OrderModel;
use App\Models\PizzaModel;
use App\Models\OrderItemModel;
use App\Controllers\BaseController;
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
            'validation' => \Config\Services::validation(),
        ];

        return view('templates/admin/header', $data)
            . view('admin/orders/index', $data)
            . view('templates/admin/footer');
    }
}
