<!DOCTYPE html>
<html>
<head>
    <title>Edit Venue - MyTicket</title>
</head>
<body>
    <h1>Edit Venue</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=venue&action=index">Back to Venues</a></p>
    
    <form method="POST" action="index.php?page=venue&action=update">
        <input type="hidden" name="id" value="<?= $venue['id'] ?>">
        <label>Name<br><input type="text" name="name" value="<?= htmlspecialchars($venue['name']) ?>" required></label><br><br>
        <label>Address<br><textarea name="address" rows="3"><?= htmlspecialchars($venue['address']) ?></textarea></label><br><br>
        <label>Capacity<br><input type="number" name="capacity" value="<?= $venue['capacity'] ?>" required></label><br><br>
        <button type="submit">Update</button>
        <a href="index.php?page=venue&action=index">Cancel</a>
    </form>
</body>
</html>
