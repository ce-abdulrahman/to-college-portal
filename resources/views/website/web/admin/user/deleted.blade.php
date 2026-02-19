@extends('website.web.admin.layouts.app')

@section('page_name', 'users')
@section('view_name', 'deleted')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right d-flex align-items-center gap-2">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">بەکارهێنەران</a></li>
                            <li class="breadcrumb-item active">سڕاوەکان</li>
                        </ol>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
                        </a>
                    </div>
                    <h4 class="page-title">
                        <i class="fa-solid fa-trash-can me-2"></i>
                        لیستی بەکارهێنەری سڕاوەکان
                    </h4>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3 col-xl-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <small class="text-muted d-block">کۆی سڕاوەکان</small>
                        <h5 class="mb-0">{{ $stats['total'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <small class="text-muted d-block">Admin</small>
                        <h5 class="mb-0">{{ $stats['admins'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <small class="text-muted d-block">Center</small>
                        <h5 class="mb-0">{{ $stats['centers'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <small class="text-muted d-block">Teacher</small>
                        <h5 class="mb-0">{{ $stats['teachers'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <small class="text-muted d-block">Student</small>
                        <h5 class="mb-0">{{ $stats['students'] ?? 0 }}</h5>
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
                            <option value="admin">Admin</option>
                            <option value="center">Center</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
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
                                <th>کاتی سڕینەوە</th>
                                <th>کردار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr data-role="{{ $user->role }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                    </td>
                                    <td>{{ $user->code }}</td>
                                    <td>{{ $user->rand_code }}</td>
                                    <td>
                                        @if ($user->role === 'admin')
                                            <span class="badge bg-dark">Admin</span>
                                        @elseif ($user->role === 'center')
                                            <span class="badge bg-danger">Center</span>
                                        @elseif ($user->role === 'teacher')
                                            <span class="badge bg-warning text-dark">Teacher</span>
                                        @else
                                            <span class="badge bg-info text-dark">Student</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($user->deleted_at)->format('Y-m-d H:i') ?: '—' }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    title="گەڕاندنەوە">
                                                    <i class="fa-solid fa-rotate-left"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.users.force-destroy', $user->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('ئەم بەکارهێنەرە بە تەواوی دەسڕدرێتەوە. دڵنیایت؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    title="سڕینەوەی تەواو">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        هیچ بەکارهێنەرێکی سڕاوە نییە.
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('users-search');
            const roleFilter = document.getElementById('users-role-filter');
            const tableRows = Array.from(document.querySelectorAll('#users-table tbody tr'));

            function applyFilter() {
                const q = (searchInput.value || '').toLowerCase().trim();
                const role = roleFilter.value;

                tableRows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    const rowRole = row.getAttribute('data-role');

                    const matchesSearch = q === '' || text.includes(q);
                    const matchesRole = role === '' || role === rowRole;

                    row.style.display = matchesSearch && matchesRole ? '' : 'none';
                });
            }

            if (searchInput) searchInput.addEventListener('input', applyFilter);
            if (roleFilter) roleFilter.addEventListener('change', applyFilter);
        });
    </script>
@endpush
