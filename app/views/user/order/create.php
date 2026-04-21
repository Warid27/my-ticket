<!DOCTYPE html>
<html>
<head>
    <title>Buy Ticket - MyTicket</title>
</head>
<body>
    <h1>Buy Ticket</h1>
    <p><a href="index.php?page=dashboard&action=customer">Dashboard</a> | <a href="index.php?page=event&action=show&id=<?= $ticket['event_id'] ?>">Back to Event</a></p>
    
    <?php if (!empty($_SESSION['error'])): ?>
        <p><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    
    <h2>Ticket Information</h2>
    <p>Event: <?= htmlspecialchars($ticket['event_name']) ?></p>
    <p>Ticket Type: <?= htmlspecialchars($ticket['name']) ?></p>
    <p>Price: Rp <?= number_format($ticket['price']) ?></p>
    <p>Available: <?= $ticket['quota'] ?></p>
    
    <form method="POST" action="index.php?page=order&action=store">
        <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
        <label>Quantity<br><input type="number" name="qty" min="1" max="<?= $ticket['quota'] ?>" required></label><br><br>
        <label>Voucher Code (Optional)<br><input type="text" name="voucher_code"></label><br><br>
        <button type="submit">Buy Now</button>
        <a href="index.php?page=event&action=show&id=<?= $ticket['event_id'] ?>">Cancel</a>
    </form>
</body>
</html>
