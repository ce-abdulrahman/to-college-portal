@extends('website.web.admin.layouts.app')

@section('title', 'پرۆفایلی سەنتەر')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('center.dashboard') }}">داشبۆرد</a></li>
                            <li class="breadcrumb-item active">پرۆفایل</li>
                        </ol>
                    </div>
                    <h4 class="page-title">
                        <i class="fas fa-user me-1"></i>
                        پرۆفایل
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
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">دەستکاری زانیاری سەرەکی</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('center.profile.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">ناو</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ژمارەی مۆبایل</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">پارێزگا</label>
                                <select name="province" class="form-select">
                                    <option value="">هەڵبژێرە...</option>
                                    @foreach (($provinces ?? collect()) as $province)
                                        <option value="{{ $province->name }}" @selected(old('province', $center?->province) === $province->name)>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ناونیشان</label>
                                <input type="text" name="address" class="form-control"
                                    value="{{ old('address', $center?->address) }}">
                                @error('address')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">وەسف کردن</label>
                                <textarea type="text" name="description" class="form-control">{{ old('description', $center?->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="btn btn-primary w-100">پاشەکەوتکردن</button>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
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

                @php
                    $centerReferralLink = route('register', ['ref' => $user->rand_code]);
                @endphp
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Referral Link بۆ بڵاوکردنەوە</h6>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" id="center-referral-link" class="form-control" value="{{ $centerReferralLink }}" readonly>
                            <button type="button" id="copy-center-referral-link" class="btn btn-outline-primary">کۆپی</button>
                        </div>
                        <small id="center-referral-feedback" class="text-muted d-inline-block mt-2">
                            ئەم لینکە بنێرە بۆ قوتابی/مامۆستاکان بۆ خۆتۆمارکردن لەژێر کۆدی تۆ.
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">زانیاری سەنتەر</h6>
                        <span class="badge {{ $user->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $user->status ? 'چالاک' : 'ناچالاک' }}
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
                                <div class="text-muted small">پارێزگا</div>
                                <div class="fw-semibold">{{ $center?->province ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">کۆدی داخیل بوون</div>
                                <div class="fw-semibold">{{ $user->code ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <div class="text-muted small">پیشە</div>
                                <div class="fw-semibold">{{ $user->role === 'center' ? 'سەنتەر' : '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">سنووری مامۆستا</div>
                                <div class="fw-semibold">
                                    {{ is_null($center?->limit_teacher) ? 'بێ سنوور' : $center->limit_teacher }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">سنووری قوتابی</div>
                                <div class="fw-semibold">
                                    {{ is_null($center?->limit_student) ? 'بێ سنوور' : $center->limit_student }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">مامۆستا دروستکراوەکان</div>
                                <div class="fw-semibold">
                                    {{ $currentTeachersCount ?? 0 }}
                                    @if (!is_null($center?->limit_teacher))
                                        / {{ $center->limit_teacher }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">قوتابی دروستکراوەکان</div>
                                <div class="fw-semibold">
                                    {{ $currentStudentsCount ?? 0 }}
                                    @if (!is_null($center?->limit_student))
                                        / {{ $center->limit_student }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">کۆدی بانگێشت کردن</div>
                                <div class="fw-semibold">{{ $center?->referral_code ?? '—' }}</div>
                            </div>
                            <div class="col-md-12">
                                <div class="text-muted small">ناونیشان</div>
                                <div class="fw-semibold">{{ $center?->address ?? '—' }}</div>
                            </div>
                            <div class="col-md-12">
                                <div class="text-muted small">وەسف</div>
                                <div class="fw-semibold">{{ $center?->description ?? '—' }}</div>
                            </div>
                            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                                <h6 class="mb-0">تایبەتمەندیەکانی سیستەم</h6>

                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">سیستەمی زیرەکی دەستکرد</div>
                                <div class="fw-semibold">
                                    @if ($center && $center->ai_rank)
                                        <span class="badge bg-success ms-2">چاڵاکە</span>
                                    @else
                                        <span class="badge bg-danger ms-2">ناچاڵاک</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">سیستەمی نەخشە</div>
                                <div class="fw-semibold">
                                    @if ($center && $center->gis)
                                        <span class="badge bg-success ms-2">چاڵاکە</span>
                                    @else
                                        <span class="badge bg-danger ms-2">ناچاڵاک</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">ڕێزبەندی کردنی بەشی زیاتر</div>
                                <div class="fw-semibold">
                                    @if ($center && $center->all_departments)
                                        <span class="badge bg-success ms-2">بەڵێ</span>
                                    @else
                                        <span class="badge bg-danger ms-2">نەخێر</span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyBtn = document.getElementById('copy-center-referral-link');
            const input = document.getElementById('center-referral-link');
            const feedback = document.getElementById('center-referral-feedback');

            if (!copyBtn || !input || !feedback) {
                return;
            }

            copyBtn.addEventListener('click', async function() {
                try {
                    await navigator.clipboard.writeText(input.value);
                    feedback.textContent = 'لینکەکە کۆپی کرا.';
                    feedback.classList.remove('text-muted', 'text-danger');
                    feedback.classList.add('text-success');
                } catch (e) {
                    input.select();
                    document.execCommand('copy');
                    feedback.textContent = 'لینکەکە کۆپی کرا.';
                    feedback.classList.remove('text-muted', 'text-danger');
                    feedback.classList.add('text-success');
                }
            });
        });
    </script>
@endpush
