@extends('website.web.admin.layouts.app')

@section('title', 'داشبۆردی مامۆستا')

@section('content')
    @php
        $teacher = $teacher ?? auth()->user()->teacher;
        $dashboard = $dashboard ?? [];

        $studentsCount = (int) ($dashboard['students_count'] ?? App\Models\Student::query()->where('referral_code', auth()->user()->rand_code)->count());
        $departmentsCount = (int) ($dashboard['departments_count'] ?? App\Models\Department::query()->where('status', 1)->count());

        $studentLimit = $dashboard['student_limit'] ?? $teacher?->limit_student;
        $studentRemaining = $dashboard['student_remaining'] ?? (is_null($studentLimit) ? null : max((int) $studentLimit - $studentsCount, 0));
        $studentUsagePercent = $dashboard['student_usage_percent'] ?? (is_null($studentLimit) || (int) $studentLimit === 0 ? null : min(100, (int) round(($studentsCount / (int) $studentLimit) * 100)));

        $features = $dashboard['features'] ?? [
            [
                'key' => 'ai_rank',
                'label' => 'ڕیزبەندی کرد بە زیرەکی دەستکرد',
                'icon' => 'fa-robot',
                'active' => (int) ($teacher?->ai_rank ?? 0) === 1,
            ],
            [
                'key' => 'gis',
                'label' => 'سەیرکردن بە نەخشە',
                'icon' => 'fa-map-location-dot',
                'active' => (int) ($teacher?->gis ?? 0) === 1,
            ],
            [
                'key' => 'all_departments',
                'label' => 'ڕێزبەندی ٥٠ بەش + بینینی پارێزگاکانی تر',
                'icon' => 'fa-layer-group',
                'active' => (int) ($teacher?->all_departments ?? 0) === 1,
            ],
            [
                'key' => 'queue_hand_department',
                'label' => 'ڕیزبەندی دەستی بەشەکان',
                'icon' => 'fa-list-ol',
                'active' => (int) ($teacher?->queue_hand_department ?? 0) === 1,
            ],
        ];

        $activeFeaturesCount = (int) ($dashboard['active_features_count'] ?? collect($features)->where('active', true)->count());
        $featuresCount = (int) ($dashboard['features_count'] ?? count($features));
        $missingFeatures = collect($features)->where('active', false)->values();
    @endphp

    <div class="container-fluid py-4 dashboard-simple-page dashboard-teacher">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">سەرەکی</li>
                        </ol>
                    </div>
                    <h4 class="page-title mb-0">
                        <i class="fas fa-chalkboard-user me-1"></i>
                        داشبۆردی مامۆستا
                    </h4>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden hero-card mb-4">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <span class="hero-kicker">
                            <i class="fa-solid fa-compass-drafting me-1"></i>
                            بەخێربێیت، {{ auth()->user()->name }}
                        </span>
                        <h2 class="hero-title mt-2 mb-3">بەڕێوەبردنی قوتابیەکانت بە ڕێکخستنێکی باشتر</h2>
                        <p class="hero-subtitle mb-0">
                            لە یەک شوێندا ژمارەی قوتابیەکان، بەشە بەردەستەکان، دۆخی تایبەتمەندیەکان و سنووری دروستکردن
                            چاودێری بکە.
                        </p>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <span class="hero-chip"><i class="fas fa-user-graduate me-1"></i>{{ $studentsCount }} قوتابی</span>
                            <span class="hero-chip"><i class="fas fa-building-columns me-1"></i>{{ $departmentsCount }} بەش</span>
                            <span class="hero-chip"><i class="fas fa-sliders me-1"></i>{{ $activeFeaturesCount }}/{{ $featuresCount }} تایبەتمەندی چالاک</span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-grid gap-2">
                            <a href="{{ route('teacher.students.create') }}" class="btn btn-light fw-semibold">
                                <i class="fas fa-user-plus me-1"></i>زیادکردنی قوتابی
                            </a>
                            <a href="{{ route('teacher.departments.index') }}" class="btn btn-outline-light fw-semibold">
                                <i class="fas fa-building-columns me-1"></i>بینینی بەشەکان
                            </a>
                            <a href="{{ route('teacher.profile.edit', auth()->user()->id) }}" class="btn btn-outline-light fw-semibold">
                                <i class="fas fa-user-pen me-1"></i>دەستکاری پرۆفایل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body">
                        <div class="stat-label">قوتابیان</div>
                        <div class="stat-value js-counter" data-target="{{ $studentsCount }}">0</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('teacher.students.index') }}" class="stat-link">بینینی هەموو</a>
                            <span class="stat-icon bg-soft-success text-success"><i class="fas fa-user-graduate"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body">
                        <div class="stat-label">بەشە بەردەستەکان</div>
                        <div class="stat-value js-counter" data-target="{{ $departmentsCount }}">0</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('teacher.departments.index') }}" class="stat-link">بڕۆ بۆ بەشەکان</a>
                            <span class="stat-icon bg-soft-info text-info"><i class="fas fa-building-columns"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body">
                        <div class="stat-label">تایبەتمەندی چالاک</div>
                        <div class="stat-value">{{ $activeFeaturesCount }}/{{ $featuresCount }}</div>
                        <div class="progress modern-progress mt-2 mb-3">
                            <div class="progress-bar bg-primary" role="progressbar"
                                style="width: {{ $featuresCount > 0 ? (int) round(($activeFeaturesCount / $featuresCount) * 100) : 0 }}%"></div>
                        </div>
                        <a href="{{ route('teacher.features.request') }}" class="stat-link">داواکردنی تایبەتمەندی</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body">
                        <div class="stat-label">سنووری قوتابی</div>
                        <div class="stat-value">
                            @if (is_null($studentLimit))
                                بێ سنوور
                            @else
                                {{ $studentRemaining }} ماوە
                            @endif
                        </div>
                        @if (!is_null($studentUsagePercent))
                            <div class="progress modern-progress mt-2 mb-2">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $studentUsagePercent }}%"></div>
                            </div>
                            <small class="text-muted">{{ $studentUsagePercent }}% بەکارهاتوو</small>
                        @else
                            <small class="text-muted">سنوور دیاری نەکراوە</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-xl-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2 text-warning"></i>کردارە خێراکان</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <a href="{{ route('teacher.students.create') }}" class="quick-action qa-success">
                                    <i class="fas fa-user-plus"></i>
                                    <span>زیادکردنی قوتابی</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('teacher.students.index') }}" class="quick-action qa-primary">
                                    <i class="fas fa-users"></i>
                                    <span>لیستی قوتابیەکان</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('teacher.departments.index') }}" class="quick-action qa-info">
                                    <i class="fas fa-building-columns"></i>
                                    <span>بینینی بەشەکان</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('teacher.profile.edit', auth()->user()->id) }}" class="quick-action qa-warning">
                                    <i class="fas fa-user-pen"></i>
                                    <span>دەستکاری پرۆفایل</span>
                                </a>
                            </div>
                            <div class="col-sm-12">
                                @if ((int) ($teacher?->queue_hand_department ?? 0) === 1)
                                    <a href="{{ route('teacher.queue-hand-departments.index') }}" class="quick-action qa-dark">
                                        <i class="fas fa-list-check"></i>
                                        <span>ڕێزبەندی دەستی بەشەکان</span>
                                    </a>
                                @else
                                    <a href="{{ route('teacher.features.request') }}" class="quick-action qa-muted">
                                        <i class="fas fa-paper-plane"></i>
                                        <span>چالاککردنی ڕێزبەندی دەستی بەشەکان</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-sliders me-2 text-primary"></i>دۆخی تایبەتمەندییەکان</h5>
                        <span class="badge bg-light text-dark">{{ $activeFeaturesCount }}/{{ $featuresCount }}</span>
                    </div>
                    <div class="card-body">
                        <div class="feature-list">
                            @foreach ($features as $feature)
                                <div class="feature-item">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="feature-icon {{ $feature['active'] ? 'active' : 'inactive' }}">
                                            <i class="fas {{ $feature['icon'] }}"></i>
                                        </span>
                                        <div class="feature-label">{{ $feature['label'] }}</div>
                                    </div>
                                    <span class="badge {{ $feature['active'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $feature['active'] ? 'چالاک' : 'ناچالاک' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        @if ($missingFeatures->isNotEmpty())
                            <div class="mt-3 d-grid">
                                <a href="{{ route('teacher.features.request') }}" class="btn btn-warning">
                                    <i class="fas fa-paper-plane me-1"></i>داواکردنی تایبەتمەندییە ناچالاکەکان
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($missingFeatures->isNotEmpty())
            <div class="alert alert-warning border-0 shadow-sm mt-3 mb-0">
                <i class="fas fa-triangle-exclamation me-2"></i>
                هەندێک تایبەتمەندی بۆت ناچالاکن. دەتوانیت لە ڕێی داواکارییەوە چالاکیان بکەیت.
            </div>
        @endif
    </div>
