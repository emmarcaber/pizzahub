<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class User extends BaseController
{
    protected $helpers = ['form', 'url'];
    protected $userModel;

    public function __construct()
    {
        $this->userModel = model(UserModel::class);
    }


    public function index()
    {

        $data = [
            'title' => 'Users',
            'users' => $this->userModel->findAll(),
        ];

        return view('admin/templates/header', $data)
            . view('admin/pages/users/index', $data)
            . view('admin/templates/footer');
    }

    public function create() {}

    public function delete(int $id)
    {
        $user = $this->userModel->find($id);

        if (! $user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->userModel->delete($id);

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
