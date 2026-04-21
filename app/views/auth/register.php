<!DOCTYPE html>
<html>
<head>
    <title>Register - MyTicket</title>
</head>
<body>
    <?php if (!empty($_SESSION['error'])): ?>
        <p><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    
    <?php if (!empty($_SESSION['success'])): ?>
        <p><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <h1>Register</h1>
    <form method="POST" action="index.php?page=auth&action=store">
        <label>Name<br><input type="text" name="name" required></label><br><br>
        <label>Email<br><input type="email" name="email" required></label><br><br>
        <label>Password<br><input type="password" name="password" required></label><br><br>
        <button type="submit">Register</button>
    </form>
    
    <p>Already have an account? <a href="index.php?page=auth&action=login">Login here</a></p>
</body>
</html>
