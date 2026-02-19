<!DOCTYPE html>
<html lang="ku" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $appSettings['site_name'] ?? 'زانکۆلاین' }}</title>

    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon" />
    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Core CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- DataTables v2 --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- Summernote CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/summernote-lite.min.css" rel="stylesheet">

    {{-- App CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    @stack('styles')

    @php
        $fontKu = $appSettings['font_ku'] ?? null;
        $fontAr = $appSettings['font_ar'] ?? null;
        $fontEn = $appSettings['font_en'] ?? null;
    @endphp
    @if ($fontKu || $fontAr || $fontEn)
        <style>
            @if ($fontKu)
                @font-face {
                    font-family: 'CustomKu';
                    src: url('{{ asset($fontKu) }}');
                    font-display: swap;
                }
                html[lang="ku"] body { font-family: 'CustomKu', 'NizarNastaliqKurdish', sans-serif; }
            @endif
            @if ($fontAr)
                @font-face {
                    font-family: 'CustomAr';
                    src: url('{{ asset($fontAr) }}');
                    font-display: swap;
                }
                html[lang="ar"] body { font-family: 'CustomAr', 'Wafeq', sans-serif; }
            @endif
            @if ($fontEn)
                @font-face {
                    font-family: 'CustomEn';
                    src: url('{{ asset($fontEn) }}');
                    font-display: swap;
                }
                html[lang="en"] body { font-family: 'CustomEn', 'PatuaOne', sans-serif; }
            @endif
        </style>
    @endif

    @livewireStyles
    @stack('head-scripts')
</head>

<body class="d-flex flex-column min-vh-100" data-page="@yield('page_name', 'default')" data-view="@yield('view_name', 'index')">
    @php
        if (!function_exists('navActive')) {
            function navActive($route)
            {
                return request()->routeIs($route) ? 'active' : '';
            }
        }
    @endphp

    @include('layouts.toasts')
    @include('website.web.admin.layouts.header')

    <main class="flex-grow-1 @yield('main_container_class', 'container') mt-4">
        @yield('content')
    </main>

    @include('website.web.admin.layouts.footer')

    {{-- JavaScript Libraries --}}
    {{--  <script src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>  --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Summernote JS --}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/summernote-lite.min.js" defer></script>

    {{-- Dashboard Script --}}
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>

    @livewireScripts
    @stack('scripts')

</body>

</html>
