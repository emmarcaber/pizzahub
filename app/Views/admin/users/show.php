<?php

use App\Types\StatusType;

?>

<h1 class="mt-4"><?= $title ?? '' ?></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="<?= route_to('admin.index') ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= route_to('admin.users.index') ?>">Users</a></li>
    <li class="breadcrumb-item active">View User - <?= esc($user['name']) ?></li>
</ol>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        User Details
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <td><?= esc($user['name']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= esc($user['email']) ?></td>
            </tr>
            <tr>
                <th>Role</th>
                <td><?= esc(ucfirst($user['role'])) ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?= esc($user['address']) ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>+63<?= esc($user['phone']) ?></td>
            </tr>
        </table>

        <?php if ($orders): ?>
            <h4 class="mt-4">Orders</h4>

            <table class="table table-bordered" id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th>Date Ordered</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <?php foreach ($orders as $order): ?>

                    <tbody>
                        <tr class="align-middle">
                            <td><?= esc($order['order_number']) ?></td>
                            <td>
                                <span class="badge bg-<?= StatusType::getColor($order['status']) ?>"><?= strtoupper(StatusType::getLabel(esc($order['status']))) ?></span>
                            </td>
                            <td>&#8369;<?= number_format($order['total_amount'] + 30, 2) ?></td>
                            <td><?= esc(date('M d, Y h:i A', strtotime($order['created_at']))) ?></td>
                            <td>
                                <a href="<?= route_to('admin.orders.show', $order['id']) ?>"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>

                    </tbody>

                <?php endforeach; ?>
            </table>

        <?php else: ?>
            No orders found for this user.
        <?php endif; ?>

    </div>