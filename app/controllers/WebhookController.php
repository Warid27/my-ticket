<?php
require_once 'app/core/BaseController.php';
require_once 'app/services/XenditService.php';
require_once 'app/models/OrderModel.php';
require_once 'app/models/OrderDetailModel.php';
require_once 'app/models/TicketModel.php';
require_once 'app/models/VoucherModel.php';
require_once 'app/models/NotificationModel.php';

class WebhookController extends BaseController
{
    private OrderModel $orderModel;
    private OrderDetailModel $orderDetailModel;
    private TicketModel $ticketModel;
    private VoucherModel $voucherModel;
    private NotificationModel $notificationModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new OrderModel();
        $this->orderDetailModel = new OrderDetailModel();
        $this->ticketModel = new TicketModel();
        $this->voucherModel = new VoucherModel();
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Handle Xendit webhook callbacks
     */
    public function xendit(): void
    {
        header('Content-Type: application/json');

        // Get raw POST data
        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);

        // Get headers
        $headers = $this->getHeaders();

        // Log for debugging
        error_log('Xendit Callback: ' . $rawData);
        error_log('Headers: ' . print_r($headers, true));

        try {
            // Verify webhook
            $xendit = new XenditService();

            if (!$xendit->verifyWebhook($headers, $rawData)) {
                http_response_code(401);
                echo json_encode([
                    'error' => 'Unauthorized',
                    'message' => 'Invalid webhook token'
                ]);
                exit;
            }

            // Process callback based on status
            if (isset($data['status'])) {
                switch ($data['status']) {
                    case 'PAID':
                        $this->handleInvoicePaid($data);
                        break;
                    case 'EXPIRED':
                        $this->handleInvoiceExpired($data);
                        break;
                    case 'FAILED':
                        $this->handleInvoiceFailed($data);
                        break;
                    default:
                        error_log('Unhandled webhook status: ' . $data['status']);
                        break;
                }
            } else {
                error_log('No status found in webhook data');
            }

            // Return success response
            http_response_code(200);
            echo json_encode(['status' => 'success']);

        } catch (Exception $e) {
            error_log('Xendit Callback Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Get HTTP headers - handles different server configurations
     */
    private function getHeaders(): array
    {
        $headers = [];

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {
            // Fallback for nginx/fastcgi
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                    $headers[$headerName] = $value;
                }
            }
        }

        return $headers;
    }

    /**
     * Handle successful payment (PAID status)
     */
    private function handleInvoicePaid(array $data): void
    {
        $externalId = $data['external_id'] ?? '';
        $invoiceId = $data['id'] ?? '';
        $paidAmount = $data['paid_amount'] ?? 0;

        error_log("Processing invoice paid: external_id=$externalId, invoice_id=$invoiceId");

        // Extract order ID from external_id (format: ORDER-{id})
        if (strpos($externalId, 'ORDER-') !== 0) {
            error_log("Invalid external_id format: $externalId");
            return;
        }

        $orderId = (int) substr($externalId, 6);

        // Get order
        $order = $this->orderModel->find($orderId);
        if (!$order) {
            error_log("Order not found: $orderId");
            return;
        }

        // Only process if order is still pending
        if ($order['status'] !== 'pending') {
            error_log("Order #$orderId already processed with status: {$order['status']}");
            return;
        }

        $db = getDB();
        $db->beginTransaction();

        try {
            // Update order status to paid
            $this->orderModel->update($orderId, [
                'status' => 'paid',
                'date' => date('Y-m-d H:i:s') // Update date to payment time
            ]);

            // Send notification to user
            $this->notificationModel->insert([
                'user_id' => $order['user_id'],
                'title' => 'Payment Successful',
                'message' => "Your order #{$orderId} has been paid successfully. Invoice ID: {$invoiceId}",
                'type' => 'payment'
            ]);

            $db->commit();
            error_log("Order #$orderId marked as paid via Xendit invoice $invoiceId");

        } catch (Exception $e) {
            $db->rollBack();
            error_log("Error processing paid invoice: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle expired invoice
     */
    private function handleInvoiceExpired(array $data): void
    {
        $externalId = $data['external_id'] ?? '';

        if (strpos($externalId, 'ORDER-') !== 0) {
            error_log("Invalid external_id format: $externalId");
            return;
        }

        $orderId = (int) substr($externalId, 6);
        $order = $this->orderModel->find($orderId);

        if (!$order || $order['status'] !== 'pending') {
            error_log("Order not found or not pending: $orderId");
            return;
        }

        $this->cancelOrder($orderId, 'expired');
        error_log("Order #$orderId marked as cancelled due to expired invoice");
    }

    /**
     * Handle failed payment
     */
    private function handleInvoiceFailed(array $data): void
    {
        $externalId = $data['external_id'] ?? '';

        if (strpos($externalId, 'ORDER-') !== 0) {
            error_log("Invalid external_id format: $externalId");
            return;
        }

        $orderId = (int) substr($externalId, 6);
        $order = $this->orderModel->find($orderId);

        if (!$order || $order['status'] !== 'pending') {
            error_log("Order not found or not pending: $orderId");
            return;
        }

        $this->cancelOrder($orderId, 'failed');
        error_log("Order #$orderId marked as cancelled due to failed payment");
    }

    /**
     * Cancel order and restore ticket quota
     */
    private function cancelOrder(int $orderId, string $reason): void
    {
        $db = getDB();
        $db->beginTransaction();

        try {
            $order = $this->orderModel->find($orderId);
            if (!$order) {
                throw new Exception("Order not found");
            }

            // Get order details to restore ticket quotas
            $details = $this->orderDetailModel->byOrder($orderId);

            foreach ($details as $detail) {
                $ticket = $this->ticketModel->find($detail['ticket_id']);
                if ($ticket) {
                    $this->ticketModel->update($detail['ticket_id'], [
                        'quota' => $ticket['quota'] + $detail['qty']
                    ]);
                }
            }

            // Restore voucher quota if used
            if ($order['voucher_id']) {
                $voucher = $this->voucherModel->find($order['voucher_id']);
                if ($voucher) {
                    $this->voucherModel->update($order['voucher_id'], [
                        'quota' => $voucher['quota'] + 1
                    ]);
                }
            }

            // Update order status to cancelled
            $this->orderModel->update($orderId, ['status' => 'cancel']);

            // Send notification
            $this->notificationModel->insert([
                'user_id' => $order['user_id'],
                'title' => 'Order Cancelled',
                'message' => "Your order #{$orderId} has been cancelled due to {$reason} payment.",
                'type' => 'payment'
            ]);

            $db->commit();

        } catch (Exception $e) {
            $db->rollBack();
            error_log("Error cancelling order: " . $e->getMessage());
            throw $e;
        }
    }
}
