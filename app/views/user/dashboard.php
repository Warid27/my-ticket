<div class="page-heading">
    <h3>User Dashboard</h3>
    <p class="text-subtitle text-muted">Your activity overview</p>
</div>

<div class="page-content">
    <!-- Debug: Always show notifications section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">
                        Notifications 
                            <?php if ($unreadCount > 0): ?>
                                <span class="badge bg-danger"><?= $unreadCount ?></span>
                            <?php endif; ?>
                        </h4>
                        <?php if ($unreadCount > 0): ?>
                            <a href="index.php?page=notification&action=markAllRead" class="btn btn-sm btn-outline-secondary">Mark all as read</a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php foreach ($notifications as $notification): ?>
                            <div class="alert <?= $notification['is_read'] ? 'alert-light' : 'alert-info' ?> alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading">
                                    <?= htmlspecialchars($notification['title']) ?>
                                    <small class="text-muted"><?= date('M j, Y H:i', strtotime($notification['created_at'])) ?></small>
                                </h6>
                                <p class="mb-0"><?= htmlspecialchars($notification['message']) ?></p>
                                <?php if (!$notification['is_read']): ?>
                                    <a href="index.php?page=notification&action=markRead&id=<?= $notification['id'] ?>" class="btn btn-sm btn-outline-info mt-2">Mark as read</a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    
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
                                                <span class="badge bg-<?= $order['status'] === 'paid' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'danger') ?>">
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