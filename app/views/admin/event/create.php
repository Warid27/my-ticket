<!DOCTYPE html>
<html>
<head>
    <title>Add Event - MyTicket</title>
</head>
<body>
    <h1>Add Event</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=event&action=index">Back to Events</a></p>
    
    <form method="POST" action="index.php?page=event&action=store" enctype="multipart/form-data">
        <label>Name<br><input type="text" name="name" required></label><br><br>
        <label>Date<br><input type="date" name="date" required></label><br><br>
        <label>Venue<br>
            <select name="venue_id" required>
                <option value="">Select Venue</option>
                <?php foreach ($venues as $v): ?>
                    <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br><br>
        <label>Image<br><input type="file" name="image" accept="image/*"></label><br><br>
        <button type="submit">Save</button>
        <a href="index.php?page=event&action=index">Cancel</a>
    </form>
</body>
</html>
