<h1 class="mt-4"><?= $title ?? '' ?></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="<?= route_to('admin.index') ?>">Dashboard</a></li>
    <li class="breadcrumb-item">Categories</li>
</ol>

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-end align-items-center mb-4">
            <a href="<?= route_to('admin.categories.create') ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i>
                Create Category
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
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= esc($category['name']) ?></td>
                                <td><?= esc($category['description']) ?></td>
                                <td>
                                    <a href="<?= route_to('admin.categories.edit', esc($category['id'], 'url')) ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form class="d-inline" action="<?= route_to('admin.categories.delete', esc($category['id'], 'url')) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This cannot be undone.');">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>