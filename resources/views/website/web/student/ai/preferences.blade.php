@extends('website.web.admin.layouts.app')

@section('title', 'AI - ڕێزبەندی ئۆتۆماتیکی')

@section('content')
    @php
        $selectedSystemsInput = old('systems', $defaultSystemIds ?? []);
        $selectedSystemIds = collect((array) $selectedSystemsInput)
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
        $provinceScopeInput = old('province_scope', 'local_only');
        $studentMark = rtrim(rtrim(number_format((float) $student->mark, 3, '.', ''), '0'), '.');
    @endphp

    <div class="container-fluid py-4 ai-pref-page">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">AI ڕێزبەندی</li>
                        </ol>
                    </div>
                    <h4 class="page-title mb-0">
                        <i class="fas fa-robot me-1"></i>
                        ڕێزبەندی بە سیستەمی ژیری دەستکرد AI
                    </h4>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 ai-hero-card">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8">
                        <span class="hero-kicker">
                            <i class="fas fa-wand-magic-sparkles me-1"></i>
                            ڕێزبەندی ئۆتۆماتیکی
                        </span>
                        <h3 class="hero-title mt-2 mb-2">باشترین 50 بەش بۆت ڕێزدەکرێت</h3>
                        <p class="hero-subtitle mb-0">
                            ئەم پەیجە بە پێی نمرە و تایبەتمەندییەکانی تۆ بەشەکان فلتەر دەکات و دواتر لە نمرەی بەرز بۆ نزم
                            ڕیزدەکات.
                        </p>
                    </div>
                    <div class="col-lg-4">
                        <div class="hero-count-box">
                            <div class="hero-count">{{ $rankedRows->count() }}</div>
                            <div class="hero-count-label">ئەنجامی ئامادە / 50</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm">
                <i class="fas fa-circle-check me-1"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm">
                <i class="fas fa-circle-xmark me-1"></i> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-warning border-0 shadow-sm">
                <div class="fw-bold mb-2"><i class="fas fa-triangle-exclamation me-1"></i> هەندێک هەڵە هەیە:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-circle-info me-1 text-primary"></i> چۆنیەتی کارکردنی ڕێزبەندی</h6>
                    </div>
                    <div class="card-body">
                        <ol class="rule-list mb-0">
                            <li>
                                ئەگەر <code class="badge bg-warning">ناو پارێزگا</code> هەڵبژێریت، تەنها بەشەکانی هەمان پارێزگات هەڵدەبژێردرێن و
                                <code class="badge bg-warning">نمرەی ناو پارێزگا</code> حیساب دەکرێت.
                            </li>
                            <li>
                                ئەگەر <code class="badge bg-warning">لەگەڵ دەرەوەی پارێزگا</code> هەڵبژێریت، ناو پارێزگا بە
                                <code class="badge bg-warning">نمرەی ناو پارێزگا</code> و دەرەوەی پارێزگا بە <code class="badge bg-warning">نمرەی دەرەوەی پارێزگا</code> حیساب دەکرێت.
                            </li>
                            <li>
                                بۆ <code class="badge bg-warning">لق</code>: ئەگەر زانستی بیت، تەنها <code class="badge bg-warning">زانستی</code> و
                                <code class="badge bg-warning">زانستی و وێژەیی</code>. ئەگەر وێژەیی بیت، تەنها <code class="badge bg-warning">وێژەیی</code> و
                                <code class="badge bg-warning">زانستی و وێژەیی</code>.
                            </li>
                            <li>
                                بۆ <code class="badge bg-warning">ڕەگەز</code>: ئەگەر <code class="badge bg-warning">مێ</code> بیت دەتوانیت هەموو هەڵبژێریت، ئەگەر
                                <code class="badge bg-warning">نێر</code> بیت تەنها بەشەکانی <code class="badge bg-warning">نێر</code>.
                            </li>
                            <li>
                                بۆ <code class="badge bg-warning">پڕکردنەوەی فۆرم</code>: ئەگەر <code class="badge bg-warning">1</code> بێت هەموو سیستەمەکان؛ ئەگەر
                                <code class="badge bg-warning">گەورەتر لە 1</code> بێت تەنها <span class="badge bg-danger">پاراڵیل</span> و <span class="badge bg-dark">ئێواران</span>.
                            </li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-user-graduate me-1 text-success"></i> زانیاری قوتابی</h6>
                    </div>
                    <div class="card-body">
                        <div class="student-info-grid">
                            <div class="student-info-item">
                                <span class="label">ناوی قوتابی</span>
                                <span class="value">{{ $student->user->name ?? '-' }}</span>
                            </div>
                            <div class="student-info-item">
                                <span class="label">کۆد</span>
                                <span class="value">{{ $student->user->code ?? '-' }}</span>
                            </div>
                            <div class="student-info-item">
                                <span class="label">نمرە</span>
                                <span class="value">{{ $studentMark }}</span>
                            </div>
                            <div class="student-info-item">
                                <span class="label">لق</span>
                                <span class="value">{{ $student->type }}</span>
                            </div>
                            <div class="student-info-item">
                                <span class="label">ڕەگەز</span>
                                <span class="value">{{ $student->gender }}</span>
                            </div>
                            <div class="student-info-item">
                                <span class="label">پڕکردنەوەی فۆرم</span>
                                <span class="value">{{ $student->year }}</span>
                            </div>
                            <div class="student-info-item full">
                                <span class="label">پارێزگا</span>
                                <span class="value">{{ $student->province ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-sliders me-1 text-primary"></i> فۆرمی ڕێزبەندی</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('student.ai-ranking.generate') }}">
                    @csrf
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold mb-2">سنووری پارێزگا</label>
                            <div class="option-wrap">
                                <label class="option-item">
                                    <input class="d-none" type="radio" name="province_scope" value="local_only"
                                        {{ $provinceScopeInput === 'local_only' ? 'checked' : '' }}>
                                    <span class="option-body">
                                        <strong>تەنها ناو پارێزگای خۆم</strong>
                                        <small>تەنها بەشە ناوخۆییەکان هەڵدەبژێردرێن.</small>
                                    </span>
                                </label>
                                <label class="option-item">
                                    <input class="d-none" type="radio" name="province_scope" value="include_outside"
                                        {{ $provinceScopeInput === 'include_outside' ? 'checked' : '' }}>
                                    <span class="option-body">
                                        <strong>ناو + دەرەوەی پارێزگا</strong>
                                        <small>بەشەکانی دەرەوەش لە ڕێزبەندی تێدایە.</small>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label fw-semibold mb-2">سیستەمەکانی تێکەڵبوون</label>
                            <div class="system-wrap">
                                @foreach ($systems as $system)
                                    @php
                                        $allowed = in_array((int) $system->id, $allowedSystemIdsByYear, true);
                                        $checked = $allowed && in_array((int) $system->id, $selectedSystemIds, true);
                                    @endphp

                                    <label class="system-item {{ !$allowed ? 'is-disabled' : '' }}">
                                        <input class="d-none" type="checkbox" name="systems[]"
                                            value="{{ $system->id }}" {{ $checked ? 'checked' : '' }}
                                            {{ $allowed ? '' : 'disabled' }}>
                                        <span class="system-body">
                                            <span class="system-name">{{ $system->name }}</span>
                                            <span class="system-status {{ $allowed ? 'ok' : 'no' }}">
                                                {{ $allowed ? 'تێدایە' : 'ڕێگەپێنەدراو بۆ ساڵەکەت' }}
                                            </span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary w-100 px-4 py-2">
                            <i class="fas fa-gears me-1"></i>
                            ئەنجامدانی ڕێزبەندی (50)
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if ($summary)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-chart-simple me-1 text-success"></i> کورتەی ئەنجامی ڕێزبەندی</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6 col-xl-3">
                            <div class="summary-item">
                                <span class="summary-label">سنووری پارێزگا</span>
                                <span
                                    class="summary-value">{{ $summary['province_scope'] === 'local_only' ? 'تەنها ناو پارێزگا' : 'ناو + دەرەوەی پارێزگا' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="summary-item">
                                <span class="summary-label">نمرەی کارپێکراو</span>
                                <span
                                    class="summary-value">{{ rtrim(rtrim(number_format((float) $summary['mark_with_bonus'], 3, '.', ''), '0'), '.') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="summary-item">
                                <span class="summary-label">ئەنجام</span>
                                <span class="summary-value">{{ (int) $summary['count'] }} / 50</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($systems as $system)
                            @php
                                $included = in_array((int) $system->id, $summary['selected_system_ids'], true);
                            @endphp
                            <span class="badge {{ $included ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                                {{ $system->name }}: {{ $included ? 'تێدایە' : 'تێدا نییە' }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="card border-0 shadow-sm result-card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fas fa-list-ol me-1 text-primary"></i> ئەنجامی ڕێزبەندی (بەرز بۆ نزم)</h6>
                <span class="badge bg-primary px-3 py-2">{{ $rankedRows->count() }} / 50</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 ai-result-table">
                        <thead>
                            <tr>
                                <th style="width: 72px;" class="text-center">ڕیز</th>
                                <th>بەش</th>
                                <th class="text-center">جۆری نمرە</th>
                                <th class="text-center">نمرەی پێویست</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rankedRows as $row)
                                <tr>
                                    <td class="text-center">
                                        <span class="rank-badge">{{ $row['rank'] }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap align-items-center gap-1 text-muted small mb-1">
                                            <span
                                                class="badge {{ $row['department']->system->id == 1 ? 'bg-success' : ($row['department']->system->id == 2 ? 'bg-danger' : 'bg-dark') }}">
                                                {{ $row['department']->system->name ?? '-' }}
                                            </span>
                                            <span>/ {{ $row['department']->province->name ?? '-' }}</span>
                                            <span>/ {{ $row['department']->university->name ?? '-' }}</span>
                                            <span>/ {{ $row['department']->college->name ?? '-' }}</span>/
                                            <strong style="font-weight: bold; color:#000 !important"> {{ $row['department']->name }}</strong>
                                            <span class="badge {{ $row['is_local'] ? 'bg-warning' : 'bg-warning text-dark' }}">
                                                {{ $row['is_local'] ? 'ناو پارێزگا' : 'دەرەوەی پارێزگا' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-white px-3 py-2">
                                            {{ $row['score_type'] === 'local_score' ? 'نمرەی ناوخۆیی' : 'نمرەی دەرەکی' }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold score-cell">
                                        {{ rtrim(rtrim(number_format((float) $row['required_score'], 3, '.', ''), '0'), '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                        هێشتا هیچ ئەنجامێکی ڕێزبەندی نەبوو. فۆرمەکە پڕبکەوە و دگمەی ئەنجامدان دابگرە.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .ai-pref-page {
            background:
                radial-gradient(circle at 6% 8%, rgba(59, 130, 246, 0.10), transparent 35%),
                radial-gradient(circle at 92% 4%, rgba(16, 185, 129, 0.10), transparent 28%);
            border-radius: 1rem;
        }

        .ai-hero-card {
            background: linear-gradient(125deg, #0f172a, #1d4ed8);
            color: #fff;
            overflow: hidden;
        }

        .hero-kicker {
            font-size: 0.8rem;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: inline-flex;
            align-items: center;
        }

        .hero-title {
            font-weight: 800;
            letter-spacing: 0.2px;
        }

        .hero-subtitle {
            opacity: 0.92;
            line-height: 1.8;
            max-width: 58ch;
        }

        .hero-count-box {
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            text-align: center;
            padding: 1rem 0.75rem;
        }

        .hero-count {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .hero-count-label {
            margin-top: 0.4rem;
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .rule-list {
            counter-reset: ai-rules;
            padding-inline-start: 0;
        }

        .rule-list li {
            list-style: none;
            counter-increment: ai-rules;
            position: relative;
            padding: 0.7rem 0.8rem 0.7rem 3.1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.8rem;
            margin-bottom: 0.6rem;
            background: #fff;
            line-height: 1.7;
        }

        .ai-pref-page code {
            font-family: "NizarNastaliqKurdish", "Wafeq", "Noto Sans Arabic", Tahoma, sans-serif;
            font-size: 0.83rem;
            font-weight: 700;
            color: #1e3a8a;
            background: #e0ecff;
            border: 1px solid #bfdbfe;
            border-radius: 0.45rem;
            padding: 0.08rem 0.42rem;
        }

        .rule-list li::before {
            content: counter(ai-rules);
            position: absolute;
            left: 0.85rem;
            top: 0.7rem;
            width: 1.55rem;
            height: 1.55rem;
            border-radius: 50%;
            background: #dbeafe;
            color: #1e3a8a;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.65rem;
        }

        .student-info-item {
            border: 1px solid #e2e8f0;
            border-radius: 0.7rem;
            padding: 0.65rem 0.7rem;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .student-info-item.full {
            grid-column: 1 / -1;
        }

        .student-info-item .label {
            font-size: 0.74rem;
            color: #64748b;
        }

        .student-info-item .value {
            font-weight: 700;
            color: #0f172a;
            font-size: 0.92rem;
        }

        .option-wrap,
        .system-wrap {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .option-item,
        .system-item {
            cursor: pointer;
            margin: 0;
        }

        .option-body,
        .system-body {
            display: block;
            border: 1px solid #dbe3ee;
            border-radius: 0.8rem;
            padding: 0.8rem 0.9rem;
            background: #fff;
            transition: all 0.18s ease;
        }

        .option-body strong,
        .system-name {
            display: block;
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 0.15rem;
        }

        .option-body small {
            color: #64748b;
            font-size: 0.8rem;
        }

        .system-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.7rem;
        }

        .system-status {
            font-size: 0.75rem;
            padding: 0.22rem 0.55rem;
            border-radius: 999px;
            font-weight: 700;
            white-space: nowrap;
        }

        .system-status.ok {
            background: #dcfce7;
            color: #166534;
        }

        .system-status.no {
            background: #e2e8f0;
            color: #334155;
        }

        .option-item input:checked + .option-body,
        .system-item input:checked + .system-body {
            border-color: #2563eb;
            background: #eff6ff;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
        }

        .system-item.is-disabled {
            cursor: not-allowed;
            opacity: 0.78;
        }

        .summary-item {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 0.8rem;
            padding: 0.75rem 0.8rem;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            height: 100%;
        }

        .summary-label {
            font-size: 0.74rem;
            color: #64748b;
        }

        .summary-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }

        .result-card .card-header {
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .ai-result-table thead th {
            background: #f8fafc;
            font-size: 0.82rem;
            color: #64748b;
            font-weight: 700;
        }

        .rank-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #e0e7ff;
            color: #3730a3;
            font-weight: 800;
        }

        .score-cell {
            color: #0f766e;
        }

        @media (max-width: 768px) {
            .student-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
