<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\QueueEntry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportAnalyticsReportsCommand extends Command
{
    protected $signature = 'reports:export {--weekly : Include weekly summary columns}';

    protected $description = 'Export CSV analytics to storage/app/reports';

    public function handle(): int
    {
        $dir = storage_path('app/reports');
        File::ensureDirectoryExists($dir);

        $date = now()->format('Y-m-d');
        $path = $dir."/daily-{$date}.csv";

        $bookingsBySource = Booking::query()
            ->whereDate('created_at', today())
            ->selectRaw('COALESCE(source, "website") as src, COUNT(*) as c')
            ->groupByRaw('COALESCE(source, "website")')
            ->pluck('c', 'src');

        $queueBySource = QueueEntry::query()
            ->whereDate('created_at', today())
            ->selectRaw('COALESCE(source, "website") as src, COUNT(*) as c')
            ->groupByRaw('COALESCE(source, "website")')
            ->pluck('c', 'src');

        $fh = fopen($path, 'w');
        fputcsv($fh, ['report', 'date', 'metric', 'value']);
        fputcsv($fh, ['daily', $date, 'bookings_total', Booking::whereDate('created_at', today())->count()]);
        fputcsv($fh, ['daily', $date, 'queue_joins_total', QueueEntry::whereDate('created_at', today())->count()]);

        foreach ($bookingsBySource as $src => $c) {
            fputcsv($fh, ['daily', $date, 'bookings_'.$src, $c]);
        }
        foreach ($queueBySource as $src => $c) {
            fputcsv($fh, ['daily', $date, 'queue_'.$src, $c]);
        }
        fclose($fh);

        $this->info("Wrote {$path}");

        if ($this->option('weekly')) {
            $wpath = $dir.'/weekly-'.$date.'.csv';
            $from = now()->startOfWeek();
            $wf = fopen($wpath, 'w');
            fputcsv($wf, ['week_start', 'bookings', 'queue_joins']);
            fputcsv($wf, [
                $from->toDateString(),
                Booking::where('created_at', '>=', $from)->count(),
                QueueEntry::where('created_at', '>=', $from)->count(),
            ]);
            fclose($wf);
            $this->info("Wrote {$wpath}");
        }

        return self::SUCCESS;
    }
}
