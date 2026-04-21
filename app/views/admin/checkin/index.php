<!DOCTYPE html>
<html>
<head>
    <title>Check-in - MyTicket</title>
</head>
<body>
    <?php if (!empty($_SESSION['error'])): ?>
        <p><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    
    <?php if (!empty($_SESSION['success'])): ?>
        <p><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <h1>Check-in</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a></p>
    
    <form method="POST" action="index.php?page=attendee&action=checkin">
        <label>Ticket Code<br><input type="text" name="ticket_code" required autofocus></label><br><br>
        <button type="submit">Check-in</button>
    </form>
</body>
</html>
