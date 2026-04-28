<?php

class XenditService
{
    private string $apiKey;
    private string $webhookToken;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = XENDIT_API_KEY;
        $this->webhookToken = XENDIT_WEBHOOK_TOKEN;
        $this->baseUrl = 'https://api.xendit.co';

        // Validate configuration
        if (strpos($this->apiKey, 'YOUR_API_KEY_HERE') !== false) {
            throw new Exception('Xendit API key not configured. Please update config.php with your actual API key.');
        }
    }

    /**
     * Create a Xendit invoice for an order
     *
     * @param int $orderId Order ID
     * @param int $amount Amount in IDR
     * @param string $customerEmail Customer email
     * @param string $customerName Customer name
     * @param string $description Invoice description
     * @return array|false Invoice data or false on failure
     */
    public function createInvoice(int $orderId, int $amount, string $customerEmail, string $customerName, string $description = ''): array|false
    {
        $externalId = 'ORDER-' . $orderId;
        $successRedirectUrl = APP_URL . 'index.php?page=order&action=show&id=' . $orderId;

        $payload = [
            'external_id' => $externalId,
            'amount' => $amount,
            'payer_email' => $customerEmail,
            'description' => $description ?: 'Payment for Order #' . $orderId,
            'success_redirect_url' => $successRedirectUrl,
            'failure_redirect_url' => $successRedirectUrl,
            'currency' => 'IDR',
            'customer' => [
                'given_names' => $customerName,
                'email' => $customerEmail
            ]
        ];

        $response = $this->request('POST', '/v2/invoices', $payload);

        if ($response && isset($response['id'])) {
            return $response;
        }

        error_log('Xendit create invoice failed: ' . json_encode($response));
        return false;
    }

    /**
     * Get invoice by ID
     *
     * @param string $invoiceId Xendit invoice ID
     * @return array|false Invoice data or false on failure
     */
    public function getInvoice(string $invoiceId): array|false
    {
        $response = $this->request('GET', '/v2/invoices/' . $invoiceId);

        if ($response && isset($response['id'])) {
            return $response;
        }

        return false;
    }

    /**
     * Verify webhook callback token
     *
     * @param array $headers HTTP headers
     * @param string $rawBody Raw request body
     * @return bool True if verified
     */
    public function verifyWebhook(array $headers, string $rawBody): bool
    {
        // Try different header variations
        $callbackToken = $headers['x-callback-token'] ??
                        $headers['X-Callback-Token'] ??
                        $_SERVER['HTTP_X_CALLBACK_TOKEN'] ??
                        '';

        if (empty($callbackToken)) {
            error_log('Xendit webhook: No callback token found');
            return false;
        }

        // Verify token matches
        if ($callbackToken !== $this->webhookToken) {
            error_log('Xendit webhook: Invalid callback token');
            return false;
        }

        return true;
    }

    /**
     * Make HTTP request to Xendit API
     *
     * @param string $method HTTP method
     * @param string $endpoint API endpoint
     * @param array|null $payload Request payload
     * @return array|false Response data or false on failure
     */
    private function request(string $method, string $endpoint, ?array $payload = null): array|false
    {
        $url = $this->baseUrl . $endpoint;

        $ch = curl_init($url);

        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->apiKey . ':')
        ];

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            error_log('Xendit API curl error: ' . $error);
            return false;
        }

        $data = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300) {
            return $data;
        }

        error_log('Xendit API error (HTTP ' . $httpCode . '): ' . $response);
        return false;
    }
}
