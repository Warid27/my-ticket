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
        unset($_SESSION['error']);
        unset($_SESSION['success']);
        
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
            'search' => $search
        ]);
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
            $_SESSION['error'] = 'Ticket already checked in';
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

        $_SESSION['success'] = 'Check-in successful and notification sent to user';
        header('Location: index.php?page=attendee&action=index');
        exit;
    }
}
