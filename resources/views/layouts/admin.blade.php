<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'e-NSAH')) - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        @include('partials.sidebar')
        <div class="main-content flex-grow-1">
            @include('partials.navbar')
            <div class="page-content">
                @include('partials.alerts')
                @hasSection('page-header')
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">@yield('page-header')</h4>
                        @yield('page-actions')
                    </div>
                @endif
                @yield('content')
            </div>
            @include('partials.footer')
        </div>
    </div>
    <div class="sidebar-overlay d-none d-md-none" onclick="toggleSidebar()"></div>
    @stack('scripts')
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
            document.querySelector('.sidebar-overlay').classList.toggle('d-none');
        }
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.alert-dismissible').forEach(function(el) {
                    var bsAlert = new bootstrap.Alert(el);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
