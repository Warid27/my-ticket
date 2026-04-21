<?php
require_once 'app/core/BaseController.php';
class OrderUserController extends BaseController
{
    private OrderModel $model;

    public function __construct()
    {
        parent::__construct();
        require_once 'app/models/OrderModel.php';
        $this->model = new OrderModel();
        $this->guard($this->userRoles);
    }

    public function create(): void
    {
        require_once 'app/models/TicketModel.php';
        $ticketModel = new TicketModel();
        $ticket = $ticketModel->find((int) $_GET['ticket_id']);

        require_once 'app/models/EventModel.php';
        $eventModel = new EventModel();
        $event = $eventModel->find((int) $_GET['event_id']);

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('orders'));
        $this->layout->render('user/order/create', [
            'title' => 'Create Order - MyTicket',
            'ticket' => $ticket,
            'event' => $event,
            'activeMenu' => 'orders'
        ]);
    }

    public function applyVoucher(): void
    {
        header('Content-Type: application/json');
        
        if (empty($_POST['voucher_code'])) {
            echo json_encode(['success' => false, 'message' => 'Voucher code is required']);
            exit;
        }
        
        require_once 'app/models/VoucherModel.php';
        $voucherModel = new VoucherModel();
        $voucher = $voucherModel->findByCode($_POST['voucher_code']);
        
        if (!$voucher) {
            echo json_encode(['success' => false, 'message' => 'Invalid voucher code']);
            exit;
        }
        
        if ($voucher['quota'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'Voucher quota has been exhausted']);
            exit;
        }
        
        echo json_encode([
            'success' => true,
            'discount' => $voucher['discount'],
            'type' => $voucher['type'],
            'message' => 'Voucher applied successfully!'
        ]);
        exit;
    }

    public function store(): void
    {
        require_once 'app/models/TicketModel.php';
        require_once 'app/models/OrderModel.php';
        require_once 'app/models/OrderDetailModel.php';
        require_once 'app/models/AttendeeModel.php';
        require_once 'app/models/VoucherModel.php';

        $ticketModel = new TicketModel();
        $voucherModel = new VoucherModel();
        $orderModel = new OrderModel();
        $orderDetailModel = new OrderDetailModel();
        $attendeeModel = new AttendeeModel();

        $qty = (int) $_POST['qty'];
        $ticket = $ticketModel->find((int) $_POST['ticket_id']);

        if ($qty > $ticket['quota']) {
            $_SESSION['error'] = 'Kuota tidak cukup!';
            header("Location: index.php?page=order&action=create&ticket_id =" . $_POST['ticket_id']);
        }
        $voucher_id = null;
        $discount = 0;
        $discountType = null;

        if (!empty($_POST['voucher_code'])) {
            require_once 'app/models/VoucherModel.php';
            $voucherModel = new VoucherModel();
            $voucher = $voucherModel->findByCode($_POST['voucher_code']);

            if ($voucher && $voucher['quota'] > 0) {
                $voucher_id = $voucher['id'];
                $discount = $voucher['discount'];
                $discountType = $voucher['type'];

                $voucherModel->update($voucher_id, [
                    'quota' => $voucher['quota'] - 1
                ]);
            }
        }

        $subTotal = $ticket['price'] * $qty;
        $discountTotal = 0;

        if ($discountType === 'value') {
            $discountTotal = $discount;
        } elseif ($discountType === 'percentage') {
            $discountTotal = $subTotal * ($discount / 100);
        }

        $total = max(0, $subTotal - $discountTotal);

        $db = getDB();
        $db->beginTransaction();
        try {
            $order_id = $orderModel->insert([
                'user_id' => $_SESSION['user_id'],
                'total' => $total,
                'status' => 'pending',
                'voucher_id' => $voucher_id,
                'date' => date('Y-m-d H:i:s')
            ]);

            $detail_id = $orderDetailModel->insert([
                'order_id' => $order_id,
                'ticket_id' => $ticket['id'],
                'qty' => $qty,
                'subtotal' => $subTotal
            ]);

            for ($i = 0; $i < $qty; $i++) {
                $attendeeModel->insert([
                    'detail_id' => $detail_id,
                    'ticket_code' => strtoupper(uniqid('TKT-')),
                    'checkin_status' => 'belum'
                ]);
            }

            $ticketModel->update($ticket['id'], ['quota' => $ticket['quota'] - $qty]);

            $db->commit();
            header("Location: index.php?page=order&action=show&id=$order_id");
            exit;
        } catch (PDOException $e) {
            $db->rollBack();
            $_SESSION['error'] = 'Order gagal: ' . $e->getMessage();
            header("Location: index.php?page=order&action=create&ticket_id=" . $_POST['ticket_id']);
            exit;
        }
    }
    public function show(): void
    {
        $order = $this->model->find((int) $_GET['id']);
        require_once 'app/models/OrderDetailModel.php';

        $detailModel = new OrderDetailModel();
        $details = $detailModel->byOrder((int) $_GET['id']);

        require_once 'app/models/AttendeeModel.php';
        $attendeeModel = new AttendeeModel();
        $attendees = [];

        foreach ($details as $detail) {
            $detailAttendees = $attendeeModel->query("SELECT * FROM attendees WHERE detail_id = ?", [$detail['id']]);
            $attendees = [...$attendees, $detailAttendees];
        }

        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('orders'));
        $this->layout->render('user/order/show', [
            'title' => 'Order Details - MyTicket',
            'order' => $order,
            'details' => $details,
            'attendees' => $attendees,
            'activeMenu' => 'orders'
        ]);
    }

    public function history(): void
    {
        $orders = $this->model->byUser($_SESSION['user_id']);
        $this->layout->extend('mazer-dashboard');
        $this->layout->section('sidebarMenu', $this->getSidebarMenu('orders'));
        $this->layout->render('user/order/history', [
            'title' => 'Order History - MyTicket',
            'orders' => $orders,
            'activeMenu' => 'orders'
        ]);
    }
}
