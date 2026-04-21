<?php
require_once 'app/core/BaseController.php';
class UserController extends BaseController
{
    private UserModel $model;
    private string $indexPage = 'index.php?page=user&action=index';

    public function __construct()
    {
        $this->guard($this->adminRoles);
        require_once 'app/models/UserModel.php';
        $this->model = new UserModel();
    }

    public function index(): void
    {
        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['p'] ?? 1);
        $pagination = $this->model->paginate($search, $page);
        require 'app/views/admin/user/index.php';
    }

    public function create(): void
    {
        require 'app/views/admin/user/create.php';
    }

    public function edit(): void
    {
        require 'app/views/admin/user/edit.php';
    }

    public function store(): void
    {
        $existingUser = $this->model->findByEmail($_POST['email']);
        if ($existingUser) {
            $_SESSION['error'] = "Email sudah ada!";
            header("Location: $this->indexPage");
            exit;
        }

        $this->model->insert([
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role' => $_POST['role'] ?? 'user'
        ]);

        $_SESSION['success'] = "User berhasil dibuat!";
        header("Location: $this->indexPage");
        exit;
    }

    public function update(): void
    {
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'role' => $_POST['role'] ?? 'user'
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        $this->model->update((int) $_POST['id'], $data);
        $_SESSION['success'] = 'User berhasil diupdate';
        header("Location: $this->indexPage");
    }

    public function destroy(): void
    {
        if ((int) $_GET['id'] === $_SESSION['user_id']) {
            $_SESSION['error'] = "Tidak bisa menghapus akun sendiri!";
            header("Location: $this->indexPage");
            exit;
        }

        $this->model->delete((int) $_GET['id']);
        $_SESSION['success'] = "Pengguna berhasil dihapus!";
        header("Location: $this->indexPage");
        exit;
    }

}
