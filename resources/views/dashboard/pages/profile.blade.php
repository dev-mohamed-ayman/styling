@extends('dashboard.layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card ">
                <div class="card-header">
                    <h1 class="card-title m-0">@lang('Profile')</h1>
                </div>
                <form action="{{route('dashboard.update-profile')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-4">
                                <label for="name" class="form-label">@lang('Name')</label>
                                <input id="name" type="text" name="name" value="{{old('name', admin()->name)}}"
                                       class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                <code>{{$message}}</code>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="email" class="form-label">@lang('Email')</label>
                                <input id="email" type="email" name="email" value="{{old('email', admin()->email)}}"
                                       class="form-control @error('email') is-invalid @enderror">
                                @error('name')
                                <code>{{$message}}</code>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="username" class="form-label">@lang('Username')</label>
                                <input id="username" type="text" name="username"
                                       value="{{old('username', admin()->username)}}"
                                       class="form-control @error('username') is-invalid @enderror">
                                @error('username')
                                <code>{{$message}}</code>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title m-0">@lang('Password')</h1>
                </div>
                <form action="{{route('dashboard.update-password')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-4">
                                <label for="old_password" class="form-label">@lang('Old Password')</label>
                                <input id="old_password" type="password" name="old_password"
                                       placeholder="@lang('Old Password')"
                                       class="form-control @error('old_password') is-invalid @enderror">
                                @error('old_password')
                                <code>{{$message}}</code>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">@lang('New Password')</label>
                                <input id="password" type="password" name="password" placeholder="@lang('New Password')"
                                       class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                <code>{{$message}}</code>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation"
                                       class="form-label">@lang('Confirmation Password')</label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                       placeholder="@lang('Confirmation Password')"
                                       class="form-control @error('password_confirmation') is-invalid @enderror">
                                @error('password_confirmation')
                                <code>{{$message}}</code>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
