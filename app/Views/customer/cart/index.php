<?php if (session('isLoggedIn')): ?>
    <div class="offcanvas offcanvas-end bg-warning" data-bs-scroll="true" data-bs-backdrop="false" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">
                <i class="fas fa-shopping-cart"></i>
                <span class="text-dark">Cart (<?= count($cartItems) ?>)</span>
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between">
            <?php if (empty($cartItems)): ?>
                <div class="text-center my-4">
                    <i class="fas fa-shopping-cart fa-3x mb-3 text-light"></i>
                    <p class="text-light">Your cart is empty.</p>
                    <a href="<?= route_to('home.index') . '#pizzaList' ?>" class="btn btn-outline-dark">Browse Pizzas</a>
                </div>
            <?php else: ?>
                <div class="cart-items" style="max-height: 60vh; overflow-y: auto;">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="<?= $item['image'] ? base_url($item['image']) : base_url('images/no-image-available.jpg') ?>"
                                        class="img-fluid rounded-start h-100"
                                        style="object-fit: cover; min-height: 100px;"
                                        alt="<?= esc($item['name']) ?>">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="card-title mb-1"><?= esc($item['name']) ?></h6>
                                            <a href="<?= site_url('cart/remove/' . $item['id']) ?>" class="text-danger">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                        <p class="card-text mb-2">
                                            <small class="text-dark">₱<?= number_format($item['price'], 2) ?></small>
                                        </p>
                                        <div class="d-flex align-items-center">
                                            <form action="<?= route_to('cart.update', $item['id']) ?>" method="post" class="d-flex align-items-center">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="PUT">
                                                <button type="button" class="btn btn-sm btn-outline-dark quantity-btn minus" data-action="decrease">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" name="quantity"
                                                    value="<?= $item['quantity'] ?>"
                                                    min="1"
                                                    class="form-control form-control-sm text-center mx-1 quantity-input"
                                                    style="width: 50px;">
                                                <button type="button" class="btn btn-sm btn-outline-dark quantity-btn plus" data-action="increase">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button type="submit" class="btn btn-sm btn-link d-none update-btn">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <div class="ms-auto text-dark fw-bold">
                                                ₱<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="cart-summary border-top pt-3">
                    <div class="d-flex justify-content-between mb-3 fs-5">
                        <span>Total:</span>
                        <span class="fw-bold">₱<?= number_format($total, 2) ?></span>
                    </div>
                    <a href="<?= route_to('orders.checkout') ?>" class="btn btn-danger w-100 py-2">
                        Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>