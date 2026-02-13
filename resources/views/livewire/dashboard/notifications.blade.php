<div class="dropdown-notifications" wire:poll.30s="loadNotifications">
    <!-- Notification Bell -->
    <button class="btn btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow position-relative"
            wire:click="toggleDropdown"
            type="button"
            aria-expanded="{{ $isOpen ? 'true' : 'false' }}">
        <i class="ti ti-bell ti-md"></i>
        @if($unreadCount > 0)
            <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle badge-notifications">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Menu -->
    @if($isOpen)
        <div class="dropdown-menu dropdown-menu-end show notification-dropdown p-0" style="width: 380px; max-height: 500px;">
            <!-- Header -->
            <div class="dropdown-header d-flex align-items-center justify-content-between p-3 border-bottom">
                <h6 class="mb-0">@lang('Notifications')</h6>
                @if($unreadCount > 0)
                    <button class="btn btn-sm btn-link text-primary p-0" wire:click="markAllAsRead">
                        @lang('Mark all as read')
                    </button>
                @endif
            </div>

            <!-- Notifications List -->
            <div class="notification-list" style="max-height: 350px; overflow-y: auto;">
                @forelse($notifications as $notification)
                    <div class="dropdown-item p-3 border-bottom {{ is_null($notification['read_at']) ? 'bg-label-light-primary' : '' }}">
                        <div class="d-flex align-items-start">
                            <!-- Icon -->
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-sm {{ $notification['type_bg'] ?? 'bg-label-info' }}">
                                    <span class="avatar-initial rounded-circle">
                                        <i class="ti {{ $notification['type_icon'] ?? 'ti-info-circle' }}"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 {{ is_null($notification['read_at']) ? 'fw-semibold' : '' }}">
                                            {{ $notification['title'] }}
                                        </h6>
                                        <p class="mb-1 text-muted small">{{ $notification['message'] }}</p>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}</small>
                                    </div>

                                    <!-- Actions -->
                                    <div class="dropdown ms-2">
                                        <button class="btn btn-icon btn-sm btn-text-secondary" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if(is_null($notification['read_at']))
                                                <li>
                                                    <button class="dropdown-item" wire:click="markAsRead({{ $notification['id'] }})">
                                                        <i class="ti ti-check me-2"></i>@lang('Mark as read')
                                                    </button>
                                                </li>
                                            @endif
                                            <li>
                                                <button class="dropdown-item text-danger" wire:click="deleteNotification({{ $notification['id'] }})" wire:confirm="@lang('Are you sure?')">
                                                    <i class="ti ti-trash me-2"></i>@lang('Delete')
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Click to view link -->
                        @if($notification['link'])
                            <div class="mt-2">
                                <a href="{{ $notification['link'] }}" wire:click.prevent="markAsRead({{ $notification['id'] }})" class="btn btn-sm btn-primary w-100">
                                    @lang('View Details')
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center p-5">
                        <div class="mb-3">
                            <i class="ti ti-bell-off ti-3x text-muted"></i>
                        </div>
                        <h6 class="text-muted">@lang('No notifications yet')</h6>
                        <p class="text-muted small mb-0">@lang('You\'ll see notifications here when they arrive.')</p>
                    </div>
                @endforelse
            </div>

            <!-- Footer -->
            @if(count($notifications) > 0)
                <div class="dropdown-footer p-2 text-center border-top">
                    <a href="#" class="text-primary small">@lang('View all notifications')</a>
                </div>
            @endif
        </div>

        <!-- Click outside to close -->
        <div class="dropdown-backdrop" wire:click="closeDropdown" style="position: fixed; inset: 0; z-index: 999;"></div>
    @endif
</div>

@push('styles')
<style>
    .dropdown-notifications {
        position: relative;
    }
    .notification-dropdown {
        position: absolute;
        right: 0;
        top: 100%;
        z-index: 1000;
        margin-top: 0.5rem;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .notification-dropdown .dropdown-item:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .badge-notifications {
        font-size: 0.65rem;
        padding: 0.25em 0.5em;
        transform: translate(-60%, -30%) !important;
    }
    .notification-list::-webkit-scrollbar {
        width: 6px;
    }
    .notification-list::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .notification-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
</style>
@endpush
