@extends('website.web.admin.layouts.app')

@section('title', 'پرۆفایلی قوتابی')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Title & Breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item active">پرۆفایل</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-user-cog me-2"></i>
                    پرۆفایلی قوتابی
                </h4>
            </div>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success">زانیارییەکان بەسەرکەوتوویی نوێکرانەوە.</div>
    @endif
    @if (session('status') === 'password-updated')
        <div class="alert alert-success">پاسۆرد بەسەرکەوتوویی گۆڕدرێت.</div>
    @endif

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card glass border-0 shadow-sm mb-3 fade-in">
                <div class="card-header bg-white">
                    <h6 class="mb-0">دەستکاری زانیاری سەرەکی</h6>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label class="form-label">ناو</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ژمارەی مۆبایل</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-primary w-100">نوێکردنەوە</button>
                    </form>
                </div>
            </div>

            <div class="card glass border-0 shadow-sm fade-in">
                <div class="card-header bg-white">
                    <h6 class="mb-0">گۆڕینی پاسۆرد</h6>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label class="form-label">پاسۆردی ئێستا</label>
                            <input type="password" name="current_password" class="form-control">
                            @error('current_password', 'updatePassword')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">پاسۆردی نوێ</label>
                            <input type="password" name="password" class="form-control">
                            @error('password', 'updatePassword')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">دڵنیاکردنەوەی پاسۆرد</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <button class="btn btn-outline-primary w-100">گۆڕین</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card glass border-0 shadow-sm fade-in">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">زانیاری قوتابی</h6>
                    <span class="badge {{ ($student && $student->status) ? 'bg-success' : 'bg-secondary' }}">
                        {{ ($student && $student->status) ? 'چالاک' : 'ناچالاک' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">ناو</div>
                            <div class="fw-semibold">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">ژمارەی مۆبایل</div>
                            <div class="fw-semibold">{{ $user->phone ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">کۆدی قوتابی</div>
                            <div class="fw-semibold">{{ $user->code ?? '—' }}</div>
                        </div>
                        {{--  <div class="col-md-6">
                            <div class="text-muted small">کۆدی تایبەتی (rand_code)</div>
                            <div class="fw-semibold">{{ $user->rand_code ?? '—' }}</div>
                        </div>  --}}
                        <div class="col-md-6">
                            <div class="text-muted small">پیشە</div>
                            <div class="fw-semibold">{{ $user->role === 'student' ? 'قوتابی' : 'ناوەندی' }}</div>
                        </div>
                        {{--  <div class="col-md-6">
                            <div class="text-muted small">دۆخی هەژمار</div>
                            <div class="fw-semibold">{{ $user->status ? 'چالاک' : 'ناچالاک' }}</div>
                        </div>  --}}
                        <div class="col-md-6">
                            <div class="text-muted small">ناوچە (پارێزگا)</div>
                            <div class="fw-semibold">{{ $student?->province ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">نمرە</div>
                            <div class="fw-semibold">{{ $student?->mark ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">جۆری خوێندن</div>
                            <div class="fw-semibold">{{ $student?->type ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">ڕەگەز</div>
                            <div class="fw-semibold">{{ $student?->gender ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">چەند جارە فۆرمی زانکۆلاین پێشکەش کردووە</div>
                            <div class="fw-semibold">{{ $student?->year ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            @if($student?->referral_code)
                                <div class="text-muted small">کۆدی پێشنیار</div>
                                <div class="fw-semibold">
                                    @php
                                        $referrerUser = \App\Models\User::where('rand_code', $student->referral_code)
                                            ->whereIn('role', ['teacher', 'center'])
                                            ->first();
                                    @endphp
                                    {{ $referrerUser?->name ?? $student->referral_code }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">جۆری کەسایەتی MBTI</div>
                            <div class="fw-semibold"><span class="badge bg-{{ $student->mbti_type ? 'success' : 'danger' }} text-black">{{ $student?->mbti_type ?? 'دیاری نەکراوە' }}</span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">سیستەمی زیرەکی دەستکرد</div>
                            <div class="fw-semibold"><span class="badge bg-{{ $student->ai_rank == 1 ? 'success' : 'danger' }} text-black">{{ ($student && $student->ai_rank) ? 'چاڵاکە' : 'چاڵاک نیە' }}</span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">نەخشە</div>
                            <div class="fw-semibold"><span class="badge bg-{{ $student->gis ? 'success' : 'danger' }} text-black">{{ ($student && $student->gis) ? 'چاڵاکە' : 'چاڵاک نیە' }}</span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">ڕێزبەندی کردنی بەشی زیاتر</div>
                            <div class="fw-semibold"><span class="badge bg-{{ $student->all_departments ? 'success' : 'danger' }} text-black">{{ ($student && $student->all_departments) ? 'چاڵاکە' : 'چاڵاک نیە' }}</span></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
