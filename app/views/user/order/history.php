<!DOCTYPE html>
<html>
<head>
    <title>Order History - MyTicket</title>
</head>
<body>
    <h1>Order History</h1>
    <p><a href="index.php?page=dashboard&action=customer">Dashboard</a></p>

    <table border="1">
        <tr>
            <th>Order ID</th><th>Date</th><th>Total</th><th>Status</th><th>Voucher</th><th>Action</th>
        </tr>
        <?php foreach ($orders as $o): ?>
        <tr>
            <td><?= $o['id'] ?></td>
            <td><?= $o['order_date'] ?></td>
            <td>Rp <?= number_format($o['total']) ?></td>
            <td><?= $o['status'] ?></td>
            <td><?= $o['voucher_code'] ?? '-' ?></td>
            <td>
                <a href="index.php?page=order&action=show&id=<?= $o['id'] ?>">View</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
