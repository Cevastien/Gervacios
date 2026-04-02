@extends('layouts.admin')

@section('page_title', 'Activity logs')

@section('panel_heading')
    <x-admin-panel-heading title="Activity logs" />
@endsection

@section('content')
    <div class="mx-auto w-full max-w-7xl">
        @livewire('admin.activity-logs')
    </div>
@endsection
