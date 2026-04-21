<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="shortcut icon" href="<?= $this->asset('compiled/svg/favicon.svg') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= $this->asset('compiled/css/app.css') ?>">
    <link rel="stylesheet" href="<?= $this->asset('compiled/css/app-dark.css') ?>">
    <link rel="stylesheet" href="<?= $this->asset('compiled/css/auth.css') ?>">
</head>
<body>
    <script src="<?= $this->asset('static/js/initTheme.js') ?>"></script>
    <div id="auth">
        <?= $content ?>
    </div>
    <script src="<?= $this->asset('static/js/components/dark.js') ?>"></script>
    <script src="<?= $this->asset('compiled/js/app.js') ?>"></script>
</body>
</html>
