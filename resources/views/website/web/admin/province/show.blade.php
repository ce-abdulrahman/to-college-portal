@extends('website.web.admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.provinces.index') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left me-1"></i> گەڕانەوە
        </a>

        <div class="d-none d-lg-block text-center flex-grow-1">
            <div class="navbar-page-title">{{ __('زانیاری پارێزگا') }}</div>
        </div>

        <div class="d-flex gap-2">
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.provinces.edit', $province->id) }}" class="btn btn-primary">
                    <i class="fa-solid fa-pen-to-square me-1"></i>
                </a>
                <form action="{{ route('admin.provinces.destroy', $province->id) }}" method="POST"
                    onsubmit="return confirm('دڵنیایت؟');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash-can me-1"></i>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-table-list me-2"></i> زانیاری تەواوی پارێزگا
                    </h4>
                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <th style="width:260px"><i class="fa-solid fa-hashtag me-1 text-muted"></i> #</th>
                                        <td>{{ $province->id }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-map-pin me-1 text-muted"></i> ناو</th>
                                        <td class="fw-semibold">{{ $province->name }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ</th>
                                        <td>
                                            @if ($province->status)
                                                <span class="badge bg-success">چاڵاک</span>
                                            @else
                                                <span class="badge bg-danger">ناچاڵاک</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-calendar-check me-1 text-muted"></i> دروستکراوە لە</th>
                                        <td>{{ $province->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa-solid fa-calendar-pen me-1 text-muted"></i> گۆڕدراوە لە</th>
                                        <td>{{ $province->updated_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- دەتوانیت لێرەدا زانیاری پەیوەندیدار لە یونیڤەرسیتییەکانی ئەم پارێزگایەش پیشان بدەی --}}
            @isset($universities)
                <div class="card glass fade-in mt-3">
                    <div class="card-body">
                        <h4 class="card-title mb-4"><i class="fa-solid fa-building-columns me-2"></i> زانکۆکانی ئەم پارێزگایە
                        </h4>
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width:60px">#</th>
                                            <th>ناو</th>
                                            <th style="width:120px">دۆخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($universities as $i => $u)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td class="fw-semibold"><i class="fa-solid fa-school me-1 text-muted"></i>
                                                    {{ $u->name }}</td>
                                                <td>
                                                    @if ($u->status)
                                                        <span class="badge bg-success">چاڵاک</span>
                                                    @else
                                                        <span class="badge bg-danger">ناچاڵاک</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">هیچ زانیارییەک نیە</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endisset
        </div>
    </div>
@endsection
