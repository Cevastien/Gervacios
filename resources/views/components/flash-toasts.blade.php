{{-- Session flash → JS toasts (see resources/js/toasts.js). Livewire: $this->dispatch('notify', type: 'success', message: '…') --}}
@php
    $__flash = [
        'success' => session('success'),
        'error' => session('error'),
        'warning' => session('warning'),
        'info' => session('info'),
    ];
@endphp
<script type="application/json" id="app-flash-data">{!! json_encode($__flash) !!}</script>
