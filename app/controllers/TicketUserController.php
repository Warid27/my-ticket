<?php
require_once 'app/core/BaseController.php';
class TicketUserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->guard($this->userRoles);
    }

    public function index(): void
    {
        require_once 'app/models/AttendeeModel.php';
        require_once 'app/models/OrderDetailModel.php';
        require_once 'app/models/OrderModel.php';

        $orderModel = new OrderModel();
        $orderDetailModel = new OrderDetailModel();
        $attendeeModel = new AttendeeModel();

        // Get user's orders
        $orders = $orderModel->byUser($_SESSION['user_id']);
        
        $tickets = [];
        foreach ($orders as $order) {
            $details = $orderDetailModel->byOrder($order['id']);
            foreach ($details as $detail) {
                $attendees = $attendeeModel->query("SELECT * FROM attendees WHERE detail_id = ?", [$detail['id']]);
                foreach ($attendees as $attendee) {
                    $tickets[] = [
                        'ticket_code' => $attendee['ticket_code'],
                        'checkin_status' => $attendee['checkin_status'],
                        'order_id' => $order['id'],
                        'order_date' => $order['date'],
                        'order_total' => $order['total'],
                        'order_status' => $order['status']
                    ];
                }
            }
        }

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('tickets'));
        $this->layout->render('user/ticket/index', [
            'title' => 'My Tickets - MyTicket',
            'tickets' => $tickets,
            'activeMenu' => 'tickets'
        ]);
    }

    public function show(): void
    {
        require_once 'app/models/AttendeeModel.php';
        require_once 'app/models/OrderDetailModel.php';

        $orderDetailModel = new OrderDetailModel();
        $attendeeModel = new AttendeeModel();

        $details = $orderDetailModel->byOrder((int) $_GET['order_id']);
        $attendees = [];

        foreach ($details as $detail) {
            $detailAttendees = $attendeeModel->query("SELECT * FROM attendees WHERE detail_id = ?", [$detail['id']]);

            $attendees = [...$attendees, $detailAttendees];
        }

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('tickets'));
        $this->layout->render('user/ticket/show', [
            'title' => 'Ticket Details - MyTicket',
            'attendees' => $attendees,
            'activeMenu' => 'tickets'
        ]);
    }
}
