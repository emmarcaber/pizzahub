<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class User extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Users',
        ];

        return view('admin/templates/header', $data)
        . view('admin/pages/users/index', $data)
        . view('admin/templates/footer');
    }
}
