<div class="container" style="padding-top: 5em; padding-bottom: 5em;">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card bg-light py-4">
                <div class="card-header bg-light text-center border-bottom-0">
                    <h4 class="fw-bold">REGISTER</h4>
                    <p class="text-muted">Create an account to order delicious pizzas</p>
                </div>
                <div class="card-body">
                    <form id="registrationForm" action="">
                        <div class="form-group mb-3">
                            <label for="name">
                                Name
                            </label>
                            <input type="name"
                                class="form-control mt-2"
                                id="name" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">
                                Email
                            </label>
                            <input type="email"
                                class="form-control mt-2"
                                id="email" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone">
                                Phone Number
                            </label>
                            <input type="phone"
                                class="form-control mt-2"
                                id="phone" required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">
                                Password
                            </label>
                            <input type="password"
                                class="form-control mt-2"
                                id="password"
                                required />
                        </div>
                        <div class="form-group mb-3">
                            <label for="confirm_password">
                                Confirm Password
                            </label>
                            <input type="confirm_password"
                                class="form-control mt-2"
                                id="confirm_password"
                                required />
                        </div>
                        <button class="btn btn-warning">
                            Sign Up
                        </button>
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