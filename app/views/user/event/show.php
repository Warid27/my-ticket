<div class="page-heading">
    <h3><?= htmlspecialchars($event['name']) ?></h3>
    <p class="text-subtitle text-muted">Event details and tickets</p>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Event Details</h4>
                </div>
                <div class="card-body">
                    <p><strong>Date:</strong> <?= $event['date'] ?></p>
                    <p><strong>Venue:</strong> <?= htmlspecialchars($event['venue_name']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Available Tickets</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($tickets)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Available</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tickets as $t): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($t['name']) ?></td>
                                            <td>Rp <?= number_format($t['price']) ?></td>
                                            <td><?= $t['quota'] ?></td>
                                            <td>
                                                <?php if ($event['is_expired']): ?>
                                                    <span class="badge bg-danger">Kadaluwarsa</span>
                                                <?php elseif ($t['quota'] > 0): ?>
                                                    <a href="index.php?page=order&action=create&ticket_id=<?= $t['id'] ?>&event_id=<?= $event['id'] ?>"
                                                        class="btn btn-primary btn-sm">
                                                        Beli
                                                    </a>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Habis</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No tickets available for this event.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>