<!DOCTYPE html>
<html lang="id" class="light-style" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) | SILAP</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @if (request()->routeIs('dashboard', 'users.*'))
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <aside class="layout-menu menu-vertical menu bg-menu-theme silap-sidebar" aria-label="Menu utama">
                    <a class="app-brand-link p-4" href="{{ route('dashboard') }}">
                        <i class="bx bxs-school text-primary fs-2 me-2" aria-hidden="true"></i>
                        <span class="h4 mb-0">SILAP</span>
                    </a>
                    <nav class="menu-inner py-2">
                        <div class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a class="menu-link" href="{{ route('dashboard') }}"><i class="menu-icon bx bx-home-circle" aria-hidden="true"></i>Dashboard</a>
                        </div>
                        @can('viewAny', \App\Models\User::class)
                            <div class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <a class="menu-link" href="{{ route('users.index') }}"><i class="menu-icon bx bx-user" aria-hidden="true"></i>Pengelolaan Akun</a>
                            </div>
                        @endcan
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('password.edit') }}"><i class="menu-icon bx bx-lock-alt" aria-hidden="true"></i>Ganti Password</a>
                        </div>
                    </nav>
                </aside>
                <div class="layout-page silap-layout-page">
                    <header class="container-xxl pt-3">
                        <div class="card">
                            <div class="card-body d-flex align-items-center justify-content-between gap-3 py-3">
                                <div><strong>{{ auth()->user()->name }}</strong><div class="small text-muted">{{ auth()->user()->username }}</div></div>
                                <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-secondary btn-sm" type="submit">Keluar</button></form>
                            </div>
                        </div>
                    </header>
                    <main class="container-xxl py-4">
                        @include('partials.feedback')
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
    @else
        <main class="container-xxl py-5 silap-shell">
            @include('partials.feedback')
            @yield('content')
        </main>
    @endif
</body>
</html>
