<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'University') }} - @yield('title', 'Authentication')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:70px;height:70px;border-radius:50%;background:var(--bs-primary-bg-subtle, #e7f1ff);border:3px solid var(--bs-primary, #0d6efd);">
                            <i class="fa fa-graduation-cap fa-3x text-primary"></i>
                        </span>
                    </div>
                    <h2 class="fw-bold text-primary mb-1">E-NSAH Hoceima</h2>
                    <p class="text-muted small mb-0">Academic Absence Management</p>
                </div>
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        @include('partials.alerts')
                        @yield('content')
                    </div>
                </div>
                <div class="text-center mt-3">
                    <p class="text-muted small">&copy; {{ date('Y') }} e-NSAH - Soufiane Devops</p>
                </div>
            </div>
        </div>
    </div>
    @yield('extra')
</body>
</html>
