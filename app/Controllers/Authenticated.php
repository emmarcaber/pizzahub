<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Authenticated extends BaseController
{
    protected $userModel;
    protected $validation;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
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
