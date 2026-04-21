<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'MyTicket') ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .alert { padding: 10px 15px; margin-bottom: 15px; border-radius: 4px; }
        .alert-error { background: #fee; color: #c33; border: 1px solid #fcc; }
        .alert-success { background: #efe; color: #3c3; border: 1px solid #cfc; }
        h1 { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background: #f5f5f5; }
        a { color: #3498db; }
        button, input[type="submit"] {
            background: #3498db; color: white; border: none;
            padding: 8px 16px; border-radius: 4px; cursor: pointer;
        }
        button:hover, input[type="submit"]:hover { background: #2980b9; }
        input[type="text"], input[type="email"], input[type="password"], select {
            padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 100%; max-width: 300px;
        }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        form { max-width: 400px; }
        .actions { margin-bottom: 20px; }
        .actions a, .actions button { margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
