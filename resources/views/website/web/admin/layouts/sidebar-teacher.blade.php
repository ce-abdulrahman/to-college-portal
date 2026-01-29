<nav class="drawer-nav">

            <a href="{{ route('teacher.dashboard') }}" class="drawer-nav-item {{ navActive('admin.dashboard') }}">
                <i class="bi bi-house"></i><span>ماڵەوە</span>
            </a>
            <a href="{{ route('teacher.departments.index') }}"
                class="drawer-nav-item {{ navActive('teacher.departments.index') }}">
                <i class="fa-solid fa-building"></i>
                <span>بەشەکانی</span>
            </a>
            <a href="{{ route('teacher.students.index') }}"
                class="drawer-nav-item {{ navActive('teacher.students.index') }}">
                <i class="fa-solid fa-user-graduate"></i>
                <span>قوتابیەکانم</span>
            </a>
        </nav>