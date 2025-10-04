<!DOCTYPE html>
<html lang="ku" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>زانکۆلاین</title>

    <link rel="icon" href="{{ asset('assets/admin/images/favicon.png') }}" type="image/x-icon" />

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <!-- DataTables v2 (Vanilla JS) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" />

    <!-- Your styles -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/nav.css') }}" />
</head>

<body>

    {{-- Toast container (top-end) --}}
    <div class="position-fixed top-0 end-0 p-3" style="z-index:1080">
        @if (session('success'))
            <div class="toast align-items-center text-bg-success border-0 shadow-lg" role="alert"
                data-bs-delay="3500">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="toast align-items-center text-bg-danger border-0 shadow-lg" role="alert" data-bs-delay="4500">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="toast align-items-center text-bg-warning border-0 shadow-lg" role="alert"
                data-bs-delay="6000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        هەڵە هەیە: {{ implode(' | ', $errors->all()) }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
    </div>

    @include('website.web.admin.layouts.header')

    <main class="container mt-4">
        @yield('content')
    </main>

    {{-- Footer هەبێت ئەگەر پێویستە --}}
    {{-- @include('website.web.admin.layouts.footer') --}}

    <!-- JS (Bootstrap bundle first) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables v2 (Vanilla JS only — no jQuery) -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>

    @stack('scripts')

    <script>
        // Enable tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });

        // Auto-show any toasts present
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.toast').forEach(el => new bootstrap.Toast(el).show());
        });
    </script>
</body>

</html>
