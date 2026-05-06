<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Base Job class to provide common functionality for all application jobs.
 */
abstract class Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Default number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Default number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    /**
     * Common method to log job activities with context.
     */
    protected function logInfo(string $message, array $context = []): void
    {
        Log::info(sprintf('[%s] %s', class_basename($this), $message), $context);
    }

    protected function logWarning(string $message, array $context = []): void
    {
        Log::warning(sprintf('[%s] %s', class_basename($this), $message), $context);
    }

    protected function logError(string $message, \Throwable $exception, array $context = []): void
    {
        Log::error(sprintf('[%s] %s: %s', class_basename($this), $message, $exception->getMessage()), array_merge($context, [
            'exception' => $exception->getTraceAsString(),
        ]));
    }

    /**
     * Standard failure handling for all jobs.
     */
    public function failed(\Throwable $exception): void
    {
        $this->logError('Job permanently failed', $exception);
    }
}
