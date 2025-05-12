<div class="container my-5 py-5 w-100">
    <h1 class="mb-4">Profile</h1>

    <div class="card bg-light py-3">
        <div class="card-header bg-light border-bottom-0">
            <h4 class="fw-bold m-0">Update Profile</h4>
        </div>
        <form class="card-body" action="" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">

            <div class="form-group mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= esc($user['name']) ?>" required disabled>
            </div>

            <div class="form-group mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email"
                    class="form-control <?= session('validation') && session('validation')->hasError('email') ? 'is-invalid' : '' ?>"
                    value="<?= esc($user['email']) ?>" required>
                <?php if (session('validation') && session('validation')->hasError('email')): ?>
                    <div class="invalid-feedback">
                        <?= session('validation')->getError('email') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group mb-3">
                <label for="phone">Phone Number</label>
                <div class="input-group mt-2">
                    <span class="input-group-text">+63</span>
                    <input type="tel" class="form-control <?= session('validation') && session('validation')->hasError('phone') ? 'is-invalid' : '' ?>"
                        id="phone" name="phone" maxlength="10"
                        value="<?= old('phone', esc($user['phone'])) ?>" required />
                    <?php if (session('validation') && session('validation')->hasError('phone')): ?>
                        <div class="invalid-feedback">
                            <?= session('validation')->getError('phone') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group mb-4">
                <label for="address" class="form-label">Address</label>
                <textarea id="address" name="address"
                    class="form-control <?= session('validation') && session('validation')->hasError('address') ? 'is-invalid' : '' ?>" rows="4"><?= old('address', esc($user['address'])) ?></textarea>
                <?php if (session('validation') && session('validation')->hasError('address')): ?>
                    <div class="invalid-feedback">
                        <?= session('validation')->getError('address') ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-warning">Update Profile</button>
        </form>
    </div>

    <div class="card bg-light py-3 my-5">
        <div class="card-header bg-light border-bottom-0">
            <h4 class="fw-bold m-0">Change Password</h4>
        </div>
        <form class="card-body" action="<?= route_to('auth.changePassword') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">

            <div class="form-group mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" id="new_password" name="new_password" 
                class="form-control 
                <?= session('validation') && (session('validation')->hasError('new_password') || session('validation')->hasError('confirm_password')) ? 'is-invalid' : '' ?>" 
                required>
                <?php if (session('validation') && session('validation')->hasError('new_password')): ?>
                    <div class="invalid-feedback">
                        <?= session('validation')->getError('new_password') ?>
                    </div>
                <?php endif; ?>
                <?php if (session('validation') && session('validation')->hasError('confirm_password')): ?>
                    <div class="invalid-feedback">
                        <?= session('validation')->getError('confirm_password') ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-danger">Change Password</button>
        </form>
    </div>
</div>