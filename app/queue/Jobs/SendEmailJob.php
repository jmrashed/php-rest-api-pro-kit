<?php

namespace App\Queue\Jobs;

class SendEmailJob extends BaseJob
{
    private $to;
    private $subject;
    private $message;
    private $headers;

    public function __construct(string $to, string $subject, string $message, array $headers = [])
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->headers = $headers;
        $this->maxRetries = 3;
        $this->delay = 30; // 30 seconds delay on retry
    }

    public function handle(): bool
    {
        try {
            $headers = implode("\r\n", array_merge([
                'From: noreply@example.com',
                'Content-Type: text/html; charset=UTF-8'
            ], $this->headers));

            $result = mail($this->to, $this->subject, $this->message, $headers);
            
            if ($result) {
                error_log("Email sent successfully to: {$this->to}");
                return true;
            }
            
            throw new \Exception("Failed to send email to: {$this->to}");
        } catch (\Exception $e) {
            error_log("Email job failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Exception $exception): void
    {
        error_log("Email job permanently failed for {$this->to}: " . $exception->getMessage());
        // Could save to failed_emails table or send notification
    }
}