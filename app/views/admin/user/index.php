<!DOCTYPE html>
<html>

<head>
    <title>Users - MyTicket</title>
</head>

<body>
    <?php if (!empty($_SESSION['error'])): ?>
        <p><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <p><?= $_SESSION['success'];
        unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <h1>Users</h1>
    <p><a href="index.php?page=dashboard&action=admin">Dashboard</a> | <a href="index.php?page=user&action=create">Add
            User</a> |
        <button onclick="exportToPDF('usersTable', 'users.pdf')">Export PDF</button> |
        <button onclick="exportToExcel('usersTable', 'users.xlsx')">Export Excel</button>
    </p>

    <?php require_once 'app/views/partials/search-pagination.php'; ?>

    <table border="1" id="usersTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($pagination['data'] as $index => $u): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['role'] ?></td>
                    <td>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="index.php?page=user&action=edit&id=<?= $u['id'] ?>">Edit</a>
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <a href="index.php?page=user&action=destroy&id=<?= $u['id'] ?>"
                                    onclick="return confirm('Delete this user?')">Delete</a>
                            <?php else: ?>
                                <span>Tidak bisa dihapus!</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span>-</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php require_once 'app/views/partials/export-scripts.php'; ?>
</body>

</html>