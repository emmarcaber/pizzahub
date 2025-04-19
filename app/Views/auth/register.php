<div class="container" style="padding-top: 5em; padding-bottom: 5em;">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card bg-light py-4">
                <div class="card-header bg-light text-center border-bottom-0">
                    <h4 class="fw-bold">REGISTER</h4>
                    <p class="text-muted">Create an account to order delicious pizzas</p>
                </div>
                <div class="card-body">
                    <form action="<?= route_to('auth.attemptRegister') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control mt-2
                            <?= session('validation') && session('validation')->hasError('name') ? 'is-invalid' : '' ?>"" id=" name" name="name"
                                value="<?= old('name') ?>" required />
                            <?php if (session('validation') && session('validation')->hasError('name')): ?>
                                <div class="invalid-feedback">
                                    <?= session('validation')->getError('name') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control mt-2
                            <?= session('validation') && session('validation')->hasError('email') ? 'is-invalid' : '' ?>"
                                id="email" name="email"
                                value="<?= old('email') ?>" required />
                            <?php if (session('validation') && session('validation')->hasError('email')): ?>
                                <div class="invalid-feedback">
                                    <?= session('validation')->getError('email') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone">Phone Number</label>
                            <input type="tel" class="form-control mt-2
                              <?= session('validation') && session('validation')->hasError('phone') ? 'is-invalid' : '' ?>"
                                id="phone" name="phone"
                                value="<?= old('phone') ?>" required />
                            <?php if (session('validation') && session('validation')->hasError('phone')): ?>
                                <div class="invalid-feedback">
                                    <?= session('validation')->getError('phone') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control mt-2
                            <?= session('validation') && session('validation')->hasError('password') ? 'is-invalid' : '' ?>"
                                id="password" name="password" required />
                            <?php if (session('validation') && session('validation')->hasError('password')): ?>
                                <div class="invalid-feedback">
                                    <?= session('validation')->getError('password') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group mb-3">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control mt-2
                            <?= session('validation') && session('validation')->hasError('confirm_password') ? 'is-invalid' : '' ?>"
                                id="confirm_password"
                                name="confirm_password" required />
                            <?php if (session('validation') && session('validation')->hasError('confirm_password')): ?>
                                <div class="invalid-feedback">
                                    <?= session('validation')->getError('confirm_password') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-warning">Sign Up</button>
                    </form>
                    <p class="mt-3">
                        Already have an account?
                        <a href="<?= route_to('auth.login') ?>">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>