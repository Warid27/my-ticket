<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Order History</h3>
                <p class="text-subtitle text-muted">View your past orders</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard&action=user">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order History</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">My Orders</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Voucher</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $o): ?>
                            <tr>
                                <td><?= $o['id'] ?></td>
                                <td><?= $o['date'] ?></td>
                                <td>Rp <?= number_format($o['total']) ?></td>
                                <td>
                                    <?php
                                    $statusClass = 'bg-secondary';
                                    if ($o['status'] === 'paid') $statusClass = 'bg-success';
                                    elseif ($o['status'] === 'pending') $statusClass = 'bg-warning';
                                    elseif ($o['status'] === 'cancelled') $statusClass = 'bg-danger';
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
