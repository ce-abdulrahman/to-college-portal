<nav class="drawer-nav">

            <a href="{{ route('center.dashboard') }}" class="drawer-nav-item {{ navActive('center.dashboard') }}">
                <i class="bi bi-house"></i><span>ماڵەوە</span>
            </a>
            <a href="{{ route('center.departments.index') }}"
                class="drawer-nav-item {{ navActive('center.departments.index') }}">
                <i class="fa-solid fa-building"></i>
                <span>بەشەکانی</span>
            </a>
            <a href="{{ route('center.teachers.index') }}"
                class="drawer-nav-item {{ navActive('center.teachers.index') }}">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>مامۆستاکانم</span>
            </a>
            <a href="{{ route('center.students.index') }}"
                class="drawer-nav-item {{ navActive('center.students.index') }}">
                <i class="fa-solid fa-user-graduate"></i>
                <span>قوتابیەکانم</span>
            </a>
        </nav>