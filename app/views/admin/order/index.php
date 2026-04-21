<!DOCTYPE html>
<html>
<head>
    <title>Orders - MyTicket</title>
</head>
<body>
    <?php if (!empty($_SESSION['error'])): ?>
        <p><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    
    <?php if (!empty($_SESSION['success'])): ?>
        <p><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <h1>Orders</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | 
       <button onclick="exportToPDF('ordersTable', 'orders.pdf')">Export PDF</button> | 
       <button onclick="exportToExcel('ordersTable', 'orders.xlsx')">Export Excel</button></p>
    
    <?php require_once 'app/views/partials/search-pagination.php'; ?>

    <table border="1" id="ordersTable">
        <tr>
            <th>ID</th><th>Customer</th><th>Date</th><th>Total</th><th>Status</th><th>Voucher</th><th>Action</th>
        </tr>
        <?php foreach ($pagination['data'] as $o): ?>
        <tr>
            <td><?= $o['id'] ?></td>
            <td><?= htmlspecialchars($o['customer_name']) ?></td>
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
    
    <?php require_once 'app/views/partials/export-scripts.php'; ?>
</body>
</html>
