<!DOCTYPE html>
<html>
<head>
    <title>Venues - MyTicket</title>
</head>
<body>
    <?php if (!empty($_SESSION['error'])): ?>
        <p><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    
    <?php if (!empty($_SESSION['success'])): ?>
        <p><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <h1>Venues</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=venue&action=create">Add Venue</a> | 
       <button onclick="exportToPDF('venuesTable', 'venues.pdf')">Export PDF</button> | 
       <button onclick="exportToExcel('venuesTable', 'venues.xlsx')">Export Excel</button></p>
    
    <?php require_once 'app/views/partials/search-pagination.php'; ?>

    <table border="1" id="venuesTable">
        <tr>
            <th>ID</th><th>Name</th><th>Address</th><th>Capacity</th><th>Action</th>
        </tr>
        <?php foreach ($pagination['data'] as $v): ?>
        <tr>
            <td><?= $v['id'] ?></td>
            <td><?= htmlspecialchars($v['name']) ?></td>
            <td><?= htmlspecialchars($v['address']) ?></td>
            <td><?= $v['capacity'] ?></td>
            <td>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="index.php?page=venue&action=edit&id=<?= $v['id'] ?>">Edit</a>
                    <a href="index.php?page=venue&action=destroy&id=<?= $v['id'] ?>"
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
