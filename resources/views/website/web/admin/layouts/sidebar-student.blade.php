<nav class="drawer-nav">

    <a href="{{ route('student.dashboard') }}" class="drawer-nav-item {{ navActive('student.dashboard') }}">
        <i class="bi bi-house-door"></i><span>ماڵەوە</span>
    </a>
    <a href="{{ route('student.mbti.index') }}" class="drawer-nav-item {{ navActive('student.mbti.index') }}">
        <i class="bi bi-puzzle"></i>
        <span>MBTI</span>
    </a>
    <a href="{{ route('student.ai-ranking.preferences') }}"
        class="drawer-nav-item {{ navActive('student.ai-ranking.*') }}">
        <i class="fas fa-robot"></i>
        <span>AI ڕێزبەندی</span>
    </a>
    <a href="{{ route('student.departments.selection') }}"
        class="drawer-nav-item {{ navActive('student.departments.selection') }}">
        <i class="fas fa-university"></i>
        <span>هەڵبژاردنی بەش</span>
    </a>
    <a href="{{ route('student.final-report') }}" class="drawer-nav-item {{ navActive('student.final-report') }}">
        <i class="fa-solid fa-file-invoice"></i>
        <span>لیستی کۆتایی</span>
    </a>
    <a href="{{ route('student.departments.request-more') }}"
        class="drawer-nav-item {{ navActive('student.departments.request-more') }}">
        <i class="fas fa-history"></i>
        <span>مێژووی داواکاری بۆ بەش</span>
    </a>
    
</nav>
