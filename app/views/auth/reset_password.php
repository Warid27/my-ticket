<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="index.php"><img style="height: 10rem; margin-bottom: -8rem; margin-left: -2rem" src="<?= $this->asset('compiled/svg/logo.svg') ?>" alt="Logo"></a>
            </div>
            <h1 class="auth-title">Reset Password.</h1>
            <p class="auth-subtitle mb-5">Enter your new password below.</p>

            <form action="index.php?page=auth&action=updatePassword" method="post">
                <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
                
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" name="password" placeholder="New Password" required minlength="8">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" name="password_confirmation" placeholder="Confirm New Password" required minlength="8">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Reset Password</button>
            </form>
            <div class="text-center mt-5 text-lg fs-4">
                <p class="text-gray-600">Remember your password? <a href="index.php?page=auth&action=login" class="font-bold">Back to login</a>.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right">

        </div>
    </div>
</div>
