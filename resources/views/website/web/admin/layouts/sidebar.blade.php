<link rel="stylesheet" href="{{ asset('assets/admin/css/nav.css') }}">
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
                    @if(auth()->user()->role == 'admin')
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

    @php
        if (!function_exists('navActive')) {
            function navActive($route)
            {
                return request()->routeIs($route) ? 'active' : '';
            }
        }
    @endphp
    <div class="drawer-content">
        @if (auth()->check() && auth()->user()->role === 'admin')
            <nav class="drawer-nav">

                <a href="{{ route('admin.dashboard') }}" class="drawer-nav-item {{ navActive('admin.dashboard') }}">
                    <i class="bi bi-house"></i><span>ماڵەوە</span>
                </a>


                <a href="{{ route('admin.systems.index') }}"
                    class="drawer-nav-item {{ navActive('admin.systems.index') }}">
                    <i class="bi bi-building"></i>
                    <span>سیستەمەکان</span>
                </a>
                <a href="{{ route('admin.provinces.index') }}"
                    class="drawer-nav-item {{ navActive('admin.provinces.index') }}">
                    <i class="bi bi-geo-alt"></i>
                    <span>پارێزگاکان</span>
                </a>
                <a href="{{ route('admin.universities.index') }}"
                    class="drawer-nav-item {{ navActive('admin.universities.index') }}">
                    <i class="bi bi-mortarboard"></i>
                    <span>زانکۆکان</span>
                </a>
                <a href="{{ route('admin.colleges.index') }}"
                    class="drawer-nav-item {{ navActive('admin.colleges.index') }}">
                    <i class="bi bi-bank"></i>
                    <span>کۆلێژەکان</span>
                </a>
                <a href="{{ route('admin.departments.index') }}"
                    class="drawer-nav-item {{ navActive('admin.departments.index') }}">
                    <i class="fa-regular fa-building"></i>
                    <span>بەشەکانی</span>
                </a>

                <a href="{{ route('admin.centers.index') }}"
                    class="drawer-nav-item back-btn {{ navActive('admin.centers.index') }}">
                    <i class="fa-regular fa-id-card"></i>
                    <span>سەنتەرەکان</span>
                </a>

                <a href="{{ route('admin.teachers.index') }}"
                    class="drawer-nav-item back-btn {{ navActive('admin.teachers.index') }}">
                    <i class="fa-regular fa-id-card"></i>
                    <span>مامۆستایەکان</span>
                </a>

                <a href="{{ route('admin.students.index') }}"
                    class="drawer-nav-item back-btn {{ navActive('admin.students.index') }}">
                    <i class="fa-regular fa-id-card"></i>
                    <span>قوتابیان</span>
                </a>

                <a href="{{ route('admin.results.index') }}"
                    class="drawer-nav-item back-btn {{ navActive('admin.results.index') }}">
                    <i class="fa-solid fa-receipt"></i>
                    <span>ئەنجامی هەڵبژاردنەکانی قوتابیان</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="drawer-nav-item back-btn {{ navActive('admin.users.index') }}">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>بەکارهێنەر</span>
                </a>
            </nav>
        @endif

        @if (auth()->check() && auth()->user()->role === 'center')
            <a href="{{ route('center.dashboard') }}" class="drawer-nav-item {{ navActive('center.dashboard') }}">
                <i class="bi bi-house"></i><span>ماڵەوە</span>
            </a>
            <a href="{{ route('center.departments.index') }}"
                class="drawer-nav-item {{ navActive('center.departments.index') }}">
                <i class="fa-regular fa-building"></i>
                <span>بەشەکانی</span>
            </a>
            <a href="{{ route('center.teachers.index') }}"
                class="drawer-nav-item {{ navActive('center.teachers.index') }}">
                <i class="fa fa-users"></i>
                <span>مامۆستاکانم</span>
            </a>
            <a href="{{ route('center.students.index') }}"
                class="drawer-nav-item {{ navActive('center.students.index') }}">
                <i class="fa fa-users"></i>
                <span>قوتابیەکانم</span>
            </a>
        @endif

        @if (auth()->check() && auth()->user()->role === 'teacher')
            <a href="{{ route('teacher.dashboard') }}" class="drawer-nav-item {{ navActive('admin.dashboard') }}">
                <i class="bi bi-house"></i><span>ماڵەوە</span>
            </a>
            <a href="{{ route('teacher.departments.index') }}"
                class="drawer-nav-item {{ navActive('teacher.departments.index') }}">
                <i class="fa-regular fa-building"></i>
                <span>بەشەکانی</span>
            </a>
            <a href="{{ route('teacher.students.index') }}"
                class="drawer-nav-item {{ navActive('teacher.students.index') }}">
                <i class="fa-regular fa-users"></i>
                <span>قوتابیەکانم</span>
            </a>
        @endif

    </div>

    <div class="drawer-footer">
        @if (auth()->check() && auth()->user()->role === 'admin')
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
        @endif


        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="drawer-btn logout-btn w-100" type="submit">
                <i class="bi bi-box-arrow-right"></i><span>دەرچوون</span>
            </button>
        </form>

    </div>
</div>


<script>
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
