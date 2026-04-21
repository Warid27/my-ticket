<!DOCTYPE html>
<html>
<head>
    <title>Edit Ticket - MyTicket</title>
</head>
<body>
    <h1>Edit Ticket</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=ticket&action=index">Back to Tickets</a></p>
    
    <form method="POST" action="index.php?page=ticket&action=update">
        <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
        <label>Event<br>
            <select name="event_id" required>
                <option value="">Select Event</option>
                <?php foreach ($events as $e): ?>
                    <option value="<?= $e['id'] ?>" <?= $e['id'] == $ticket['event_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($e['name']) ?> (<?= $e['venue_name'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br><br>
        <label>Ticket Name<br><input type="text" name="name" value="<?= htmlspecialchars($ticket['name']) ?>" required></label><br><br>
        <label>Price (Rp)<br><input type="number" name="price" value="<?= $ticket['price'] ?>" required></label><br><br>
        <label>Quota<br><input type="number" name="quota" value="<?= $ticket['quota'] ?>" required></label><br><br>
        <button type="submit">Update</button>
        <a href="index.php?page=ticket&action=index">Cancel</a>
    </form>
</body>
</html>
