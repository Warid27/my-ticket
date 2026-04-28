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
    <link rel="stylesheet" href="<?= $this->asset('extensions/toastify-js/src/toastify.css') ?>">
</head>

<body>
    <script src="<?= $this->asset('static/js/initTheme.js') ?>"></script>
    <div id="auth">
        <?= $content ?>
    </div>
    <script src="<?= $this->asset('static/js/components/dark.js') ?>"></script>
    <script src="<?= $this->asset('compiled/js/app.js') ?>"></script>
    <script src="<?= $this->asset('extensions/toastify-js/src/toastify.js') ?>"></script>
    <script>
        // patch prototype setelah app.js load
        if (window.sidebar && window.sidebar.__proto__) {

            window.sidebar.__proto__.isElementInViewport = function (z) {
                if (!z) return false;

                const s = z.getBoundingClientRect();
                return (
                    s.top >= 0 &&
                    s.left >= 0 &&
                    s.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    s.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            };

            window.sidebar.__proto__.forceElementVisibility = function (z) {
                if (!z) return;

                if (!this.isElementInViewport(z)) {
                    z.scrollIntoView(false);
                }
            };
        }

        // Toastify notifications for session messages
        document.addEventListener('DOMContentLoaded', function() {
            <?php 
            $errorMessage = $_SESSION['error'] ?? null;
            $successMessage = $_SESSION['success'] ?? null;
            unset($_SESSION['error'], $_SESSION['success']);
            ?>
            
            <?php if ($errorMessage): ?>
                Toastify({
                    text: <?= json_encode($errorMessage) ?>,
                    duration: 3000,
                    close: true,
                    backgroundColor: "#e74c3c",
                    gravity: "top",
                    position: "right"
                }).showToast();
            <?php endif; ?>
            
            <?php if ($successMessage): ?>
                Toastify({
                    text: <?= json_encode($successMessage) ?>,
                    duration: 3000,
                    close: true,
                    backgroundColor: "#4fbe87",
                    gravity: "top",
                    position: "right"
                }).showToast();
            <?php endif; ?>
        });
    </script>
</body>

</html>