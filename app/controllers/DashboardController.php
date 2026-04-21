<?php
require_once 'app/core/BaseController.php';
class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
    }

    public function admin()
    {
        $this->guard($this->adminRoles);
        require_once 'app/models/UserModel.php';
        require_once 'app/models/OrderModel.php';

        $activeMenu = 'dashboard';
        $userModel = new UserModel();
        $orderModel = new OrderModel();

        // Stats
        $totalUsers = $userModel->queryOne("SELECT COUNT(*) AS total FROM users WHERE role = 'user'")['total'];
        $totalOrders = $orderModel->queryOne("SELECT COUNT(*) AS total FROM orders")['total'];
        $totalRevenues = $orderModel->queryOne("SELECT COALESCE(SUM(total), 0) AS revenue FROM orders WHERE status = 'paid'")['revenue'];

        $salesData = $orderModel->query("SELECT events.name AS event_name, SUM(order_details.qty) AS total_sold FROM order_details JOIN tickets ON order_details.ticket_id = tickets.id JOIN events ON tickets.event_id = events.id JOIN orders ON order_details.order_id = orders.id WHERE orders.status = 'paid' GROUP BY events.id, events.name ORDER BY total_sold DESC LIMIT 5");

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu($activeMenu));
        $this->layout->render('admin/dashboard', [
            'title' => 'Admin Dashboard - MyTicket',
            'totalUsers' => $totalUsers,
            'totalOrders' => $totalOrders,
            'totalRevenues' => $totalRevenues,
            'salesData' => $salesData,
            'activeMenu' => 'dashboard'
        ]);
    }

    public function petugas()
    {
        $this->guard($this->petugasRoles);

        $activeMenu = 'dashboard';
        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu($activeMenu));
        $this->layout->render('petugas/dashboard', [
            'title' => 'Petugas Dashboard - MyTicket',
            'activeMenu' => 'dashboard'
        ]);
    }

    public function user()
    {
        $this->guard($this->userRoles);

        require_once 'app/models/OrderModel.php';
        require_once 'app/models/EventModel.php';

        $orderModel = new OrderModel();
        $eventModel = new EventModel();

        // Order terbaru
        $recentOrders = $orderModel->query("SELECT * FROM orders WHERE user_id = ? ORDER BY date DESC LIMIT 5", [$_SESSION['user_id']]);

        // Event yang akan datang
        $upcomingEvents = $eventModel->query("SELECT events.*, venues.name AS venue_name FROM events JOIN venues ON events.venue_id = venues.id WHERE events.date >= CURDATE() ORDER BY events.date ASC LIMIT 5");

        $activeMenu = 'dashboard';
        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu($activeMenu));
        $this->layout->render('user/dashboard', [
            'title' => 'User Dashboard - MyTicket',
            'recentOrders' => $recentOrders,
            'upcomingEvents' => $upcomingEvents,
            'activeMenu' => 'dashboard'
        ]);
    }
}
