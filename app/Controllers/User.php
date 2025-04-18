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
        $users = $this->userModel
            ->orderBy('id', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Users',
            'users' => $users,
        ];

        return view('templates/admin/header', $data)
            . view('admin/users/index', $data)
            . view('templates/admin/footer');
    }

    /**
     * Show the form to create a new user or registration form.
     */
    public function create() {}

    public function delete(int $id)
    {
        $user = $this->userModel->find($id);

        if (! $user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->userModel->delete($id);

        return redirect()->route('admin.users.index')
            ->with('success', 'User has been deleted successfully.');
    }
}
