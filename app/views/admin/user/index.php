<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Users</h3>
                <p class="text-subtitle text-muted">Manage system users</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard&action=admin">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">User List</h4>
                <div class="d-flex gap-2">
                    <a href="index.php?page=user&action=create" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add User
                    </a>
                    <button onclick="exportToPDF('usersTable', 'users.pdf')" class="btn btn-secondary">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </button>
                    <button onclick="exportToExcel('usersTable', 'users.xlsx')" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php require_once 'app/views/partials/search-pagination.php'; ?>

                <div class="table-responsive">
                    <table class="table table-striped" id="usersTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pagination['data'] as $index => $u): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($u['name']) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $u['role'] === 'admin' ? 'danger' : ($u['role'] === 'petugas' ? 'warning' : 'info') ?>">
                                            <?= ucfirst($u['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($_SESSION['role'] === 'admin'): ?>
                                            <a href="index.php?page=user&action=edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                                <a href="index.php?page=user&action=destroy&id=<?= $u['id'] ?>"
                                                    onclick="return confirm('Delete this user?')" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once 'app/views/partials/export-scripts.php'; ?>