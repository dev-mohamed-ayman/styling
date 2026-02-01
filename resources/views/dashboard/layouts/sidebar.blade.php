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

        <li class="menu-item {{ isActiveRoute('dashboard.user.*') }}">
            <a href="{{ route('dashboard.user.index') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-users"></i>
                <div data-i18n="@lang('Users')">@lang('Users')</div>
            </a>
        </li>

    </ul>
</aside>
