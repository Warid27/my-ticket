<?php
require_once 'app/core/BaseController.php';
class VoucherController extends BaseController
{
    private VoucherModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->guard($this->adminRoles);
        require_once 'app/models/VoucherModel.php';
        $this->model = new VoucherModel();
    }

    public function index(): void
    {
        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['p'] ?? 1);
        $pagination = $this->model->paginate($search, $page, 10, 'code');
        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('vouchers'));
        $this->layout->render('admin/voucher/index', [
            'title' => 'Vouchers - MyTicket',
            'pagination' => $pagination,
            'activeMenu' => 'vouchers'
        ]);
    }
    public function create(): void
    {
        $error = $_SESSION['error'] ?? null;

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('vouchers'));
        $this->layout->render('admin/voucher/create', [
            'title' => 'Add Voucher - MyTicket',
            'activeMenu' => 'vouchers',
            'error' => $error
        ]);
    }
    public function store(): void
    {
        $code = $_POST['code'];
        $discount = (int) $_POST['discount'];
        $quota = (int) $_POST['quota'];
        $type = $_POST['type'];
        $status = $_POST['status'];

        // Validasi dasar
        if ($type === 'percentage' && $discount > 100) {
            $_SESSION['error'] = "Diskon persentase tidak boleh lebih dari 100%!";
            header('Location: index.php?page=voucher&action=create');
            exit;
        }

        if ($discount < 0) {
            $_SESSION['error'] = "Diskon tidak boleh negatif!";
            header('Location: index.php?page=voucher&action=create');
            exit;
        }

        $this->model->insert([
            'code' => $code,
            'discount' => $discount,
            'quota' => $quota,
            'type' => $type,
            'status' => $status
        ]);

        header('Location: index.php?page=voucher&action=index');
        exit;
    }

    public function edit(): void
    {
        $voucher = $this->model->find((int) $_GET['id']);

        if (!$voucher) {
            $_SESSION['error'] = "Voucher not found";
            header('Location: index.php?page=voucher&action=index');
            exit;
        }

        $error = $_SESSION['error'] ?? null;
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['error'], $_SESSION['old']);

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('vouchers'));
        $this->layout->render('admin/voucher/edit', [
            'title' => 'Edit Voucher - MyTicket',
            'voucher' => $voucher,
            'error' => $error,
            'old' => $old,
            'activeMenu' => 'vouchers'
        ]);
    }

    public function update(): void
    {
        session_start();

        $id = (int) $_POST['id'];
        $code = $_POST['code'];
        $discount = (int) $_POST['discount'];
        $quota = (int) $_POST['quota'];
        $type = $_POST['type'];
        $status = $_POST['status'];

        // simpan old input
        $_SESSION['old'] = $_POST;

        // validasi
        if ($type === 'percentage' && $discount > 100) {
            $_SESSION['error'] = "Max percentage is 100%";
            header("Location: index.php?page=voucher&action=edit&id=$id");
            exit;
        }

        if ($discount < 0) {
            $_SESSION['error'] = "Discount cannot be negative";
            header("Location: index.php?page=voucher&action=edit&id=$id");
            exit;
        }

        if ($quota < 0) {
            $_SESSION['error'] = "Quota cannot be negative";
            header("Location: index.php?page=voucher&action=edit&id=$id");
            exit;
        }

        $this->model->update($id, [
            'code' => $code,
            'discount' => $discount,
            'quota' => $quota,
            'type' => $type,
            'status' => $status
        ]);

        unset($_SESSION['old']);

        header('Location: index.php?page=voucher&action=index');
        exit;
    }

    public function destroy(): void
    {
        $this->model->delete((int) $_GET['id']);
        header('Location: index.php?page=voucher&action=index');
        exit;
    }
}
