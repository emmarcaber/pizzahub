<?php

namespace App\Filters;

use App\Models\OrderModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class OrderOwnerFilter implements FilterInterface
{
    /**
     * Filter to ensure users can only view their own orders
     *
     * @param RequestInterface $request
     * @param array|null $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = Services::session();
        
        $userId = $session->get('id');
        
        $uri = $request->getUri();
        $segments = $uri->getSegments();
        
        if (count($segments) >= 2 && $segments[0] === 'orders' && is_numeric($segments[1])) {
            $orderId = (int) $segments[1];
            
            if ($orderId > 0) {
                $orderModel = model(OrderModel::class);
                /** @var mixed $order */
                $order = $orderModel->find($orderId);
                
                if (!$order || $order['user_id'] !== $userId) {
                    // Admins can bypass this restriction if needed
                    if ($session->get('role') !== 'admin') {
                        return redirect()->back()
                            ->with('error', 'You do not have permission to view that order.');
                    }
                }
            }
        }
        
        return;
    }

    /**
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}