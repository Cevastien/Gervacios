<?php

namespace App\Livewire\Admin;

use App\Http\Controllers\Admin\SeatingLayoutController;
use Livewire\Component;

class DashboardSeatMap extends Component
{
    /**
     * What a plain click on a seat marker does: edit dots | waitlist table | table status modal.
     */
    public string $seatClickMode = 'edit';

    public function setSeatClickMode(string $mode): void
    {
        if (!in_array($mode, ['edit', 'waitlist', 'table'], true)) {
            return;
        }

        $this->seatClickMode = $mode;

        $this->dispatch('table-selected', tableId: null);
        $this->dispatch('table-ops-select', tableId: null);
    }

    public function render()
    {
        return view('livewire.admin.dashboard-seat-map', array_merge(
            SeatingLayoutController::layoutData(),
            ['seatClickMode' => $this->seatClickMode]
        ));
    }
}
