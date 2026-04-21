<?php
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
}