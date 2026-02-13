<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo ">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-3">Styling</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <li class="menu-item {{ isActiveRoute('dashboard.dashboard') }}">
            <a href="{{ route('dashboard.dashboard') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-smart-home"></i>
                <div data-i18n="@lang('Dashboard')">@lang('Dashboard')</div>
            </a>
        </li>

        @php
        // Check if admin is Super Admin
        $isSuperAdmin = false;

        if (auth('admin')->check()) {
            $admin = auth('admin')->user();

            // Always treat admin@admin.com as Super Admin (first admin)
            if ($admin->email === 'admin@admin.com') {
                $isSuperAdmin = true;
            } elseif (method_exists($admin, 'hasRole')) {
                // Check via Spatie method
                $isSuperAdmin = $admin->hasRole('Super Admin');
            } else {
                // Fallback: check directly in database
                $isSuperAdmin = \DB::table('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('model_has_roles.model_id', $admin->id)
                    ->where('model_has_roles.model_type', 'App\Models\Admin')
                    ->where('roles.name', 'Super Admin')
                    ->exists();
            }
        }
        @endphp

        @if($isSuperAdmin || auth('admin')->user()->can('view users'))
        <li class="menu-item {{ isActiveRoute('dashboard.users.*') }}">
            <a href="{{ route('dashboard.users.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-users"></i>
                <div data-i18n="@lang('Users')">@lang('Users')</div>
            </a>
        </li>
        @endif

        @if($isSuperAdmin || auth('admin')->user()->can('view admins'))
        <li class="menu-item {{ isActiveRoute('dashboard.admins.*') }}">
            <a href="{{ route('dashboard.admins.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-shield"></i>
                <div data-i18n="@lang('Admins')">@lang('Admins')</div>
            </a>
        </li>
        @endif

        @if($isSuperAdmin || auth('admin')->user()->canany(['view roles', 'view permissions', 'assign roles']))
        <li class="menu-item {{ isActiveRoute(['dashboard.roles.*', 'dashboard.admin_roles.*', 'dashboard.permissions.*']) }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-lock"></i>
                <div data-i18n="@lang('Permissions')">@lang('Permissions')</div>
            </a>
            <ul class="menu-sub">
                @if($isSuperAdmin || auth('admin')->user()->can('view roles'))
                <li class="menu-item {{ isActiveRoute('dashboard.roles.*') }}">
                    <a href="{{ route('dashboard.roles.index') }}" class="menu-link">
                        <div data-i18n="@lang('Roles')">@lang('Roles')</div>
                    </a>
                </li>
                @endif
                @if($isSuperAdmin || auth('admin')->user()->can('view permissions'))
                <li class="menu-item {{ isActiveRoute('dashboard.permissions.*') }}">
                    <a href="{{ route('dashboard.permissions.index') }}" class="menu-link">
                        <div data-i18n="@lang('Permissions')">@lang('Permissions')</div>
                    </a>
                </li>
                @endif
                @if($isSuperAdmin || auth('admin')->user()->can('assign roles'))
                <li class="menu-item {{ isActiveRoute('dashboard.admin_roles.*') }}">
                    <a href="{{ route('dashboard.admin_roles.index') }}" class="menu-link">
                        <div data-i18n="@lang('Assign Roles')">@lang('Assign Roles')</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if($isSuperAdmin || auth('admin')->user()->can('view fashion_styles'))
        <li class="menu-item {{ isActiveRoute('dashboard.fashion_styles.*') }}">
            <a href="{{ route('dashboard.fashion_styles.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-shirt"></i>
                <div data-i18n="@lang('FashionStyle')">@lang('FashionStyle')</div>
            </a>
        </li>
        @endif

        @if($isSuperAdmin || auth('admin')->user()->can('view banners'))
        <li class="menu-item {{ isActiveRoute('dashboard.banners.*') }}">
            <a href="{{ route('dashboard.banners.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-photo"></i>
                <div data-i18n="@lang('Banners')">@lang('Banners')</div>
            </a>
        </li>
        @endif

        @if($isSuperAdmin || auth('admin')->user()->can('view stylists'))
        <li class="menu-item {{ isActiveRoute('dashboard.stylists.*') }}">
            <a href="{{ route('dashboard.stylists.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-users"></i>
                <div data-i18n="@lang('Stylists')">@lang('Stylists')</div>
            </a>
        </li>
        @endif

        @if($isSuperAdmin || auth('admin')->user()->can('view stylist_features'))
        <li class="menu-item {{ isActiveRoute('dashboard.stylist_features.*') }}">
            <a href="{{ route('dashboard.stylist_features.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-star"></i>
                <div data-i18n="@lang('Stylist_features')">@lang('Stylist_features')</div>
            </a>
        </li>
        @endif

        @if($isSuperAdmin || auth('admin')->user()->can('view stylist_images'))
        <li class="menu-item {{ isActiveRoute('dashboard.stylist_images.*') }}">
            <a href="{{ route('dashboard.stylist_images.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-photo-plus"></i>
                <div data-i18n="@lang('Stylist_images')">@lang('Stylist_images')</div>
            </a>
        </li>
        @endif

        @if($isSuperAdmin || auth('admin')->user()->can('view stylist_services'))
        <li class="menu-item {{ isActiveRoute('dashboard.stylist_services.*') }}">
            <a href="{{ route('dashboard.stylist_services.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-scissors"></i>
                <div data-i18n="@lang('Stylist_services')">@lang('Stylist_services')</div>
            </a>
        </li>
        @endif

        @if($isSuperAdmin || auth('admin')->user()->can('view stylist_reviews'))
        <li class="menu-item {{ isActiveRoute('dashboard.stylist_reviews.*') }}">
            <a href="{{ route('dashboard.stylist_reviews.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-message"></i>
                <div data-i18n="@lang('Stylist_reviews')">@lang('Stylist_reviews')</div>
            </a>
        </li>
        @endif

    </ul>
</aside>
