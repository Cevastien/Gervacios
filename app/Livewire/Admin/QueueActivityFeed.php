<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\QueueEntry;
use Illuminate\Support\Carbon;
use Livewire\Component;

/**
 * Dashboard sidebar: live queue & reservation activity (joins, seating, exits, paid bookings).
 */
class QueueActivityFeed extends Component
{
    public int $lookbackHours = 24;

    public int $maxItems = 18;

    public function render()
    {
        $since = now()->subHours($this->lookbackHours);

        $nextInLine = QueueEntry::waiting()->sorted()->first();

        $items = $this->buildTimeline($since);

        return view('livewire.admin.queue-activity-feed', [
            'nextInLine' => $nextInLine,
            'items' => $items,
            'lookbackHours' => $this->lookbackHours,
        ]);
    }

    /** @return array<int, array<string, mixed>> */
    protected function buildTimeline(Carbon $since): array
    {
        $rows = [];

        /** @var \Illuminate\Database\Eloquent\Collection<int, QueueEntry> $joined */
        $joined = QueueEntry::query()
            ->where('joined_at', '>=', $since)
            ->orderByDesc('joined_at')
            ->limit(40)
            ->get();

        foreach ($joined as $e) {
            $rows[] = [
                'type' => 'join',
                'at' => $e->joined_at,
                'title' => 'Joined queue',
                'detail' => $this->queueLine($e),
                'highlight' => $e->isPriority(),
            ];
        }

        /** @var \Illuminate\Database\Eloquent\Collection<int, QueueEntry> $notified */
        $notified = QueueEntry::query()
            ->whereNotNull('notified_at')
            ->where('notified_at', '>=', $since)
            ->orderByDesc('notified_at')
            ->limit(40)
            ->get();

        foreach ($notified as $e) {
            $rows[] = [
                'type' => 'notified',
                'at' => $e->notified_at,
                'title' => 'Table ready — SMS sent',
                'detail' => $this->queueLine($e),
                'highlight' => false,
            ];
        }

        /** @var \Illuminate\Database\Eloquent\Collection<int, QueueEntry> $seatedRows */
        $seatedRows = QueueEntry::query()
            ->whereNotNull('seated_at')
            ->where('seated_at', '>=', $since)
            ->orderByDesc('seated_at')
            ->limit(40)
            ->get();

        foreach ($seatedRows as $e) {
            $rows[] = [
                'type' => 'seated',
                'at' => $e->seated_at,
                'title' => 'Seated',
                'detail' => $this->queueLine($e),
                'highlight' => false,
            ];
        }

        /** @var \Illuminate\Database\Eloquent\Collection<int, QueueEntry> $cancelledRows */
        $cancelledRows = QueueEntry::query()
            ->where('status', 'cancelled')
            ->where('updated_at', '>=', $since)
            ->orderByDesc('updated_at')
            ->limit(40)
            ->get();

        foreach ($cancelledRows as $e) {
            $rows[] = [
                'type' => 'cancelled',
                'at' => $e->updated_at,
                'title' => 'Left queue',
                'detail' => $this->queueLine($e),
                'highlight' => false,
            ];
        }

        /** @var \Illuminate\Database\Eloquent\Collection<int, Booking> $paidBookings */
        $paidBookings = Booking::query()
            ->where('payment_status', 'paid')
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', $since)
            ->orderByDesc('paid_at')
            ->limit(40)
            ->get();

        foreach ($paidBookings as $b) {
            $rows[] = [
                'type' => 'paid',
                'at' => $b->paid_at,
                'title' => 'Reservation paid',
                'detail' => $this->bookingLine($b),
                'highlight' => true,
            ];
        }

        usort($rows, static function (array $a, array $b): int {
            return $b['at']->timestamp <=> $a['at']->timestamp;
        });

        return array_slice($rows, 0, $this->maxItems);
    }

    protected function queueLine(QueueEntry $e): string
    {
        $num = $e->queue_display_number ?? $e->id;
        $name = $e->customer_name;
        $party = (int) $e->party_size;
        $suffix = $e->isPriority() ? ' · Priority' : '';

        return "#{$num} · {$name} · {$party}p{$suffix}";
    }

    protected function bookingLine(Booking $b): string
    {
        $ref = $b->booking_ref ?? ('#'.$b->id);
        $name = $b->customer_name;
        $party = (int) $b->party_size;

        return "{$ref} · {$name} · {$party}p";
    }
}
