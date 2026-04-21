<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($event['name']) ?> - MyTicket</title>
</head>
<body>
    <h1><?= htmlspecialchars($event['name']) ?></h1>
    <p><a href="index.php?page=dashboard&action=customer">Dashboard</a> | <a href="index.php?page=event&action=index">Back to Events</a></p>
    
    <h2>Event Details</h2>
    <p>Date: <?= $event['date'] ?></p>
    <p>Venue: <?= htmlspecialchars($event['venue_name']) ?></p>
    <?php if ($event['image']): ?>
        <p>Image: <img src="uploads/<?= $event['image'] ?>" alt="<?= htmlspecialchars($event['name']) ?>" width="300"></p>
    <?php endif; ?>
    
    <h2>Available Tickets</h2>
    <table border="1">
        <tr>
            <th>Type</th><th>Price</th><th>Available</th><th>Action</th>
        </tr>
        <?php foreach ($tickets as $t): ?>
        <tr>
            <td><?= htmlspecialchars($t['name']) ?></td>
            <td>Rp <?= number_format($t['price']) ?></td>
            <td><?= $t['quota'] ?></td>
            <td>
                <?php if ($t['quota'] > 0): ?>
                    <a href="index.php?page=order&action=create&ticket_id=<?= $t['id'] ?>">Buy</a>
                <?php else: ?>
                    Sold Out
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
