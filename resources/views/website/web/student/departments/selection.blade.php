@extends('website.web.admin.layouts.app')

@section('title', 'هەڵبژاردنی بەشەکان')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Title & Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">هەڵبژاردنی بەشەکان</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-building-columns me-1"></i>
                        هەڵبژاردنی بەشەکان
                    </h4>
                </div>
            </div>
        </div>

        <!-- Header Info Card -->
        <div class="card glass border-0 shadow-sm mb-4 fade-in">
            <div class="card-header bg-primary text-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1"><i class="fas fa-university me-2"></i> زانیاری قوتابی</h4>
                        <p class="mb-0 opacity-75 small"> قوتابی: {{ $student->user->name }} | کۆد:
                            {{ $student->user->code }}</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4 text-center">
                    <div class="col-6 col-md-3">
                        <div class="p-3 border-soft rounded-4 bg-light shadow-none">
                            <div class="fs-4 fw-bold text-primary">{{ $student->mark }}</div>
                            <small class="text-muted fw-bold"> نمرەی کۆی قوتابی</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 border-soft rounded-4 bg-light shadow-none">
                            <div class="fs-4 fw-bold text-success">{{ $student->type }}</div>
                            <small class="text-muted fw-bold"> لقی قوتابی</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 border-soft rounded-4 bg-light shadow-none">
                            <div class="fs-4 fw-bold text-warning">{{ $maxSelections }}</div>
                            <small class="text-muted fw-bold"> سنووری هەڵبژاردن</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 border-soft rounded-4 bg-light shadow-none">
                            <div class="fs-4 fw-bold {{ $student->all_departments ? 'text-danger' : 'text-info' }}">
                                {{ $student->all_departments ? '٥٠ بەش' : '٢٠ بەش' }}
                            </div>
                            <small class="text-muted fw-bold"> جۆری هەڵبژاردن</small>
                        </div>
                    </div>
                </div>

                @if ($student->all_departments == 0 || $student->ai_rank == 0 || $student->gis == 0)
                    <div class="alert alert-soft-warning border-0 mt-4 d-flex align-items-center p-3 rounded-4">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-crown text-warning fs-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark p-1"> تایبەتمەندییە نایابەکان</div>
                            <div class="small text-muted p-1"> دەتوانیت AI، GIS و جێگای زیاتر بۆ بەشەکانت چالاک بکەیت.</div>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('student.departments.request-more') }}"
                                class="btn btn-warning btn-sm fw-bold px-3">
                                ناردنی داواکاری
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="row g-4">
            <!-- Available Departments (Right Side in RTL) -->
            <div class="col-xl-8">
                <!-- Filters Card -->
                <div class="card glass border-0 shadow-sm mb-4 fade-in">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <span class="avatar-title bg-soft-primary text-primary rounded-circle fs-4">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold"> گەڕان و فلتەرکردنی بەشەکان</h5>
                                <p class="text-muted small mb-0"> بەشە گونجاوەکان ڕەچاوکراون بەپێی نمرە و ڕەگەز و لقی
                                    خوێندنت</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted"> سیستەم</label>
                                <select id="filter-system" class="form-select border-soft">
                                    <option value=""> هەموو سیستەمەکان</option>
                                    @foreach ($systems as $system)
                                        <option value="{{ $system->id }}">{{ $system->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted"> پارێزگا</label>
                                <select id="filter-province" class="form-select border-soft">
                                    <option value=""> هەموو پارێزگاکان</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted"> زانکۆ</label>
                                <select id="filter-university" class="form-select border-soft">
                                    <option value=""> هەموو زانکۆکان</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"> کۆلێژ</label>
                                <select id="filter-college" class="form-select border-soft">
                                    <option value=""> هەموو کۆلێژەکان</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"> گەڕان</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-soft"><i
                                            class="fa-solid fa-search"></i></span>
                                    <input id="filter-search" type="text" class="form-control border-soft"
                                        placeholder=" بگەڕێ بۆ ناوی بەش...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Database Table Card -->
                <div class="card glass border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-soft-light border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-list-ul me-2"></i> بەشە بەردەستەکان</h6>
                    </div>
                    <div class="p-3">
                        <table id="depts-table" class="table table-hover align-middle mb-0" style="width:100%">
                            <thead class="bg-soft-secondary text-muted small">
                                <tr>
                                    <th> بەش و زانیاری</th>
                                    <th class="text-center"> نمرە</th>
                                    <th class="text-center"> سیستەم</th>
                                    <th class="text-center"> کردار</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ranked Selection List (Left Side in RTL) -->
            <div class="col-xl-4">
                <div class="card glass border-0 shadow-lg sticky-top" style="top: 2rem; z-index: 100;">
                    <div
                        class="card-header bg-primary text-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-white"><i class="fa-solid fa-ranking-star me-2"></i> ڕێزبەندی
                            هەڵبژاردنەکان</h6>
                        <span id="selected-count-badge"
                            class="badge bg-white text-primary px-3">{{ $selectedDepartments->count() }} /
                            {{ $maxSelections }}</span>
                    </div>
                    <div class="card-body p-0" style="max-height: 70vh; overflow-y: auto;">
                        <div class="list-group list-group-flush" id="selected-list">
                            @forelse ($selectedDepartments as $index => $item)
                                <div class="list-group-item bg-transparent border-soft py-3 selected-dept-row"
                                    data-id="{{ $item->department_id }}">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 p-1">
                                            <i class="fa-solid fa-grip-vertical text-muted cursor-move drag-handle"></i>
                                        </div>
                                        <div class="me-3 text-muted fw-bold rank-number" style="width: 25px;">
                                            {{ $index + 1 }}</div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-dark small">{{ $item->department->name }} 
                                                <span style="font-weight: bold !important;" class="badge {{ $item->department->system->name == 'زانکۆلاین' ? 'bg-soft-success border-success text-success bg-light' : ($item->department->system->name == 'پاراڵیل' ? 'bg-soft-danger border-danger text-danger bg-light' : 'bg-soft-dark text-dark') }}">
                                                    {{ $item->department->system->name }}
                                                </span>
                                            </div>
                                            <div style="font-size: 12px;" class="text-muted smaller">{{ $item->department->university->name }}/{{ $item->department->college->name }}
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-soft-danger rounded-circle p-0 remove-btn ms-2"
                                            style="width: 30px; height: 30px;">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="p-5 text-center no-depts-msg">
                                    <i class="fa-solid fa-list-ol fa-3x text-muted opacity-25 mb-3 d-block"></i>
                                    <p class="text-muted small"> هێشتا چ بەشێکت هەڵنەبژاردووە بۆ ڕێزبەندکردن.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="card-footer bg-soft-light border-0 p-3">
                        <div id="unsaved-alert" class="alert alert-warning border-0 small py-2 px-3 mb-3 d-none">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> گۆڕانکارییەکان هێشتا پاشەکەوت نەکراون!
                        </div>
                        <button id="save-ranking-btn" class="btn btn-success w-100 py-2 fw-bold shadow-sm"
                            {{ $selectedDepartments->count() === 0 ? 'disabled' : '' }}>
                            <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوتکردنی ڕێزبەندی
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .bg-soft-primary {
            background-color: rgba(108, 99, 255, 0.1);
        }

        .bg-soft-secondary {
            background-color: rgba(108, 117, 125, 0.1);
        }

        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .bg-soft-danger {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .bg-soft-light {
            background-color: rgba(243, 246, 249, 0.8);
        }

        .border-soft {
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        .smaller {
            font-size: 0.75rem;
        }

        .btn-xs {
            padding: 0.1rem 0.4rem;
            font-size: 0.7rem;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
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

        .cursor-move {
            cursor: move;
        }

        .sortable-ghost {
            opacity: 0.4;
            background: #f0f0f0;
        }

        /* DataTables Custom Styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.2em 0.8em;
        }

        .dataTables_filter {
            display: none;
        }

        /* We use custom search */
        table.dataTable thead th {
            border-bottom: none;
        }

        table.dataTable td {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const maxSelections = {{ $maxSelections }};
            const studentId = {{ $student->id }};
            let unsavedChanges = false;
            let selectedIds = @json($selectedDepartments->pluck('department_id'));

            // Initialize DataTable
            const table = $('#depts-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('student.departments.available-api') }}",
                    data: function(d) {
                        d.system_id = $('#filter-system').val();
                        d.province_id = $('#filter-province').val();
                        d.university_id = $('#filter-university').val();
                        d.college_id = $('#filter-college').val();
                        d.search_val = $('#filter-search').val();
                    }
                },
                columns: [{
                        data: 'name',
                        render: function(data, type, row) {
                            return `<div><div class="fw-bold text-dark">${data}</div><div class="text-muted smaller">${row.province} • ${row.university} • ${row.college}</div></div>`;
                        }
                    },
                    {
                        data: 'local_score',
                        className: 'text-center',
                        render: data =>
                            `<span class="badge bg-soft-primary text-primary px-3 py-2">${data}</span>`
                    },
                    {
                        data: 'system_name',
                        className: 'text-center',
                        render: (data, type, row) => {
                            let badgeClass = 'bg-soft-dark text-dark';
                            if (data === 'زانکۆلاین') badgeClass = 'bg-soft-success border-success text-success bg-light';
                            else if (data === 'پاراڵیل') badgeClass = 'bg-soft-danger border-danger text-danger bg-light';
                            return `<span class="badge ${badgeClass} px-2 py-1">${data}</span>`;
                        }
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        sortable: false,
                        render: function(data, type, row) {
                            const isSelected = selectedIds.includes(data);
                            return `<button class="btn btn-sm w-100 add-dept-btn ${isSelected ? 'btn-soft-success' : 'btn-primary shadow-sm'}" data-id="${data}" data-name="${row.name}" data-uni="${row.university}" ${isSelected ? 'disabled' : ''}>
                                    ${isSelected ? '<i class="fa-solid fa-check me-1"></i> هەڵبژێردرا' : '<i class="fa-solid fa-plus me-1"></i> زیادکردن'}
                                </button>`;
                        }
                    }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/ku.json" ||
                        "" // Kurdish language if available
                },
                pageLength: 10,
                dom: 'rtp',
            });

            // Filters implementation
            $('#filter-system, #filter-province, #filter-university, #filter-college').on('change', () => table.ajax
                .reload());
            $('#filter-search').on('keyup', function() {
                if (window.searchTimeout) clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(() => table.ajax.reload(), 300);
            });

            // Cascading filters
            $('#filter-province').on('change', function() {
                const id = $(this).val();
                $('#filter-university').html('<option value=""> هەموو زانکۆکان</option>').prop('disabled', !
                    id);
                $('#filter-college').html('<option value=""> هەموو کۆلێژەکان</option>').prop('disabled',
                    true);
                if (id) {
                    $.get(`/s/universities-by-province/${id}`, function(data) {
                        data.forEach(uni => $('#filter-university').append(
                            `<option value="${uni.id}">${uni.name}</option>`));
                    });
                }
            });

            $('#filter-university').on('change', function() {
                const id = $(this).val();
                $('#filter-college').html('<option value=""> هەموو کۆلێژەکان</option>').prop('disabled', !
                    id);
                if (id) {
                    $.get(`/s/colleges-by-university/${id}`, function(data) {
                        data.forEach(col => $('#filter-college').append(
                            `<option value="${col.id}">${col.name}</option>`));
                    });
                }
            });

            // Sortable implementation
            const sortable = new Sortable(document.getElementById('selected-list'), {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                onEnd: function() {
                    updateRankingNumbers();
                    markUnsaved();
                }
            });

            // Add Department logic
            $(document).on('click', '.add-dept-btn', function() {
                if (selectedIds.length >= maxSelections) {
                    Swal.fire('هەڵە', `تۆ ناتوانیت زیاتر لە ${maxSelections} بەش هەڵبژێریت.`, 'error');
                    return;
                }

                const btn = $(this);
                const id = btn.data('id');
                const name = btn.data('name');
                const uni = btn.data('uni');

                if (!selectedIds.includes(id)) {
                    selectedIds.push(id);
                    $('.no-depts-msg').addClass('d-none');
                    const rank = selectedIds.length;
                    const html = `
                        <div class="list-group-item bg-transparent border-soft py-3 selected-dept-row" data-id="${id}">
                            <div class="d-flex align-items-center">
                                <div class="me-3 p-1">
                                    <i class="fa-solid fa-grip-vertical text-muted cursor-move drag-handle"></i>
                                </div>
                                <div class="me-3 text-muted fw-bold rank-number" style="width: 25px;">${rank}</div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark small">${name}</div>
                                    <div class="text-muted smaller">${uni}</div>
                                </div>
                                <button class="btn btn-sm btn-soft-danger rounded-circle p-0 remove-btn ms-2" style="width: 30px; height: 30px;">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>`;

                    $('#selected-list').append(html);
                    updateCounters();
                    markUnsaved();
                    table.ajax.reload(null, false);
                }
            });

            // Remove logic
            $(document).on('click', '.remove-btn', function() {
                const row = $(this).closest('.selected-dept-row');
                const id = row.data('id');
                selectedIds = selectedIds.filter(i => i != id);
                row.remove();
                if (selectedIds.length === 0) $('.no-depts-msg').removeClass('d-none');
                updateRankingNumbers();
                updateCounters();
                markUnsaved();
                table.ajax.reload(null, false);
            });

            // Save Function
            $('#save-ranking-btn').on('click', function() {
                const btn = $(this);
                const originalHtml = btn.html();
                const ids = [];
                $('.selected-dept-row').each(function() {
                    ids.push($(this).data('id'));
                });

                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> خەریکی پاشەکەوتکردنە...'
                );

                $.ajax({
                    url: "{{ route('student.departments.save-ranking') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        department_ids: ids
                    },
                    success: function(res) {
                        unsavedChanges = false;
                        $('#unsaved-alert').addClass('d-none');
                        Swal.fire('سەرکەوتوو', res.message, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('هەڵە', xhr.responseJSON?.message || 'کێشەیەک ڕوویدا',
                            'error');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            function updateRankingNumbers() {
                $('.selected-dept-row').each(function(idx) {
                    $(this).find('.rank-number').text(idx + 1);
                    $(this).find('.move-up').prop('disabled', idx === 0);
                    $(this).find('.move-down').prop('disabled', idx === $('.selected-dept-row').length - 1);
                });
            }

            function updateCounters() {
                const count = selectedIds.length;
                $('#selected-count-badge').text(`${count} / ${maxSelections}`);
                $('#save-ranking-btn').prop('disabled', count === 0);
            }

            function markUnsaved() {
                unsavedChanges = true;
                $('#unsaved-alert').removeClass('d-none');
            }
        });
    </script>
@endpush
