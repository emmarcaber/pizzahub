<?php

namespace App\Controllers;

use App\Models\PizzaModel;
use App\Models\CategoryModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Pizza extends BaseController
{
    protected $helpers = ['form', 'url'];

    protected $pizzaModel;

    protected $categoryModel;

    public function __construct()
    {
        $this->pizzaModel = model(PizzaModel::class);
        $this->categoryModel = model(CategoryModel::class);
    }

    public function index()
    {
        $pizzas = $this->pizzaModel->getPizzasWithCategory(onlyAvailable: false);

        $data = [
            'title' => 'Pizzas',
            'pizzas' => $pizzas,
            'validation' => \Config\Services::validation(),
        ];

        return view('templates/admin/header', $data)
            . view('admin/pizzas/index', $data)
            . view('templates/admin/footer');
    }

    public function create()
    {
        $categories = $this->categoryModel->orderBy('name')->findAll();

        $data = [
            'title' => 'Create Pizza',
            'categories' => $categories,
            'validation' => \Config\Services::validation(),
        ];

        return view('templates/admin/header', $data)
            . view('admin/pizzas/create', $data)
            . view('templates/admin/footer');
    }

    public function store()
    {
        if (! $this->validate($this->pizzaModel->getCreateValidationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator)
                ->with('error', 'Failed to create pizza. Please try again.');
        }

        $data = [
            'category_id' => $this->request->getPost('category_id'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'is_available' => (int) $this->request->getPost('is_available') ?? 0
        ];

        $pizzaId = $this->pizzaModel->insert($data, true);

        // Handle image upload using model's method
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $this->pizzaModel->handleImageUpload($pizzaId, $image);
        }

        return redirect()->route('admin.pizzas.index')
            ->with('success', 'Pizza has been created successfully.');
    }

    public function edit(int $id)
    {
        $pizza = $this->pizzaModel->find($id);

        if (!$pizza) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $categories = $this->categoryModel->findAll();

        $data = [
            'title' => 'Edit Pizza',
            'pizza' => $pizza,
            'categories' => $categories,
            'validation' => \Config\Services::validation()
        ];

        return view('templates/admin/header', $data)
            . view('admin/pizzas/edit', $data)
            . view('templates/admin/footer');
    }

    public function update(int $id)
    {
        $pizza = $this->pizzaModel->find($id);
        if (! $pizza) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (! $this->validate($this->pizzaModel->getUpdateValidationRules($id))) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator)
                ->with('error', 'Failed to update pizza. Please try again.');
        }

        $data = [
            'category_id' => $this->request->getPost('category_id'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'is_available' => $this->request->getPost('is_available') ?? 0
        ];

        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && ! $image->hasMoved()) {
            $this->pizzaModel->handleImageUpload($id, $image);
        }
        
        $result = $this->pizzaModel->update($id, $data);

        if ($result === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update pizza. Please try again.');
        }

        return redirect()->route('admin.pizzas.index', [$id])
            ->with('success', 'Pizza has been updated successfully.');
    }

    public function delete(int $id)
    {
        $pizza = $this->pizzaModel->find($id);

        if (! $pizza) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (! $this->pizzaModel->canDelete($id)) {
            return redirect()->back()
                ->with('error', 'Pizza cannot be deleted as it is associated with an order.');
        }

        $this->pizzaModel->deleteImage($id);

        $this->pizzaModel->delete($id);

        return redirect()->route('admin.pizzas.index')
            ->with('success', 'Pizza has been deleted successfully.');
    }
}