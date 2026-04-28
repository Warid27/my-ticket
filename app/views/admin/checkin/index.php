<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Check-in</h3>
                <p class="text-subtitle text-muted">Scan ticket codes for event check-in</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard&action=admin">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Check-in</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/partials/checkin-modal.php'; ?>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Ticket Check-in</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-4" id="checkinTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual-pane" type="button" role="tab">
                            <i class="bi bi-keyboard"></i> Manual Input
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="camera-tab" data-bs-toggle="tab" data-bs-target="#camera-pane" type="button" role="tab">
                            <i class="bi bi-camera"></i> Camera Scan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="file-tab" data-bs-toggle="tab" data-bs-target="#file-pane" type="button" role="tab">
                            <i class="bi bi-upload"></i> File Upload
                        </button>
                    </li>
                </ul>

                <form method="POST" action="index.php?page=attendee&action=checkin" id="checkinForm">
                    <input type="hidden" id="ticket_code" name="ticket_code" value="">

                    <div class="tab-content" id="checkinTabContent">
                        <!-- Manual Input Tab -->
                        <div class="tab-pane fade show active" id="manual-pane" role="tabpanel">
                            <div class="form-group">
                                <label for="manual_ticket_code">Ticket Code</label>
                                <input type="text" class="form-control form-control-lg" id="manual_ticket_code"
                                    autofocus placeholder="Enter ticket code">
                            </div>
                            <button type="button" class="btn btn-primary btn-lg mt-3" id="manualSubmitBtn">
                                <i class="bi bi-check-lg"></i> Check-in
                            </button>
                        </div>

                        <?php require_once 'app/views/partials/checkin-camera.php'; ?>
                        <?php require_once 'app/views/partials/checkin-file-input.php'; ?>
                    </div>
                </form>

                <?php if (!empty($successCheck)): ?>
                    <div class="alert alert-success d-flex align-items-start gap-3 mt-3">

                        <i class="bi bi-check-circle-fill fs-4"></i>

                        <div>
                            <div class="fw-bold mb-1">Check-in Berhasil!</div>

                            <div class="small text-dark">
                                Kode Tiket: <span class="text-dark fw-semibold">
                                    <?= htmlspecialchars($successCheck['ticketCode'] ?? '') ?>
                                </span>
                            </div>

                            <div class="small text-dark">
                                Event: <span class="text-dark fw-semibold">
                                    <?= htmlspecialchars($successCheck['eventName'] ?? '') ?>
                                </span>
                            </div>

                            <div class="small text-dark">
                                Nama: <span class="text-dark fw-semibold">
                                    <?= htmlspecialchars($successCheck['userName'] ?? '') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endif; unset($successCheck)?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Attendee List</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="page" value="attendee">
                            <input type="hidden" name="action" value="index">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search"
                                    placeholder="Search by ticket code or status..."
                                    value="<?= htmlspecialchars($search ?? '') ?>">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="text-muted">
                            Showing <?= $attendees['total'] ?? 0 ?> attendees
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Ticket Code</th>
                                <th>Event</th>
                                <th>Ticket Type</th>
                                <th>Order Status</th>
                                <th>Order Date</th>
                                <th>Check-in Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($attendees['data'])): ?>
                                <?php foreach ($attendees['data'] as $attendee): ?>
                                    <tr>
                                        <td>
                                            <code><?= htmlspecialchars($attendee['ticket_code']) ?></code>
                                        </td>
                                        <td><?= htmlspecialchars($attendee['event_name']) ?></td>
                                        <td><?= htmlspecialchars($attendee['ticket_name']) ?></td>
                                        <td>
                                            <?php if ($attendee['order_status'] === 'paid'): ?>
                                                <span class="badge bg-success">Paid</span>
                                            <?php elseif ($attendee['order_status'] === 'pending'): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php elseif ($attendee['order_status'] === 'cancel'): ?>
                                                <span class="badge bg-danger">Cancelled</span>
                                            <?php else: ?>
                                                <span
                                                    class="badge bg-secondary"><?= htmlspecialchars($attendee['order_status']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($attendee['order_date']) ?></td>
                                        <td>
                                            <?php if ($attendee['checkin_status'] === 'sudah'): ?>
                                                <span class="badge bg-success">Checked In</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Not Checked In</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        No attendees found
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($attendees) && $attendees['lastPage'] > 1): ?>
                    <nav aria-label="Attendee pagination">
                        <ul class="pagination justify-content-center">
                            <?php if ($attendees['hasPrev']): ?>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="?page=attendee&action=index&p=<?= $attendees['currentPage'] - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $attendees['lastPage']; $i++): ?>
                                <li class="page-item <?= $i === $attendees['currentPage'] ? 'aktif' : '' ?>">
                                    <a class="page-link"
                                        href="?page=attendee&action=index&p=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($attendees['hasMore']): ?>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="?page=attendee&action=index&p=<?= $attendees['currentPage'] + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php require_once 'app/views/partials/checkin-js.php'; ?>
