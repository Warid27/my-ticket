<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyTicket - Login</title>
</head>

<body>
    <?php if (!empty($_SESSION['error'])) { ?>
        <p><?= $_SESSION['error']; unset($_SESSION['error'])  ?></p>
    <?php } ?>
    <?php if (!empty($_SESSION['success'])) { ?>
        <p><?= $_SESSION['success']; unset($_SESSION['success'])  ?></p>
    <?php } ?>

    <h1>Login</h1>
    <form action="index.php?page=auth&action=authenticate" method="post">
        <label for="email">Email</label>
        <br>
        <input type="email" name="email" id="email" required>
        <br>
        <br>
        <label for="password">Password</label>
        <br>
        <input type="password" name="password" id="password" required>
        <br>
        <br>
        <button type="submit">Login</button>
    </form>
</body>

</html>