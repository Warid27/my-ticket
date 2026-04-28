<div class="page-heading">
    <h3>Browse Events</h3>
    <p class="text-subtitle text-muted">Discover and book events</p>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Available Events</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($events)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Date</th>
                                        <th>Venue</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($events as $e): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($e['name']) ?></td>
                                            <td><?= $e['date'] ?></td>
                                            <td><?= htmlspecialchars($e['venue_name']) ?></td>
                                            <td>
                                                <?php
                                                $eventTime = strtotime($e['date']);
                                                $now = time();
                                                ?>

                                                <?php if ($eventTime < $now): ?>
                                                    <span class="badge bg-danger">Kadaluwarsa</span>
                                                <?php else: ?>
                                                    <a href="index.php?page=event&action=show&id=<?= $e['id'] ?>"
                                                        class="btn btn-primary btn-sm">
                                                        Lihat dan Beli Tiket
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No events available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>