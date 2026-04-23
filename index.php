<?php
session_start();
require_once 'db/db.php';

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';
$role = $_SESSION['role'] ?? null;

$map = [
    'home'      => 'HomeController',
    'auth'      => 'AuthController',
    'user'      => 'UserController',
    'venue'     => 'VenueController',
    'event'     => 'EventController',
    'ticket'    => 'TicketController',
    'voucher'   => 'VoucherController',
    'order'     => 'OrderController',
    'attendee'  => 'AttendeeController',
    'dashboard' => 'DashboardController',
    'notification' => 'NotificationController',
];

// 1. Guard Clause: Cek apakah halaman ada di map
if (!isset($map[$page])) {
    require_once 'app/views/404.php';
    exit;
}

$controllerName = $map[$page];

// 2. Logic Role User (Override controller ke versi User)
$userModules = ['event', 'ticket', 'order'];
if ($role === 'user' && in_array($page, $userModules)) {
    $controllerName = str_replace('Controller', 'UserController', $controllerName);
}

// 3. Load File & Inisialisasi
$filePath = "app/controllers/{$controllerName}.php";

if (!file_exists($filePath)) {
    require_once 'app/views/404.php';
    exit;
}

require_once $filePath;
$controller = new $controllerName();

// 4. Eksekusi Action
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    require_once 'app/views/404.php';
}