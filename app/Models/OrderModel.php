<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Types\StatusType;
use App\Models\OrderItemModel;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'delivery_address',
        'contact_number',
        'notes'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id' => 'required|numeric',
        'order_number' => 'required|max_length[20]|is_unique[orders.order_number,id,{id}]',
        'total_amount' => 'required|numeric|greater_than[0]',
        'status' => 'required|in_list[pending,preparing,baking,out_for_delivery,delivered,cancelled]',
        'delivery_address' => 'required',
        'contact_number' => 'required|max_length[20]',
        'notes' => 'permit_empty',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function generateOrderNumber(): string
    {
        $prefix = 'PIZ';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));

        return "{$prefix}-{$date}-{$random}";
    }

    public function createOrder(array $orderData, array $items): ?int
    {
        $this->db->transStart();

        if (empty($orderData['order_number'])) {
            $orderData['order_number'] = $this->generateOrderNumber();
        }

        $orderData['status'] = strtolower(StatusType::PENDING->name);
        $orderId = $this->insert($orderData, true);

        if ($orderId) {
            // Insert order items
            $orderItemModel = new OrderItemModel();
            foreach ($items as $item) {
                $item['order_id'] = $orderId;
                $orderItemModel->insert($item);
            }
        }

        $this->db->transComplete();

        return $this->db->transStatus() ? $orderId : null;
    }

    public function getOrderWithDetails(int $orderId): array|object|null
    {
        $order = $this->select('orders.*, users.name as customer_name, users.email as customer_email, users.phone as customer_phone, users.address as customer_address, users.id as customer_id')
            ->join('users', 'users.id = orders.user_id')
            ->find($orderId);

        if (!$order) {
            return null;
        }

        // Get order items
        $orderItemModel = new OrderItemModel();
        $order['items'] = $orderItemModel->getItemsWithDetails($orderId);

        return $order;
    }

    public function getOrdersByUser(int $userId): array
    {
        $orders = $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        if (!$orders) {
            return [];
        }

        $orderItemModel = new OrderItemModel();
        foreach ($orders as &$order) {
            $order['items'] = $orderItemModel->getItemsWithDetails($order['id']);
        }

        return $orders;
    }

    public function getOrdersByStatus(string $status, int $limit = 0): array
    {
        $builder = $this->where('status', $status)
            ->orderBy('created_at', 'ASC');

        if ($limit > 0) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function updateStatus(int $orderId, string $status)
    {
        if (!in_array($status, StatusType::optionsLower())) {
            return false;
        }

        return $this->update($orderId, ['status' => $status]);
    }

    public function cancelOrder(int $orderId): bool
    {
        $order = $this->find($orderId);
        $status = $order['status'] ?? null;

        if (! $order || $status !== strtolower(StatusType::PENDING->name)) {
            return false;
        }

        return $this->update($orderId, ['status' => strtolower(StatusType::CANCELLED->name)]);
    }

    public function getOrderItemTotalQuantity(int $orderId): int
    {
        $orderItemModel = new OrderItemModel();
        return $orderItemModel->selectSum('quantity')
            ->where('order_id', $orderId)
            ->get()
            ->getRow()
            ->quantity ?? 0;
    }

    public function getWeeklySalesData()
    {
        return $this->db->query("
            SELECT 
                DATE(created_at) as day, 
                SUM(total_amount) as total 
            FROM orders 
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY day ASC
        ")->getResultArray();
    }

    public function searchOrdersByUser($userId, $keyword = null): array
    {
        $builder = $this->db->table('orders')
            ->select('orders.*, users.name as customer_name')
            ->join('users', 'users.id = orders.user_id')
            ->where('orders.user_id', $userId)
            ->orderBy('orders.created_at', 'DESC');

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('orders.order_number', $keyword)
                ->orLike('orders.status', $keyword)
                ->groupEnd();
        }

        $orders = $builder->get()->getResultArray();

        $orderItemModel = new OrderItemModel();
        foreach ($orders as &$order) {
            $order['items'] = $orderItemModel->getItemsWithDetails($order['id']);
        }

        return $orders;
    }
}
