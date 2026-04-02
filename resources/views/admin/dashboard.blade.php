@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('panel_heading')
    <x-admin-panel-heading title="Dashboard" />
@endsection

@section('content')
    <div class="admin-dashboard-animate mx-auto w-full max-w-7xl">
        <div class="grid min-h-0 grid-cols-1 gap-5 xl:grid-cols-12 xl:items-stretch xl:gap-6">

            {{-- Primary column: metrics + charts --}}
            <div class="min-w-0 space-y-5 xl:col-span-8">
                @if (auth()->user()->isAdmin())
                    @livewire('admin.summary-bar')
                @endif
            </div>

            {{-- Secondary column: live status + staff --}}
            <aside class="flex min-w-0 flex-col gap-5 xl:col-span-4">
                @if (auth()->user()->isAdmin())
                    @livewire('admin.queue-activity-feed')
                @endif
                @if (auth()->user()->isAdmin())
                    @livewire('admin.staff-on-shift')
                @endif
            </aside>

        </div>
    </div>
@endsection