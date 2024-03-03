@extends('master')
@section('title')
    Reset Password
@endsection
@section('novi_css')
@stop
@section('body')
    <div class="page page-center">
        <div class="container container-normal py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg">
                    <div class="container-tight">
                        <div class="">
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <a href="{{ route('login') }}" class="navbar-brand navbar-brand-autodark">
                                        <img src="{{ asset('static/logo/logo.png')}}" height="80" alt="">
                                    </a>
                                </div>
                                <h2 class="h2 text-center mb-4"> {{ __('Reset Password') }}</h2>
                                <form method="post" action="{{ route('password.update') }}" autocomplete="off" novalidate>
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Email Address') }}</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" placeholder="your@email.com" required autocomplete="off" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Password</label>
                                        <div class="input-group input-group-flat">
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Password" autocomplete="off">
                                            <span class="input-group-text">
                                                <a href="javascript:void(0)" class="link-secondary" onclick="togglePasswordVisibility()">
                                                    <span id="showPasswordIcon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <circle cx="12" cy="12" r="2" />
                                                            <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </span>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Confirm Password</label>
                                        <div class="input-group input-group-flat">
                                            <input type="password" name="password_confirmation"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                placeholder="Password" autocomplete="off">
                                            <span class="input-group-text">
                                                <a href="javascript:void(0)" class="link-secondary" onclick="toggleConfirmPasswordVisibility()">
                                                    <span id="showConfirmPasswordIcon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <circle cx="12" cy="12" r="2" />
                                                            <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </span>
                                            @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-primary w-100">{{ __('Reset Password') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg d-none d-lg-block">
                    <img src="{{ asset('static/background/bg.svg') }}" class="d-block mx-auto h-100" alt="">
                </div>
            </div>
        </div>
    </div>
@stop()
@section('novi_libs')
@stop
@section('novi_js')
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.querySelector('input[name="password"]');
            var icon = document.querySelector('#showPasswordIcon');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.innerHTML =
                `
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"></path>
                        <path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"></path>
                        <path d="M3 3l18 18"></path>
                    </svg>
                `
                ;
            } else {
                passwordInput.type = "password";
                icon.innerHTML =
                `
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="2" />
                        <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                    </svg>
                `
                ;
            }
        }
        function toggleConfirmPasswordVisibility() {
            var passwordInput = document.querySelector('input[name="password_confirmation"]');
            var icon = document.querySelector('#showConfirmPasswordIcon');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.innerHTML =
                `
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"></path>
                        <path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"></path>
                        <path d="M3 3l18 18"></path>
                    </svg>
                `
                ;
            } else {
                passwordInput.type = "password";
                icon.innerHTML =
                `
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="2" />
                        <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                    </svg>
                `
                ;
            }
        }
    </script>
@stop
