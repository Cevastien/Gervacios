<?php

namespace App\Livewire\Admin;

use App\Services\AutomationSettings;
use Livewire\Component;

class PeakHoursStatus extends Component
{
    public function render()
    {
        return view('livewire.admin.peak-hours-status', [
            'd' => AutomationSettings::queuePeakDiagnostics(),
        ]);
    }
}
