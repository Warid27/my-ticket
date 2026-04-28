<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>My Profile</h3>
                <p class="text-subtitle text-muted">Manage your account information</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard&action=<?= $_SESSION['role'] ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="profile-icon mb-3">
                            <i class="bi bi-person-circle" style="font-size: 8rem; color: #6c63ff;"></i>
                        </div>
                        <h4 class="card-title"><?= htmlspecialchars($user['name']) ?></h4>
                        <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'petugas' ? 'warning' : 'info') ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update Profile</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?page=dashboard&action=updateProfile">
                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>
                            
                            <hr class="my-4">
                            <h5 class="mb-3">Change Password</h5>
                            <p class="text-muted mb-3">Leave blank if you don't want to change your password</p>
                            
                            <div class="form-group mb-3">
                                <label for="current_password">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter current password">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="new_password">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password" minlength="<?= PASSWORD_MIN_LENGTH ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="password_confirmation">Confirm New Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" minlength="<?= PASSWORD_MIN_LENGTH ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> Update Profile
                                </button>
                                <a href="index.php?page=dashboard&action=<?= $_SESSION['role'] ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
