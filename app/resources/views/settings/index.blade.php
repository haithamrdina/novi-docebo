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
                        <form action="{{ route('settings.update') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <h2 class="mb-4">USERFIELDS</h2>
                                <h3 class="card-title mt-4">LIST</h3>
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>DOCEBO ID</th>
                                                <th>DOCEBO NAME</th>
                                                <th>NOVI CMS NAME</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($doceboUserFields as $docebokey => $doceboField)
                                            <tr>
                                                <td>
                                                    {{ $docebokey }}
                                                </td>
                                                <td>
                                                    {{ $doceboField }}
                                                </td>
                                                <td>
                                                    <select class="form-select" name="{{ $docebokey }}" type ="text" id="{{ $docebokey }}" value="">
                                                        <option value="{{ null }}">Your Novi Field</option>
                                                        @foreach($noviMemberFields as $noviField)
                                                            <option value="{{ $noviField }}" {{ config('userfields.'.$docebokey) ==  $noviField  ? 'selected' : ''}}>{{ $noviField }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
<script src="{{ asset('dist/libs/tom-select/dist/js/tom-select.base.min.js') }}" defer></script>
@stop
@section('novi_js')
@foreach($doceboUserFields as $docebokey => $doceboField)
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var el;
            window.TomSelect && (new TomSelect(el = document.getElementById({{ $docebokey }}), {
                copyClassesToDropdown: false,
                dropdownParent: 'body',
                controlInput: '<input>',
                render:{
                    item: function(data,escape) {
                        if( data.customProperties ){
                            return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                        }
                        return '<div>' + escape(data.text) + '</div>';
                    },
                    option: function(data,escape){
                        if( data.customProperties ){
                            return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                        }
                        return '<div>' + escape(data.text) + '</div>';
                    },
                },
            }));
        });
    </script>
@endforeach
@stop
