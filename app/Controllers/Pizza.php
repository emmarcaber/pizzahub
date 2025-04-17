<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PizzaModel;
use CodeIgniter\HTTP\ResponseInterface;

class Pizza extends BaseController
{
    protected $helpers = ['form', 'url'];

    protected $pizzaModel;

    public function __construct()
    {
        $this->pizzaModel = model(PizzaModel::class);
    }

    public function index()
    {
        $pizzas = $this->pizzaModel->getPizzasWithCategory(onlyAvailable: false);

        $data = [
            'title' => 'Pizzas',
            'pizzas' => $pizzas,
        ];

        return view('admin/templates/header', $data)
            . view('admin/pages/pizzas/index', $data)
            . view('admin/templates/footer');
    }
}
