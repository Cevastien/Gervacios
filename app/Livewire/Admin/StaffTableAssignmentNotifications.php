<?php

namespace App\Livewire\Admin;

use App\Models\StaffNotification;
use Livewire\Component;

class StaffTableAssignmentNotifications extends Component
{
    public function dismiss(int $id): void
    {
        StaffNotification::query()
            ->whereKey($id)
            ->where('type', 'table_assigned')
            ->update(['is_read' => true]);
    }

    public function render()
    {
        $notifications = StaffNotification::query()
            ->where('type', 'table_assigned')
            ->where('is_read', false)
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.admin.staff-table-assignment-notifications', [
            'notifications' => $notifications,
        ]);
    }
}
