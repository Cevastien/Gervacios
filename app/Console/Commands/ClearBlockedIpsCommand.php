<?php

namespace App\Console\Commands;

use App\Models\BlockedIp;
use Illuminate\Console\Command;

class ClearBlockedIpsCommand extends Command
{
    protected $signature = 'blocked:clear';

    protected $description = 'Remove all entries from the automation IP block list';

    public function handle(): int
    {
        $n = BlockedIp::query()->count();
        BlockedIp::query()->delete();
        $this->info("Cleared {$n} blocked IP(s).");

        return self::SUCCESS;
    }
}
