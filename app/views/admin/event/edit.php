<!DOCTYPE html>
<html>
<head>
    <title>Edit Event - MyTicket</title>
</head>
<body>
    <h1>Edit Event</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=event&action=index">Back to Events</a></p>
    
    <form method="POST" action="index.php?page=event&action=update" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $event['id'] ?>">
        <label>Name<br><input type="text" name="name" value="<?= htmlspecialchars($event['name']) ?>" required></label><br><br>
        <label>Date<br><input type="date" name="date" value="<?= $event['date'] ?>" required></label><br><br>
        <label>Venue<br>
            <select name="venue_id" required>
                <option value="">Select Venue</option>
                <?php foreach ($venues as $v): ?>
                    <option value="<?= $v['id'] ?>" <?= $v['id'] == $event['venue_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($v['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br><br>
        <label>Image<br><input type="file" name="image" accept="image/*"></label><br>
        <?php if ($event['image']): ?>
            <p>Current image: <?= $event['image'] ?></p>
        <?php endif; ?>
        <br>
        <button type="submit">Update</button>
        <a href="index.php?page=event&action=index">Cancel</a>
    </form>
</body>
</html>
