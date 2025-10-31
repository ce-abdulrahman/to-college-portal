@extends('website.web.admin.layouts.app')

@section('content')
    {{-- Top Bar --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>

        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title fw-bold">زانیاری بەش</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary" data-bs-toggle="tooltip"
                title="دەستکاری">
                <i class="fa-solid fa-pen-to-square me-1"></i>
            </a>
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                onsubmit="return confirm('دڵنیایت دەتەوێت بسڕیتەوە؟');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" title="سڕینەوە">
                    <i class="fa-solid fa-trash-can me-1"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">

            {{-- User Info Card --}}
            <div class="card glass fade-in shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-circle-info me-2 text-primary"></i> زانیاری تەواوی قوتابی
                    </h4>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <tbody>
                                <tr>
                                    <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i></th>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa-solid fa-user me-1 text-muted"></i> ناو</th>
                                    <td>{{ $user->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa-solid fa-location-dot me-1 text-muted"></i> پارێزگا</th>
                                    <td>{{ $user->student->province ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa-solid fa-barcode me-1 text-muted"></i> کۆد</th>
                                    <td>{{ $user->code ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa-solid fa-percent me-1 text-muted"></i> نمرە</th>
                                    <td>{{ $user->student->mark ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa-solid fa-layer-group me-1 text-muted"></i> لق</th>
                                    <td><span class="chip chip-primary">{{ $user->student->type ?? '—' }}</span></td>
                                </tr>
                                <tr>
                                    <th><i class="fa-regular fa-calendar-plus me-1 text-muted"></i> دروستکراوە</th>
                                    <td>{{ optional($user->created_at)->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa-regular fa-clock me-1 text-muted"></i> نوێکرایەوە</th>
                                    <td>{{ optional($user->updated_at)->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ</th>
                                    <td>
                                        @if ($user->status)
                                            <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>
                                                چاڵاکە</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fa-solid fa-circle-xmark me-1"></i>
                                                ناچاڵاکە</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                            <i class="fa-solid fa-pen-to-square me-1"></i> گۆڕین
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-outline">
                            <i class="fa-solid fa-list me-1"></i> لیست
                        </a>
                    </div>
                </div>
            </div>

            {{-- Departments Table --}}
            <div class="card glass fade-in shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-building-columns me-2 text-success"></i> بەشە هەڵبژێدراوەکان لە کۆلێژ و پەیمانگا
                    </h4>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>ناو</th>
                                    <th>ن. ناوەندی</th>
                                    <th>ن. ناوخۆی</th>
                                    <th>جۆر</th>
                                    <th>ڕەگەز</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($result_deps as $index => $result_dep)
                                @php
                                    $systemName = $result_dep->department->system->name;
                                    $systemBadgeClass = match ($systemName) {
                                        'زانکۆلاین' => 'bg-primary',
                                        'پاراڵیل' => 'bg-success',
                                        default => 'bg-danger',
                                    };
                                @endphp
                                    <tr
                                        class="{{ $result_dep->department->system->name == 'زانکۆلاین' ? 'table-primary' : ($result_dep->department->system->name == 'پاراڵیل' ? 'table-success' : 'table-danger') }}">
                                        <td>{{ ++$index }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <div class="fw-semibold">{{ $result_dep->department->name }}</div>
                                                <div class="text-muted small">
                                                    <span class="badge {{ $systemBadgeClass }}">
                                                        <i class="fa-solid fa-cube me-1"></i> {{ $systemName }}
                                                    </span> /
                                                    {{ $result_dep->department->province->name }} /
                                                    {{ $result_dep->department->university->name }} /
                                                    {{ $result_dep->department->college->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $result_dep->department->local_score ?? '—' }}</td>
                                        <td>{{ $result_dep->department->internal_score ?? '—' }}</td>
                                        <td><span class="chip"><i
                                                    class="fa-solid fa-layer-group me-1"></i> {{ $result_dep->department->type }}</span>
                                        </td>
                                        <td>{{ $result_dep->department->sex ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            <i class="fa-solid fa-circle-info me-1"></i> هیچ بەشێک نەدۆزرایەوە
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
