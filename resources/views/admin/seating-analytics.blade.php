@extends('layouts.admin')

@section('page_title', 'Seating analytics')

@section('panel_heading')
    <x-admin-panel-heading title="Seating analytics" />
@endsection

@section('content')
    <div class="mx-auto w-full max-w-7xl">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @livewire('admin.seating-analytics')
    </div>
@endsection
