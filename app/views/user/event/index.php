<!DOCTYPE html>
<html>
<head>
    <title>Events - MyTicket</title>
</head>
<body>
    <h1>Events</h1>
    <p><a href="index.php?page=dashboard&action=customer">Dashboard</a></p>

    <table border="1">
        <tr>
            <th>Event</th><th>Date</th><th>Venue</th><th>Action</th>
        </tr>
        <?php foreach ($events as $e): ?>
        <tr>
            <td><?= htmlspecialchars($e['name']) ?></td>
            <td><?= $e['date'] ?></td>
            <td><?= htmlspecialchars($e['venue_name']) ?></td>
            <td>
                <a href="index.php?page=event&action=show&id=<?= $e['id'] ?>">View & Buy Tickets</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
