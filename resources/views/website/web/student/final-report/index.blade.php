@extends('website.web.admin.layouts.app')

@section('title', 'لیستی کۆتایی')

@section('content')
    @php
        $studentProvinceId = (int) ($student->province_id ?? 0);
        $studentMeta = [
            'name' => $student->user->name ?? '-',
            'code' => $student->user->code ?? '-',
            'mark' => rtrim(rtrim(number_format((float) ($student->mark ?? 0), 3, '.', ''), '0'), '.'),
            'province' => $student->province ?? '-',
            'type' => $student->type ?? '-',
            'gender' => $student->gender ?? '-',
        ];
    @endphp

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">لیستی کۆتایی</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-file-invoice me-1"></i>
                        ڕاپۆرتی کۆتایی
                    </h4>
                </div>
            </div>
        </div>

        <div class="card glass border-0 shadow-sm mb-4 fade-in">
            <div class="card-header bg-gradient-primary border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1"><i class="fas fa-user-graduate me-2"></i> پوختەی زانیارییەکان</h4>
                        <p class="mb-0 opacity-75 small">قوتابی: {{ $studentMeta['name'] }} | نمرە:
                            {{ $studentMeta['mark'] }} |
                            لق: {{ $studentMeta['type'] }}</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-light btn-sm fw-bold" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> چاپی پەڕە
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card glass border-0 shadow-sm h-100 overflow-hidden">
                    <div
                        class="card-header bg-soft-primary border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="fa-solid fa-list-check me-2"></i>
                            لیستی بەشە هەڵبژێردراوەکان
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary px-3 py-2">{{ $chosenDepartments->count() }} بەش</span>
                            <button type="button" class="btn btn-outline-primary btn-sm print-section-btn"
                                data-print-table="selectedDepartmentsTable" data-print-title="لیستی بەشە هەڵبژێردراوەکان">
                                <i class="fas fa-print me-1"></i> چاپ
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="selectedDepartmentsTable" class="table table-hover align-middle mb-0"
                                data-select-url="{{ route('student.departments.select-final') }}">
                                <thead class="bg-light text-muted smaller">
                                    <tr>
                                        <th class="ps-3" style="width: 50px;">ڕیز</th>
                                        <th>زانیاری بەش</th> 
                                        <th class="text-center no-print-col" style="width: 120px;">هەڵبژاردن</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($chosenDepartments as $item)
                                        @php $isFinal = !is_null($item->result_rank); @endphp
                                        <tr class="{{ $isFinal ? 'table-success final-selected' : '' }}">
                                            <td class="ps-3 fw-bold text-muted">{{ $item->rank }}</td>
                                            <td>
                                                    <span
                                                        class="badge {{ $item->department->system->id == 1 ? 'bg-success text-white' : ($item->department->system->id == 2 ? 'bg-danger text-white' : 'bg-dark text-white') }}">
                                                        {{ $item->department->system->name ?? '-' }}
                                                    </span> /
                                                    {{ $item->department->province->name ?? '-' }} /
                                                    {{ $item->department->university->name ?? '-' }} /
                                                    {{ $item->department->college->name ?? '-' }} /
                                                    <b>{{ $item->department->name }}</b>
                                                </td>   
                                            <td class="text-center no-print-col">
                                                <div class="form-check d-inline-flex align-items-center gap-2">
                                                    <input class="form-check-input final-select-input" type="radio"
                                                        name="final_selection" data-id="{{ $item->id }}"
                                                        data-current="{{ $isFinal ? '1' : '0' }}"
                                                        {{ $isFinal ? 'checked' : '' }}>
                                                    <span
                                                        class="badge bg-success final-badge {{ $isFinal ? '' : 'd-none' }}">هەڵبژێردراو</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="p-5 text-center">
                                                <i class="fas fa-folder-open fa-3x text-muted opacity-25 mb-3 d-block"></i>
                                                <p class="text-muted">هێشتا هیچ بەشێکت هەڵنەبژاردووە.</p>
                                                <a href="{{ route('student.departments.selection') }}"
                                                    class="btn btn-primary btn-sm mt-2">چوون بۆ هەڵبژاردن</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card glass border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-header bg-soft-info border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-info">
                            <i class="fa-solid fa-robot me-2"></i>
                            ڕێزبەندی ئۆتۆماتیکی AI
                        </h6>
                        @if ((int) ($student->ai_rank ?? 0) === 1)
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-info text-dark px-3 py-2">{{ $aiRankings->count() }} بەش</span>
                                <button type="button" class="btn btn-outline-info btn-sm print-section-btn"
                                    data-print-table="aiRankingTable" data-print-title="ڕێزبەندی ئۆتۆماتیکی AI">
                                    <i class="fas fa-print me-1"></i> چاپ
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        @if ((int) ($student->ai_rank ?? 0) === 1)
                            <div class="table-responsive">
                                <table id="aiRankingTable" class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-muted smaller">
                                        <tr>
                                            <th class="ps-3" style="width: 50px;">ڕیز</th>
                                            <th>زانیاری بەش</th>
                                            <th class="text-center">نمرە</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($aiRankings as $item)
                                            @php
                                                $isLocal = (int) $item->department->province_id === $studentProvinceId;
                                                $requiredScore = $isLocal
                                                    ? (float) $item->department->local_score
                                                    : (float) $item->department->external_score;
                                            @endphp
                                            <tr>
                                                <td class="ps-3 fw-bold text-muted">{{ $item->rank }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $item->department->system->id == 1 ? 'bg-success text-white' : ($item->department->system->id == 2 ? 'bg-danger text-white' : 'bg-dark text-white') }}">
                                                        {{ $item->department->system->name ?? '-' }}
                                                    </span> /
                                                    {{ $item->department->province->name ?? '-' }} /
                                                    {{ $item->department->university->name ?? '-' }} /
                                                    {{ $item->department->college->name ?? '-' }} /
                                                    <b>{{ $item->department->name }}</b>
                                                    <span
                                                        class="badge {{ $isLocal ? 'bg-success' : 'bg-warning text-dark' }}">
                                                        {{ $isLocal ? 'ناو پارێزگا' : 'دەرەوەی پارێزگا' }}
                                                    </span>
                                                </td>
                                                <td class="text-center fw-bold">
                                                    {{ rtrim(rtrim(number_format($requiredScore, 3, '.', ''), '0'), '.') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="p-5 text-center">
                                                    <i class="fas fa-robot fa-3x text-muted opacity-25 mb-3 d-block"></i>
                                                    <p class="text-muted">هێشتا ڕێزبەندی AI ئەنجام نەدراوە.</p>
                                                    <a href="{{ route('student.ai-ranking.preferences') }}"
                                                        class="btn btn-info btn-sm text-white mt-2">ئەنجامدانی ڕێزبەندی</a>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-5 text-center">
                                <div class="mb-4">
                                    <div class="icon-box-lg bg-soft-warning rounded-circle mx-auto mb-3">
                                        <i class="fas fa-lock text-warning fa-2x"></i>
                                    </div>
                                    <h5 class="fw-bold">تایبەتمەندی AI چالاک نییە</h5>
                                    <p class="text-muted px-4">بۆ بەکارهێنانی ڕێزبەندی ئۆتۆماتیکی، پێویستە داواکاری
                                        بنێریت.</p>
                                </div>
                                <a href="{{ route('student.features.request') }}"
                                    class="btn btn-warning fw-bold px-4 rounded-pill shadow-sm">
                                    <i class="fas fa-paper-plane me-1"></i> ناردنی داواکاری
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div id="printFooterContent" class="d-none">
            @include('website.web.admin.layouts.footer')
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #6C63FF 0%, #3F3D56 100%);
        }

        .bg-soft-primary {
            background: rgba(108, 99, 255, 0.1);
        }

        .bg-soft-info {
            background: rgba(13, 202, 240, 0.1);
        }

        .bg-soft-warning {
            background: rgba(255, 193, 7, 0.1);
        }

        .smaller {
            font-size: 0.75rem;
        }

        .icon-box-lg {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        .final-selected td {
            font-weight: 600;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media print {

            .btn,
            .breadcrumb,
            .page-title-right,
            .no-print-col {
                display: none !important;
            }

            .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            const table = $('#selectedDepartmentsTable');
            if (table.length) {
                const url = table.data('select-url');
                const token = "{{ csrf_token() }}";
                const studentMeta = @json($studentMeta);

                const printConfig = {
                    logo: "{{ asset($appSettings['site_logo'] ?? 'images/logo.png') }}",
                    siteName: "{{ $appSettings['site_name'] ?? 'ToCollegePortal' }}",
                    author: "{{ $appSettings['site_author'] ?? 'ئەندازیار عبدالرحمن' }}",
                    authorPhone: "{{ $appSettings['author_phone'] ?? '075043424' }}",
                    currentUser: "{{ auth()->user()->name ?? '' }}",
                    currentRole: "{{ auth()->user()->role === 'admin' ? 'بەرێوبەر' : (auth()->user()->role === 'center' ? 'سەنتەر' : (auth()->user()->role === 'teacher' ? 'مامۆستا' : 'قوتابی')) }}",
                    fontKu: "{{ isset($appSettings['font_ku']) ? asset($appSettings['font_ku']) : '' }}",
                    fontAr: "{{ isset($appSettings['font_ar']) ? asset($appSettings['font_ar']) : '' }}",
                    fontEn: "{{ isset($appSettings['font_en']) ? asset($appSettings['font_en']) : '' }}"
                };

                function escapeHtml(value) {
                    return String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/\"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }

                function buildPrintStyles() {
                    return `
                        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;700&display=swap');
                        ${printConfig.fontKu ? `@font-face { font-family: 'CustomKu'; src: url('${printConfig.fontKu}'); font-display: swap; }` : ''}
                        ${printConfig.fontAr ? `@font-face { font-family: 'CustomAr'; src: url('${printConfig.fontAr}'); font-display: swap; }` : ''}
                        body {
                            font-family: ${printConfig.fontKu ? "'CustomKu'," : ''} ${printConfig.fontAr ? "'CustomAr'," : ''} "Noto Sans Arabic", "Tahoma", sans-serif;
                            background: #fff;
                            color: #000 !important;
                            padding: 20px;
                            min-height: 100vh;
                            position: relative;
                        }
                        .watermark {
                            position: fixed;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%) rotate(-45deg);
                            text-align: center;
                            font-weight: 900;
                            color: rgba(0, 0, 0, 0.04);
                            z-index: 100;
                            pointer-events: none;
                            white-space: nowrap;
                        }
                        .watermark-main { font-size: 90px; }
                        .watermark-sub { font-size: 70px; display: block; margin-top: 10px; }
                        .watermark-sub-phone { font-size: 70px; display: block; margin-top: 10px; }
                        .print-header {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            border-bottom: 2px solid #000;
                            padding-bottom: 15px;
                            margin-bottom: 20px;
                        }
                        .header-logo img { height: 60px; width: auto; }
                        .header-info { text-align: right; }
                        .header-name { font-size: 20px; font-weight: 800; color: #000; }
                        .header-date { font-size: 12px; color: #666; margin-top: 5px; }
                        .student-meta {
                            margin-bottom: 12px;
                            font-size: 10px;
                            color: #1f2937;
                            border: 1px solid #d1d5db;
                            border-radius: 6px;
                            padding: 6px 8px;
                            background: #f9fafb;
                        }
                        .student-meta-row {
                            display: flex;
                            flex-wrap: wrap;
                            align-items: center;
                            gap: 4px 14px;
                        }
                        .student-meta-item { white-space: nowrap; }
                        .student-meta-label {
                            font-weight: 700;
                            color: #111827;
                            margin-left: 4px;
                        }
                        table { width: 100%; margin-bottom: 20px; font-size: 12px; }
                        th, td { border: 1px solid #dee2e6; padding: 6px 8px; vertical-align: middle; }
                        th { background-color: #f8f9fa !important; color: #000; font-weight: 800; border-bottom: 2px solid #000; }
                        tr:nth-child(even) { background-color: #fcfcfc; }
                        #print-footer { margin-top: auto; padding-top: 15px; border-top: 1px solid #eee; font-size: 10px; }
                        #print-footer .bg-dark { background: none !important; }
                        #print-footer .text-light { color: #000 !important; }
                        #print-footer .footer { margin-top: 0 !important; padding-top: 5px !important; }
                        .footer-logo i { color: #000 !important; }
                    `;
                }

                function getPrintableRowCount(tableEl) {
                    if (!tableEl || !tableEl.tBodies.length) {
                        return 0;
                    }

                    const bodyRows = Array.from(tableEl.tBodies[0].rows || []);
                    return bodyRows.filter((row) => row.querySelectorAll('td').length > 1).length;
                }

                function buildPrintableBody(title, now, clonedTableHtml, rowsCount) {
                    const footerHtml = $('#printFooterContent').html() || '';
                    return `
                        <div class="watermark">
                            <div class="watermark-main">${printConfig.siteName}</div>
                            <div class="watermark-sub">${printConfig.author}</div>
                            <div class="watermark-sub-phone">${printConfig.authorPhone}</div>
                        </div>
                        <div class="print-header">
                            <div class="header-info">
                                <div class="header-name">${escapeHtml(printConfig.author)}</div>
                                <div class="header-date">بەروار: ${escapeHtml(now)}</div>
                                <div class="mt-1 small">کۆی ڕیزەکان: ${rowsCount}</div>
                            </div>
                            <div class="header-logo">
                                <img src="${printConfig.logo}" alt="Logo">
                            </div>
                        </div>
                        <div class="student-meta">
                            <div class="student-meta-row justify-content-between align-items-center m-auto">
                                <span class="student-meta-item"><span class="student-meta-label">ناوی قوتابی:</span>${escapeHtml(studentMeta.name)}</span>
                                <span class="student-meta-item"><span class="student-meta-label">کۆد:</span>${escapeHtml(studentMeta.code)}</span>
                                <span class="student-meta-item"><span class="student-meta-label">نمرە:</span>${escapeHtml(studentMeta.mark)}</span>
                                <span class="student-meta-item"><span class="student-meta-label">پارێزگا:</span>${escapeHtml(studentMeta.province)}</span>
                                <span class="student-meta-item"><span class="student-meta-label">لق:</span>${escapeHtml(studentMeta.type)}</span>
                                <span class="student-meta-item"><span class="student-meta-label">ڕەگەز:</span>${escapeHtml(studentMeta.gender)}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                            <div class="fw-bold fs-6">ناو : ${escapeHtml(printConfig.currentUser)}</div>
                            <h5 class="fw-bold m-0">${escapeHtml(title)}</h5>
                            <div class="fw-bold fs-6">پیشە : ${escapeHtml(printConfig.currentRole)}</div>
                        </div>
                        ${clonedTableHtml}
                        <div id="print-footer">${footerHtml}</div>
                    `;
                }

                function printTable(tableId, title) {
                    const tableEl = document.getElementById(tableId);
                    if (!tableEl) return;

                    const rowsCount = getPrintableRowCount(tableEl);
                    const cloned = tableEl.cloneNode(true);
                    cloned.querySelectorAll('.no-print-col').forEach(el => el.remove());
                    cloned.querySelectorAll('input, button').forEach(el => el.remove());

                    const now = new Date().toLocaleString('en-GB');
                    const w = window.open('', '_blank');
                    if (!w) return;

                    w.document.write(`
                        <html lang="ku" dir="rtl">
                            <head>
                                <meta charset="utf-8">
                                <title>${escapeHtml(title)}</title>
                                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
                                <style>${buildPrintStyles()}</style>
                            </head>
                            <body>
                                ${buildPrintableBody(title, now, cloned.outerHTML, rowsCount)}
                            </body>
                        </html>
                    `);

                    w.document.close();
                    w.focus();
                    setTimeout(() => w.print(), 300);
                }

                table.on('change', '.final-select-input', function() {
                    const input = $(this);
                    const prev = table.find('.final-select-input[data-current="1"]');
                    const resultDepId = input.data('id');

                    $.ajax({
                        url,
                        method: 'POST',
                        data: {
                            _token: token,
                            result_dep_id: resultDepId
                        },
                        success: function(res) {
                            table.find('tbody tr').removeClass('table-success final-selected');
                            table.find('.final-badge').addClass('d-none');
                            table.find('.final-select-input').attr('data-current', '0');

                            const row = input.closest('tr');
                            row.addClass('table-success final-selected');
                            row.find('.final-badge').removeClass('d-none');
                            input.attr('data-current', '1');

                            if (typeof toastr !== 'undefined') {
                                toastr.success(res.message || 'سەرکەوتوو');
                            }
                        },
                        error: function(xhr) {
                            const msg = xhr.responseJSON?.message || 'هەڵەیەک ڕوویدا';
                            if (typeof toastr !== 'undefined') {
                                toastr.error(msg);
                            } else {
                                alert(msg);
                            }

                            if (prev.length) {
                                prev.prop('checked', true);
                            } else {
                                input.prop('checked', false);
                            }
                        }
                    });
                });
            }

            document.querySelectorAll('.print-section-btn').forEach((btn) => {
                btn.addEventListener('click', function() {
                    const tableId = this.getAttribute('data-print-table');
                    const title = this.getAttribute('data-print-title') || 'چاپ';
                    printTable(tableId, title);
                });
            });
        });
    </script>
@endpush
