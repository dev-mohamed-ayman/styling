@extends('dashboard.layouts.app')

@section('title', __('Permissions'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4">@lang('Permissions Management')</h4>

    <livewire:dashboard.permissions />
</div>
@endsection