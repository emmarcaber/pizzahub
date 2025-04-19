<div class="container" style="padding-top: 10em">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card bg-light py-4">
                <div class="card-header bg-light text-center border-bottom-0">
                    <h4 class="fw-bold">LOGIN</h4>
                    <p class="text-muted">Login to your account to order delicious pizzas</p>
                </div>
                <div class="card-body">
                    <form action="<?= route_to('auth.attemptLogin') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control mt-2" id="email" name="email" 
                                   value="<?= old('email') ?>" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control mt-2" id="password" 
                                   name="password" required />
                        </div>
                        <button type="submit" class="btn btn-warning">Login</button>
                    </form>
                    <p class="mt-3">
                        Don't have an account?
                        <a href="<?= route_to('auth.register') ?>">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>