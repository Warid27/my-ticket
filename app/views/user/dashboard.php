<div class="page-heading">
    <h3>User Dashboard</h3>
    <p class="text-subtitle text-muted">Your activity overview</p>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Recent Orders</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentOrders)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td>#<?= $order['id'] ?></td>
                                            <td><?= $order['date'] ?></td>
                                            <td>Rp <?= number_format($order['total']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $order['status'] === 'paid' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'secondary') ?>">
                                                    <?= ucfirst($order['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No recent orders.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Upcoming Events</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($upcomingEvents)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Venue</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($upcomingEvents as $event): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($event['name']) ?></td>
                                            <td><?= htmlspecialchars($event['venue_name']) ?></td>
                                            <td><?= $event['date'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No upcoming events.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>