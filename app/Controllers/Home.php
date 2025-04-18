<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('customer/templates/header', ['title' => 'Home'])
            . view('customer/templates/hero')
            . view('customer/templates/footer'); 
    }
}
