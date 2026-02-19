<nav class="drawer-nav">

            <a href="{{ route('teacher.dashboard') }}" class="drawer-nav-item {{ navActive('teacher.dashboard') }}">
                <i class="bi bi-house"></i><span>ماڵەوە</span>
            </a>
            <a href="{{ route('teacher.departments.index') }}"
                class="drawer-nav-item {{ navActive('teacher.departments.index') }}">
                <i class="fa-solid fa-building"></i>
                <span>بەشەکانی</span>
            </a>
            <a href="{{ route('teacher.departments.compare-descriptions') }}"
                class="drawer-nav-item {{ navActive('teacher.departments.compare-descriptions') }}">
                <i class="fa-solid fa-code-compare"></i>
                <span>بەراوردی وەسف</span>
            </a>
            @if (auth()->user()?->teacher?->queue_hand_department)
                <a href="{{ route('teacher.queue-hand-departments.index') }}"
                    class="drawer-nav-item {{ navActive('teacher.queue-hand-departments.index') }}">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Queue Hand Department</span>
                </a>
            @endif
            <a href="{{ route('teacher.students.index') }}"
                class="drawer-nav-item {{ navActive('teacher.students.index') }}">
                <i class="fa-solid fa-user-graduate"></i>
                <span>قوتابیەکانم</span>
            </a>
            <a href="{{ route('teacher.features.request') }}"
                class="drawer-nav-item {{ navActive('teacher.features.request') }}">
                <i class="fa-solid fa-file-invoice me-1"></i>
                <span>داواکارییەکان</span>
            </a>
            
        </nav>
