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

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->userModel = new \App\Models\UserModel();
        $this->orderModel = new \App\Models\OrderModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard',
        ];

        return view('templates/admin/header', $data)
            . view('admin/index', $data)
            . view('templates/admin/footer');
    }
}
