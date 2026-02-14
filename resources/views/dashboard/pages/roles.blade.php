@extends('dashboard.layouts.app')

@section('title', __('Roles'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4">@lang('Roles Management')</h4>

    <livewire:dashboard.roles />
</div>
@endsection