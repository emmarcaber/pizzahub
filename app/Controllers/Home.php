<?php

namespace App\Controllers;

use App\Models\PizzaModel;

class Home extends BaseController
{
    protected $helpers = ['form', 'url'];

    protected $pizzaModel;

    public function __construct()
    {
        $this->pizzaModel = model(PizzaModel::class);
    }

    public function index(): string
    {
        $pizzas = $this->pizzaModel->getPizzasWithCategory(onlyAvailable: true);

        $data = [
            'title' => 'Home',
            'pizzas' => $pizzas,
        ];

        return view('templates/customer/header', $data)
            . view('templates/customer/hero')
            . view('customer/pizzas/index')
            . view('templates/customer/footer'); 
    }
}
