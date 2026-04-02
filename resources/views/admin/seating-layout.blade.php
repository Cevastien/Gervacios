@extends('layouts.admin')

@section('page_title', 'Layout Editor')

@section('panel_heading')
    <x-admin-panel-heading title="Layout Editor" />
@endsection

@push('scripts')
    @vite(['resources/js/seating-layout.js'])
@endpush

@section('content')
    {{-- Match Auto Table shell: top padding under header + horizontal inset (same rhythm as seat map) --}}
    <div
        class="seating-full-editor-shell -mt-1 flex min-h-0 flex-1 flex-col overflow-hidden bg-panel-canvas pt-2 pl-4 md:-mt-0 md:pt-3 md:pl-5 lg:pl-6 -mx-5 -mb-6 md:-mx-6 md:-mb-8">
        @include('admin.partials.seating-map-inner-styles')
        @include('admin.partials.seating-map-inner', [
            'dashboardEmbed' => false,
            'fullEditor' => true,
            'showToolbar' => false,
        ])
        </div>

        @include('admin.partials.seat-focus-mode-script')
@endsection