<h1 class="mt-4"><?= $title ?? '' ?></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="<?= route_to('admin.index') ?>">Dashboard</a></li>
    <li class="breadcrumb-item">Orders</li>
</ol>

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-end align-items-center mb-4">
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                <?= $title ?? '' ?> Table
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Contact Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="5 class="text-center">No orders found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= esc($order['order_number']) ?></td>
                                    <td><?= esc($order['customer']) ?></td>
                                    <td>&#8369;<?= number_format(esc($order['total_amount'] + 30), 2) ?></td>
                                    <td><span class="badge bg-<?= \App\Types\StatusType::getColor($order['status']) ?>"><?= strtoupper(esc($order['status'])) ?></span></td>
                                    <td>+63<?= esc($order['customer_phone']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>