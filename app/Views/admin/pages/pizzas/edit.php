<h1 class="mt-4"><?= $title ?? '' ?></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="<?= route_to('admin.index') ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= route_to('admin.categories.index') ?>">Categories</a></li>
    <li class="breadcrumb-item active">Edit Pizza</li>
</ol>

<div class="card mb-4">
    <div class="card-body">

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-edit me-1"></i>
                <?= $title ?? '' ?>
            </div>
            <div class="card-body">
                <form action="<?= route_to('admin.pizzas.update', $pizza['id']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-control <?= session('validation') && session('validation')->hasError('category_id') ? 'is-invalid' : '' ?>"
                                    id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" <?= old('category_id', $pizza['category_id']) == $category['id'] ? 'selected' : '' ?>>
                                            <?= esc($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (session('validation') && session('validation')->hasError('category_id')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('validation')->getError('category_id') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Pizza Name</label>
                                <input type="text" class="form-control <?= session('validation') && session('validation')->hasError('name') ? 'is-invalid' : '' ?>"
                                    id="name" name="name" value="<?= old('name', $pizza['name']) ?>" required>
                                <?php if (session('validation') && session('validation')->hasError('name')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('validation')->getError('name') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">&#8369;</span>
                                    <input type="number" step="0.01" class="form-control <?= session('validation') && session('validation')->hasError('price') ? 'is-invalid' : '' ?>"
                                        id="price" name="price" value="<?= old('price', $pizza['price']) ?>" required>
                                    <?php if (session('validation') && session('validation')->hasError('price')): ?>
                                        <div class="invalid-feedback">
                                            <?= session('validation')->getError('price') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= old('description', $pizza['description']) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Pizza Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                <small class="text-muted">Recommended size: 500x500 pixels</small>
                                
                                <div class="d-flex justify-content-start align-items-center">
                                    <?php if (!empty($pizza['image'])): ?>
                                        <div class="mt-2">
                                            <p class="mb-1">Current Image:</p>
                                            <img src="<?= base_url($pizza['image']) ?>" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                        </div>
                                    <?php endif; ?>
    
                                    <div class="mt-2" id="imagePreviewContainer" style="display: none;">
                                        <p class="mb-1">New Image Preview:</p>
                                        <img id="imagePreview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                        <button type="button" class="btn btn-sm btn-danger ms-2" onclick="clearImagePreview()">
                                            <i class="fas fa-times"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" <?= old('is_available', $pizza['is_available']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_available">Available for order</label>
                            </div>
                        </div>
                    </div>

                    <a href="<?= route_to('admin.pizzas.index') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-warning" onclick="enableImageInput()">Update Pizza</button>
                </form>
            </div>
        </div>
    </div>