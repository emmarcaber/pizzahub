<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\UserModel;
use App\Models\PizzaModel;
use App\Models\CartItemModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;

class Authenticated extends BaseController
{
    protected $userModel;

    protected $pizzaModel;

    protected $cartModel;

    protected $cartItemModel;
    protected $validation;
    protected $session;

    public function __construct()
    {
        $this->userModel = model(UserModel::class);
        $this->pizzaModel = model(PizzaModel::class);
        $this->cartModel = model(CartModel::class);
        $this->cartItemModel = model(CartItemModel::class);
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
    }

    public function login(): RedirectResponse|string
    {
        $data = [
            'title' => 'Login',
            'validation' => $this->validation
        ];

        // Redirect if already logged in
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to($this->getRedirectUrl());
        }

        return view('templates/customer/header', $data)
            . view('auth/login', $data)
            . view('templates/customer/footer');
    }

    public function attemptLogin(): RedirectResponse
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Invalid email or password')
                ->with('validation', $this->validator);
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->verifyCredentials($email, $password);

        if (!$user) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Invalid email or password');
        }

        $this->setUserSession($user);

        return redirect()->to($this->getRedirectUrl());
    }

    public function register(): RedirectResponse|string
    {
        $data = [
            'title' => 'Register',
            'validation' => $this->validation
        ];

        // Redirect if already logged in
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to($this->getRedirectUrl());
        }

        return view('templates/customer/header', $data)
            . view('auth/register', $data)
            . view('templates/customer/footer');
    }

    public function updateProfile(): RedirectResponse|string
    {
        $pizzas = $this->pizzaModel->getPizzasWithCategory(onlyAvailable: true);
        $cartItems = [];
        $total = 0;

        if ($this->session->get('isLoggedIn')) {
            $userId = $this->session->get('id');
            $cart = $this->cartModel->getOrCreateCart($userId);
            $cartItems = $this->cartItemModel->getItems($cart['id']);
            $total = $this->cartItemModel->getCartTotal($cart['id']);
        }

        $data = [
            'title' => 'Update Profile',
            'validation' => $this->validation,
            'user' => $this->userModel->find($this->session->get('id')),
            'pizzas' => $pizzas,
            'cartItems' => $cartItems,
            'cartCount' => count($cartItems),
            'total' => $total,
        ];

        return view('templates/customer/header', $data)
            . view('auth/update-profile', $data)
            . view('templates/customer/footer');
    }

    public function attemptUpdateProfile(): RedirectResponse
    {
        $userId = $this->session->get('id');

        $rules = [
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'phone' => 'required|regex_match[/^[0-9]{10}$/]',
            'address' => 'required|min_length[5]|max_length[255]'
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update profile. Please try again.')
                ->with('validation', $this->validator);
        }

        $userId = $this->session->get('id');
        $userData = [
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address')
        ];

        if (!$this->userModel->update($userId, $userData)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update profile. Please try again.');
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(): RedirectResponse
    {
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        $messages = [
            'current_password' => [
                'required' => 'The current password field is required.',
                'min_length' => 'Current password must be at least 8 characters.'
            ],
            'new_password' => [
                'required' => 'The new password field is required.',
                'min_length' => 'New password must be at least 8 characters.'
            ],
            'confirm_password' => [
                'required' => 'Please confirm your new password.',
                'matches' => 'Passwords do not match.'
            ]
        ];

        $userId = $this->session->get('id');
        /** @var mixed */
        $user = $this->userModel->find($userId);
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        if (!$this->userModel->verifyCredentials($user['email'], $currentPassword)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to change password. Current password is incorrect.');
        }

        if (!$this->validate($rules, $messages)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to change password. Please try again.')
                ->with('validation', $this->validator);
        }

        if (!$this->userModel->update($userId, ['password' => password_hash($newPassword, PASSWORD_DEFAULT)])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to change password. Please try again.');
        }

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    public function attemptRegister(): RedirectResponse
    {
        // Define validation rules
        $rules = [
            'name' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'phone' => 'required|regex_match[/^[0-9]{10,15}$/]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ];

        // Custom error messages
        $messages = [
            'name' => [
                'required' => 'The name field is required.',
                'min_length' => 'Name must be at least 3 characters.',
                'max_length' => 'Name cannot exceed 50 characters.'
            ],
            'email' => [
                'required' => 'The email field is required.',
                'valid_email' => 'Please enter a valid email address.',
                'is_unique' => 'This email is already registered.'
            ],
            'phone' => [
                'required' => 'The phone number is required.',
                'regex_match' => 'Phone number must be 10-15 digits.'
            ],
            'password' => [
                'required' => 'The password field is required.',
                'min_length' => 'Password must be at least 8 characters.'
            ],
            'confirm_password' => [
                'required' => 'Please confirm your password.',
                'matches' => 'Passwords do not match.'
            ]
        ];

        // Validate input
        if (!$this->validate($rules, $messages)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('validation', $this->validator)
                ->with('error', 'Registration failed. Please check your input.');
        }

        try {
            // Prepare user data
            $userData = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'password' => $this->request->getPost('password'), // Will be hashed by model
                'role' => 'customer',
            ];

            // Attempt to insert user
            if (!$this->userModel->insert($userData)) {
                throw new \RuntimeException('Failed to create user account.');
            }

            // Get the newly created user
            $user = $this->userModel->getUserByEmail($userData['email']);
            if (!$user) {
                throw new \RuntimeException('Failed to retrieve user after registration.');
            }

            // Set user session
            $this->setUserSession($user);

            return redirect()
                ->to($this->getRedirectUrl())
                ->with('success', 'Registration successful! Welcome, ' . $user['name'] . '!');
        } catch (\Exception $e) {
            // Log the error for debugging
            log_message('error', 'Registration error: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Registration failed. Please try again later.');
        }
    }

    public function logout(): RedirectResponse
    {
        $this->session->destroy();
        return redirect()->to(route_to('auth.login'))->with('success', 'You have been logged out.');
    }

    protected function setUserSession(array $user): void
    {
        $userData = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'address' => $user['address'],
            'phone' => $user['phone'],
            'role' => $user['role'],
            'isLoggedIn' => true
        ];

        $this->session->set($userData);
    }

    protected function getRedirectUrl(): string
    {
        return $this->session->get('role') === 'admin' ? '/admin/dashboard' : '/';
    }
}
