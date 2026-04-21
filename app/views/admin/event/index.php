<!DOCTYPE html>
<html>
<head>
    <title>Events - MyTicket</title>
</head>
<body>
    <?php if (!empty($_SESSION['error'])): ?>
        <p><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    
    <?php if (!empty($_SESSION['success'])): ?>
        <p><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <h1>Events</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=event&action=create">Add Event</a> | 
       <button onclick="exportToPDF('eventsTable', 'events.pdf')">Export PDF</button> | 
       <button onclick="exportToExcel('eventsTable', 'events.xlsx')">Export Excel</button></p>
    
    <?php require_once 'app/views/partials/search-pagination.php'; ?>

    <table border="1" id="eventsTable">
        <tr>
            <th>ID</th><th>Name</th><th>Date</th><th>Venue</th><th>Image</th><th>Action</th>
        </tr>
        <?php foreach ($pagination['data'] as $e): ?>
        <tr>
            <td><?= $e['id'] ?></td>
            <td><?= htmlspecialchars($e['name']) ?></td>
            <td><?= $e['date'] ?></td>
            <td><?= htmlspecialchars($e['venue_name']) ?></td>
            <td><?= $e['image'] ? 'Yes' : 'No' ?></td>
            <td>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="index.php?page=event&action=edit&id=<?= $e['id'] ?>">Edit</a>
                    <a href="index.php?page=event&action=destroy&id=<?= $e['id'] ?>"
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
