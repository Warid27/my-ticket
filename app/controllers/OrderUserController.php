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
        require_once 'app/models/EventModel.php';

        $ticketModel = new TicketModel();
        $eventModel = new EventModel();

        $ticket = $ticketModel->find((int) ($_GET['ticket_id'] ?? 0));
        $event = $eventModel->find((int) ($_GET['event_id'] ?? 0));

        if (!$ticket || !$event) {
            $_SESSION['error'] = 'Ticket or event not found.';
            header('Location: index.php?page=event&action=index');
            exit;
        }

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

        if (!$voucher || $voucher['status'] !== 'aktif') {
            echo json_encode(['success' => false, 'message' => 'Invalid or inactive voucher']);
            exit;
        }

        if ($voucher['quota'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'Voucher quota exhausted']);
            exit;
        }

        $subTotal = (int) ($_POST['subtotal'] ?? 0);
        $discountTotal = 0;

        if ($voucher['type'] === 'value') {
            $discountTotal = min($voucher['discount'], $subTotal);
        } else {
            $discountTotal = $subTotal * ($voucher['discount'] / 100);
        }

        echo json_encode([
            'success' => true,
            'discount' => $discountTotal,
            'type' => $voucher['type'],
            'message' => 'Voucher applied successfully!'
        ]);
        exit;
    }

    public function store(): void
    {
        session_start();

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

        if (!$ticket) {
            $_SESSION['error'] = 'Ticket not found!';
            header("Location: index.php?page=event&action=index");
            exit;
        }

        if ($qty <= 0 || $qty > $ticket['quota']) {
            $_SESSION['error'] = 'Invalid quantity!';
            header("Location: index.php?page=order&action=create&ticket_id={$_POST['ticket_id']}&event_id={$_POST['event_id']}");
            exit;
        }

        // Voucher setup
        $voucher_id = null;
        $discount = 0;
        $discountType = null;
        $voucher = null;

        if (!empty($_POST['voucher_code'])) {
            $voucher = $voucherModel->findByCode($_POST['voucher_code']);

            if ($voucher && $voucher['quota'] > 0 && $voucher['status'] === 'aktif') {
                $voucher_id = $voucher['id'];
                $discount = $voucher['discount'];
                $discountType = $voucher['type'];
            }
        }

        // Hitung total
        $subTotal = $ticket['price'] * $qty;

        if ($discountType === 'value') {
            $discountTotal = min($discount, $subTotal);
        } elseif ($discountType === 'percentage') {
            $discountTotal = $subTotal * ($discount / 100);
        } else {
            $discountTotal = 0;
        }

        $total = $subTotal - $discountTotal;

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

            // update ticket quota
            $ticketModel->update($ticket['id'], [
                'quota' => $ticket['quota'] - $qty
            ]);

            // update voucher quota (AMAN karena di dalam transaction)
            if ($voucher_id) {
                $voucherModel->update($voucher_id, [
                    'quota' => $voucher['quota'] - 1
                ]);
            }

            $db->commit();

            header("Location: index.php?page=order&action=show&id=$order_id");
            exit;

        } catch (PDOException $e) {
            $db->rollBack();
            $_SESSION['error'] = 'Order gagal: ' . $e->getMessage();

            header("Location: index.php?page=order&action=create&ticket_id={$_POST['ticket_id']}&event_id={$_POST['event_id']}");
            exit;
        }
    }

    public function show(): void
    {
        $order = $this->model->find((int) $_GET['id']);

        require_once 'app/models/OrderDetailModel.php';
        require_once 'app/models/AttendeeModel.php';

        $detailModel = new OrderDetailModel();
        $attendeeModel = new AttendeeModel();

        $details = $detailModel->byOrder($order['id']);
        $attendees = [];

        foreach ($details as $detail) {
            $attendees = array_merge(
                $attendees,
                $attendeeModel->query("SELECT * FROM attendees WHERE detail_id = ?", [$detail['id']])
            );
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

    public function pay(): void
    {
        $orderId = (int) $_GET['id'];
        $order = $this->model->find($orderId);

        if (!$order || $order['user_id'] !== $_SESSION['user_id']) {
            $_SESSION['error'] = 'Unauthorized or not found!';
            header("Location: index.php?page=order&action=history");
            exit;
        }

        if ($order['status'] !== 'pending') {
            $_SESSION['error'] = 'Order cannot be paid.';
            header("Location: index.php?page=order&action=show&id=$orderId");
            exit;
        }

        $this->model->update($orderId, ['status' => 'paid']);

        $_SESSION['success'] = 'Payment successful!';
        header("Location: index.php?page=order&action=show&id=$orderId");
        exit;
    }

    public function cancel(): void
    {
        $orderId = (int) $_GET['id'];
        $order = $this->model->find($orderId);

        if (!$order || $order['user_id'] !== $_SESSION['user_id']) {
            $_SESSION['error'] = 'Unauthorized!';
            header("Location: index.php?page=order&action=history");
            exit;
        }

        if ($order['status'] !== 'pending') {
            $_SESSION['error'] = 'Order cannot be cancelled.';
            header("Location: index.php?page=order&action=show&id=$orderId");
            exit;
        }

        require_once 'app/models/OrderDetailModel.php';
        require_once 'app/models/TicketModel.php';
        require_once 'app/models/VoucherModel.php';

        $orderDetailModel = new OrderDetailModel();
        $ticketModel = new TicketModel();
        $voucherModel = new VoucherModel();

        $db = getDB();
        $db->beginTransaction();

        try {
            $details = $orderDetailModel->byOrder($orderId);

            foreach ($details as $detail) {
                $ticket = $ticketModel->find($detail['ticket_id']);
                if ($ticket) {
                    $ticketModel->update($detail['ticket_id'], [
                        'quota' => $ticket['quota'] + $detail['qty']
                    ]);
                }
            }

            if ($order['voucher_id']) {
                $voucher = $voucherModel->find($order['voucher_id']);
                if ($voucher) {
                    $voucherModel->update($order['voucher_id'], [
                        'quota' => $voucher['quota'] + 1
                    ]);
                }
            }

            $this->model->update($orderId, ['status' => 'cancel']);

            $db->commit();

            $_SESSION['success'] = 'Order cancelled successfully!';
        } catch (PDOException $e) {
            $db->rollBack();
            $_SESSION['error'] = 'Cancel failed: ' . $e->getMessage();
        }

        header("Location: index.php?page=order&action=history");
        exit;
    }
}