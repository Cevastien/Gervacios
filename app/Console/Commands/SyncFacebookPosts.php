<?php

namespace App\Console\Commands;

use App\Services\FacebookPostService;
use Illuminate\Console\Command;

class SyncFacebookPosts extends Command
{
    protected $signature = 'blog:sync-facebook';
    protected $description = 'Fetch latest posts from the Facebook page and store them as blog posts';

    public function handle(FacebookPostService $service): int
    {
        if (!$service->isConfigured()) {
            $this->warn('Facebook API not configured. Set FB_PAGE_ID and FB_ACCESS_TOKEN in .env or admin settings.');
            return self::FAILURE;
        }

        $this->info('Syncing Facebook posts…');

        $count = $service->sync();

        if ($count === -1) {
            $this->error('Sync failed. Check logs for details.');
            return self::FAILURE;
        }

        $this->info("Done. {$count} post(s) synced.");
        return self::SUCCESS;
    }
}
