<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyTicket - Dashboard</title>
</head>

<body>
    <?php if (!empty($_SESSION['success'])) { ?>
        <p><?= $_SESSION['success'];
        unset($_SESSION['success']) ?></p>
    <?php } ?>
    Dashboard
</body>

</html>