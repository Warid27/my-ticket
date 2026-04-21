<!DOCTYPE html>
<html>
<head>
    <title>Tickets - MyTicket</title>
</head>
<body>
    <?php if (!empty($_SESSION['error'])): ?>
        <p><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    
    <?php if (!empty($_SESSION['success'])): ?>
        <p><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <h1>Tickets</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=ticket&action=create">Add Ticket</a> | 
       <button onclick="exportToPDF('ticketsTable', 'tickets.pdf')">Export PDF</button> | 
       <button onclick="exportToExcel('ticketsTable', 'tickets.xlsx')">Export Excel</button></p>
    
    <?php require_once 'app/views/partials/search-pagination.php'; ?>

    <table border="1" id="ticketsTable">
        <tr>
            <th>ID</th><th>Name</th><th>Event</th><th>Price</th><th>Quota</th><th>Action</th>
        </tr>
        <?php foreach ($pagination['data'] as $t): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td><?= htmlspecialchars($t['name']) ?></td>
            <td><?= htmlspecialchars($t['event_name']) ?></td>
            <td>Rp <?= number_format($t['price']) ?></td>
            <td><?= $t['quota'] ?></td>
            <td>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="index.php?page=ticket&action=edit&id=<?= $t['id'] ?>">Edit</a>
                    <a href="index.php?page=ticket&action=destroy&id=<?= $t['id'] ?>"
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
