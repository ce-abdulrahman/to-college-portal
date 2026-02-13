<nav class="navbar navbar-expand-lg navbar-glass sticky-top mb-3">
    @php
        $siteName = $appSettings['site_name'] ?? 'بۆ کۆلێژ';
        $siteLogo = $appSettings['site_logo'] ?? 'images/logo.png';
    @endphp

    <div class="container-fluid px-4 d-flex justify-content-between align-items-center">

        {{-- 1. Left Section: Logo & Mobile Toggle --}}
        <div class="d-flex align-items-center">
            <button class="btn btn-link text-primary p-0 me-3 d-lg-none" onclick="toggleDrawer()">
                <i class="bi bi-list fs-1"></i>
            </button>
            <a class="navbar-brand d-flex align-items-center" href="#">
                <div class="logo-container bg-white rounded-3 shadow-sm p-1 d-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px;">
                    <img src="{{ asset($siteLogo) }}" alt="Logo" class="img-fluid"
                        style="max-height: 35px;">
                </div>
                <span class="ms-3 d-none d-md-inline fw-bolder text-gradient fs-4 tracking-tight p-2">{{ $siteName }}</span>
            </a>
        </div>

        {{-- 2. Center Section: Navigation Links --}}
        <div class="d-none d-lg-block">
            @include('website.web.admin.layouts.header-nav')
        </div>

        {{-- 3. Right Section: Profile & Theme Toggle --}}
        <div class="d-flex align-items-center gap-3">
            @if (auth()->check())
                {{-- Theme Toggle --}}
                <div class="">
                    <button
                        class="btn btn-light rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center"
                        onclick="toggleTheme()" title="Toggle Theme" style="width: 40px; height: 40px;">
                        <i class="bi bi-moon fs-5" id="themeIconDesktop"></i>
                    </button>
                </div>

                {{-- Profile Dropdown --}}
                <div class="dropdown">
                    <a class="nav-link p-0 profile-dropdown-toggle" href="#" id="navbarDropdownProfile"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div
                            class="d-flex align-items-center bg-white rounded-pill p-1 ps-3 shadow-sm border border-light-subtle profile-pill">
                            <span class="fw-bold text-dark small me-2">{{ auth()->user()->name }}</span>
                            <div class="header-avatar bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 38px; height: 38px;">
                                <i class="bi bi-person-fill fs-5"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-left text-center shadow-lg border-0 rounded-4 mt-2 animate slideIn"
                        aria-labelledby="navbarDropdownProfile" style="min-width: 220px;">
                        <li class="px-3 py-2 text-center bg-light-subtle rounded-top-4 mb-2">
                            <small class="text-uppercase fw-bold text-muted letter-spacing-1">
                                @if (auth()->user()->role == 'admin')
                                    بەرێوبەر
                                @elseif (auth()->user()->role == 'center')
                                    سەنتەر
                                @elseif (auth()->user()->role == 'teacher')
                                    مامۆستا
                                @elseif (auth()->user()->role == 'student')
                                    قوتابی
                                @endif
                            </small>
                        </li>

                        @if (auth()->user()->role === 'admin')
                            <li><a class="dropdown-item px-3 py-2"
                                    href="{{ route('admin.users.edit', auth()->user()->id) }}">
                                    <i class="bi bi-person-gear me-2"></i> پرۆفایل</a></li>
                        @elseif (auth()->user()->role === 'center')
                            <li><a class="dropdown-item px-3 py-2"
                                    href="{{ route('center.profile.edit', auth()->user()->id) }}">
                                    <i class="bi bi-person-gear me-2"></i> پرۆفایل</a></li>
                        @elseif (auth()->user()->role === 'teacher')
                            <li><a class="dropdown-item px-3 py-2"
                                    href="{{ route('teacher.profile.edit', auth()->user()->id) }}">
                                    <i class="bi bi-person-gear me-2"></i> پرۆفایل</a></li>
                        @elseif (auth()->user()->role === 'student')
                            <li><a class="dropdown-item px-3 py-2"
                                    href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-gear me-2"></i> پرۆفایل</a></li>
                        @endif

                        @if (auth()->user()->role === 'admin')
                            <li><a class="dropdown-item px-3 py-2"
                                    href="{{ route('admin.settings.index', auth()->user()->id) }}">
                                    <i class="bi bi-gear me-2"></i> ڕێکخستنەکان</a></li>
                        @endif
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        {{--  <button class="drawer-btn theme-toggle-btn" onclick="toggleTheme()">
                            <i class="bi bi-moon" id="themeIcon"></i>
                            <span id="themeText" class="text-black">Dark Mode</span>
                        </button>
                        <li>
                            <hr class="dropdown-divider">
                        </li>  --}}
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button class="dropdown-item text-danger text-center px-3 py-2 fw-medium"
                                    type="submit">
                                    <i class="bi bi-box-arrow-right me-2"></i> دەرچوون
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endif
        </div>

    </div>
</nav>

<style>
    .navbar-glass {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    [data-bs-theme="dark"] .navbar-glass {
        background: rgba(18, 18, 18, 0.85) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .text-gradient {
        background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .tracking-tight {
        letter-spacing: -0.5px;
    }
</style>

@include('website.web.admin.layouts.sidebar')
