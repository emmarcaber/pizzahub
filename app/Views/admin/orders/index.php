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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="6 class=" text-center">No orders found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= esc($order['order_number']) ?></td>
                                    <td><?= esc($order['customer']) ?></td>
                                    <td>&#8369;<?= number_format(esc($order['total_amount'] + 30), 2) ?></td>
                                    <td>
                                        <form id="statusUpdateForm-<?= $order['id'] ?>"
                                            method="POST"
                                            action="<?= route_to('admin.orders.update_status', $order['id']) ?>"
                                            class="position-relative">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="PUT">
                                            <input type="hidden" name="original_status" value="<?= esc($order['status']) ?>">

                                            <div class="d-flex align-items-center">
                                                <select name="status"
                                                    id="statusSelect-<?= $order['id'] ?>"
                                                    class="form-select form-select-sm me-2"
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
                                    <td>+63<?= esc($order['customer_phone']) ?></td>
                                    <td>
                                        <a href="<?= route_to('admin.orders.show', $order['id']) ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>