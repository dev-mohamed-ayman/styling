<!doctype html>

<html lang="ar" class="layout-navbar-fixed layout-menu-fixed layout-compact " dir="rtl" data-skin="default"
      data-bs-theme="light" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta name="robots" content="noindex, nofollow"/>
    <title>{{config('app.name')}} @yield('title')</title>

    <meta name="description" content=""/>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}"/>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet"/>

{{--    <script>--}}
{{--        const color = localStorage.getItem('templateCustomizer-vertical-menu-template--Color')--}}
{{--        if (!color) {--}}
{{--            localStorage.setItem('templateCustomizer-vertical-menu-template--Color', '#EB3D63')--}}
{{--        }--}}
{{--    </script>--}}

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}"/>


    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}"/>

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}"/>

    <!-- Vendors CSS -->

    <link rel="stylesheet"
          href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}"/>


    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('assets/js/config.js') }}"></script>
    @livewireStyles
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar  ">
    <div class="layout-container">
        <!-- Menu -->

        @include('dashboard.layouts.sidebar')

        <div class="menu-mobile-toggler d-xl-none rounded-1">
            <a href="javascript:void(0);"
               class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
                <i class="ti tabler-menu icon-base"></i>
                <i class="ti tabler-chevron-right icon-base"></i>
            </a>
        </div>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

            @include('dashboard.layouts.navbar')

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    @yield('content')
                </div>
                <!-- / Content -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
</div>
<!-- / Layout wrapper -->

<!-- Core JS -->
<!-- build:js assets/vendor/js/theme.js  -->

<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>

<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

<!-- Main JS -->

<script src="{{ asset('assets/js/main.js') }}"></script>

<!-- Livewire Navigation Fix -->
<script>

</script>

<!-- Page JS -->

@include('dashboard.layouts.toast')
@livewireScripts
</body>

</html>
