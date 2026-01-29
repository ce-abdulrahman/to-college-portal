<ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
    {{-- Admin Navigation --}}
    @if (auth()->check() && auth()->user()->role === 'admin')
        <li class="nav-item">
            <a class="nav-link custom-nav-link {{ navActive('admin.dashboard') }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid-fill me-1"></i> ماڵەوە
            </a>
        </li>

        {{-- General Data Dropdown --}}
        @php
            $isGeneralDataActive =
                request()->routeIs('admin.systems.index') ||
                request()->routeIs('admin.provinces.index') ||
                request()->routeIs('admin.universities.index') ||
                request()->routeIs('admin.colleges.index') ||
                request()->routeIs('admin.departments.index');
        @endphp
        <li class="nav-item dropdown">
            <a class="nav-link custom-nav-link dropdown-toggle {{ $isGeneralDataActive ? 'active' : '' }}" href="#"
                id="navbarDropdownGeneral" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-database me-1"></i> داتا سەرەکییەکان
            </a>
            <ul class="dropdown-menu text-center shadow-lg border-0 rounded-4 animate slideIn"
                aria-labelledby="navbarDropdownGeneral">
                <li><a class="dropdown-item rounded-2 {{ navActive('admin.systems.index') }}"
                        href="{{ route('admin.systems.index') }}">سیستەمەکان</a></li>
                <li><a class="dropdown-item rounded-2 {{ navActive('admin.provinces.index') }}"
                        href="{{ route('admin.provinces.index') }}">پارێزگاکان</a></li>
                <li><a class="dropdown-item rounded-2 {{ navActive('admin.universities.index') }}"
                        href="{{ route('admin.universities.index') }}">زانکۆکان</a></li>
                <li><a class="dropdown-item rounded-2 {{ navActive('admin.colleges.index') }}"
                        href="{{ route('admin.colleges.index') }}">کۆلێژەکان</a></li>
                <li><a class="dropdown-item rounded-2 {{ navActive('admin.departments.index') }}"
                        href="{{ route('admin.departments.index') }}">بەشەکانی</a></li>
            </ul>
        </li>

        @php
            $isMBTIActive =
                request()->routeIs('admin.mbti.questions.index') || request()->routeIs('admin.mbti.results.index');
        @endphp
        <li class="nav-item dropdown">
            <a class="nav-link custom-nav-link dropdown-toggle {{ $isMBTIActive ? 'active' : '' }}" href="#"
                id="navbarDropdownMBTI" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bar-chart me-1"></i> داتا سەرەکییەکان
            </a>
            <ul class="dropdown-menu text-center shadow-lg border-0 rounded-4 animate slideIn" aria-labelledby="navbarDropdownMBTI">
                <li><a class="dropdown-item rounded-2 {{ navActive('admin.mbti.questions.index') }}"
                        href="{{ route('admin.mbti.questions.index') }}">پرسیاری MBTI</a></li>
                <li><a class="dropdown-item rounded-2 {{ navActive('admin.mbti.results.index') }}"
                        href="{{ route('admin.mbti.results.index') }}">ئەنجامی MBTI</a></li>
            </ul>
        </li>

        {{-- Requests --}}
        @php
            $pendingCount = \App\Models\RequestMoreDepartments::where('status', 'pending')->count();
        @endphp
        <li class="nav-item">
            <a class="nav-link custom-nav-link position-relative {{ navActive('admin.requests.index') }}"
                href="{{ route('admin.requests.index') }}">
                <i class="bi bi-bell me-1"></i> پرسەکانی بەشەکانی
                @if ($pendingCount > 0)
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm border border-white">
                        {{ $pendingCount }}
                        <span class="visually-hidden">unread messages</span>
                    </span>
                @endif
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link custom-nav-link {{ navActive('admin.backups.index') }}"
                href="{{ route('admin.backups.index') }}">
                <i class="bi bi-cloud-arrow-up me-1"></i> Backup
            </a>
        </li>

        {{-- Users Dropdown --}}
        @php
            $isUsersActive =
                request()->routeIs('admin.centers.index') ||
                request()->routeIs('admin.teachers.index') ||
                request()->routeIs('admin.students.index') ||
                request()->routeIs('admin.users.index');
        @endphp
        <li class="nav-item dropdown">
            <a class="nav-link custom-nav-link dropdown-toggle {{ $isUsersActive ? 'active' : '' }}" href="#"
                id="navbarDropdownUsers" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-people me-1"></i> بەکارهێنەرەکان
            </a>
            <ul class="dropdown-menu text-center shadow-lg border-0 rounded-4 animate slideIn"
                aria-labelledby="navbarDropdownUsers">
                <li><a class="dropdown-item rounded-2 {{ navActive('admin.centers.index') }}"
                        href="{{ route('admin.centers.index') }}">سەنتەرەکان</a></li>
                <li><a class="dropdown-item rounded-2 {{ navActive('admin.teachers.index') }}"
                        href="{{ route('admin.teachers.index') }}">مامۆستایەکان</a></li>
                <li><a class="dropdown-item rounded-2 {{ navActive('admin.students.index') }}"
                        href="{{ route('admin.students.index') }}">قوتابیان</a></li>
                <li><a class="dropdown-item rounded-2 {{ navActive('admin.users.index') }}"
                        href="{{ route('admin.users.index') }}">بەکارهێنەر</a></li>
            </ul>
        </li>
    @endif

    {{-- Center Navigation --}}
    @if (auth()->check() && auth()->user()->role === 'center')
        <li class="nav-item">
            <a class="nav-link custom-nav-link {{ navActive('center.dashboard') }}"
                href="{{ route('center.dashboard') }}">ماڵەوە</a>
        </li>
        <li class="nav-item">
            <a class="nav-link custom-nav-link {{ navActive('center.departments.index') }}"
                href="{{ route('center.departments.index') }}">بەشەکانی</a>
        </li>
        <li class="nav-item">
            <a class="nav-link custom-nav-link {{ navActive('center.teachers.index') }}"
                href="{{ route('center.teachers.index') }}">مامۆستاکانم</a>
        </li>
        <li class="nav-item">
            <a class="nav-link custom-nav-link {{ navActive('center.students.index') }}"
                href="{{ route('center.students.index') }}">قوتابیەکانم</a>
        </li>
    @endif

    {{-- Teacher Navigation --}}
    @if (auth()->check() && auth()->user()->role === 'teacher')
        <li class="nav-item">
            <a class="nav-link custom-nav-link {{ navActive('teacher.dashboard') }}"
                href="{{ route('teacher.dashboard') }}">ماڵەوە</a>
        </li>
        <li class="nav-item">
            <a class="nav-link custom-nav-link {{ navActive('teacher.departments.index') }}"
                href="{{ route('teacher.departments.index') }}">بەشەکانی</a>
        </li>
        <li class="nav-item">
            <a class="nav-link custom-nav-link {{ navActive('teacher.students.index') }}"
                href="{{ route('teacher.students.index') }}">قوتابیەکانم</a>
        </li>
    @endif

    {{-- Student Navigation --}}
    @if (auth()->check() && auth()->user()->role === 'student')
        <li class="nav-item">
            <a class="nav-link custom-nav-link {{ navActive('student.dashboard') }}"
                href="{{ route('student.dashboard') }}">
                <i class="bi bi-house-door me-1"></i> ماڵەوە
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link custom-nav-link {{ navActive('student.mbti.index') }}"
                href="{{ route('student.mbti.index') }}">
                <i class="bi bi-puzzle me-1"></i> MBTI
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link custom-nav-link {{ navActive('student.departments.selection') }}"
                href="{{ route('student.departments.selection') }}">
                <i class="fas fa-university me-1"></i> هەڵبژاردنی بەش
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link custom-nav-link {{ navActive('student.departments.request-more') }}"
                href="{{ route('student.departments.request-more') }}">
                <i class="fas fa-history me-1"></i> مێژووی داواکاری
            </a>
        </li>
    @endif
