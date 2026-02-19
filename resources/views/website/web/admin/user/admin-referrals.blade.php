@extends('website.web.admin.layouts.app')

@section('page_name', 'users')
@section('view_name', 'admin-referrals')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right d-flex align-items-center gap-2">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">بەکارهێنەرەکان</a></li>
                            <li class="breadcrumb-item active">بەکارهێنەری بە admin دروستکراو</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fa-solid fa-user-shield me-2"></i>
                        بەکارهێنەرەکانی سەر بە ئەدمین
                    </h4>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە بۆ هەموو بەکارهێنەران
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-user-plus me-1"></i> زیادکردنی بەکارهێنەر
            </a>
        </div>

        <div class="alert alert-info d-flex flex-wrap gap-3 align-items-center">
            <span><strong>ئەدمین:</strong> {{ $primaryAdmin->name }}</span>
            <span><strong>ID:</strong> {{ $primaryAdmin->id }}</span>
            <span><strong>Rand Code:</strong> {{ $adminRandCode }}</span>
            <span class="text-muted">لیستەکە referral_code = {{ $adminRandCode }} یان 0 پیشان دەدات.</span>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <small class="text-muted d-block">کۆی گشتی</small>
                        <h5 class="mb-0">{{ $stats['total'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <small class="text-muted d-block">سەنتەر</small>
                        <h5 class="mb-0">{{ $stats['centers'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <small class="text-muted d-block">مامۆستا</small>
                        <h5 class="mb-0">{{ $stats['teachers'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <small class="text-muted d-block">قوتابی</small>
                        <h5 class="mb-0">{{ $stats['students'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <small class="text-muted d-block">چاڵاک</small>
                        <h5 class="mb-0 text-success">{{ $stats['active'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <small class="text-muted d-block">ناچاڵاک</small>
                        <h5 class="mb-0 text-danger">{{ $stats['inactive'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <input type="text" id="users-search" class="form-control"
                            placeholder="گەڕان بە ناو/کۆد/تەلەفۆن...">
                    </div>
                    <div class="col-md-3">
                        <select id="users-role-filter" class="form-select">
                            <option value="">هەموو role ـەکان</option>
                            <option value="center">Center</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="users-status-filter" class="form-select">
                            <option value="">هەموو دۆخەکان</option>
                            <option value="1">چاڵاک</option>
                            <option value="0">ناچاڵاک</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle" id="users-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ناو</th>
                                <th>کۆد</th>
                                <th>Rand</th>
                                <th>Role</th>
                                <th>Referral</th>
                                <th>دۆخ</th>
                                <th>تەلەفۆن</th>
                                <th>کردار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                                @php
                                    $referralCode = null;
                                    if ($user->role === 'center') {
                                        $referralCode = data_get($user, 'center.referral_code');
                                    } elseif ($user->role === 'teacher') {
                                        $referralCode = data_get($user, 'teacher.referral_code');
                                    } elseif ($user->role === 'student') {
                                        $referralCode = data_get($user, 'student.referral_code');
                                    }
                                @endphp

                                <tr data-role="{{ $user->role }}" data-status="{{ (int) $user->status }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                    </td>
                                    <td>{{ $user->code }}</td>
                                    <td>{{ $user->rand_code }}</td>
                                    <td>
                                        @if ($user->role === 'center')
                                            <span class="badge bg-danger">Center</span>
                                        @elseif ($user->role === 'teacher')
                                            <span class="badge bg-warning text-dark">Teacher</span>
                                        @else
                                            <span class="badge bg-info text-dark">Student</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ((string) $referralCode === '0')
                                            <span class="badge bg-secondary">0</span>
                                        @else
                                            <span class="badge bg-primary">{{ $referralCode }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->status)
                                            <span class="badge bg-success">چاڵاک</span>
                                        @else
                                            <span class="badge bg-secondary">ناچاڵاک</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->phone ?: '—' }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                                class="btn btn-sm btn-outline-secondary" title="بینین">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="دەستکاری">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">هیچ بەکارهێنەرێکی سەر بە ئەدمین
                                        نەدۆزرایەوە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('users-search');
            const roleFilter = document.getElementById('users-role-filter');
            const statusFilter = document.getElementById('users-status-filter');
            const tableRows = Array.from(document.querySelectorAll('#users-table tbody tr'));

            function applyFilter() {
                const q = (searchInput.value || '').toLowerCase().trim();
                const role = roleFilter.value;
                const status = statusFilter.value;

                tableRows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    const rowRole = row.getAttribute('data-role');
                    const rowStatus = row.getAttribute('data-status');

                    const matchesSearch = q === '' || text.includes(q);
                    const matchesRole = role === '' || role === rowRole;
                    const matchesStatus = status === '' || status === rowStatus;

                    row.style.display = matchesSearch && matchesRole && matchesStatus ? '' : 'none';
                });
            }

            if (searchInput) searchInput.addEventListener('input', applyFilter);
            if (roleFilter) roleFilter.addEventListener('change', applyFilter);
            if (statusFilter) statusFilter.addEventListener('change', applyFilter);
        });
    </script>
@endpush
