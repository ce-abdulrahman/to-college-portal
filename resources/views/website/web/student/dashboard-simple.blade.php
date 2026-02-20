@extends('website.web.admin.layouts.app')

@section('title', 'داشبۆردی قوتابی')

@section('content')
    @php
        $student = $student ?? auth()->user()->student;
        $dashboard = $dashboard ?? [];

        $mark = (float) ($student?->mark ?? 0);
        $fieldType = $student?->type ?? '---';
        $year = (int) ($student?->year ?? 0);
        $provinceName = $student?->province ?? '---';

        $maxSelections = (int) ($dashboard['max_selections'] ?? ((int) ($student?->all_departments ?? 0) === 1 ? 50 : 10));
        $selectedDepartmentsCount = (int) ($dashboard['selected_departments_count'] ?? App\Models\ResultDep::query()->where('student_id', $student?->id)->count());
        $selectionPercent = (int) ($dashboard['selection_percent'] ?? ($maxSelections > 0 ? min(100, (int) round(($selectedDepartmentsCount / $maxSelections) * 100)) : 0));
        $finalSelection = $dashboard['final_selection'] ?? null;

        $features = $dashboard['features'] ?? [
            [
                'key' => 'ai_rank',
                'label' => 'ڕیزبەندی کرد بە زیرەکی دەستکرد',
                'icon' => 'fa-robot',
                'active' => (int) ($student?->ai_rank ?? 0) === 1,
            ],
            [
                'key' => 'gis',
                'label' => 'سەیرکردن بە نەخشە',
                'icon' => 'fa-map-location-dot',
                'active' => (int) ($student?->gis ?? 0) === 1,
            ],
            [
                'key' => 'all_departments',
                'label' => 'ڕێزبەندی ٥٠ بەش + بینینی پارێزگاکانی تر',
                'icon' => 'fa-layer-group',
                'active' => (int) ($student?->all_departments ?? 0) === 1,
            ],
        ];

        $activeFeaturesCount = (int) ($dashboard['active_features_count'] ?? collect($features)->where('active', true)->count());
        $featuresCount = (int) ($dashboard['features_count'] ?? count($features));
        $missingFeatures = collect($features)->where('active', false)->values();
        $aiEnabled = (int) ($student?->ai_rank ?? 0) === 1;
        $aiActionRoute = $aiEnabled ? route('student.ai-ranking.preferences') : route('student.departments.request-more');
        $aiActionLabel = $aiEnabled ? 'ڕێزبەندی AI ئۆتۆماتیکی' : 'داوای چالاککردنی AI';
    @endphp

    <div class="container-fluid py-4 dashboard-simple-page dashboard-student">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">سەرەکی</li>
                        </ol>
                    </div>
                    <h4 class="page-title mb-0">
                        <i class="fas fa-user-graduate me-1"></i>
                        داشبۆردی قوتابی
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
                        <h2 class="hero-title mt-2 mb-3">هەنگاوەکانت بۆ هەڵبژاردنی بەشی داهاتوو</h2>
                        <p class="hero-subtitle mb-0">
                            نمرە و زانیارییەکانت، دۆخی هەڵبژاردنی بەشەکان و تایبەتمەندیە چالاکەکانت لێرەدا بە ڕوونی
                            پیشان دەدرێن.
                        </p>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <span class="hero-chip"><i class="fas fa-percent me-1"></i>نمرە: {{ rtrim(rtrim(number_format($mark, 3, '.', ''), '0'), '.') }}</span>
                            <span class="hero-chip"><i class="fas fa-graduation-cap me-1"></i>لق: {{ $fieldType }}</span>
                            <span class="hero-chip"><i class="fas fa-calendar-alt me-1"></i>ساڵ: {{ $year }}</span>
                            <span class="hero-chip"><i class="fas fa-location-dot me-1"></i>{{ $provinceName }}</span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-grid gap-2">
                            <a href="{{ route('student.departments.selection') }}" class="btn btn-light fw-semibold">
                                <i class="fas fa-list-check me-1"></i>هەڵبژاردنی بەشەکان
                            </a>
                            <a href="{{ route('student.mbti.index') }}" class="btn btn-outline-light fw-semibold">
                                <i class="fas fa-brain me-1"></i>تاقیکردنەوەی MBTI
                            </a>
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-light fw-semibold">
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
                        <div class="stat-label">نمرەی کۆتایی</div>
                        <div class="stat-value">{{ rtrim(rtrim(number_format($mark, 3, '.', ''), '0'), '.') }}</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">لق: {{ $fieldType }}</span>
                            <span class="stat-icon bg-soft-primary text-primary"><i class="fas fa-percent"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body">
                        <div class="stat-label">بەشە هەڵبژێردراوەکان</div>
                        <div class="stat-value js-counter" data-target="{{ $selectedDepartmentsCount }}">0</div>
                        <div class="progress modern-progress mt-2 mb-3">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $selectionPercent }}%"></div>
                        </div>
                        <small class="text-muted">{{ $selectedDepartmentsCount }} لە {{ $maxSelections }} هەڵبژێردراوە</small>
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
                        <a href="{{ route('student.features.request') }}" class="stat-link">داواکردنی تایبەتمەندی</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm stat-card h-100">
                    <div class="card-body">
                        <div class="stat-label">دۆخی ڕێزبەندی</div>
                        @if ($finalSelection && $finalSelection->department)
                            <div class="final-badge mb-2">
                                <i class="fas fa-check-circle me-1"></i>بەشی کۆتایی دیاریکراوە
                            </div>
                            <div class="small fw-semibold text-dark">{{ $finalSelection->department->name }}</div>
                        @else
                            <div class="final-badge muted mb-2">
                                <i class="fas fa-hourglass-half me-1"></i>هێشتا بەشی کۆتایی دیاری نەکراوە
                            </div>
                            <a href="{{ route('student.departments.selection') }}" class="stat-link">بڕۆ بۆ ڕێزبەندی</a>
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
                                <a href="{{ route('student.departments.selection') }}" class="quick-action qa-primary">
                                    <i class="fas fa-list-check"></i>
                                    <span>هەڵبژاردنی بەشەکان</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ $aiActionRoute }}" class="quick-action qa-success">
                                    <i class="fas fa-robot"></i>
                                    <span>{{ $aiActionLabel }}</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('student.mbti.index') }}" class="quick-action qa-info">
                                    <i class="fas fa-brain"></i>
                                    <span>تاقیکردنەوەی MBTI</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('student.final-report') }}" class="quick-action qa-warning">
                                    <i class="fas fa-file-lines"></i>
                                    <span>ڕاپۆرتی کۆتایی</span>
                                </a>
                            </div>
                            <div class="col-sm-12">
                                <a href="{{ route('profile.edit') }}" class="quick-action qa-dark">
                                    <i class="fas fa-user-pen"></i>
                                    <span>دەستکاری پرۆفایل</span>
                                </a>
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
                                <a href="{{ route('student.features.request') }}" class="btn btn-warning">
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
                بۆ چالاککردنی هەموو تایبەتمەندییەکان، دەتوانیت لە پەیجی داواکاریەوە داوایان بکەیت.
            </div>
        @endif
    </div>
@endsection

@push('head-scripts')
    <style>
        .dashboard-student {
            --dash-primary: #ca8a04;
            --dash-secondary: #fb923c;
            --dash-surface: #fffaf0;
        }

        .dashboard-simple-page {
            background:
                radial-gradient(circle at 10% 4%, rgba(251, 146, 60, .12), transparent 42%),
                radial-gradient(circle at 82% 8%, rgba(56, 189, 248, .09), transparent 34%),
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
            color: #a16207;
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

        .final-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .35rem .6rem;
            border-radius: .65rem;
            background: rgba(22, 163, 74, .12);
            color: #166534;
            font-size: .82rem;
            font-weight: 700;
        }

        .final-badge.muted {
            background: rgba(148, 163, 184, .2);
            color: #334155;
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