</ul>

<style>
    /* Navigation Styles */
    .custom-nav-link {
        font-weight: 500;
        padding: 0.6rem 1rem !important;
        margin: 0 0.15rem;
        border-radius: 50rem;
        /* Pill shape */
        transition: all 0.2s ease-in-out;
        color: #555;
    }

    .custom-nav-link:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.08);
        color: var(--bs-primary);
        transform: translateY(-1px);
    }

    .custom-nav-link.active {
        background-color: rgba(var(--bs-primary-rgb), 0.12);
        color: var(--bs-primary) !important;
        font-weight: 600;
    }

    /* Dropdown Animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate.slideIn {
        animation: slideIn 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    /* Profile Pill */
    .profile-pill {
        transition: all 0.2s ease;
    }

    .profile-pill:hover {
        background-color: #f8f9fa !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
    }

    /* Dark Mode Adjustments */
    [data-bs-theme="dark"] .custom-nav-link {
        color: #ddd;
    }

    [data-bs-theme="dark"] .custom-nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    [data-bs-theme="dark"] .custom-nav-link.active {
        background-color: rgba(var(--bs-primary-rgb), 0.25);
        color: #6ea8fe !important;
    }

    [data-bs-theme="dark"] .profile-pill {
        background-color: #2b3035 !important;
        border-color: #373b3e !important;
    }

    [data-bs-theme="dark"] .profile-pill .text-dark {
        color: #e9ecef !important;
    }
</style>

<script>
    // Sync desktop theme icon with current theme
    (function() {
        const theme = localStorage.getItem('theme') || 'light';
        const icon = document.getElementById('themeIconDesktop');
        if (icon) {
            updateIcon(icon, theme === 'dark');
        }
    })();

    function updateIcon(icon, isDark) {
        if (isDark) {
            icon.classList.remove('bi-moon');
            icon.classList.add('bi-sun');
        } else {
            icon.classList.remove('bi-sun');
            icon.classList.add('bi-moon');
        }
    }

    // Override toggleTheme for desktop icon update
    const originalToggleTheme = window.toggleTheme;
    window.toggleTheme = function() {
        if (typeof originalToggleTheme === 'function') originalToggleTheme();

        // Update desktop icon specifically
        const theme = document.documentElement.getAttribute('data-bs-theme');
        const icon = document.getElementById('themeIconDesktop');
        if (icon) {
            updateIcon(icon, theme === 'dark');
        }
    }
</script>
