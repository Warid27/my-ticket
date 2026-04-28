<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Orders</h3>
                <p class="text-subtitle text-muted">Manage customer orders</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard&action=admin">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
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
                <h4 class="card-title">Order List</h4>
                <div class="d-flex gap-2">
                    <button onclick="exportToPDF('ordersTable', 'orders.pdf')" class="btn btn-secondary">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </button>
                    <button onclick="exportToExcel('ordersTable', 'orders.xlsx')" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php require_once 'app/views/partials/search-pagination.php'; ?>

                <div class="table-responsive">
                    <table class="table table-striped" id="ordersTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Voucher</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pagination['data'] as $index => $o): ?>
                            <tr>
                                <td><?= $o['id'] ?></td>
                                <td><?= htmlspecialchars($o['customer_name']) ?></td>
                                <td><?= $o['date'] ?></td>
                                <td>Rp <?= number_format($o['total']) ?></td>
                                <td>
                                    <?php
                                    $statusClass = 'bg-secondary';
                                    if ($o['status'] === 'paid') $statusClass = 'bg-success';
                                    elseif ($o['status'] === 'pending') $statusClass = 'bg-warning';
                                    elseif ($o['status'] === 'cancel') $statusClass = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= ucfirst($o['status']) ?></span>
                                </td>
                                <td><?= $o['voucher_code'] ?? '-' ?></td>
                                <td>
                                    <a href="index.php?page=order&action=show&id=<?= $o['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
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
