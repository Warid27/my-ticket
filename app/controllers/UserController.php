<?php
require_once 'app/core/BaseController.php';
class UserController extends BaseController
{
    private UserModel $model;
    private string $indexPage = 'index.php?page=user&action=index';

    public function __construct()
    {
        parent::__construct();
        $this->guard($this->adminRoles);
        require_once 'app/models/UserModel.php';
        $this->model = new UserModel();
    }

    protected function getSidebarMenu(string $activeMenu = 'users'): string
    {
        return '
            <li class="sidebar-title">Menu</li>
            <li class="sidebar-item">
                <a href="index.php?page=dashboard&action=admin" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-title">Management</li>
            <li class="sidebar-item ' . ($activeMenu === 'users' ? 'aktif' : '') . '">
                <a href="index.php?page=user&action=index" class="sidebar-link">
                    <i class="bi bi-people-fill"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="index.php?page=venue&action=index" class="sidebar-link">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>Venues</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="index.php?page=event&action=index" class="sidebar-link">
                    <i class="bi bi-calendar-event-fill"></i>
                    <span>Events</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="index.php?page=ticket&action=index" class="sidebar-link">
                    <i class="bi bi-ticket-perforated-fill"></i>
                    <span>Tickets</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="index.php?page=voucher&action=index" class="sidebar-link">
                    <i class="bi bi-tag-fill"></i>
                    <span>Vouchers</span>
                </a>
            </li>
            <li class="sidebar-title">Operations</li>
            <li class="sidebar-item">
                <a href="index.php?page=order&action=index" class="sidebar-link">
                    <i class="bi bi-cart-fill"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="index.php?page=attendee&action=index" class="sidebar-link">
                    <i class="bi bi-qr-code-scan"></i>
                    <span>Check-in</span>
                </a>
            </li>';
    }

    public function index(): void
    {
        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['p'] ?? 1);
        $pagination = $this->model->paginate($search, $page);

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('users'));
        $this->layout->render('admin/user/index', [
            'title' => 'Users - MyTicket',
            'pagination' => $pagination,
            'activeMenu' => 'users'
        ]);
    }

    public function create(): void
    {
        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('users'));
        $this->layout->render('admin/user/create', [
            'title' => 'Add User - MyTicket',
            'activeMenu' => 'users'
        ]);
    }

    public function edit(): void
    {
        $id = (int) $_GET['id'];
        $user = $this->model->find($id);

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('users'));
        $this->layout->render('admin/user/edit', [
            'title' => 'Edit User - MyTicket',
            'user' => $user,
            'activeMenu' => 'users'
        ]);
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
