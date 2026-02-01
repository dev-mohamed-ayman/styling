<!doctype html>

<html
    lang="en"
    class=" layout-wide  customizer-hide"
    dir="ltr"
    data-skin="default"
    data-bs-theme="light"
    data-assets-path="/assets/"
    data-template="vertical-menu-template">
<head>
    <meta charset="utf-8"/>
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta name="robots" content="noindex, nofollow"/>
    <title>@lang('Login')</title>

    <meta name="description" content=""/>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon/favicon.ico')}}"/>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet"/>

    <link rel="stylesheet" href="{{asset('assets/vendor/fonts/iconify-icons.css')}}"/>

    <script src="{{asset('assets/vendor/libs/@algolia/autocomplete-js.js')}}"></script>

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/node-waves/node-waves.css')}}"/>

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/pickr/pickr-themes.css')}}"/>

    <link rel="stylesheet" href="{{asset('assets/vendor/css/core.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}"/>

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}"/>

    <!-- endbuild -->

    <!-- Vendor -->
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/form-validation.css')}}"/>

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}"/>

    <!-- Helpers -->
    <script src="{{asset('assets/vendor/js/helpers.js')}}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{asset('assets/vendor/js/template-customizer.js')}}"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{asset('assets/js/config.js')}}"></script>
</head>

<body>
<!-- Content -->

<div class="authentication-wrapper authentication-cover">
    <!-- Logo -->
{{--    <a href="index.html" class="app-brand auth-cover-brand">--}}
{{--        <span class="app-brand-logo demo">--}}
{{--          <span class="text-primary">--}}
{{--            <svg width="32" height="32" viewBox="0 0 32 22" fill="none"--}}
{{--                 xmlns="http://www.w3.org/2000/svg">--}}
{{--              <path--}}
{{--                  fill-rule="evenodd"--}}
{{--                  clip-rule="evenodd"--}}
{{--                  d="--}}
{{--                  M4 0--}}
{{--                  H14--}}
{{--                  C22 0 32 4.5 32 11--}}
{{--                  C32 17.5 22 22 14 22--}}
{{--                  H4--}}
{{--                  V0--}}
{{--                  Z--}}

{{--                  M9 4--}}
{{--                  V18--}}
{{--                  H14--}}
{{--                  C19.5 18 26 15 26 11--}}
{{--                  C26 7 19.5 4 14 4--}}
{{--                  H9--}}
{{--                  Z--}}
{{--                "--}}
{{--                  fill="currentColor"/>--}}
{{--            </svg>--}}

{{--          </span>--}}
{{--        </span>--}}
{{--        <span class="app-brand-text demo text-heading fw-bold">Darza</span>--}}
{{--    </a>--}}
    <!-- /Logo -->
    <div class="authentication-inner row m-0">
        <!-- /Left Text -->
        <div class="d-none d-xl-flex col-xl-8 p-0">
            <div class="auth-cover-bg d-flex justify-content-center align-items-center">
                <img
                    src="{{asset('assets/img/illustrations/auth-login-illustration-light.png')}}"
                    alt="auth-login-cover"
                    class="my-5 auth-illustration"
                    data-app-light-img="illustrations/auth-login-illustration-light.png"
                    data-app-dark-img="illustrations/auth-login-illustration-dark.png"/>
                <img
                    src="{{asset('assets/img/illustrations/bg-shape-image-light.png')}}"
                    alt="auth-login-cover"
                    class="platform-bg"
                    data-app-light-img="illustrations/bg-shape-image-light.png"
                    data-app-dark-img="illustrations/bg-shape-image-dark.png"/>
            </div>
        </div>
        <!-- /Left Text -->

        <!-- Login -->
        <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
            <div class="w-px-400 mx-auto mt-12 pt-5">
                <h4 class="mb-1">@lang('Welcome to') Styling! 👋</h4>
                <p class="mb-6">@lang('Please sign-in to your account and start the adventure')</p>

                <form class="mb-6" action="{{route('dashboard.login')}}" method="POST">
                    @csrf
                    <div class="mb-6 form-control-validation">
                        <label for="email" class="form-label">@lang('Email or Username')</label>
                        <input
                            type="text"
                            class="form-control @error('email_username') is-invalid @enderror"
                            id="email"
                            name="email_username"
                            value="{{old('email_username')}}"
                            placeholder="@lang('Enter your email or username')"
                            autofocus/>
                        @error('email_username')
                        <code>{{$message}}</code>
                        @enderror
                    </div>
                    <div class="mb-6 form-password-toggle form-control-validation">
                        <label class="form-label" for="password">@lang('Password')</label>
                        <div class="input-group input-group-merge">
                            <input
                                type="password"
                                id="password"
                                class="form-control"
                                name="password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password"/>
                            <span class="input-group-text cursor-pointer"><i
                                    class="icon-base ti tabler-eye-off"></i></span>
                        </div>
                    </div>
                    <div class="my-8">
                        <div class="d-flex justify-content-between">
                            <div class="form-check mb-0 ms-2">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember-me"/>
                                <label class="form-check-label" for="remember-me"> @lang('Remember Me') </label>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary d-grid w-100">Sign in</button>
                </form>
            </div>
        </div>
        <!-- /Login -->
    </div>
</div>

<!-- / Content -->

<!-- Core JS -->
<!-- build:js assets/vendor/js/theme.js  -->

<script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>

<script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{asset('assets/vendor/libs/node-waves/node-waves.js')}}"></script>

<script src="{{asset('assets/vendor/libs/pickr/pickr.js')}}"></script>

<script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

<script src="{{asset('assets/vendor/libs/hammer/hammer.js')}}"></script>

<script src="{{asset('assets/vendor/libs/i18n/i18n.js')}}"></script>

<script src="{{asset('assets/vendor/js/menu.js')}}"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{asset('assets/vendor/libs/@form-validation/popular.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/auto-focus.js')}}"></script>

<!-- Main JS -->

<script src="{{asset('assets/js/main.js')}}"></script>

<!-- Page JS -->
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
@include('dashboard.layouts.toast')
</body>
</html>
