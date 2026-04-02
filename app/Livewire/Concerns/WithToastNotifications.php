<?php

namespace App\Livewire\Concerns;

/**
 * Dispatch browser toasts (handled in resources/js/toasts.js via Livewire notify event).
 */
trait WithToastNotifications
{
    protected function toastSuccess(string $message): void
    {
        $this->dispatch('notify', type: 'success', message: $message);
    }

    protected function toastError(string $message): void
    {
        $this->dispatch('notify', type: 'error', message: $message);
    }

    protected function toastWarning(string $message): void
    {
        $this->dispatch('notify', type: 'warning', message: $message);
    }

    protected function toastInfo(string $message): void
    {
        $this->dispatch('notify', type: 'info', message: $message);
    }
}
