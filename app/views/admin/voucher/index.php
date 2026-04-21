<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Vouchers</h3>
                <p class="text-subtitle text-muted">Manage discount vouchers</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard&action=admin">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vouchers</li>
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
                <h4 class="card-title">Voucher List</h4>
                <div class="d-flex gap-2">
                    <a href="index.php?page=voucher&action=create" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Voucher
                    </a>
                    <button onclick="exportToPDF('vouchersTable', 'vouchers.pdf')" class="btn btn-secondary">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </button>
                    <button onclick="exportToExcel('vouchersTable', 'vouchers.xlsx')" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php require_once 'app/views/partials/search-pagination.php'; ?>

                <div class="table-responsive">
                    <table class="table table-striped" id="vouchersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Code</th>
                                <th>Discount</th>
                                <th>Quota</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pagination['data'] as $v): ?>
                                <tr>
                                    <td><?= $v['id'] ?></td>
                                    <td><?= htmlspecialchars($v['code']) ?></td>
                                    <td> <?php if ($v['type'] === 'percentage') {
                                                echo $v['discount'] . '%';
                                            } else {
                                                echo 'Rp ' . number_format($v['discount']);
                                            } ?></td>
                                    <td><?= $v['quota'] ?></td>
                                    <td>
                                        <?php if ($v['status'] === 'aktif'): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($_SESSION['role'] === 'admin'): ?>
                                            <a href="index.php?page=voucher&action=edit&id=<?= $v['id'] ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="index.php?page=voucher&action=destroy&id=<?= $v['id'] ?>"
                                                onclick="return confirm('Delete?')" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </a>
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