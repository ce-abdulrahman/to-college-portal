<link rel="stylesheet" href="{{ asset('css/nav.css') }}">
<!-- Drawer/Sidebar -->
<div class="drawer-overlay" onclick="closeDrawer()"></div>
<div class="drawer">
    <div class="drawer-header">
        <div class="drawer-logo">
            <div class="logo-icon">
                <i class="bi bi-person"></i>
            </div>
            <div class="company-info">
                <h3 class="company-name">{{ auth()->user()->name }}</h3>
                <p class="company-tagline">
                    @if (auth()->user()->role == 'admin')
                        {{ 'بەرێوبەر' }}
                    @endif
                    @if (auth()->user()->role == 'center')
                        {{ 'سەنتەر' }}
                    @endif
                    @if (auth()->user()->role == 'teacher')
                        {{ 'مامۆستا' }}
                    @endif
                    @if (auth()->user()->role == 'student')
                        {{ 'قوتابی' }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- navActive helper moved to app.blade.php --}}
    <div class="drawer-content">
        @if (auth()->check() && auth()->user()->role === 'admin')
            @include('website.web.admin.layouts.sidebar-admin')
        @endif

        @if (auth()->check() && auth()->user()->role === 'center')
            @include('website.web.admin.layouts.sidebar-center')
        @endif

        @if (auth()->check() && auth()->user()->role === 'teacher')
            @include('website.web.admin.layouts.sidebar-teacher')
        @endif

        @if (auth()->check() && auth()->user()->role === 'student')
            @include('website.web.admin.layouts.sidebar-student')
        @endif

    </div>

    <div class="drawer-footer justify-content-center align-items-center">
        {{--  @if (auth()->check() && auth()->user()->role === 'admin')
            <button class="drawer-btn change-password-btn"
                onclick="window.location.href='{{ route('admin.users.edit', auth()->user()->id) }}'">
                <i class="bi bi-person"></i>
                <span>پرۆفایل</span>
            </button>
        @endif

        @if (auth()->check() && auth()->user()->role === 'center')
            <button class="drawer-btn change-password-btn"
                onclick="window.location.href='{{ route('center.profile.edit', auth()->user()->id) }}'">
                <i class="bi bi-person"></i>
                <span>پرۆفایل</span>
            </button>
        @endif

        @if (auth()->check() && auth()->user()->role === 'teacher')
            <button class="drawer-btn change-password-btn"
                onclick="window.location.href='{{ route('teacher.profile.edit', auth()->user()->id) }}'">
                <i class="bi bi-person"></i>
                <span>پرۆفایل</span>
            </button>
        @endif  --}}



        {{-- Theme Toggle --}}
        {{--  <button class="drawer-btn theme-toggle-btn" onclick="toggleTheme()">
            <i class="bi bi-moon" id="themeIcon"></i>
            <span id="themeText">Dark Mode</span>
        </button>  --}}

        {{--  <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="drawer-btn logout-btn w-100" type="submit">
                <i class="bi bi-box-arrow-right"></i><span>دەرچوون</span>
            </button>
        </form>  --}}

        @php
            $copyright = $appSettings['copyright'] ?? null;
            $socialAccounts = [];
            if (!empty($appSettings['social_accounts'])) {
                $socialAccounts = json_decode($appSettings['social_accounts'], true) ?: [];
            }
            if (empty($socialAccounts)) {
                $socialAccounts = [
                    ['name' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'url' => 'https://www.facebook.com/AghaAS7421'],
                    ['name' => 'Telegram', 'icon' => 'fab fa-telegram-plane', 'url' => 'https://t.me/AGHA_ACE'],
                    ['name' => 'Whatsapp', 'icon' => 'fab fa-whatsapp', 'url' => 'https://wa.me/9647504342452'],
                    ['name' => 'Instagram', 'icon' => 'fab fa-instagram', 'url' => 'https://www.instagram.com/agha_ace'],
                    ['name' => 'Viber', 'icon' => 'fab fa-viber', 'url' => 'viber://chat?number=9647504342452'],
                ];
            }
        @endphp

        @if ($copyright)
            <span class="text-light footer-text d-flex justify-content-center w-100 me mb-3 fx-text fx-extrude">
                {{ $copyright }}
            </span>
        @else
            <span class="text-light footer-text d-flex justify-content-center w-100 me mb-3 fx-text fx-extrude">
                ئەندازیار عبدالرحمن
            </span>
        @endif
        <div class="social-links d-flex gap-3 justify-content-center w-100">
            @foreach ($socialAccounts as $social)
                <a href="{{ $social['url'] ?? '#' }}" target="_blank" class="text-light" data-bs-toggle="tooltip"
                    title="{{ $social['name'] ?? '' }}">
                    <i class="{{ $social['icon'] ?? 'fa-solid fa-link' }}"></i>
                </a>
            @endforeach
        </div>
    </div>
</div>

<style>

</style>

<script>
    // Theme Management
    function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        setTheme(newTheme);
    }

    function setTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        try {
            localStorage.setItem('theme', theme);
        } catch (e) {
            // Storage might be blocked (e.g., Safari tracking prevention)
        }

        // Update Icon and Text
        const icon = document.getElementById('themeIcon');
        const text = document.getElementById('themeText');
        if (!icon || !text) return;

        if (theme === 'dark') {
            icon.classList.remove('bi-moon');
            icon.classList.add('bi-sun');
            text.textContent = 'Light Mode';
        } else {
            icon.classList.remove('bi-sun');
            icon.classList.add('bi-moon');
            text.textContent = 'Dark Mode';
        }
    }

    // Initialize Theme
    (function() {
        let savedTheme = 'light';
        try {
            savedTheme = localStorage.getItem('theme') || 'light';
        } catch (e) {
            // Ignore storage access errors
        }
        setTheme(savedTheme);
    })();

    // Drawer/Sidebar Functions
    function toggleDrawer() {
        const drawer = document.querySelector('.drawer');
        const overlay = document.querySelector('.drawer-overlay');

        if (drawer.classList.contains('active')) {
            closeDrawer();
        } else {
            openDrawer();
        }
    }

    function openDrawer() {
        const drawer = document.querySelector('.drawer');
        const overlay = document.querySelector('.drawer-overlay');
        document.body.classList.add('no-scroll');

        drawer.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        const drawer = document.querySelector('.drawer');
        const overlay = document.querySelector('.drawer-overlay');
        document.body.classList.remove('no-scroll');

        drawer.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    function goBack() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = '/dashboard'; // Redirect to a default page if no history
        }
    }
</script>
