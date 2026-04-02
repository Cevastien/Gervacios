@extends('layouts.admin')

@section('page_title', 'Menu')

@section('panel_heading')
    <x-admin-panel-heading title="Menu" />
@endsection

@section('content')
    <div class="mx-auto w-full min-w-0 max-w-7xl">
        @livewire('admin.menu-manager')
    </div>
@endsection
