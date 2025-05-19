<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Types\StatusType;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class User extends BaseController
{
    protected $helpers = ['form', 'url'];

    protected $session;

    protected $userModel;

    protected $orderModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->userModel = model(UserModel::class);
        $this->orderModel = model(\App\Models\OrderModel::class);
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

    public function show(int $userId)
    {
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(route_to('admin.users.index'))->with('error', 'User not found.');
        }

        $orders = $this->orderModel->getOrdersByUser($userId);

        $data = [
            'title' => 'View User',
            'user' => $user,
            'orders' => $orders,
            'statusOptions' => StatusType::optionsKeyValue(),
        ];

        return view('templates/admin/header', $data)
            . view('admin/users/show', $data)
            . view('templates/admin/footer');
    }

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
