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

        return view('admin/templates/header', $data)
        . view('admin/pages/dashboard', $data)
        . view('admin/templates/footer');
    }
}
