@extends('dashboard.layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="card-title text-white mb-1">@lang('Welcome back, :name!', ['name' => auth('admin')->user()->name])</h4>
                        <p class="mb-0">@lang('Here is what\'s happening with your platform today.')</p>
                    </div>
                    <div class="flex-shrink-0">
                        <img src="{{ asset('assets/img/illustrations/OIP.jpeg') }}" alt="Welcome" class="img-fluid" style="max-height: 140px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Users Card -->
        <div class="col-sm-6 col-lg-4">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="statistics-icon bg-label-primary">
                            <i class="ti ti-users ti-lg"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0">{{ $statistics['users_count'] }}</h2>
                            <span class="text-muted">@lang('Total Users')</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('dashboard.users.index') }}" class="btn btn-sm btn-primary">
                            @lang('View All') <i class="ti ti-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stylists Card -->
        <div class="col-sm-6 col-lg-4">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="statistics-icon bg-label-success">
                            <i class="ti ti-scissors ti-lg"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0">{{ $statistics['stylists_count'] }}</h2>
                            <span class="text-muted">@lang('Total Stylists')</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('dashboard.stylists.index') }}" class="btn btn-sm btn-success">
                            @lang('View All') <i class="ti ti-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admins Card -->
        <div class="col-sm-6 col-lg-4">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="statistics-icon bg-label-warning">
                            <i class="ti ti-shield ti-lg"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0">{{ $statistics['admins_count'] }}</h2>
                            <span class="text-muted">@lang('Total Admins')</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('dashboard.admins.index') }}" class="btn btn-sm btn-warning">
                            @lang('View All') <i class="ti ti-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Illustrations -->
    <div class="row g-4">
        <!-- Quick Actions -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">@lang('Quick Actions')</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <a href="{{ route('dashboard.users.index') }}" class="quick-action-card d-flex align-items-center p-3 rounded bg-label-primary">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-user-plus ti-xl text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">@lang('Manage Users')</h6>
                                    <small class="text-muted">@lang('Add, edit or remove users')</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('dashboard.stylists.index') }}" class="quick-action-card d-flex align-items-center p-3 rounded bg-label-success">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-scissors ti-xl text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">@lang('Manage Stylists')</h6>
                                    <small class="text-muted">@lang('Add, edit or remove stylists')</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('dashboard.banners.index') }}" class="quick-action-card d-flex align-items-center p-3 rounded bg-label-info">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-photo ti-xl text-info"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">@lang('Manage Banners')</h6>
                                    <small class="text-muted">@lang('Update homepage banners')</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('dashboard.roles.index') }}" class="quick-action-card d-flex align-items-center p-3 rounded bg-label-warning">
                                <div class="flex-shrink-0">
                                    <i class="ti ti-lock ti-xl text-warning"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">@lang('Permissions')</h6>
                                    <small class="text-muted">@lang('Manage roles & permissions')</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Illustration Card -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <img src="{{ asset('assets/img/illustrations/girl-doing-yoga-light.png') }}" alt="Statistics" class="img-fluid mb-3" style="max-height: 200px;">
                    <h5 class="card-title">@lang('Platform Overview')</h5>
                    <p class="text-muted mb-0">@lang('Your platform is growing! Keep managing your users and stylists to ensure the best experience.')</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-statistics {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }
    .card-statistics:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    .statistics-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .quick-action-card {
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    .quick-action-card:hover {
        transform: translateX(5px);
        border-color: currentColor;
    }
</style>
@endpush
