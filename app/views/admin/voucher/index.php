<!DOCTYPE html>
<html>
<head>
    <title>Vouchers - MyTicket</title>
</head>
<body>
    <?php if (!empty($_SESSION['error'])): ?>
        <p><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    
    <?php if (!empty($_SESSION['success'])): ?>
        <p><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <h1>Vouchers</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=voucher&action=create">Add Voucher</a> | 
       <button onclick="exportToPDF('vouchersTable', 'vouchers.pdf')">Export PDF</button> | 
       <button onclick="exportToExcel('vouchersTable', 'vouchers.xlsx')">Export Excel</button></p>
    
    <?php require_once 'app/views/partials/search-pagination.php'; ?>

    <table border="1" id="vouchersTable">
        <tr>
            <th>ID</th><th>Code</th><th>Discount</th><th>Quota</th><th>Status</th><th>Action</th>
        </tr>
        <?php foreach ($pagination['data'] as $v): ?>
        <tr>
            <td><?= $v['id'] ?></td>
            <td><?= htmlspecialchars($v['code']) ?></td>
            <td>Rp <?= number_format($v['discount']) ?></td>
            <td><?= $v['quota'] ?></td>
            <td><?= $v['status'] ?></td>
            <td>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="index.php?page=voucher&action=edit&id=<?= $v['id'] ?>">Edit</a>
                    <a href="index.php?page=voucher&action=destroy&id=<?= $v['id'] ?>"
                       onclick="return confirm('Delete?')">Delete</a>
                <?php else: ?>
                    <span>-</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <?php require_once 'app/views/partials/export-scripts.php'; ?>
</body>
</html>
