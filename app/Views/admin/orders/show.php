<a?php

use App\Types\StatusType;

?>

<h1 class="mt-4"><?= $title ?? '' ?></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="<?= route_to('admin.index') ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= route_to('admin.orders.index') ?>">Orders</a></li>
    <li class="breadcrumb-item active">Order #<?= esc($order['order_number']) ?></li>
</ol>

<div class="card mb-4">
    <div class="card-body">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Order Details
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Order Number</th>
                        <td><?= esc($order['order_number']) ?></td>
                    </tr>
                    <tr>
                        <th>Customer Name</th>
                        <td><a href="<?= route_to('admin.users.show', $order['customer_id']) ?>"><?= esc($order['customer_name']) ?></a></td>
                    </tr>
                    <tr>
                        <th>Contact Number</th>
                        <td><?= esc($order['customer_phone']) ?></td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td>&#8369;<?= number_format(esc($order['total_amount'] + 30), 2) ?></td>
                    </tr>
                    <tr class="align-middle">
                        <th>Status</th>
                        <td>
                            <form id="statusUpdateForm-<?= $order['id'] ?>"
                                method="POST"
                                action="<?= route_to('admin.orders.update_status', $order['id']) ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="original_status" value="<?= esc($order['status']) ?>">

                                <div class="d-flex align-items-center">
                                    <select name="status"
                                        id="statusSelect-<?= $order['id'] ?>"
                                        class="form-select form-select-sm me-2 w-25"
                                        data-order-id="<?= $order['id'] ?>"
                                        onchange="toggleSubmitButton(this)">
                                        <?php foreach ($statusOptions as $statusKey => $statusValue): ?>
                                            <option value="<?= esc($statusKey) ?>"
                                                <?= $statusKey === $order['status'] ? 'selected' : '' ?>>
                                                <?= strtoupper(esc($statusValue)) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <button type="submit"
                                        id="submitStatusBtn-<?= $order['id'] ?>"
                                        class="btn btn-success btn-sm d-none"
                                        onclick="return confirmStatusUpdate(event, '<?= $order['id'] ?>')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <th>Date Ordered</th>
                        <td><?= esc(date('M d, Y h:i A', strtotime($order['created_at']))) ?></td>
                    </tr>
                </table>

                <!-- Order Items Table -->
                <h5 class="mt-4">Items</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Pizza Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($order['items'])): ?>
                            <tr>
                                <td colspan="4" class="text-center">No items found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($order['items'] as $item): ?>
                                <tr>
                                    <td><?= esc($item['pizza']['name']) ?></td>
                                    <td><?= esc($item['quantity']) ?></td>
                                    <td>&#8369;<?= number_format(esc($item['price']), 2) ?></td>
                                    <td>&#8369;<?= number_format(esc($item['price']) * esc($item['quantity']), 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>