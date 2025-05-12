<?php

use App\Types\StatusType;

?>


<div class="container mt-5 py-5 w-100">
    <div class="row my-2">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h2>Order Details</h2>
            <div class="row">
                <div class="col-md-12">
                    <a href="<?= route_to('orders.index') ?>" class="btn btn-sm btn-secondary text-white">Back to Orders</a>
                    <?php if ($isOrderCancellable): ?>
                        <a href="<?= route_to('orders.cancel', $order['id']) ?>" class="btn btn-sm btn-danger">Cancel Order</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card my-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center gap-3">
                        <h5 class="card-title m-0"><?= esc($order['order_number']) ?></h5>
                        <h4>&#8369;<?= esc(number_format($order['total_amount'] + 30, 2)) ?></h4>
                    </div>
                    <span class="text-muted"><?= StatusType::getDescription($order['status']) ?></span>
                </div>
                <span class="badge bg-<?= StatusType::getColor($order['status']) ?>"><?= esc(strtoupper($order['status'])) ?></span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                            <tr class="align-middle">
                                <td class="d-flex align-items-center gap-3">
                                    <img src="<?= $item['pizza']['image'] ? base_url($item['pizza']['image']) : base_url('images/no-image-available.jpg') ?>" alt="<?= esc($item['pizza']['name']) ?>" class="img-fluid" style="max-width: 100px;">
                                    <?= esc($item['pizza']['name']) ?>
                                </td>
                                <td><span class="text-muted">Price:</span> &#8369;<?= esc(number_format($item['price'], 2)) ?></td>
                                <td><span class="text-muted">Qty:</span> <?= esc($item['quantity']) ?></td>
                                <td> &#8369;<?= esc(number_format($item['quantity'] * $item['price'], 2)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th>&#8369;<?= esc(number_format($order['total_amount'], 2)) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="row container d-flex justify-content-around">
        <div class="card col-md-5 col-lg-5 p-1">
            <div class="card-body d-flex flex-column gap-1">
                <span class="fw-bold"><?= $order['customer_name'] ?></span>
                <span><?= $order['delivery_address'] ?></span>
                <span class="mt-2"><?= $order['customer_email'] ?></span>
                <span>+63<?= $order['customer_phone'] ?></span>
            </div>
            <div class="card-footer d-flex flex-column gap-1">
                <span>Notes:</span>
                <span class="fw-bold"><?= $order['notes'] ? esc($order['notes']) : 'No special instructions' ?></span>
            </div>
        </div>


        <div class="card col-md-7 col-lg-6 p-0">
            <div class="card-body">
                <h5 class="mt-2">Total Summary</h5>
                <div class="d-flex flex-column">
                    <div class="d-flex justify-content-between">
                        <span>Subtotal (<?= $orderItemsCount ?> item<?= $orderItemsCount > 1 ? 's' : '' ?>): </span>
                        <span>&#8369;<?= esc(number_format($order['total_amount'], 2)) ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Delivery Fee:</span>
                        <span>&#8369;30.00</span>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <span class="fw-bold">Total:</span>
                <span class="fw-bold">&#8369;<?= esc(number_format($order['total_amount'] + 30, 2)) ?></span>
            </div>
        </div>
    </div>
</div>