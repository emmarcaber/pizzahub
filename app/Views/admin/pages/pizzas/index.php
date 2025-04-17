<h1 class="mt-4"><?= $title ?? '' ?></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="<?= route_to('admin.index') ?>">Dashboard</a></li>
    <li class="breadcrumb-item">Pizzas</li>
</ol>

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-end align-items-center mb-4">
            <a href="<?= route_to('admin.categories.create') ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i>
                Create Pizza
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                <?= $title ?? '' ?> Table
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Available</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pizzas)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No pizzas found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pizzas as $pizza): ?>
                                <tr>
                                    <td><?= esc($pizza['category_name']) ?></td>
                                    <td><?= esc($pizza['name']) ?></td>
                                    <td><?= esc($pizza['description']) ?></td>
                                    <td>&#8369;<?= esc($pizza['price']) ?></td>
                                    <td><img src="<?= base_url('uploads/pizzas/' . $pizza['image']) ?>" alt="<?= esc($pizza['name']) ?>" width="50"></td>
                                    <td><?= $pizza['is_available'] ? 'Yes' : 'No' ?></td>
                                    <td class="d-flex flex-column  justify-content-center align-items-center">
                                        <a href="<?= route_to('admin.pizzas.edit', $pizza['id']) ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </a>
                                        <form action="<?= route_to('admin.pizzas.delete', $pizza['id']) ?>" method="POST" class="d-inline-flex py-1">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>