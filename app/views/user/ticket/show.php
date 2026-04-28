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
                        <?php foreach ($attendees as $index => $a): ?>
                            <div class="mb-4 p-3 border rounded">
                                <p><strong>Ticket Code:</strong> <code><?= htmlspecialchars($a['ticket_code'] ?? '') ?></code></p>
                                <p><strong>Status:</strong> 
                                    <span class="badge bg-<?= ($a['checkin_status'] ?? 'belum') === 'sudah' ? 'success' : 'secondary' ?>">
                                        <?= ($a['checkin_status'] ?? 'belum') === 'sudah' ? 'Checked In' : 'Not Checked In' ?>
                                    </span>
                                </p>
                                <?php if (!empty($a['checkin_time'])): ?>
                                    <p><strong>Check-in Time:</strong> <?= htmlspecialchars($a['checkin_time']) ?></p>
                                <?php endif; ?>
                                <div id="qr-<?= $index ?>" class="mt-2"></div>
                                <button class="btn btn-sm btn-primary mt-2" onclick="downloadQR(<?= $index ?>)">
                                    <i class="bi bi-download"></i> Download QR
                                </button>
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
<?php foreach ($attendees as $index => $a): ?>
    new QRCode(document.getElementById("qr-<?= $index ?>"), "<?= htmlspecialchars($a['ticket_code'] ?? '') ?>");
<?php endforeach; ?>

    function downloadQR(index) {
        const qrDiv = document.getElementById("qr-" + index);
        const img = qrDiv.querySelector("img");
        if (img) {
            const link = document.createElement("a");
            link.href = img.src;
            link.download = "ticket-qr-" + index + ".png";
            link.click();
        }
    }
</script>
