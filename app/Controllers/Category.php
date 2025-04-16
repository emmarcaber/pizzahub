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
        $data = [
            'title' => 'Categories',
            'categories' => $this->categoryModel->findAll(),
        ];

        return view('admin/templates/header', $data)
            . view('admin/pages/categories/index', $data)
            . view('admin/templates/footer');
    }

    public function delete(int $id)
    {
        $category = $this->categoryModel->find($id);

        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->categoryModel->delete($id);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
