<?php

require_once __DIR__ . '/../../config.php';

class EmailService
{
    private $apiKey;
    private $fromEmail;
    private $fromName;

    public function __construct()
    {
        $this->apiKey = RESEND_API_KEY;
        $this->fromEmail = FROM_EMAIL;
        $this->fromName = FROM_NAME;
    }

    /**
     * Send email using Resend API
     */
    public function sendEmail(string $to, string $subject, string $htmlContent, string $textContent = ''): bool
    {
        try {
            $data = [
                'from' => "{$this->fromName} <{$this->fromEmail}>",
                'to' => [$to],
                'subject' => $subject,
                'html' => $htmlContent,
            ];

            if (!empty($textContent)) {
                $data['text'] = $textContent;
            }

            $ch = curl_init('https://api.resend.com/emails');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new Exception("cURL Error: " . $error);
            }

            $result = json_decode($response, true);

            if ($httpCode !== 200) {
                throw new Exception("Resend API Error: " . ($result['message'] ?? 'Unknown error'));
            }

            return true;

        } catch (Exception $e) {
            // Log error for debugging
            error_log("Email sending failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail(string $to, string $resetLink): bool
    {
        $subject = "Password Reset Request - " . APP_NAME;
        
        $htmlContent = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Password Reset Request</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { text-align: center; padding: 20px 0; }
                .logo { font-size: 24px; font-weight: bold; color: #007bff; }
                .content { padding: 20px; background: #f8f9fa; border-radius: 8px; }
                .button { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <div class='logo'>" . APP_NAME . "</div>
                </div>
                <div class='content'>
                    <h2>Password Reset Request</h2>
                    <p>Hello,</p>
                    <p>You requested a password reset for your " . APP_NAME . " account.</p>
                    <p>Click the button below to reset your password:</p>
                    <div style='text-align: center;'>
                        <a href='{$resetLink}' class='button'>Reset Password</a>
                    </div>
                    <p>Or copy and paste this link into your browser:</p>
                    <p><a href='{$resetLink}'>{$resetLink}</a></p>
                    <p><strong>This link will expire in 1 hour.</strong></p>
                    <p>If you didn't request this password reset, please ignore this email.</p>
                </div>
                <div class='footer'>
                    <p>Thank you,<br>" . APP_NAME . " Team</p>
                    <p>This is an automated message. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>";

        $textContent = "
Password Reset Request - " . APP_NAME . "

Hello,

You requested a password reset for your " . APP_NAME . " account.

Click the link below to reset your password:
{$resetLink}

This link will expire in 1 hour.

If you didn't request this password reset, please ignore this email.

Thank you,
" . APP_NAME . " Team
";

        return $this->sendEmail($to, $subject, $htmlContent, $textContent);
    }
}
