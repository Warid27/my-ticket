<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Order Details</h3>
                <p class="text-subtitle text-muted">View order information</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard&action=admin">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php?page=order&action=index">Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Order Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Order ID:</strong> <?= $order['id'] ?></p>
                                <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                                <p><strong>Date:</strong> <?= $order['date'] ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Total:</strong> Rp <?= number_format($order['total']) ?></p>
                                <p><strong>Status:</strong>
                                    <?php
                                    $statusClass = 'bg-secondary';
                                    if ($order['status'] === 'paid') $statusClass = 'bg-success';
                                    elseif ($order['status'] === 'pending') $statusClass = 'bg-warning';
                                    elseif ($order['status'] === 'cancel') $statusClass = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= ucfirst($order['status']) ?></span>
                                </p>
                                <p><strong>Voucher:</strong> <?= $order['voucher_code'] ?? '-' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Order Items</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Ticket</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($details as $d): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($d['event_name']) ?></td>
                                        <td><?= htmlspecialchars($d['ticket_name']) ?></td>
                                        <td>Rp <?= number_format($d['price']) ?></td>
                                        <td><?= $d['qty'] ?></td>
                                        <td>Rp <?= number_format($d['subtotal']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
