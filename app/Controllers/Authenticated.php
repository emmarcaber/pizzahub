<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Authenticated extends BaseController
{
    public function index()
    {
        return view('layout/admin-layout');
    }
}
