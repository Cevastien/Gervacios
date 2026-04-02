<?php

namespace App\Console\Commands;

use App\Services\AutomationEngine;
use Illuminate\Console\Command;

class RunAutomationCommand extends Command
{
    protected $signature = 'automation:run {task=all : queue_holds|wait_estimates|no_shows|late_checkin|reminders|all}';

    protected $description = 'Run unified automation tasks (scheduler also runs these)';

    public function handle(): int
    {
        $task = $this->argument('task');
        $tasks = $task === 'all'
            ? ['queue_holds', 'wait_estimates', 'no_shows', 'late_checkin', 'reminders']
            : [$task];

        foreach ($tasks as $t) {
            $this->line("Running: {$t}");
            AutomationEngine::run($t);
        }

        $this->info('Done.');

        return self::SUCCESS;
    }
}
