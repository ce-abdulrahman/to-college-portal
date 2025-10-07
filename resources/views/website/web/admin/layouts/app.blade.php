<!DOCTYPE html>
<html lang="ku" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>زانکۆلاین</title>

    <link rel="icon" href="{{ asset('assets/admin/images/favicon.png') }}" type="image/x-icon" />

    {{-- Core CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- DataTables v2 (Vanilla / no jQuery) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">

    {{-- App CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/nav.css') }}">

    {{-- Optional per-page <head> extras --}}
    @stack('head-scripts')
</head>

<body class="d-flex flex-column min-vh-100">

    @include('layouts.toasts')


    @include('website.web.admin.layouts.header')

    <main class="flex-grow-1 container mt-4">
        @yield('content')
    </main>

    @include('website.web.admin.layouts.footer')

</body>

<!-- ======================== JavaScript Libraries ======================== -->

<!-- jQuery (must be loaded before DataTables v1 style plugins) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>

<!-- Chart.js (optional for dashboard) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- ======================== Your App Scripts ======================== -->
<script src="{{ asset('assets/admin/js/app-core.js') }}"></script>
<script src="{{ asset('assets/admin/js/table-kit.js') }}"></script>
<script src="{{ asset('assets/admin/js/dept-filters.js') }}"></script>
<script src="{{ asset('assets/admin/js/forms-validate.js') }}"></script>
<script src="{{ asset('assets/admin/js/drawer.js') }}"></script>

@stack('scripts')

<script>
    // Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        document.querySelectorAll('.toast').forEach(el => new bootstrap.Toast(el).show());
    });
</script>


</html>
