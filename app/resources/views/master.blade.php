<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('static/logo/favicon.png') }}">
    {{-- Custom Meta Tags --}}
    @yield('meta_tags')
    {{-- Title --}}
    <title> @yield('title') - @yield('title_prefix', config('system.title_prefix', 'Ideo Read')) </title>
    <!-- CSS files -->
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    @yield('novi_css')
    <link href="{{ asset('dist/css/demo.min.css') }}" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body @if (isset($bodyClass)) class="{{ $bodyClass }}" @endif
    @if (isset($bodyStyle)) style="{{ $bodyStyle }}" @endif>
    <script src="{{ asset('dist/js/demo-theme.min.js') }}"></script>
    @unless (request()->routeIs('login') ||
            request()->routeIs('register') ||
            request()->routeIs('password.request') ||
            request()->routeIs('password.reset'))
    <div class="page">
        <!-- Top Navbar -->
        @yield('header')
        <div class="page-wrapper">
            <!-- Page Content -->
            @yield('page-header')
            @yield('page-content')
            @yield('footer')
        </div>
    </div>
    @else
        @yield('body')
    @endunless @yield('novi_libs')
    <script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>
    <script src="{{ asset('dist/js/demo.min.js') }}" defer></script>
    @yield('novi_js')
</body>

</html>
