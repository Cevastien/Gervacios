<?php

namespace App\Jobs;

use App\Services\QueueService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Runs after {@see TableService::release()} / {@see TableService::releaseExpired()} once the
 * configured cleaning window has passed, then notifies the next matching waitlist party.
 */
class NotifyNextAfterTableReleaseJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(QueueService $queueService): void
    {
        $queueService->notifyNextAfterTableRelease();
    }
}
