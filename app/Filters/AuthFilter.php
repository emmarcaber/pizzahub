<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        
        // Check if user is not logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(route_to('auth.login'))->with('error', 'Please login first to access that page.');
        }
        
        // Check for role-based access if arguments are provided
        if (!empty($arguments)) {
            $userRole = $session->get('role');
            
            if (!in_array($userRole, $arguments)) {
                return redirect()->back()->with('error', 'Sorry, you do not have permission to access that page.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}