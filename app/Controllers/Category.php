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
            ->orderBy('id', 'DESC')
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
                ->with('error', 'Failed to create category. Please try again.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        $this->categoryModel->save($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category has been created successfully.');
    }

    public function edit(int $id)
    {
        $category = $this->categoryModel->find($id);

        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Category',
            'category' => $category,
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/templates/header', $data)
            . view('admin/pages/categories/edit', $data)
            . view('admin/templates/footer');
    }

    public function update(int $id)
    {
        $category = $this->categoryModel->find($id);
        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $validationRules = $this->categoryModel->validationRules;
        $validationRules['name'] = "required|min_length[3]|max_length[50]|is_unique[categories.name,id,{$id}]";

        if (! $this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator)
                ->with('error', 'Failed to update category. Please try again.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        $this->categoryModel->update($id, $data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category has been updated successfully!');
    }

    public function delete(int $id)
    {
        $category = $this->categoryModel->find($id);

        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (! $this->categoryModel->canDelete($id)) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Category cannot be deleted because it has associated pizzas.');
        }

        $this->categoryModel->delete($id);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category has been deleted successfully.');
    }
}
