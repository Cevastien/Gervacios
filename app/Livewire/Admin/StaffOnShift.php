<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StaffOnShift extends Component
{
    /**
     * Staff and admins with an active session row (tablet / browser logged in).
     *
     * @return array{sessionsUnavailable: bool, rows: Collection<int, object{name: string, role: string, role_label: string, last_activity: int, last_seen: string}>}
     */
    public function staffWithActiveSessions(): array
    {
        if (config('session.driver') !== 'database') {
            return [
                'sessionsUnavailable' => true,
                'rows' => collect(),
            ];
        }

        $lifetime = (int) config('session.lifetime', 120);
        $cutoff = now()->subMinutes($lifetime)->timestamp;

        $roleLabels = [
            'staff' => 'Staff',
            'admin' => 'Admin',
            'superadmin' => 'Super admin',
        ];

        $rows = DB::table('sessions')
            ->join('users', 'users.id', '=', 'sessions.user_id')
            ->whereNotNull('sessions.user_id')
            ->whereIn('users.role', ['admin', 'staff', 'superadmin'])
            ->where('sessions.last_activity', '>=', $cutoff)
            ->groupBy('users.id', 'users.name', 'users.role')
            ->selectRaw('users.id as id, users.name as name, users.role as role, MAX(sessions.last_activity) as last_activity')
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($row) use ($roleLabels) {
                $ts = (int) $row->last_activity;

                return (object) [
                    'id' => (int) $row->id,
                    'name' => (string) $row->name,
                    'role' => (string) $row->role,
                    'role_label' => $roleLabels[$row->role] ?? ucfirst((string) $row->role),
                    'last_activity' => $ts,
                    'last_seen' => Carbon::createFromTimestamp($ts)->diffForHumans(),
                ];
            });

        return [
            'sessionsUnavailable' => false,
            'rows' => $rows,
        ];
    }

    public function render()
    {
        $data = $this->staffWithActiveSessions();

        return view('livewire.admin.staff-on-shift', [
            'sessionsUnavailable' => $data['sessionsUnavailable'],
            'rows' => $data['rows'],
            'currentUserId' => auth()->id(),
        ]);
    }
}
