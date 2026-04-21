<?php
require_once 'app/core/BaseController.php';

class OrderController extends BaseController
{
    private OrderModel $model;

    public function __construct()
    {
        parent::__construct();
        require_once 'app/models/OrderModel.php';
        $this->model = new OrderModel();
        $this->guard($this->staffRoles);
    }

    public function index(): void
    {
        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['p'] ?? 1);
        $pagination = $this->model->paginate($search, $page);

        // Get customer names for orders
        $orders = $pagination['data'];
        require_once 'app/models/UserModel.php';
        $userModel = new UserModel();

        foreach ($orders as &$order) {
            $customer = $userModel->find($order['user_id']);
            $order['customer_name'] = $customer['name'] ?? 'Unknown';
        }
        $pagination['data'] = $orders;

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('orders'));
        $this->layout->render('admin/order/index', [
            'title' => 'Orders - MyTicket',
            'pagination' => $pagination,
            'activeMenu' => 'orders'
        ]);
    }

    public function show(): void
    {
        $order = $this->model->find((int) $_GET['id']);
        require_once 'app/models/OrderDetailModel.php';
        $detailModel = new OrderDetailModel();
        $details = $detailModel->byOrder((int) $_GET['id']);

        require_once 'app/models/UserModel.php';
        $userModel = new UserModel();
        $customer = $userModel->find($order['user_id']);
        $order['customer_name'] = $customer['name'];

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('orders'));
        $this->layout->render('admin/order/show', [
            'title' => 'Order Details - MyTicket',
            'order' => $order,
            'details' => $details,
            'activeMenu' => 'orders'
        ]);
    }
}
