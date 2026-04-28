<?php $this->extend('mazer-auth'); ?>

<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="index.php"><img style="height: 10rem; margin-bottom: -8rem; margin-left: -2rem" src="<?= $this->asset('compiled/svg/logo.svg') ?>" alt="Logo"></a>
            </div>
            <h1 class="auth-title">Error</h1>
            <p class="auth-subtitle mb-5"><?= $errorTitle ?? 'An Error Occurred' ?></p>

            <div class="text-center mb-4">
                <i class="bi <?= $errorIcon ?? 'bi-exclamation-circle' ?>" style="font-size: 4rem; color: #dc3545;"></i>
            </div>

            <p class="text-gray-600 mb-5 text-center">
                <?= $errorMessage ?? 'Something went wrong. Please try again later.' ?>
            </p>

            <?php if (isset($errorDetails) && APP_ENV === 'development'): ?>
                <div class="alert alert-warning mb-4">
                    <small><strong>Debug Info:</strong><br><?= htmlspecialchars($errorDetails) ?></small>
                </div>
            <?php endif; ?>

            <div class="text-center">
                <a href="<?= $backUrl ?? 'index.php' ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-arrow-left"></i> <?= $backText ?? 'Go Back' ?>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right"></div>
    </div>
</div>
