<!DOCTYPE html>
<html>
<head>
    <title>Add Ticket - MyTicket</title>
</head>
<body>
    <h1>Add Ticket</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=ticket&action=index">Back to Tickets</a></p>
    
    <form method="POST" action="index.php?page=ticket&action=store">
        <label>Event<br>
            <select name="event_id" required>
                <option value="">Select Event</option>
                <?php foreach ($events as $e): ?>
                    <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['name']) ?> (<?= $e['venue_name'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </label><br><br>
        <label>Ticket Name<br><input type="text" name="name" required></label><br><br>
        <label>Price (Rp)<br><input type="number" name="price" required></label><br><br>
        <label>Quota<br><input type="number" name="quota" required></label><br><br>
        <button type="submit">Save</button>
        <a href="index.php?page=ticket&action=index">Cancel</a>
    </form>
</body>
</html>
