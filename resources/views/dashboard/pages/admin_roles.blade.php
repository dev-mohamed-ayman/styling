@extends('dashboard.layouts.app')

@section('title', __('Admin Roles'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4">@lang('Admin Roles Assignment')</h4>

    <livewire:dashboard.admin-roles />
</div>
@endsection