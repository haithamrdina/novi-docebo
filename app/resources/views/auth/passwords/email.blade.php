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
                                        <img src="{{ asset('static/logo/logo.webp')}}" height="150" alt="">
                                    </a>
                                </div>
                                @if (session('status'))
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <div class="d-flex">
                                            <div>
                                                <i class="ti ti-check-box"></i>
                                            </div>
                                            <div>
                                                &nbsp;{{ session('status') }}
                                            </div>
                                        </div>
                                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                                    </div>
                                @endif
                                <h2 class="h2 text-center mb-4">{{ __('Reset Password') }}</h2>
                                <form action="{{ route('password.email') }}" method="post" autocomplete="off" novalidate>
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Email address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" placeholder="your@email.com" autocomplete="off">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-primary w-100"> {{ __('Send Password Reset Link') }}</button>
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
@stop
