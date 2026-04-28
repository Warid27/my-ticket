<?php
require_once 'app/core/BaseController.php';
class AttendeeController extends BaseController
{
    private AttendeeModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->guard($this->staffRoles);
        require_once 'app/models/AttendeeModel.php';
        $this->model = new AttendeeModel();
    }

    public function index(): void
    {
        $successCheck = $_SESSION['success-check'] ?? [];
        require_once 'app/models/OrderDetailModel.php';
        require_once 'app/models/OrderModel.php';
        require_once 'app/models/TicketModel.php';
        require_once 'app/models/EventModel.php';

        $orderDetailModel = new OrderDetailModel();
        $orderModel = new OrderModel();
        $ticketModel = new TicketModel();
        $eventModel = new EventModel();

        $search = $_GET['search'] ?? '';
        $page = (int) ($_GET['p'] ?? 1);

        // Get all attendees with search and pagination
        $attendees = $this->model->paginate($search, $page);

        // Enrich attendee data with order, ticket, and event information
        $attendeeData = [];
        foreach ($attendees['data'] as $attendee) {
            $orderDetail = $orderDetailModel->find($attendee['detail_id']);
            if ($orderDetail) {
                $order = $orderModel->find($orderDetail['order_id']);
                $ticket = $ticketModel->find($orderDetail['ticket_id']);
                $event = $ticket ? $eventModel->find($ticket['event_id']) : null;

                $attendeeData[] = [
                    'id' => $attendee['id'],
                    'ticket_code' => $attendee['ticket_code'],
                    'checkin_status' => $attendee['checkin_status'],
                    'order_id' => $order['id'] ?? null,
                    'order_status' => $order['status'] ?? null,
                    'order_date' => $order['date'] ?? null,
                    'ticket_name' => $ticket['name'] ?? 'Unknown',
                    'event_name' => $event['name'] ?? 'Unknown',
                    'user_id' => $order['user_id'] ?? null
                ];
            }
        }

        $attendees['data'] = $attendeeData;

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('checkin'));
        $this->layout->render('admin/checkin/index', [
            'title' => 'Check-in - MyTicket',
            'activeMenu' => 'checkin',
            'attendees' => $attendees,
            'search' => $search,
            'successCheck' => $successCheck
        ]);
        unset($_SESSION['success-check']);
    }

    public function checkin(): void
    {
        $ticketCode = $_POST['ticket_code'] ?? '';
        $attendee = $this->model->findByCode($ticketCode);

        if (!$attendee) {
            $_SESSION['error'] = 'Ticket code not found';
            header('Location: index.php?page=attendee&action=index');
            exit;
        }

        if ($attendee['checkin_status'] === 'sudah') {
            $_SESSION['error'] = 'Tiket sudah digunakan untuk check-in';
            header('Location: index.php?page=attendee&action=index');
            exit;
        }

        // Check if order is paid before allowing check-in
        require_once 'app/models/OrderDetailModel.php';
        require_once 'app/models/OrderModel.php';
        $orderDetailModel = new OrderDetailModel();
        $orderModel = new OrderModel();

        $orderDetail = $orderDetailModel->find($attendee['detail_id']);
        if (!$orderDetail) {
            $_SESSION['error'] = 'Order detail not found';
            header('Location: index.php?page=attendee&action=index');
            exit;
        }

        $order = $orderModel->find($orderDetail['order_id']);
        if (!$order || $order['status'] !== 'paid') {
            $_SESSION['error'] = 'Check-in only allowed for paid orders';
            header('Location: index.php?page=attendee&action=index');
            exit;
        }

        // Update check-in status and time
        $this->model->update($attendee['id'], [
            'checkin_status' => 'sudah',
            'checkin_time' => date('Y-m-d H:i:s')
        ]);

        // Create notification for user (only if user exists)
        require_once 'app/models/NotificationModel.php';
        $notificationModel = new NotificationModel();

        // Get event name for notification
        require_once 'app/models/EventModel.php';
        require_once 'app/models/TicketModel.php';
        $ticketModel = new TicketModel();
        $eventModel = new EventModel();

        $ticket = $ticketModel->find($orderDetail['ticket_id']);
        $event = $ticket ? $eventModel->find($ticket['event_id']) : null;
        $eventName = $event ? $event['name'] : 'Event';

        // Verify user exists before creating notification
        require_once 'app/models/UserModel.php';
        $userModel = new UserModel();
        $user = $userModel->find($order['user_id']);

        if ($user) {
            $notificationModel->createNotification(
                $order['user_id'],
                'Ticket Checked In! ',
                "Your ticket for '{$eventName}' has been successfully checked in. Enjoy the event!",
                'checkin'
            );
        }

        $_SESSION['success'] = "Check-in berhasil dan notifikasi terkirim ke pengguna!";
        $_SESSION['success-check'] = [
            'ticketCode' => $ticketCode,
            'eventName' => $eventName,
            'userName' => $user['name']
        ];
        header('Location: index.php?page=attendee&action=index');
        exit;
    }

    public function getTicketInfo(): void
    {
        header('Content-Type: application/json');

        $ticketCode = $_GET['ticket_code'] ?? '';
        if (empty($ticketCode)) {
            echo json_encode(['success' => false, 'message' => 'Ticket code is required']);
            exit;
        }

        $attendee = $this->model->findByCode($ticketCode);
        if (!$attendee) {
            echo json_encode(['success' => false, 'message' => 'Ticket code not found']);
            exit;
        }

        if ($attendee['checkin_status'] === 'sudah') {
            echo json_encode(['success' => false, 'message' => 'Tiket sudah digunakan untuk check-in']);
            exit;
        }

        require_once 'app/models/OrderDetailModel.php';
        require_once 'app/models/OrderModel.php';
        require_once 'app/models/TicketModel.php';
        require_once 'app/models/EventModel.php';
        require_once 'app/models/UserModel.php';

        $orderDetailModel = new OrderDetailModel();
        $orderModel = new OrderModel();
        $ticketModel = new TicketModel();
        $eventModel = new EventModel();
        $userModel = new UserModel();

        $orderDetail = $orderDetailModel->find($attendee['detail_id']);
        if (!$orderDetail) {
            echo json_encode(['success' => false, 'message' => 'Order detail not found']);
            exit;
        }

        $order = $orderModel->find($orderDetail['order_id']);
        if (!$order || $order['status'] !== 'paid') {
            echo json_encode(['success' => false, 'message' => 'Check-in only allowed for paid orders']);
            exit;
        }

        $ticket = $ticketModel->find($orderDetail['ticket_id']);
        $event = $ticket ? $eventModel->find($ticket['event_id']) : null;
        $user = $userModel->find($order['user_id']);

        echo json_encode([
            'success' => true,
            'data' => [
                'ticketCode' => $attendee['ticket_code'],
                'eventName' => $event ? $event['name'] : 'Unknown',
                'eventDate' => $event ? $event['date'] : '-',
                'userName' => $user ? $user['name'] : 'Unknown',
                'userEmail' => $user ? $user['email'] : '-',
                'ticketName' => $ticket ? $ticket['name'] : 'Unknown',
                'ticketPrice' => $ticket ? $ticket['price'] : 0,
                'qty' => $orderDetail['qty'] ?? 1,
                'subtotal' => $orderDetail['subtotal'] ?? 0,
                'orderDate' => $order['date'] ?? '-'
            ]
        ]);
        exit;
    }
}
