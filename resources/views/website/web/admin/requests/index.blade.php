{{-- resources/views/admin/requests/index.blade.php --}}
@extends('website.web.admin.layouts.app')

@section('title', 'بەڕێوەبردنی داواکاریەکان')

@section('content')
    <div class="container-fluid py-4">
        <!-- ئامارەکان -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-primary border-5 h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                    گشتی
                                </div>
                                <div class="h5 mb-0 fw-bold text-gray-800" id="totalRequests">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-inbox fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-warning border-5 h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                    چاوەڕوان
                                </div>
                                <div class="h5 mb-0 fw-bold text-gray-800" id="pendingRequests">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-success border-5 h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                    پەسەندکراو
                                </div>
                                <div class="h5 mb-0 fw-bold text-gray-800" id="approvedRequests">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-danger border-5 h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                                    ڕەتکراوە
                                </div>
                                <div class="h5 mb-0 fw-bold text-gray-800" id="rejectedRequests">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- کارتی سەرەکی -->
        <div class="card shadow mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-envelope-open-text me-2"></i>داواکاریەکانی بەشی زیاتر
                </h6>

                <div class="d-flex align-items-center">
                    <!-- فیلتەر -->
                    <div class="me-3">
                        <select class="form-select form-select-sm" id="statusFilter">
                            <option value="all">هەموو بارەکان</option>
                            <option value="pending" selected>چاوەڕوان</option>
                            <option value="approved">پەسەندکراو</option>
                            <option value="rejected">ڕەتکراوە</option>
                        </select>
                    </div>

                    <!-- گەڕان -->
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="گەڕان بە ناو یان کۆد...">
                        <button class="btn btn-primary" type="button" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive" id="requestsTableContainer">
                    @include('website.web.admin.requests.partials.requests-table', [
                        'requests' => $requests,
                    ])
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>
    </div>
    </div>
    </div>
@endsection

@push('styles')
    <style>
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            transition: transform 0.2s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            let currentRequestId = null;

            // بارکردنی ئامارەکان
            function loadStats() {
                $.ajax({
                    url: '{{ route('admin.requests.stats') }}',
                    method: 'GET',
                    success: function(data) {
                        $('#totalRequests').text(data.total);
                        $('#pendingRequests').text(data.pending);
                        $('#approvedRequests').text(data.approved);
                        $('#rejectedRequests').text(data.rejected);
                    }
                });
            }

            // بارکردنی سەرەتا
            loadStats();

            // گەڕان و فیلتەر
            function searchRequests() {
                const status = $('#statusFilter').val();
                const search = $('#searchInput').val();

                $.ajax({
                    url: '{{ route('admin.requests.search') }}',
                    method: 'GET',
                    data: {
                        status: status,
                        search: search
                    },
                    success: function(response) {
                        $('#requestsTableContainer').html(response.html);
                    }
                });
            }

            $('#statusFilter').change(searchRequests);
            $('#searchBtn').click(searchRequests);
            $('#searchInput').on('keyup', function(e) {
                if (e.key === 'Enter') searchRequests();
            });

            // سڕینەوەی داواکاری
            $(document).on('click', '.delete-btn', function() {
                const requestId = $(this).data('id');
                const requestName = $(this).data('name');

                if (confirm(`دڵنیای لە سڕینەوەی داواکاری "${requestName}"؟`)) {
                    $.ajax({
                        url: `/admin/requests/${requestId}`,
                        method: 'DELETE',
                        data: {
                            _token: csrfToken
                        },
                        success: function(response) {
                            searchRequests();
                            loadStats();
                            showToast('سەرکەوتوو', 'داواکاریەکە سڕدرایەوە.', 'success');
                        },
                        error: function(xhr) {
                            showToast('هەڵە', xhr.responseJSON?.message || 'هەڵەیەک ڕوویدا',
                                'error');
                        }
                    });
                }
            });

            // دانانی داواکاریەکانی چاوەڕوان لەسەرەوە
            function highlightPendingRequests() {
                $('tr').each(function() {
                    const statusBadge = $(this).find('.badge.bg-warning');
                    if (statusBadge.length > 0) {
                        $(this).addClass('table-warning');
                    }
                });
            }

            // پەیامەکان
            function showToast(title, message, type) {
                if (typeof Toast !== 'undefined') {
                    Toast.fire({
                        icon: type,
                        title: title,
                        text: message
                    });
                } else {
                    alert(title + ': ' + message);
                }
            }

            // Auto-refresh ئامارەکان هەر ٣٠ چرکە جارێک
            setInterval(loadStats, 30000);
        });
    </script>
@endpush
