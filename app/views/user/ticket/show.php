<div class="page-heading">
    <h3>My Tickets</h3>
    <p class="text-subtitle text-muted">View your ticket QR codes</p>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Ticket Codes</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($attendees)): ?>
                        <?php foreach ($attendees as $a): ?>
                            <div class="mb-4 p-3 border rounded">
                                <p><strong>Ticket Code:</strong> <code><?= $a['ticket_code'] ?></code></p>
                                <p><strong>Status:</strong> 
                                    <span class="badge bg-<?= $a['checkin_status'] === 'checked' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($a['checkin_status']) ?>
                                    </span>
                                </p>
                                <?php if ($a['checkin_time']): ?>
                                    <p><strong>Check-in Time:</strong> <?= $a['checkin_time'] ?></p>
                                <?php endif; ?>
                                <div id="qr-<?= $a['id'] ?>" class="mt-2"></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No tickets found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
<?php foreach ($attendees as $a): ?>
    new QRCode(document.getElementById("qr-<?= $a['id'] ?>"), "<?= $a['ticket_code'] ?>");
<?php endforeach; ?>
</script>
