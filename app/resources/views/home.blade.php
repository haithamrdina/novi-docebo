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
                        Home
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
        <div class="container-fluid">
            <div class="row row-cards">
                <div class="col-4">
                    <div class="card" style="height: 35rem">
                        <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                            <div class="divide-y">
                                @isset($doceboUsers)
                                    @foreach ($doceboUsers as $user)
                                        <div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="text-truncate">
                                                        <strong>{{ ucfirst($user['lastname']) }}
                                                            {{ ucfirst($user['firstname']) }}</strong>
                                                    </div>
                                                    <div class="text-muted">Username: {{ $user['username'] }} </div>
                                                </div>
                                                <div class="col-auto align-self-center">
                                                    <div class="badge bg-primary"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div style="height: 35rem">
                        <div class="col" style="height:12rem"></div>
                        <div class="col">
                            <div class="mb-3">
                                <div class="btn-group-vertical w-100" role="group">
                                    <a href="{{ route('home') }}" class="btn btn-primary w-100  mb-3">
                                        Retrieve users from docebo LMS
                                    </a>
                                    <a href="{{ route('home.verify') }}" class="btn btn-warning w-100  mb-3">
                                        Check your data with NOVI AMS
                                    </a>
                                    <a href="{{ route('home.sync') }}" class="btn btn-success w-100 {{ !isset($noviDoceboUsers) ? 'disabled' : '' }} mb-3">
                                        Synchronize your data
                                    </a>
                                    <a href="{{ route('home.empty') }}" class="btn btn-danger w-100  mb-3">
                                        Clear users fields data from docebo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="card" style="height: 35rem">
                        <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                            <div class="divide-y">
                                @isset($noviDoceboUsers)
                                    @foreach ($noviDoceboUsers as $user)
                                        <div>
                                            @if($user['status'] == "done")
                                                <div class="row bg-green-lt">
                                            @elseif($user['status'] == "exist")
                                                <div class="row bg-yellow-lt">
                                            @else
                                                <div class="row bg-red-lt">
                                            @endif
                                                <div class="col">
                                                    <div class="text-truncate">
                                                        <strong>{{ ucfirst($user['lastname']) }} {{ ucfirst($user['firstname']) }} - {{ $user['noviUuid'] != null ?  $user['noviUuid'] : '******' }} </strong>
                                                    </div>
                                                    <div class="text-muted">Username : {{ $user['username'] }} </div>
                                                </div>
                                                <div class="col-auto align-self-center">
                                                    @if($user['status'] == "done")
                                                        <div class="badge bg-green"></div>
                                                    @elseif($user['status'] == "exist")
                                                        <div class="badge bg-yellow"></div>
                                                    @else
                                                        <div class="badge bg-red"></div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endisset
                            </div>
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
    @stop
