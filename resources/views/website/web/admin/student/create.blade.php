@extends('website.web.admin.layouts.app')

@section('content')
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline mb-4">
        <i class="fa-solid fa-arrow-right-long me-1"></i> گەڕانەوە
    </a>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-user-plus me-2"></i> زیادکردنی قوتابی
                    </h4>

                    {{-- Laravel validation errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> هەڵە هەیە لە داهێنان:
                            <ul class="mb-0 mt-2 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        {{-- زانیاری سەرەکی --}}
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="code" class="form-label">کۆدی قوتابی</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                        id="code" name="code" value="{{ old('code') }}" required
                                        placeholder="بۆ نموونە: 240123">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">تکایە کۆد بنووسە.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label">ناوی قوتابی</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required
                                        placeholder="ناوی تەواو">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">ناو پێویستە.</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="password" class="form-label">تێپەڕەوشە</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                                    <input type="text" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" value="{{ old('password') }}" required
                                        placeholder="تێپەڕەوشە">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">تکایە تێپەڕەوشە بنووسە.</div>
                                    @enderror
                                </div>
                            </div>

                            @if (auth()->user()->role == 'admin')
                                <div class="col-12 col-md-6">
                                    <label for="role" class="form-label">ڕۆڵ</label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role"
                                        name="role" required>
                                        <option value="admin" @selected(old('role') === 'admin')>ئەدمین</option>
                                        <option value="student" @selected(old('role') === 'student')>بەکارهێنەر</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="form-text">تەنها ئەدمین دەتوانێت ڕۆڵ دیاری بکات.</div>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        {{-- شرطی سێرڤەر: گەر لەگەڵ ڤالیدەیشن هاتەوە یان لە دیتای کۆن user=role=user --}}
                        @php
                            $showExtra = old('role') === 'student' || request()->has('student');
                        @endphp

                        @include('website.web.admin.user.info-student', ['provinces' => $provinces, 'showExtra' => $showExtra])

                        {{-- دۆخ --}}


                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="status" class="form-label">دۆخ</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="1" @selected(old('status') === '1') class="bg-success">چاڵاک</option>
                                    <option value="0" @selected(old('status') === '0') class="bg-danger">ناچاڵاک</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> پاشەکەوتکردن
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        const roleSel = document.getElementById('role');
        const queueSel = document.getElementById('queue');
        const extra = document.getElementById('extra-section');
        const chooseNum = document.getElementById('choose-num');

        if (roleSel && extra) {
            const toggleExtra = () => {
                if (roleSel.value === 'student') {
                    extra.classList.remove('d-none');
                    // ئەگەر دەتەوێت خانەکانش پێویست بکرێن:
                    extra.querySelectorAll('select, input').forEach(el => {
                        if (['mark', 'province_id', 'type', 'gender', 'year'].includes(el.name)) {
                            el.required = true;
                        }
                    });
                } else {
                    extra.classList.add('d-none');
                    extra.querySelectorAll('select, input').forEach(el => {
                        if (['mark', 'province_id', 'type', 'gender', 'year'].includes(el.name)) {
                            el.required = false;
                        }
                    });
                }
            };
            roleSel.addEventListener('change', toggleExtra);
            toggleExtra(); // لە لەبارەکردنەوەی پەیج
        }
        if (queueSel && chooseNum) {
            const toggleNum = () => {
                if (queueSel.value === 'yes') {
                    chooseNum.classList.remove('d-none');
                    // ئەگەر دەتەوێت خانەکانش پێویست بکرێن:
                    chooseNum.querySelectorAll('select, input').forEach(el => {
                        if (['num_zankoline'].includes(el.name)) {
                            el.required = true;
                        }
                    });
                } else {
                    chooseNum.classList.add('d-none');
                    chooseNum.querySelectorAll('select, input').forEach(el => {
                        if (['num_zankoline'].includes(el.name)) {
                            el.required = false;
                        }
                    });
                }
            };
            queueSel.addEventListener('change', toggleNum);
            toggleNum(); // لە لەبارەکردنەوەی پەیج
        }
    </script>
@endpush
