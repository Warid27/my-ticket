<?php
require_once 'app/core/BaseController.php';
class OrderController extends BaseController
{

    private OrderModel $model;

    public function __construct()
    {
        $this->guard($this->userRoles);
        require_once 'app/models/OrderModel.php';
        $this->model = new OrderModel();
    }

    public function create(): void
    {
        require_once 'app/models/TicketModel.php';
        $ticketModel = new TicketModel();
        $ticket = $ticketModel->find((int) $_GET['ticket_id']);

        // Get event name
        require_once 'app/models/EventModel.php';
        $eventModel = new EventModel();
        $event = $eventModel->find($ticket['event_id']);
        $ticket['event_name'] = $event['name'];

        require 'app/views/customer/order/create.php';
    }

    public function store(): void
    {
        require_once 'app/models/TicketModel.php';
        require_once 'app/models/VoucherModel.php';
        require_once 'app/models/OrderModel.php';
        require_once 'app/models/OrderDetailModel.php';
        require_once 'app/models/AttendeeModel.php';

        $ticketModel = new TicketModel();
        $orderModel = new OrderModel();
        $detailModel = new OrderDetailModel();
        $attendeeModel = new AttendeeModel();

        $qty = (int) $_POST['qty'];
        $ticket = $ticketModel->find((int) $_POST['ticket_id']);

        if ($qty > $ticket['quota']) {
            $_SESSION['error'] = 'Quota exceeded';
            header('Location: index.php?page=order&action=create&ticket_id=' . $_POST['ticket_id']);
            exit;
        }

        // Voucher (optional)
        $voucher_id = null;
        $discount = 0;
        if (!empty($_POST['voucher_code'])) {
            $voucherModel = new VoucherModel();
            $voucher = $voucherModel->findByCode($_POST['voucher_code']);
            if ($voucher) {
                $voucher_id = $voucher['id'];
                $discount = $voucher['discount'];
                $voucherModel->update($voucher_id, ['quota' => $voucher['quota'] - 1]);
            }
        }

        $subtotal = $ticket['price'] * $qty;
        $total = max(0, $subtotal - $discount);

        $db = getDB();
        $db->beginTransaction();
        try {
            $order_id = $orderModel->insert([
                'user_id' => $_SESSION['user_id'],
                'total' => $total,
                'status' => 'pending',
                'voucher_id' => $voucher_id,
            ]);

            $detail_id = $detailModel->insert([
                'order_id' => $order_id,
                'ticket_id' => $ticket['id'],
                'qty' => $qty,
                'subtotal' => $subtotal,
            ]);

            // One attendee row per ticket unit
            for ($i = 0; $i < $qty; $i++) {
                $attendeeModel->insert([
                    'order_detail_id' => $detail_id,
                    'ticket_code' => strtoupper(uniqid('TKT-')),
                    'checkin_status' => 'pending',
                ]);
            }

            $ticketModel->update($ticket['id'], ['quota' => $ticket['quota'] - $qty]);

            $db->commit();
            header("Location: index.php?page=order&action=show&id=$order_id");
            exit;
        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = 'Order failed: ' . $e->getMessage();
            header('Location: index.php?page=order&action=create&ticket_id=' . $_POST['ticket_id']);
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
            $detailAttendees = $attendeeModel->query(
                "SELECT * FROM attendees WHERE order_detail_id = ?",
                [$detail['id']]
            );
            $attendees = [...$attendees, $detailAttendees];
        }

        require 'app/views/customer/order/show.php';
    }

    public function history(): void
    {
        $orders = $this->model->byUser($_SESSION['user_id']);
        require 'app/views/customer/order/history.php';
    }
}
