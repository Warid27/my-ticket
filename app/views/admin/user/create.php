<!DOCTYPE html>
<html>
<head>
    <title>Add User - MyTicket</title>
</head>
<body>
    <h1>Add User</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=user&action=index">Back to Users</a></p>
    
    <form method="POST" action="index.php?page=user&action=store">
        <label>Name<br><input type="text" name="name" required></label><br><br>
        <label>Email<br><input type="email" name="email" required></label><br><br>
        <label>Password<br><input type="password" name="password" required></label><br><br>
        <label>Role<br>
            <select name="role">
                <option value="user">User</option>
                <option value="petugas">Petugas</option>
                <option value="admin">Admin</option>
            </select>
        </label><br><br>
        <button type="submit">Save</button>
        <a href="index.php?page=user&action=index">Cancel</a>
    </form>
</body>
</html>
