<form action="<?= route_to('orders.store') ?>" method="POST" class="row container p-5 m-5">
    <?= csrf_field() ?>
    <div class="col-md-5 col-lg-4 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-primary">Your cart</span>
            <span class="badge bg-primary rounded-pill"><?= $cartCount ?></span>
        </h4>
        <ul class="list-group mb-3">
            <?php foreach ($cartItems as $item): ?>
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0"><?= $item['name'] ?></h6>
                        <small class="text-muted"><?= $item['quantity'] ?> x ₱<?= $item['price'] ?></small>
                    </div>
                    <span class="text-muted">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                </li>
            <?php endforeach; ?>
            <li class="list-group-item d-flex justify-content-between">
                <span>Total (PHP)</span>
                <strong>&#8369;<?= $total ?></strong>
            </li>
        </ul>

        <div class="card p-2">
            <div class="input-group">
                <textarea
                    class="form-control <?= session('validation') && session('validation')->hasError('notes') ? 'is-invalid' : '' ?>"
                    name="notes" placeholder="Add your order notes here (e.g. landmarks)" rows="3" required><?= old('notes') ?></textarea>
                <?php if (session('validation') && session('validation')->hasError('notes')): ?>
                    <div class="invalid-feedback">
                        <?= session('validation')->getError('notes') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Billing Address</h4>
        <form class="needs-validation" novalidate="">
            <div class="row g-3">
                <div class="col-12">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" value="<?= session('name') ?>" disabled>
                </div>

                <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" value="<?= session('email') ?>" disabled>
                </div>

                <div class="col-12">
                    <label for="phone" class="form-label">Contact Number</label>
                    <div class="input-group">
                        <span class="input-group-text">+63</span>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Contact Number" maxlength="10" value="<?= session('phone') ?>" disabled>
                    </div>
                </div>

                <div class="col-12">
                    <label for="address" class="form-label">Address</label>
                    <input type="text"
                        class="form-control <?= session('validation') && session('validation')->hasError('address') ? 'is-invalid' : '' ?>"
                        id="address"
                        name="address"
                        value="<?= old('address', session('address')) ?>"
                        placeholder="1234 Main St"
                        required>
                    <?php if (session('validation') && session('validation')->hasError('address')): ?>
                        <div class="invalid-feedback">
                            <?= session('validation')->getError('address') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-check my-3">
                <input type="checkbox" class="form-check-input" id="save_info" name="save_info">
                <label class="form-check-label" for="save_info">Save this information for next time</label>
            </div>

            <hr class="my-4">

            <h4 class="mb-3">Payment</h4>

            <div class="my-3">
                <div class="form-check">
                    <input id="cash_on_delivery" name="paymentMethod" type="radio" class="form-check-input" checked>
                    <label class="form-check-label" for="cash_on_delivery">Cash on Delivery</label>
                </div>
            </div>

            <hr class="my-4">

            <button class="w-100 btn btn-primary btn-lg" type="submit" onclick="return confirm('Are you sure you want to place this order?')">
                <i class="fas fa-arrow-right"></i>
                Continue to checkout</button>
        </form>
    </div>
</form>