<h1 class="mt-4"><?= $title ?? '' ?></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="<?= route_to('admin.index') ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Users</li>
</ol>

<div class="card mb-4">
    <div class="card-body">
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
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No users found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= esc($user['name']) ?></td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'secondary' ?>">
                                            <?= strtoupper(esc($user['role'])) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($user['phone']) ?></td>
                                    <td class="d-flex justify-content-center align-items-center">
                                        <a href="<?= route_to('admin.users.show', esc($user['id'], 'url')) ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <?php if ($user['role'] !== 'admin'): ?>
                                        <form class="d-inline" action="<?= route_to('admin.users.delete', esc($user['id'], 'url')) ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user? This cannot be undone.');">
                                                <i class="fas fa-trash"></i>
                                                Delete
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>