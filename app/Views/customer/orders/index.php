<?php

use App\Types\StatusType;

?>

<div class="container my-5 py-5 w-100">
    <div class="row">
        <div class="col-md-12">
            <h2>Your Orders</h2>
        </div>

        <form class="d-flex mt-4" role="search" action="<?= route_to('orders.index') ?>" method="get">
            <input class="form-control me-2" type="search" name="search" placeholder="Search Orders..."
                aria-label="Search" value="<?= isset($keyword) ? esc($keyword) : '' ?>">
            <button class="btn btn-outline-danger" type="submit">Search</button>
            <?php if (isset($keyword) && !empty($keyword)): ?>
                <a href="<?= route_to('orders.index') ?>" class="btn btn-outline-secondary ms-2">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (isset($keyword) && !empty($keyword)): ?>
        <div class="alert alert-info alert-dismissible fade show position-fixed start-50 translate-middle-x mt-3" style="top: 3em" role="alert">
            <?php if (count($orders) > 0): ?>
                Found <?= count($orders) ?> result(s) for "<?= esc($keyword) ?>"
            <?php else: ?>
                No orders found matching "<?= esc($keyword) ?>". Try another search term.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="card my-4">
            <div class="card-body text-center py-5">
                <h5>No orders found</h5>
                <?php if (!isset($keyword) || empty($keyword)): ?>
                    <p>You haven't placed any orders yet.</p>
                    <a href="<?= base_url() ?>" class="btn btn-primary">Browse Pizzas</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($orders as $order): ?>
        <a href="<?= route_to('orders.show', $order['id']) ?>" class="text-decoration-none">
            <div class="card my-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex align-items-center gap-3">
                                <h5 class="card-title m-0 text-black"><?= esc($order['order_number']) ?></h5>
                            </div>
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
                        </table>
                    </div>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>