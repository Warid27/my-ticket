<!DOCTYPE html>
<html>
<head>
    <title>Order Details - MyTicket</title>
</head>
<body>
    <h1>Order Details</h1>
    <p><a href="index.php?page=dashboard&action=customer">Dashboard</a> | <a href="index.php?page=order&action=history">Order History</a></p>
    
    <h2>Order Information</h2>
    <p>Order ID: <?= $order['id'] ?></p>
    <p>Date: <?= $order['order_date'] ?></p>
    <p>Total: Rp <?= number_format($order['total']) ?></p>
    <p>Status: <?= $order['status'] ?></p>
    <p>Voucher: <?= $order['voucher_code'] ?? '-' ?></p>
    
    <h2>Order Items</h2>
    <table border="1">
        <tr>
            <th>Event</th><th>Ticket</th><th>Price</th><th>Quantity</th><th>Subtotal</th>
        </tr>
        <?php foreach ($details as $d): ?>
        <tr>
            <td><?= htmlspecialchars($d['event_name']) ?></td>
            <td><?= htmlspecialchars($d['ticket_name']) ?></td>
            <td>Rp <?= number_format($d['price']) ?></td>
            <td><?= $d['qty'] ?></td>
            <td>Rp <?= number_format($d['subtotal']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <h2>Tickets</h2>
    <p><a href="index.php?page=ticket&action=show&order_id=<?= $order['id'] ?>">View QR Codes</a></p>
</body>
</html>
