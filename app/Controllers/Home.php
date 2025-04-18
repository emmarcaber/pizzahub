<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('templates/customer/header', ['title' => 'Home'])
            . view('templates/customer/hero')
            . view('templates/customer/footer'); 
    }
}
