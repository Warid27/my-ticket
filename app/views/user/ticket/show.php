<!DOCTYPE html>
<html>
<head>
    <title>My Tickets - MyTicket</title>
</head>
<body>
    <h1>My Tickets</h1>
    <p><a href="index.php?page=dashboard&action=customer">Dashboard</a> | <a href="index.php?page=order&action=show&id=<?= $_GET['order_id'] ?>">Back to Order</a></p>
    
    <h2>Ticket Codes</h2>
    <?php foreach ($attendees as $a): ?>
        <p>
            <strong>Ticket Code:</strong> <?= $a['ticket_code'] ?><br>
            <strong>Status:</strong> <?= $a['checkin_status'] ?>
            <?php if ($a['checkin_time']): ?>
                <br><strong>Check-in Time:</strong> <?= $a['checkin_time'] ?>
            <?php endif; ?>
        </p>
        <div id="qr-<?= $a['id'] ?>"></div>
        <hr>
    <?php endforeach; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
    <?php foreach ($attendees as $a): ?>
        new QRCode(document.getElementById("qr-<?= $a['id'] ?>"), "<?= $a['ticket_code'] ?>");
    <?php endforeach; ?>
    </script>
</body>
</html>
