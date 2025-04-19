<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Authenticated extends BaseController
{
    public function login(): string
    {
        return view('templates/customer/header', ['title' => 'Login'])
            . view('auth/login')
            . view('templates/customer/footer');
    }

    public function register(): string
    {
        return view('templates/customer/header', ['title' => 'Register'])
            . view('auth/register')
            . view('templates/customer/footer');
    }
}
