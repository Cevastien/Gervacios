<?php

namespace App\Livewire\Admin;

use App\Models\AdminLog;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogs extends Component
{
    use WithPagination;

    #[Url]
    public string $type = 'all';

    #[Url]
    public string $q = '';

    #[Url]
    public string $sort = 'time_desc';

    public function mount(): void
    {
        if (! in_array($this->type, ['all', 'admin', 'staff'], true)) {
            $this->type = 'all';
        }
        $allowed = ['time_desc', 'time_asc', 'action_asc', 'action_desc', 'user_asc', 'user_desc'];
        if (! in_array($this->sort, $allowed, true)) {
            $this->sort = 'time_desc';
        }
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function updatedQ(): void
    {
        $this->resetPage();
    }

    public function setType(string $value): void
    {
        if (! in_array($value, ['all', 'admin', 'staff'], true)) {
            return;
        }
        $this->type = $value;
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->q = '';
        $this->resetPage();
    }

    public function render()
    {
        $type = in_array($this->type, ['all', 'admin', 'staff'], true) ? $this->type : 'all';
        $q = trim($this->q);
        if (mb_strlen($q) > 200) {
            $q = mb_substr($q, 0, 200);
        }
        $sort = $this->sort;
        $allowedSorts = ['time_desc', 'time_asc', 'action_asc', 'action_desc', 'user_asc', 'user_desc'];
        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'time_desc';
        }

        $query = AdminLog::query()->with('user');

        if ($type === 'admin') {
            $query->whereHas('user', fn ($uq) => $uq->whereIn('role', ['admin', 'superadmin']));
        } elseif ($type === 'staff') {
            $query->whereHas('user', fn ($uq) => $uq->where('role', 'staff'));
        }

        if ($q !== '') {
            $like = '%'.addcslashes($q, '%_\\').'%';
            $query->where(function ($sub) use ($like) {
                $sub->where('admin_logs.action', 'like', $like)
                    ->orWhere('admin_logs.details', 'like', $like)
                    ->orWhere('admin_logs.target_type', 'like', $like)
                    ->orWhere('admin_logs.ip_address', 'like', $like)
                    ->orWhereHas('user', function ($uq) use ($like) {
                        $uq->where('name', 'like', $like)
                            ->orWhere('email', 'like', $like);
                    });
            });
        }

        if (in_array($sort, ['user_asc', 'user_desc'], true)) {
            $dir = $sort === 'user_asc' ? 'asc' : 'desc';
            $query->join('users as log_users', 'log_users.id', '=', 'admin_logs.user_id')
                ->select('admin_logs.*')
                ->orderBy('log_users.name', $dir)
                ->orderBy('admin_logs.created_at', 'desc');
        } else {
            switch ($sort) {
                case 'time_asc':
                    $query->orderBy('admin_logs.created_at', 'asc');
                    break;
                case 'action_asc':
                    $query->orderBy('admin_logs.action', 'asc')->orderBy('admin_logs.created_at', 'desc');
                    break;
                case 'action_desc':
                    $query->orderBy('admin_logs.action', 'desc')->orderBy('admin_logs.created_at', 'desc');
                    break;
                default:
                    $query->orderBy('admin_logs.created_at', 'desc');
            }
        }

        $logs = $query->paginate(10);

        return view('livewire.admin.activity-logs', [
            'logs' => $logs,
            'filterType' => $type,
            'filterQ' => $q,
            'filterSort' => $sort,
        ]);
    }
}
