<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class User extends BaseController
{
    public function index()
    {

        $data = [
            'title' => 'Users',
            'users' => model(UserModel::class)->findAll(),
        ];

        return view('admin/templates/header', $data)
        . view('admin/pages/users/index', $data)
        . view('admin/templates/footer');
    }
}
