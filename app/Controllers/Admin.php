<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Admin extends BaseController
{
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
