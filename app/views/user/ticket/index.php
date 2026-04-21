<div class="page-heading">
    <h3>My Tickets</h3>
    <p class="text-subtitle text-muted">Your purchased tickets</p>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Ticket List</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($tickets)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Ticket Code</th>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Check-in Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tickets as $ticket): ?>
                                        <tr>
                                            <td><code><?= htmlspecialchars($ticket['ticket_code']) ?></code></td>
                                            <td>#<?= $ticket['order_id'] ?></td>
                                            <td><?= $ticket['date'] ?></td>
                                            <td>Rp <?= number_format($ticket['order_total']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $ticket['order_status'] === 'paid' ? 'success' : ($ticket['order_status'] === 'pending' ? 'warning' : 'secondary') ?>">
                                                    <?= ucfirst($ticket['order_status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $ticket['checkin_status'] === 'checked' ? 'success' : 'secondary' ?>">
                                                    <?= ucfirst($ticket['checkin_status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No tickets found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
