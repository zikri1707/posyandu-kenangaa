<?php

namespace App\Jobs;

use App\Services\WhatsAppService;

/**
 * Job untuk mengirim notifikasi WhatsApp secara async via queue.
 */
class SendWhatsAppNotificationJob extends Job
{
    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $target,
        protected string $message,
        protected ?int $scheduleId = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsAppService): void
    {
        $result = $whatsAppService->sendMessage($this->target, $this->message);

        if (! $result['success']) {
            $errorMessage = "Gagal mengirim WhatsApp ke {$this->target}: ".($result['message'] ?? 'Unknown error');

            $this->logWarning($errorMessage);

            throw new \RuntimeException($errorMessage);
        }

        $this->logInfo("Pesan WhatsApp berhasil dikirim ke {$this->target}");
    }
}
