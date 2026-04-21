<?php
require_once 'app/core/BaseController.php';
class VoucherController extends BaseController
{
    private VoucherModel $model;

    public function __construct()
    {
        $this->guard($this->adminRoles);
        require_once 'app/models/VoucherModel.php';
        $this->model = new VoucherModel();
    }

    public function index(): void
    {
        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['p'] ?? 1);
        $pagination = $this->model->paginate($search, $page);
        require 'app/views/admin/voucher/index.php';
    }

    public function create(): void
    {
        require 'app/views/admin/voucher/create.php';
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
        require 'app/views/admin/voucher/edit.php';
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
