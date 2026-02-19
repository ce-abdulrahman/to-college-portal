@extends('website.web.admin.layouts.app')

@section('page_name', 'department')
@section('view_name', 'compare-descriptions')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ $dashboardRoute }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ $departmentsIndexRoute }}">بەشەکان</a></li>
                            <li class="breadcrumb-item active">بەراوردکردنی وەسف</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fa-solid fa-code-compare me-1"></i>
                        بەراوردکردنی وەسفی بەشەکان
                    </h4>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <a href="{{ $departmentsIndexRoute }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
            </a>
            @if (!empty($canCreateDepartment) && !empty($createDepartmentRoute))
                <a href="{{ $createDepartmentRoute }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i> زیادکردنی بەش
                </a>
            @endif
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label for="firstDepartment" class="form-label">بەشی یەکەم</label>
                        <select id="firstDepartment" class="form-select">
                            <option value="">بەش هەڵبژێرە...</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">
                                    {{ $department->name }}
                                    ({{ $department->province->name ?? '-' }} / {{ $department->university->name ?? '-' }}
                                    / {{ $department->college->name ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="secondDepartment" class="form-label">بەشی دووەم</label>
                        <select id="secondDepartment" class="form-select">
                            <option value="">بەش هەڵبژێرە...</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">
                                    {{ $department->name }}
                                    ({{ $department->province->name ?? '-' }} / {{ $department->university->name ?? '-' }}
                                    / {{ $department->college->name ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button id="compareBtn" type="button" class="btn btn-success">
                        <i class="fa-solid fa-magnifying-glass-chart me-1"></i> بەراوردکردن
                    </button>
                    <button id="resetBtn" type="button" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-rotate-left me-1"></i> پاککردنەوە
                    </button>
                </div>
            </div>
        </div>

        <div id="comparisonStatus" class="alert alert-secondary mb-3">
            دوو بەش هەڵبژێرە بۆ بەراوردکردنی وەسف.
        </div>

        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>بەشی یەکەم</strong>
                        <span id="firstDeptMeta" class="text-muted small">—</span>
                    </div>
                    <div class="card-body">
                        <label for="firstDescriptionInput" class="form-label small text-muted mb-1">وەسف (دەستکاری بکە یان
                            دەستنووس بکە)</label>
                        <textarea id="firstDescriptionInput" class="form-control description-input mb-2" rows="6"
                            placeholder="وەسفی بەشی یەکەم..."></textarea>
                        <div id="firstDescription" class="compare-description text-muted">هیچ بەشێک هەڵنەبژێردراوە.</div>
                    </div>
                    <div class="card-footer text-muted small">
                        ژمارەی وشە: <span id="firstWordCount">0</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>بەشی دووەم</strong>
                        <span id="secondDeptMeta" class="text-muted small">—</span>
                    </div>
                    <div class="card-body">
                        <label for="secondDescriptionInput" class="form-label small text-muted mb-1">وەسف (دەستکاری بکە یان
                            دەستنووس بکە)</label>
                        <textarea id="secondDescriptionInput" class="form-control description-input mb-2" rows="6"
                            placeholder="وەسفی بەشی دووەم..."></textarea>
                        <div id="secondDescription" class="compare-description text-muted">هیچ بەشێک هەڵنەبژێردراوە.</div>
                    </div>
                    <div class="card-footer text-muted small">
                        ژمارەی وشە: <span id="secondWordCount">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <strong><i class="fa-solid fa-chart-simple me-1"></i> ئەنجامی بەراورد</strong>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <div class="text-muted small">هاوبەش</div>
                            <div id="commonWordCount" class="fw-bold fs-5">0</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <div class="text-muted small">جیاوازی (یەکەم)</div>
                            <div id="diffFirstCount" class="fw-bold fs-5">0</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <div class="text-muted small">جیاوازی (دووەم)</div>
                            <div id="diffSecondCount" class="fw-bold fs-5">0</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="text-muted">نێزیکی:</span>
                    <span id="similarityPercent" class="fw-bold">0%</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <h6>وشە جیاوازەکانی بەشی یەکەم</h6>
                        <ul id="uniqueFirstWords" class="list-group list-group-flush word-list"></ul>
                    </div>
                    <div class="col-md-6">
                        <h6>وشە جیاوازەکانی بەشی دووەم</h6>
                        <ul id="uniqueSecondWords" class="list-group list-group-flush word-list"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .description-input {
            font-size: 0.9rem;
            line-height: 1.9;
        }

        .compare-description {
            min-height: 150px;
            max-height: 320px;
            overflow: auto;
            white-space: pre-wrap;
            line-height: 1.9;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px;
            background: #fafafa;
        }

        .diff-word {
            background: #ffe08a;
            color: #212529;
            border-radius: 3px;
            padding: 0 2px;
        }

        .word-list {
            max-height: 240px;
            overflow: auto;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {
            const departments = @json($departmentsForCompare);

            const departmentMap = {};
            departments.forEach((item) => {
                departmentMap[String(item.id)] = item;
            });

            if ($.fn.select2) {
                $('#firstDepartment, #secondDepartment').select2({
                    width: '100%',
                    placeholder: 'بەش هەڵبژێرە...',
                    allowClear: true
                });
            }

            const nonWordRegex = (() => {
                try {
                    return new RegExp('[^\\p{L}\\p{N}\\s]+', 'gu');
                } catch (e) {
                    return /[^A-Za-z0-9\s]+/g;
                }
            })();

            const wordRegex = (() => {
                try {
                    return new RegExp('([\\p{L}\\p{N}_]+)', 'gu');
                } catch (e) {
                    return /([A-Za-z0-9_]+)/g;
                }
            })();

            function escapeHtml(value) {
                return $('<div>').text(String(value ?? '')).html();
            }

            function htmlToPlainText(input) {
                const html = String(input || '');
                const temp = document.createElement('div');
                temp.innerHTML = html;
                const text = temp.textContent || temp.innerText || '';
                return String(text || '');
            }

            function normalizeSorani(text) {
                return String(text || '')
                    .replace(/\u200c/g, ' ')
                    .replace(/[يى]/g, 'ی')
                    .replace(/ك/g, 'ک')
                    .replace(/[ۀة]/g, 'ە')
                    .replace(/[إأٱآ]/g, 'ا')
                    .replace(/ـ/g, '')
                    .replace(/[\u064B-\u065F\u0670]/g, '');
            }

            function normalizeText(text) {
                return normalizeSorani(htmlToPlainText(String(text || '')))
                    .replace(/\s+/g, ' ')
                    .trim();
            }

            function tokenize(text) {
                const normalized = normalizeText(text)
                    .toLocaleLowerCase('ku')
                    .replace(nonWordRegex, ' ');

                return normalized.split(/\s+/).filter(Boolean);
            }

            function uniqueWords(words) {
                return Array.from(new Set(words));
            }

            function highlightUniqueWords(text, uniqueSet) {
                const source = htmlToPlainText(String(text || ''));
                if (!source.trim()) {
                    return '<span class="text-muted">هیچ وەسفێک تۆمار نەکراوە.</span>';
                }

                const regex = new RegExp(wordRegex.source, wordRegex.flags);
                let result = '';
                let lastIndex = 0;
                let match;

                while ((match = regex.exec(source)) !== null) {
                    const start = match.index;
                    const word = match[1];

                    result += escapeHtml(source.slice(lastIndex, start));

                    const key = normalizeSorani(String(word || '')).toLocaleLowerCase('ku');
                    if (uniqueSet.has(key)) {
                        result += `<mark class="diff-word">${escapeHtml(word)}</mark>`;
                    } else {
                        result += escapeHtml(word);
                    }

                    lastIndex = regex.lastIndex;
                }

                result += escapeHtml(source.slice(lastIndex));
                return result;
            }

            function renderWordList($list, words) {
                if (!words.length) {
                    $list.html('<li class="list-group-item text-muted">هیچ وشەی جیاواز نییە.</li>');
                    return;
                }

                const limit = 120;
                let html = '';
                words.slice(0, limit).forEach((word) => {
                    html += `<li class="list-group-item py-1">${escapeHtml(word)}</li>`;
                });

                if (words.length > limit) {
                    html +=
                        `<li class="list-group-item py-1 text-muted">... ${words.length - limit} وشەی تر</li>`;
                }

                $list.html(html);
            }

            function metaText(dep) {
                if (!dep) return '—';
                return `${dep.system} / ${dep.province} / ${dep.university} / ${dep.college}`;
            }

            function syncDescriptionInputFromSelect(selectId, inputId) {
                const depId = String($(selectId).val() || '');
                const dep = depId ? (departmentMap[depId] || null) : null;
                $(inputId).val(dep ? (dep.description || '') : '');
            }

            function resetResult() {
                $('#comparisonStatus').removeClass().addClass('alert alert-secondary').text(
                    'دوو بەش هەڵبژێرە بۆ بەراوردکردنی وەسف.'
                );
                $('#firstDeptMeta,#secondDeptMeta').text('—');
                $('#firstDescriptionInput,#secondDescriptionInput').val('');
                $('#firstDescription').html('<span class="text-muted">هیچ بەشێک هەڵنەبژێردراوە.</span>');
                $('#secondDescription').html('<span class="text-muted">هیچ بەشێک هەڵنەبژێردراوە.</span>');
                $('#firstWordCount,#secondWordCount,#commonWordCount,#diffFirstCount,#diffSecondCount').text('0');
                $('#similarityPercent').text('0%');
                renderWordList($('#uniqueFirstWords'), []);
                renderWordList($('#uniqueSecondWords'), []);
            }

            function renderComparison() {
                const firstId = String($('#firstDepartment').val() || '');
                const secondId = String($('#secondDepartment').val() || '');

                const first = departmentMap[firstId] || null;
                const second = departmentMap[secondId] || null;

                const firstTextRaw = String($('#firstDescriptionInput').val() || '');
                const secondTextRaw = String($('#secondDescriptionInput').val() || '');
                const firstText = htmlToPlainText(firstTextRaw);
                const secondText = htmlToPlainText(secondTextRaw);

                if (!first && !second && !firstText.trim() && !secondText.trim()) {
                    resetResult();
                    return;
                }

                if (!firstText.trim() && !secondText.trim()) {
                    $('#comparisonStatus').removeClass().addClass('alert alert-secondary').text(
                        'تکایە لە textarea وەسف بنووسە یان دوو بەش هەڵبژێرە.'
                    );
                    $('#firstDescription').html('<span class="text-muted">هیچ وەسفێک تۆمار نەکراوە.</span>');
                    $('#secondDescription').html('<span class="text-muted">هیچ وەسفێک تۆمار نەکراوە.</span>');
                    $('#firstWordCount,#secondWordCount,#commonWordCount,#diffFirstCount,#diffSecondCount').text('0');
                    $('#similarityPercent').text('0%');
                    renderWordList($('#uniqueFirstWords'), []);
                    renderWordList($('#uniqueSecondWords'), []);
                    return;
                }

                $('#firstDeptMeta').text(first ? metaText(first) : 'نووسینی دەستی');
                $('#secondDeptMeta').text(second ? metaText(second) : 'نووسینی دەستی');

                const firstTokens = uniqueWords(tokenize(firstText));
                const secondTokens = uniqueWords(tokenize(secondText));
                const firstSet = new Set(firstTokens);
                const secondSet = new Set(secondTokens);

                const onlyFirst = firstTokens.filter((word) => !secondSet.has(word));
                const onlySecond = secondTokens.filter((word) => !firstSet.has(word));
                const commonCount = firstTokens.length - onlyFirst.length;
                const maxLen = Math.max(firstTokens.length, secondTokens.length, 1);
                const similarity = Math.round((commonCount / maxLen) * 100);

                const normalizedFirst = normalizeText(firstText);
                const normalizedSecond = normalizeText(secondText);
                const sameDescription = normalizedFirst === normalizedSecond;

                $('#firstDescription').html(highlightUniqueWords(firstTextRaw, new Set(onlyFirst)));
                $('#secondDescription').html(highlightUniqueWords(secondTextRaw, new Set(onlySecond)));
                $('#firstWordCount').text(String(firstTokens.length));
                $('#secondWordCount').text(String(secondTokens.length));
                $('#commonWordCount').text(String(commonCount));
                $('#diffFirstCount').text(String(onlyFirst.length));
                $('#diffSecondCount').text(String(onlySecond.length));
                $('#similarityPercent').text(`${similarity}%`);

                renderWordList($('#uniqueFirstWords'), onlyFirst);
                renderWordList($('#uniqueSecondWords'), onlySecond);

                if (first && second && first.id === second.id) {
                    $('#comparisonStatus').removeClass().addClass('alert alert-info').text(
                        'ئێستا هەردوو هەڵبژاردنەکە هەمان بەشن.'
                    );
                    return;
                }

                if (sameDescription) {
                    $('#comparisonStatus').removeClass().addClass('alert alert-success').text(
                        'ئەنجام: وەسفەکان یەکسانن.'
                    );
                } else {
                    $('#comparisonStatus').removeClass().addClass('alert alert-warning').text(
                        `ئەنجام: وەسفەکان جیاوازن (${similarity}% نێزیکی).`
                    );
                }
            }

            $('#compareBtn').on('click', renderComparison);
            $('#firstDepartment').on('change', function() {
                syncDescriptionInputFromSelect('#firstDepartment', '#firstDescriptionInput');
                renderComparison();
            });
            $('#secondDepartment').on('change', function() {
                syncDescriptionInputFromSelect('#secondDepartment', '#secondDescriptionInput');
                renderComparison();
            });
            $('#firstDescriptionInput, #secondDescriptionInput').on('input', function() {
                renderComparison();
            });
            $('#resetBtn').on('click', function() {
                $('#firstDepartment').val('').trigger('change');
                $('#secondDepartment').val('').trigger('change');
                resetResult();
            });

            resetResult();
        });
    </script>
@endpush
