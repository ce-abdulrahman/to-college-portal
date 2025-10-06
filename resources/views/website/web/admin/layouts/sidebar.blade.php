<!-- Drawer/Sidebar -->
<div class="drawer-overlay" onclick="closeDrawer()"></div>
<div class="drawer">
    <div class="drawer-header">
        <div class="drawer-logo">
            <div class="logo-icon">
                <i class="bi bi-calculator"></i>
            </div>
            <div class="company-info">
                <h3 class="company-name">سیستەمی زانکۆلاین</h3>
                <p class="company-tagline">بەڕێوەبردنی کاروبار</p>
            </div>
        </div>
    </div>

    <div class="drawer-content">
        <nav class="drawer-nav">
            @php
                function navActive($route)
                {
                    return request()->routeIs($route) ? 'active' : '';
                }
            @endphp

            <a href="{{ route('admin.dashboard') }}" class="drawer-nav-item {{ navActive('admin.dashboard') }}">
                <i class="bi bi-house"></i><span>ماڵەوە</span>
            </a>


            <a href="{{ route('admin.systems.index') }}" class="drawer-nav-item {{ navActive('admin.systems.index') }}">
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
                <i class="bi bi-archive"></i>
                <span>بەشەکانی</span>
            </a>

            <a href="{{ route('admin.students.index') }}"
                class="drawer-nav-item back-btn {{ navActive('admin.students.index') }}">
                <i class="bi bi-users"></i>
                <span>قوتابیان</span>
            </a>

            <a href="{{ route('admin.results.index') }}"
                class="drawer-nav-item back-btn {{ navActive('admin.results.index') }}">
                <i class="bi bi-users"></i>
                <span>ئەنجامی هەڵبژاردنەکانی قوتابیان</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="drawer-nav-item back-btn {{ navActive('admin.users.index') }}">
                <i class="bi bi-users"></i>
                <span>بەکارهێنەر</span>
            </a>
        </nav>
    </div>

    <div class="drawer-footer">
        <button class="drawer-btn change-password-btn"
            onclick="window.location.href='{{ route('admin.users.edit', auth()->user()->id) }}'">
            <i class="bi bi-person"></i>
            <span>پرۆفایل</span>
        </button>
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
            window.location.href = 'admin/dashboard'; // Redirect to a default page if no history
        }
    }
</script>
