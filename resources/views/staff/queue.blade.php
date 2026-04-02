@extends('layouts.admin')

@section('page_title', 'Register walk-in')

@section('panel_heading')
    <x-admin-panel-heading title="Register walk-in" />
@endsection

@section('content')
    <div
        class="staff-queue-shell flex min-h-0 flex-1 flex-col overflow-hidden bg-panel-canvas -mx-5 -mb-6 md:-mx-6 md:-mb-8">
        @livewire('staff-walk-in-queue')
    </div>
@endsection
