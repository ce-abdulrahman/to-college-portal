@extends('website.web.admin.layouts.app')

@section('title', 'داشبۆردی سەنتەر')

@section('content')
    @php
        $center = $center ?? auth()->user()->center;
        $dashboard = $dashboard ?? [];

        $teachersCount = (int) ($dashboard['teachers_count'] ?? App\Models\Teacher::query()->where('referral_code', auth()->user()->rand_code)->count());
        $studentsCount = (int) ($dashboard['students_count'] ?? App\Models\Student::query()->where('referral_code', auth()->user()->rand_code)->count());
        $departmentsCount = (int) ($dashboard['departments_count'] ?? App\Models\Department::query()->where('status', 1)->count());

        $teacherLimit = $dashboard['teacher_limit'] ?? $center?->limit_teacher;
        $studentLimit = $dashboard['student_limit'] ?? $center?->limit_student;
        $teacherRemaining = $dashboard['teacher_remaining'] ?? (is_null($teacherLimit) ? null : max((int) $teacherLimit - $teachersCount, 0));
        $studentRemaining = $dashboard['student_remaining'] ?? (is_null($studentLimit) ? null : max((int) $studentLimit - $studentsCount, 0));
        $teacherUsagePercent = $dashboard['teacher_usage_percent'] ?? (is_null($teacherLimit) || (int) $teacherLimit === 0 ? null : min(100, (int) round(($teachersCount / (int) $teacherLimit) * 100)));
        $studentUsagePercent = $dashboard['student_usage_percent'] ?? (is_null($studentLimit) || (int) $studentLimit === 0 ? null : min(100, (int) round(($studentsCount / (int) $studentLimit) * 100)));

        $features = $dashboard['features'] ?? [
            [
                'key' => 'ai_rank',
                'label' => 'ڕیزبەندی کرد بە زیرەکی دەستکرد',
                'icon' => 'fa-robot',
                'active' => (int) ($center?->ai_rank ?? 0) === 1,
            ],
            [
                'key' => 'gis',
                'label' => 'سەیرکردن بە نەخشە',
                'icon' => 'fa-map-location-dot',
                'active' => (int) ($center?->gis ?? 0) === 1,
            ],
            [
                'key' => 'all_departments',
                'label' => 'ڕێزبەندی ٥٠ بەش + بینینی پارێزگاکانی تر',
                'icon' => 'fa-layer-group',
                'active' => (int) ($center?->all_departments ?? 0) === 1,
            ],
            [
                'key' => 'queue_hand_department',
                'label' => 'ڕیزبەندی دەستی بەشەکان',
                'icon' => 'fa-list-ol',
                'active' => (int) ($center?->queue_hand_department ?? 0) === 1,
            ],
        ];

        $activeFeaturesCount = (int) ($dashboard['active_features_count'] ?? collect($features)->where('active', true)->count());
        $featuresCount = (int) ($dashboard['features_count'] ?? count($features));
        $missingFeatures = collect($features)->where('active', false)->values();
    @endphp

    <div class="container-fluid py-4 dashboard-simple-page dashboard-center">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('center.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">سەرەکی</li>
                        </ol>
                    </div>
                    <h4 class="page-title mb-0">
                        <i class="fas fa-building me-1"></i>
                        داشبۆردی سەنتەر
                    </h4>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden hero-card mb-4">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <span class="hero-kicker">
                            <i class="fa-solid fa-sparkles me-1"></i>
                            بەخێربێیت، {{ auth()->user()->name }}
                        </span>
                        <h2 class="hero-title mt-2 mb-3">بەڕێوەبردنی سەنتەرەکەت لە یەک پەیجدا</h2>
                        <p class="hero-subtitle mb-0">
                            لەم داشبۆردەدا دەتوانیت ژمارەی مامۆستا و قوتابی، دۆخی تایبەتمەندیەکان و سنوورەکانی
                            دروستکردن بە خێرایی چاودێری بکەیت.
                        </p>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <span class="hero-chip"><i class="fas fa-chalkboard-teacher me-1"></i>{{ $teachersCount }} مامۆستا</span>
                            <span class="hero-chip"><i class="fas fa-user-graduate me-1"></i>{{ $studentsCount }} قوتابی</span>
                            <span class="hero-chip"><i class="fas fa-layer-group me-1"></i>{{ $departmentsCount }} بەش</span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-grid gap-2">
                            <a href="{{ route('center.students.create') }}" class="btn btn-light fw-semibold">
                                <i class="fas fa-user-plus me-1"></i>زیادکردنی قوتابی
                            </a>
                            <a href="{{ route('center.teachers.create') }}" class="btn btn-outline-light fw-semibold">
                                <i class="fas fa-plus-circle me-1"></i>زیادکردنی مامۆستا
                            </a>
                            <a href="{{ route('center.departments.index') }}" class="btn btn-outline-light fw-semibold">
                                <i class="fas fa-building-columns me-1"></i>بینینی بەشەکان
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
                        <div class="stat-label">مامۆستایان</div>
                        <div class="stat-value js-counter" data-target="{{ $teachersCount }}">0</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('center.teachers.index') }}" class="stat-link">بینینی هەموو</a>
                            <span class="stat-icon bg-soft-primary text-primary"><i class="fas fa-chalkboard-teacher"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body">
                        <div class="stat-label">قوتابیان</div>
                        <div class="stat-value js-counter" data-target="{{ $studentsCount }}">0</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('center.students.index') }}" class="stat-link">بینینی هەموو</a>
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
                            <a href="{{ route('center.departments.index') }}" class="stat-link">بڕۆ بۆ بەشەکان</a>
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
                            <div class="progress-bar bg-warning" role="progressbar"
                                style="width: {{ $featuresCount > 0 ? (int) round(($activeFeaturesCount / $featuresCount) * 100) : 0 }}%"></div>
                        </div>
                        <a href="{{ route('center.features.request') }}" class="stat-link">داواکردنی تایبەتمەندی</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-xl-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2 text-warning"></i>کردارە خێراکان</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <a href="{{ route('center.teachers.create') }}" class="quick-action qa-primary">
                                    <i class="fas fa-user-plus"></i>
                                    <span>زیادکردنی مامۆستا</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('center.students.create') }}" class="quick-action qa-success">
                                    <i class="fas fa-user-graduate"></i>
                                    <span>زیادکردنی قوتابی</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('center.departments.index') }}" class="quick-action qa-info">
                                    <i class="fas fa-building-columns"></i>
                                    <span>بینینی بەشەکان</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('center.profile.edit', auth()->user()->id) }}" class="quick-action qa-warning">
                                    <i class="fas fa-pen-to-square"></i>
                                    <span>دەستکاری پرۆفایل</span>
                                </a>
                            </div>
                            <div class="col-sm-12">
                                @if ((int) ($center?->queue_hand_department ?? 0) === 1)
                                    <a href="{{ route('center.queue-hand-departments.index') }}" class="quick-action qa-dark">
                                        <i class="fas fa-list-check"></i>
                                        <span>ڕێزبەندی دەستی بەشەکان</span>
                                    </a>
                                @else
                                    <a href="{{ route('center.features.request') }}" class="quick-action qa-muted">
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
                                <a href="{{ route('center.features.request') }}" class="btn btn-warning">
                                    <i class="fas fa-paper-plane me-1"></i>داواکردنی تایبەتمەندییە ناچالاکەکان
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h6 class="mb-0"><i class="fas fa-user-tie me-1 text-primary"></i>سنووری مامۆستا</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">بەکارهاتوو: {{ $teachersCount }}</span>
                            <span class="fw-semibold">
                                @if (is_null($teacherLimit))
                                    بێ سنوور
                                @else
                                    ماوە: {{ $teacherRemaining }}
                                @endif
                            </span>
                        </div>
                        @if (!is_null($teacherUsagePercent))
                            <div class="progress modern-progress">
                                <div class="progress-bar bg-primary" role="progressbar"
                                    style="width: {{ $teacherUsagePercent }}%"></div>
                            </div>
                            <small class="text-muted d-block mt-2">{{ $teacherUsagePercent }}% لە سنوور بەکارهاتووە</small>
                        @else
                            <small class="text-muted">سنوور دیاری نەکراوە.</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h6 class="mb-0"><i class="fas fa-user-graduate me-1 text-success"></i>سنووری قوتابی</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">بەکارهاتوو: {{ $studentsCount }}</span>
                            <span class="fw-semibold">
                                @if (is_null($studentLimit))
                                    بێ سنوور
                                @else
                                    ماوە: {{ $studentRemaining }}
                                @endif
                            </span>
                        </div>
                        @if (!is_null($studentUsagePercent))
                            <div class="progress modern-progress">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $studentUsagePercent }}%"></div>
                            </div>
                            <small class="text-muted d-block mt-2">{{ $studentUsagePercent }}% لە سنوور بەکارهاتووە</small>
                        @else
                            <small class="text-muted">سنوور دیاری نەکراوە.</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($missingFeatures->isNotEmpty())
            <div class="alert alert-warning border-0 shadow-sm mt-3 mb-0">
                <i class="fas fa-triangle-exclamation me-2"></i>
                هەندێک تایبەتمەندی ناچالاکن. بۆ زیادکردنی توانا، دەتوانیت داواکاری نوێ بنێریت.
            </div>
        @endif
    </div>
@endsection

@push('head-scripts')
    <style>
        .dashboard-center {
            --dash-primary: #1d4ed8;
            --dash-secondary: #0ea5e9;
            --dash-surface: #f8fafc;
        }

        .dashboard-simple-page {
            background:
                radial-gradient(circle at 20% 0%, rgba(14, 165, 233, .09), transparent 45%),
                radial-gradient(circle at 80% 10%, rgba(251, 191, 36, .10), transparent 35%),
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
            font-size: clamp(1.3rem, 1.8vw, 2rem);
            margin-bottom: .75rem;
            color: #0f172a;
        }

        .stat-link {
            font-size: .85rem;
            text-decoration: none;
            color: var(--dash-primary);
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

        @media (max-width: 767.98px) {
            .hero-title {
                font-size: 1.2rem;
            }
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
