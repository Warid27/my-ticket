<!DOCTYPE html>
<html>
<head>
    <title>Add Venue - MyTicket</title>
</head>
<body>
    <h1>Add Venue</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=venue&action=index">Back to Venues</a></p>
    
    <form method="POST" action="index.php?page=venue&action=store">
        <label>Name<br><input type="text" name="name" required></label><br><br>
        <label>Address<br><textarea name="address" rows="3"></textarea></label><br><br>
        <label>Capacity<br><input type="number" name="capacity" required></label><br><br>
        <button type="submit">Save</button>
        <a href="index.php?page=venue&action=index">Cancel</a>
    </form>
</body>
</html>
