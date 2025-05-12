<?php

use App\Types\StatusType;

?>

<div class="container my-5 py-5 w-100">
    <div class="row">
        <div class="col-md-12">
            <h2>Your Orders</h2>
        </div>

        <form class="d-flex mt-4" role="search">
            <input class="form-control me-2" type="search" placeholder="Search Orders..." aria-label="Search">
            <button class="btn btn-outline-danger" type="submit">Search</button>
        </form>
    </div>

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