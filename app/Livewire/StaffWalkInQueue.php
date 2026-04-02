<?php

namespace App\Livewire;

use App\Livewire\Concerns\WithToastNotifications;
use App\Rules\PhilippinePhone;
use App\Models\AdminLog;
use App\Services\BookingGuardService;
use App\Services\QueueService;
use Livewire\Component;

class StaffWalkInQueue extends Component
{
    use WithToastNotifications;

    public string $customer_name = '';

    public string $customer_phone = '';

    public int $party_size = 2;

    public string $priority_type = 'none';

    public function register(): void
    {
        $this->validate([
            'customer_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\.]+$/'],
            'customer_phone' => ['nullable', new PhilippinePhone],
            'party_size' => ['required', 'integer', 'min:1', 'max:20'],
            'priority_type' => ['required', 'in:none,pwd,pregnant,senior'],
        ]);

        if (filled($this->customer_phone) && app(BookingGuardService::class)->hasActiveEntry($this->customer_phone)) {
            $this->toastError('This phone already has an active booking or queue entry.');

            return;
        }

        try {
            $entry = app(QueueService::class)->join(
                $this->customer_name,
                $this->customer_phone,
                $this->party_size,
                $this->priority_type,
                'staff',
                'desktop'
            );
        } catch (\InvalidArgumentException $e) {
            $this->toastError($e->getMessage());

            return;
        }

        AdminLog::record('staff_queue_register', 'queue_entry', $entry->id, 'Walk-in registered at host');

        $this->reset(['customer_name', 'customer_phone', 'party_size', 'priority_type']);
        $this->party_size = 2;
        $this->priority_type = 'none';
        $this->toastSuccess('Added to queue. Ticket #'.$entry->queue_display_number);
    }

    public function render()
    {
        return view('livewire.staff-walk-in-queue');
    }
}
