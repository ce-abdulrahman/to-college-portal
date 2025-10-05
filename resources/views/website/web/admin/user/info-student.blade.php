<div id="extra-section" class="{{ $showExtra ? '' : 'd-none' }}">
    <hr class="my-4">

    <input type="hidden" id="user_id" name="user_id" value="{{ request()->get('student') }}" required>

    <div class="row g-3">
        <div class="col-12 col-md-6">
            <label for="mark" class="form-label">نمرە</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-star-half-stroke"></i></span>
                <input type="number" class="form-control @error('mark') is-invalid @enderror" id="mark"
                    name="mark" value="{{ old('mark') }}" required min="0" step="0.01"
                    placeholder="نموونە: 89.50">
                @error('mark')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">نمرە پێویستە.</div>
                @enderror
            </div>
        </div>

        <div class="col-12 col-md-6">
            <label for="province_id" class="form-label">پارێزگا</label>
            <div class="position-relative">
                <select class="form-select @error('province') is-invalid @enderror" id="province" name="province"
                    required>
                    <option value="" disabled @selected(!old('province'))>هەڵبژاردنی پارێزگا
                    </option>
                    @foreach ($provinces as $province)
                        <option value="{{ $province->name }}" @selected(old('province') == $province->name)>
                            {{ $province->name }}
                        </option>
                    @endforeach
                </select>
                <span id="spinner-province" class="position-absolute top-50 end-0 translate-middle-y me-3 d-none">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                </span>
                @error('province')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-12 col-md-6">
            <label for="type" class="form-label">جۆر</label>
            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                <option value="زانستی" @selected(old('type') === 'زانستی')>زانستی</option>
                <option value="وێژەیی" @selected(old('type') === 'وێژەیی')>وێژەیی</option>
            </select>
            @error('type')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6">
            <label for="gender" class="form-label">ڕەگەز</label>
            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                <option value="نێر" @selected(old('gender') === 'نێر')>نێر</option>
                <option value="مێ" @selected(old('gender') === 'مێ')>مێ</option>
            </select>
            @error('gender')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-6">
            <label for="year" class="form-label">ساڵ</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                <input type="number" class="form-control @error('year') is-invalid @enderror" id="year"
                    name="year" value="1" required placeholder="{{ now()->year }}">
                @error('year')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">ساڵ پێویستە (٢٠٠٠ تا {{ now()->year }}).</div>
                @enderror
            </div>
        </div>

        <div class="col-12 col-md-6">
            <label for="queue" class="form-label">رێزبەندی کرد</label>
            <select class="form-select @error('queue') is-invalid @enderror" id="queue" name="queue" required>
                <option value="" disabled @selected(!old('queue'))>بەڵی یان نەخیر</option>
                <option value="yes" @selected(old('queue') === 'بەڵی')>بەڵی</option>
                <option value="no" @selected(old('queue') === 'نەخێر')>نەخێر</option>
            </select>
            @error('queue')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

    </div>
</div>

@php
    $showNum = old('queue') === 'yes' || request()->has('yes');
@endphp

<div id="choose-num" class="{{ $showNum ? '' : 'd-none' }}">
    <hr class="my-4">

    <h5 class="mb-4">هەڵبژاردنەی ژمارەی <b class="text-primary">زانکۆلاین</b> و <b class="text-success">پارالیل</b> و <b class="text-danger">ئیواران</b> بۆ ڕێزبەندی کردن.</h5>

    <div class="row g-3">

        <div class="col-12 col-md-4">
            <label for="num_zankoline" class="form-label">نمرە</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-star-half-stroke"></i></span>
                <input type="number" class="form-control @error('num_zankoline') is-invalid @enderror" id="num_zankoline"
                    name="num_zankoline" value="{{ old('num_zankoline') }}" required min="0" step="0.01"
                    placeholder="نموونە: 89.50">
                @error('num_zankoline')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">نمرە پێویستە.</div>
                @enderror
            </div>
        </div>

        <div class="col-12 col-md-4">
            <label for="num_parallel" class="form-label">نمرە</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-star-half-stroke"></i></span>
                <input type="number" class="form-control @error('num_parallel') is-invalid @enderror" id="num_parallel"
                    name="num_parallel" value="{{ old('num_parallel') }}" required min="0" step="0.01"
                    placeholder="نموونە: 89.50">
                @error('num_parallel')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">نمرە پێویستە.</div>
                @enderror
            </div>
        </div>

        <div class="col-12 col-md-4">
            <label for="num_evening" class="form-label">نمرە</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-star-half-stroke"></i></span>
                <input type="number" class="form-control @error('num_evening') is-invalid @enderror" id="num_evening"
                    name="num_evening" value="{{ old('num_evening') }}" required min="0" step="0.01"
                    placeholder="نموونە: 89.50">
                @error('num_evening')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">نمرە پێویستە.</div>
                @enderror
            </div>
        </div>

    </div>
</div>
