<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; display: flex; min-height: 100vh; }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
        }
        .sidebar h2 { margin-bottom: 20px; font-size: 1.2em; }
        .sidebar ul { list-style: none; }
        .sidebar li { margin-bottom: 10px; }
        .sidebar a { color: #ecf0f1; text-decoration: none; display: block; padding: 8px 12px; border-radius: 4px; }
        .sidebar a:hover { background: #34495e; }
        .main-content {
            flex: 1;
            padding: 20px;
            background: #ecf0f1;
        }
        .top-bar {
            background: white;
            padding: 15px 20px;
            margin: -20px -20px 20px -20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-info { font-size: 0.9em; color: #555; }
        .simple-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 0.8em;
            color: #7f8c8d;
            text-align: center;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <?= $sidebar ?>
    </aside>
    <div class="main-content">
        <div class="top-bar">
            <h1>MyTicket Dashboard</h1>
            <div class="user-info">
                Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'Guest') ?> |
                <a href="index.php?page=auth&action=logout">Logout</a>
            </div>
        </div>

        <?= $content ?>

        <footer class="simple-footer">
            <p>&copy; <?= date('Y') ?> MyTicket. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
