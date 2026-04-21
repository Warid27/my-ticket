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
        $pagination = $this->model->paginate($search, $page);
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
        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('vouchers'));
        $this->layout->render('admin/voucher/create', [
            'title' => 'Add Voucher - MyTicket',
            'activeMenu' => 'vouchers'
        ]);
    }

    public function store(): void
    {
        $this->model->insert([
            'code' => $_POST['code'],
            'discount' => (int) $_POST['discount'],
            'quota' => (int) $_POST['quota']
        ]);
        header('Location: index.php?page=voucher&action=index');
        exit;
    }

    public function edit(): void
    {
        $voucher = $this->model->find((int) $_GET['id']);
        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('vouchers'));
        $this->layout->render('admin/voucher/edit', [
            'title' => 'Edit Voucher - MyTicket',
            'voucher' => $voucher,
            'activeMenu' => 'vouchers'
        ]);
    }

    public function update(): void
    {
        $this->model->update((int) $_POST['id'], [
            'code' => $_POST['code'],
            'discount' => (int) $_POST['discount'],
            'quota' => (int) $_POST['quota'],
            'status' => $_POST['status']
        ]);
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
