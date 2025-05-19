<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Admin extends BaseController
{
    protected $helpers = ['form', 'url'];

    protected $session;

    protected $userModel;

    protected $orderModel;

    protected $pizzaModel;

    protected $categoryModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->userModel = new \App\Models\UserModel();
        $this->orderModel = new \App\Models\OrderModel();
        $this->pizzaModel = new \App\Models\PizzaModel();
        $this->categoryModel = new \App\Models\CategoryModel();
    }

    public function index()
    {
        $totalUsers = $this->userModel->countAllResults();
        $totalPizzas = $this->pizzaModel->countAllResults();
        $totalOrders = $this->orderModel->countAllResults();

        $salesData = $this->orderModel->getWeeklySalesData();
        $topPizzas = $this->pizzaModel->getTopSellingPizzas(5);

        $data = [
            'title' => 'Dashboard',
            'totalUsers' => $totalUsers,
            'totalPizzas' => $totalPizzas,
            'totalOrders' => $totalOrders,
            'salesData' => $salesData,
            'topPizzas' => $topPizzas,
        ];

        return view('templates/admin/header', $data)
            . view('admin/index', $data)
            . view('templates/admin/footer');
    }
}
