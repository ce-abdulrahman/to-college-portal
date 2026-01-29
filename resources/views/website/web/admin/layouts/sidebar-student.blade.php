<nav class="drawer-nav">

            <a href="{{ route('student.dashboard') }}" class="drawer-nav-item {{ navActive('admin.dashboard') }}">
                <i class="bi bi-house"></i><span>ماڵەوە</span>
            </a>
            <a href="{{ route('student.mbti.index') }}" class="drawer-nav-item {{ navActive('student.mbti.index') }}">
                <i class="fa-solid fa-id-card"></i>
                <span>MBTI</span>
            </a>
            <a href="{{ route('student.departments.selection') }}"
                class="drawer-nav-item {{ navActive('student.departments.selection') }}">
                <i class="fa-solid fa-id-card"></i>
                <span>هەڵبژاردنی بەش</span>
            </a>
            <a href="{{ route('student.departments.request-more') }}"
                class="drawer-nav-item {{ navActive('student.departments.request-more') }}">
                <i class="fa-solid fa-id-card"></i>
                <span>مێژووی داواکاری بۆ بەش</span>
            </a>
        </nav>