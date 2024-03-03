@php
    $layoutData['cssClasses'] =  'navbar navbar-expand-md navbar-overlap d-print-none bg-primary-lt';
@endphp
<div class="page">
    <!-- Top Navbar -->
    @include('partials.navbar.overlap-topbar')
    <div class="page-wrapper">
        <!-- Page Content -->
        @yield('content')
        @include('partials.footer.bottom')
    </div>
</div>
