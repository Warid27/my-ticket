<?php $this->extend('mazer-auth'); ?>

<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="index.php"><img style="height: 10rem; margin-bottom: -8rem; margin-left: -2rem" src="<?= $this->asset('compiled/svg/logo.svg') ?>" alt="Logo"></a>
            </div>
            <h1 class="auth-title">500</h1>
            <p class="auth-subtitle mb-5">Server Error</p>

            <div class="text-center mb-4">
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 4rem; color: #dc3545;"></i>
            </div>

            <p class="text-gray-600 mb-5 text-center">
                Something went wrong on our end. Our team has been notified and we're working to fix it.
            </p>

            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger mb-4">
                    <small><?= htmlspecialchars($errorMessage) ?></small>
                </div>
            <?php endif; ?>

            <div class="text-center">
                <a href="index.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-house-door"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right"></div>
    </div>
</div>
