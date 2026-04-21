<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - MyTicket</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?= htmlspecialchars($_SESSION['name']) ?> | <a href="index.php?page=auth&action=logout">Logout</a></p>
    
    <hr>
    
    <h2>Statistics</h2>
    <p>Total Pengguna: <?= $totalUsers ?></p>
    <p>Total Orders: <?= $totalOrders ?></p>
    <p>Total Revenue: Rp <?= number_format($totalRevenues) ?></p>
    
    <hr>
    
    <hr>
    
    <h2>Quick Actions</h2>
    <p>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="index.php?page=user&action=index">Manage Users</a> |
            <a href="index.php?page=venue&action=index">Manage Venues</a> |
            <a href="index.php?page=event&action=index">Manage Events</a> |
            <a href="index.php?page=ticket&action=index">Manage Tickets</a> |
            <a href="index.php?page=voucher&action=index">Manage Vouchers</a> |
        <?php endif; ?>
        <a href="index.php?page=event&action=index">View Events</a> |
        <a href="index.php?page=ticket&action=index">View Tickets</a> |
        <a href="index.php?page=order&action=index">View Orders</a> |
        <a href="index.php?page=attendee&action=index">Check-in</a>
    </p>
</body>
</html>
