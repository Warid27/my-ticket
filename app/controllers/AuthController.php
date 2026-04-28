<?php
require_once __DIR__ . '/../../config.php';
require_once 'app/core/BaseController.php';

class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login(): void
    {
        $this->layout->extend('mazer-auth');
        $this->layout->render('auth/login', [
            'title' => 'Login - MyTicket'
        ]);
    }

    public function register(): void
    {
        $this->layout->extend('mazer-auth');
        $this->layout->render('auth/register', [
            'title' => 'Register - MyTicket'
        ]);
    }

    public function store(): void
    {
        require_once 'app/models/UserModel.php';
        $model = new UserModel();

        $existinguser = $model->findByEmail($_POST['email']);

        if ($existinguser) {
            $_SESSION['error'] = 'Email sudah ada';
            header("Location: index.php?page=auth&action=register");
            exit;
        }

        $model->insert([
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role' => 'user'
        ]);

        if ($model) {
            $this->authenticate();
        }
    }

    public function authenticate(): void
    {
        require_once 'app/models/UserModel.php';
        $model = new UserModel;

        $user = $model->findByEmail($_POST['email']);

        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($this->hasRole($user['role'])) {
                $role = $user['role'];
                $_SESSION['success'] = 'Berhasil masuk!';
                header("Location: index.php?page=dashboard&action=$role");
                exit;
            }
            $_SESSION['error'] = 'Role tidak valid!';
            header('Location: index.php?page=auth&action=login');
            exit;
        }

        $_SESSION['error'] = 'Email atau Password salah!';
        header('Location: index.php?page=auth&action=login');
        exit;
    }

    public function logout(): void
    {
        session_destroy();
        $_SESSION['success'] = "Berhasil keluar!";
        header("Location: index.php?page=auth&action=login");
        exit;
    }

    public function forgotPassword(): void
    {
        $this->layout->extend('mazer-auth');
        $this->layout->render('auth/forgot_password', [
            'title' => 'Forgot Password - MyTicket'
        ]);
    }

    public function sendPasswordReset(): void
    {
        try {
            require_once 'app/models/UserModel.php';
            require_once 'app/services/EmailService.php';
            
            $model = new UserModel();
            $emailService = new EmailService();

            $email = $_POST['email'] ?? '';

            if (empty($email)) {
                $_SESSION['error'] = 'Email address is required.';
                header("Location: index.php?page=auth&action=forgotPassword");
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Please enter a valid email address.';
                header("Location: index.php?page=auth&action=forgotPassword");
                exit;
            }

            $user = $model->findByEmail($email);

            if (!$user) {
                $_SESSION['error'] = 'If an account with that email exists, a password reset link has been sent.';
                header("Location: index.php?page=auth&action=forgotPassword");
                exit;
            }

            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime(PASSWORD_RESET_EXPIRY));
            $resetLink = APP_URL . "index.php?page=auth&action=resetPassword&token=" . urlencode($token);

            // Set reset token first
            if (!$model->setPasswordResetToken($user['id'], $token, $expires)) {
                $_SESSION['error'] = 'Failed to generate reset token. Please try again.';
                header("Location: index.php?page=auth&action=forgotPassword");
                exit;
            }

            // Send email
            $emailService->sendPasswordResetEmail($user['email'], $resetLink);
            $_SESSION['success'] = 'Password reset link has been sent to your email.';
        } catch (Exception $e) {
            error_log("Password reset error: " . $e->getMessage());
            $_SESSION['error'] = 'An error occurred while sending the password reset email. Please try again later.';
        }

        header("Location: index.php?page=auth&action=forgotPassword");
        exit;
    }

    public function resetPassword(): void
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            $_SESSION['error'] = 'Invalid reset token.';
            header("Location: index.php?page=auth&action=login");
            exit;
        }

        $this->layout->extend('mazer-auth');
        $this->layout->render('auth/reset_password', [
            'title' => 'Reset Password - MyTicket',
            'token' => $token
        ]);
    }

    public function updatePassword(): void
    {
        try {
            require_once 'app/models/UserModel.php';
            $model = new UserModel();

            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $passwordConfirmation = $_POST['password_confirmation'] ?? '';

            if (empty($token) || empty($password) || empty($passwordConfirmation)) {
                $_SESSION['error'] = 'All fields are required.';
                header("Location: index.php?page=auth&action=resetPassword&token=" . urlencode($token));
                exit;
            }

            if ($password !== $passwordConfirmation) {
                $_SESSION['error'] = 'Passwords do not match.';
                header("Location: index.php?page=auth&action=resetPassword&token=" . urlencode($token));
                exit;
            }

            if (strlen($password) < PASSWORD_MIN_LENGTH) {
                $_SESSION['error'] = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters long.';
                header("Location: index.php?page=auth&action=resetPassword&token=" . urlencode($token));
                exit;
            }

            $user = $model->findByPasswordResetToken($token);

            if (!$user) {
                $_SESSION['error'] = 'Invalid or expired reset token.';
                header("Location: index.php?page=auth&action=login");
                exit;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Update password directly without transaction
            if ($model->update($user['id'], ['password' => $hashedPassword])) {
                // Clear reset token after successful password update
                $model->clearPasswordResetToken($user['id']);

                $_SESSION['success'] = 'Password has been reset successfully. Please login with your new password.';
                header("Location: index.php?page=auth&action=login");
                exit;
            } else {
                throw new Exception('Failed to update password');
            }
        } catch (Exception $e) {
            error_log("Password update error: " . $e->getMessage());
            $_SESSION['error'] = 'Failed to reset password. Please try again.';
            header("Location: index.php?page=auth&action=resetPassword&token=" . urlencode($token));
            exit;
        }
    }
}
