<?php
session_start();
require_once 'config.php';
require_once 'db/db.php';
require_once 'app/core/Layout.php';
require_once 'app/core/ErrorHandler.php';

// Register global error handlers
ErrorHandler::register();

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
    'webhook'   => 'WebhookController',
];

// Helper function to render 404
function render404(): void {
    $handler = new ErrorHandler();
    $handler->render404();
}

// 1. Guard Clause: Cek apakah halaman ada di map
if (!isset($map[$page])) {
    render404();
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
    render404();
    exit;
}

require_once $filePath;
$controller = new $controllerName();

// 4. Eksekusi Action with error handling
try {
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        render404();
    }
} catch (PDOException $e) {
    $handler = new ErrorHandler();
    $handler->handleDatabaseErrorWithContext($e, $page, $action === 'destroy' || $action === 'update' ? 'index' : $action);
} catch (Exception $e) {
    $handler = new ErrorHandler();
    $handler->render500($e->getMessage());
}