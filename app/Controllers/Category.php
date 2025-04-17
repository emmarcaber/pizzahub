<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class Category extends BaseController
{
    protected $helpers = ['form', 'url'];

    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = model(CategoryModel::class);
    }

    public function index()
    {
        $categories = $this->categoryModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Categories',
            'categories' => $categories,
        ];

        return view('admin/templates/header', $data)
            . view('admin/pages/categories/index', $data)
            . view('admin/templates/footer');
    }

    public function create()
    {
        $data = [
            'title' => 'Create Category',
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/templates/header', $data)
            . view('admin/pages/categories/create', $data)
            . view('admin/templates/footer');
    }

    public function store()
    {
        if (!$this->validate($this->categoryModel->validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator)
                ->with('error', 'Category has been not created.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        $this->categoryModel->save($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category has been created successfully!');
    }

    public function delete(int $id)
    {
        $category = $this->categoryModel->find($id);

        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->categoryModel->delete($id);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category has been deleted successfully.');
    }
}