@endsection

@push('head-scripts')
    <style>
        .dashboard-teacher {
            --dash-primary: #0f766e;
            --dash-secondary: #14b8a6;
            --dash-surface: #f8fafc;
        }

        .dashboard-simple-page {
            background:
                radial-gradient(circle at 16% 4%, rgba(20, 184, 166, .10), transparent 44%),
                radial-gradient(circle at 82% 12%, rgba(56, 189, 248, .09), transparent 36%),
                var(--dash-surface);
            border-radius: 1rem;
        }

        .hero-card {
            background: linear-gradient(135deg, var(--dash-primary), var(--dash-secondary));
            color: #fff;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .3);
            border-radius: 999px;
            padding: .3rem .7rem;
            font-size: .82rem;
        }

        .hero-title {
            font-size: clamp(1.15rem, 1.9vw, 1.7rem);
            font-weight: 800;
        }

        .hero-subtitle {
            opacity: .95;
            line-height: 1.8;
            max-width: 56ch;
        }

        .hero-chip {
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .28);
            border-radius: 999px;
            padding: .32rem .72rem;
            font-size: .82rem;
        }

        .stat-card {
            border: 1px solid rgba(15, 23, 42, .05);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(15, 23, 42, .08) !important;
        }

        .stat-label {
            color: #64748b;
            font-size: .82rem;
            margin-bottom: .45rem;
        }

        .stat-value {
            font-weight: 800;
            font-size: clamp(1.2rem, 1.8vw, 1.9rem);
            margin-bottom: .75rem;
            color: #0f172a;
        }

        .stat-link {
            font-size: .85rem;
            text-decoration: none;
            color: #0f766e;
            font-weight: 600;
        }

        .stat-link:hover {
            text-decoration: underline;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: .65rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .quick-action {
            display: flex;
            align-items: center;
            gap: .55rem;
            width: 100%;
            border-radius: .85rem;
            border: 1px solid transparent;
            padding: .75rem .9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s ease;
        }

        .quick-action i {
            width: 1.25rem;
            text-align: center;
        }

        .quick-action:hover {
            transform: translateY(-2px);
        }

        .qa-primary {
            background: rgba(29, 78, 216, .08);
            color: #1d4ed8;
            border-color: rgba(29, 78, 216, .2);
        }

        .qa-success {
            background: rgba(22, 163, 74, .08);
            color: #15803d;
            border-color: rgba(22, 163, 74, .2);
        }

        .qa-info {
            background: rgba(2, 132, 199, .08);
            color: #0c4a6e;
            border-color: rgba(2, 132, 199, .2);
        }

        .qa-warning {
            background: rgba(217, 119, 6, .10);
            color: #b45309;
            border-color: rgba(217, 119, 6, .25);
        }

        .qa-dark {
            background: rgba(15, 23, 42, .07);
            color: #0f172a;
            border-color: rgba(15, 23, 42, .15);
        }

        .qa-muted {
            background: rgba(100, 116, 139, .12);
            color: #334155;
            border-color: rgba(100, 116, 139, .2);
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: .6rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            border: 1px solid rgba(148, 163, 184, .25);
            border-radius: .8rem;
            padding: .6rem .7rem;
            background: #fff;
        }

        .feature-icon {
            width: 32px;
            height: 32px;
            border-radius: .6rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .feature-icon.active {
            background: rgba(22, 163, 74, .12);
            color: #15803d;
        }

        .feature-icon.inactive {
            background: rgba(239, 68, 68, .12);
            color: #b91c1c;
        }

        .feature-label {
            font-size: .88rem;
            font-weight: 600;
            color: #334155;
        }

        .modern-progress {
            height: .55rem;
            border-radius: 999px;
            background: rgba(148, 163, 184, .25);
            overflow: hidden;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('.js-counter').forEach((counter) => {
            const target = Number(counter.getAttribute('data-target') || 0);
            const duration = 550;
            const steps = 20;
            const increment = target / steps;
            let current = 0;
            let tick = 0;

            const timer = setInterval(() => {
                tick += 1;
                current += increment;
                if (tick >= steps) {
                    counter.textContent = target.toLocaleString();
                    clearInterval(timer);
                    return;
                }
                counter.textContent = Math.round(current).toLocaleString();
            }, duration / steps);
        });
    </script>
@endpush
