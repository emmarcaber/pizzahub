<div class="container mt-5 w-100">
    <div class="row">
        <div class="col-md-12">
            <h2>Order Details</h2>
            <p><strong>Order Number:</strong> <?= esc($order['order_number']) ?></p>
            <p><strong>Status:</strong> <?= esc($order['status']) ?></p>
            <p><strong>Total Amount:</strong> &#8369;<?= number_format($order['total_amount'], 2) ?></p>
            <p><strong>Order Date:</strong> <?= esc($order['created_at']) ?></p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="">
            <h3>Ordered Items</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?= esc($item['pizza']['name']) ?></td>
                            <td><?= esc($item['quantity']) ?></td>
                            <td>&#8369;<?= number_format($item['price'], 2) ?></td>
                            <td>&#8369;<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($order['status'] === 'Pending'): ?>
        <div class="row mt-4">
            <div class="col-md-12">
                <a href="<?= route_to('orders.cancel', $order['id']) ?>" class="btn btn-danger">Cancel Order</a>
            </div>
        </div>
    <?php endif; ?>