@extends('master')
@section('novi_css')
@stop
@section('header')
    @include('partials.navbar.overlap-topbar')
@stop
@section('page-header')
    <div class="page-header d-print-none text-white">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Settings
                    </h2>
                    <div class="page-pretitle">
                        American Academy of Implant Dentistry
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="row g-0">
                    <div class="col-3 d-none d-md-block border-end">
                        <div class="card-body">
                            <h4 class="subheader">Userfields setting</h4>
                            <div class="list-group list-group-transparent">
                                <a href="{{ route('settings.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('settings.index') ? 'active' : '' }}">Userfields</a>
                            </div>
                            <h4 class="subheader mt-4">Connected Apps Setting</h4>
                            <div class="list-group list-group-transparent">
                                <a href="{{ route('settings.docebo.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('settings.docebo.index') ? 'active' : '' }}">Docebo
                                    LMS</a>
                                <a href="{{ route('settings.novi.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('settings.novi.index') ? 'active' : '' }}">Novi
                                    CMS</a>
                            </div>
                        </div>
                    </div>
                    <div class="col d-flex flex-column">
                        <form action="{{ route('settings.docebo.update') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <h2 class="mb-4">DOCEBO LMS</h2>
                                <h3 class="card-title mt-4">Endpoint</h3>
                                <div class="row g-3">
                                    <div class="col-md">
                                        <input type="text" name="docebo-endpoint" class="form-control" value="{{ config('docebo.endpoint') }}">
                                    </div>
                                </div>
                                <h3 class="card-title mt-4">Username</h3>
                                <div>
                                    <div class="row g-3">
                                        <div class="col-md">
                                            <input type="text" name="docebo-username" class="form-control" value="{{ config('docebo.username') }}">
                                        </div>
                                    </div>
                                </div>
                                <h3 class="card-title mt-4">Password</h3>
                                <div>
                                    <div class="row g-3">
                                        <div class="col-md">
                                            <div class="input-group input-group-flat">
                                                <input type="password" id="docebo-password" name="docebo-password" class="form-control" placeholder="Your password" value="{{ config('docebo.password') }}" autocomplete="off">
                                                <span class="input-group-text">
                                                    <a href="#" class="link-secondary"
                                                        onclick="toggleDoceboPasswordVisibility()">
                                                        <span id="docebo-showPasswordIcon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <circle cx="12" cy="12" r="2" />
                                                                <path
                                                                    d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent mt-auto">
                                <div class="btn-list justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('footer')
    @include('partials.footer.bottom')
@stop
@section('novi_libs')
@stop
@section('novi_js')
    <script>
        function toggleDoceboPasswordVisibility() {
            var passwordInput = document.querySelector('#docebo-password');
            var icon = document.querySelector('#docebo-showPasswordIcon');

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
