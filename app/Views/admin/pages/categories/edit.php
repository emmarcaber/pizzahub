<h1 class="mt-4"><?= $title ?? '' ?></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="<?= route_to('admin.index') ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= route_to('admin.categories.index') ?>">Categories</a></li>
    <li class="breadcrumb-item active">Create Category</li>
</ol>

<div class="card mb-4">
    <div class="card-body">

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-edit me-1"></i>
                <?= $title ?? '' ?>
            </div>
            <div class="card-body">
                <form action="<?= route_to('admin.categories.update', esc($category['id'], 'url')) ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">

                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control <?= session('validation') && session('validation')->hasError('name') ? 'is-invalid' : '' ?>"
                            id="name" name="name" value="<?= old('name', $category['name']) ?>" required>
                        <?php if (session('validation') && session('validation')->hasError('name')): ?>
                            <div class="invalid-feedback">
                                <?= session('validation')->getError('name') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control <?= session('validation') && session('validation')->hasError('description') ? 'is-invalid' : '' ?>"
                            id="description" name="description" rows="2" required><?= old('description', $category['description']) ?></textarea>
                        <?php if (session('validation') && session('validation')->hasError('description')): ?>
                            <div class="invalid-feedback">
                                <?= session('validation')->getError('description') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <a href="<?= route_to('admin.categories.index') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-warning">Update Category</button>
                </form>
            </div>
        </div>
    </div>