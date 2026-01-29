{{-- This file is included in create.blade.php --}}
<div id="student-info-section" class="{{ $showExtra ? '' : 'd-none' }}">
    <hr class="my-4">

    <input type="hidden" id="user_id" name="user_id" value="{{ request()->get('student') }}" required>

    <div class="row g-3">
        <div class="col-md-6">
            <label for="mark" class="form-label">نمرەی قوتابی <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="mark" name="mark" 
                   value="{{ old('mark') }}" required step="0.01" min="0" max="100">
            <div class="invalid-feedback">تکایە نمرەی دروست بنووسە.</div>
        </div>

        <div class="col-md-6">
            <label for="province" class="form-label">پارێزگا <span class="text-danger">*</span></label>
            <select class="form-select" id="province" name="province" required>
                <option value="">هەڵبژێرە...</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province->name }}" @selected(old('province') == $province->name)>
                        {{ $province->name }}
                    </option>
                @endforeach
            </select>
            <div class="invalid-feedback">تکایە پارێزگا هەڵبژێرە.</div>
        </div>

        <div class="col-md-6">
            <label for="type" class="form-label">لق <span class="text-danger">*</span></label>
            <select class="form-select" id="type" name="type" required>
                <option value="زانستی" @selected(old('type') === 'زانستی')>زانستی</option>
                <option value="وێژەیی" @selected(old('type') === 'وێژەیی')>وێژەیی</option>
            </select>
            <div class="invalid-feedback">تکایە لق هەڵبژێرە.</div>
        </div>

        <div class="col-md-6">
            <label for="gender" class="form-label">ڕەگەز <span class="text-danger">*</span></label>
            <select class="form-select" id="gender" name="gender" required>
                <option value="نێر" @selected(old('gender') === 'نێر')>نێر</option>
                <option value="مێ" @selected(old('gender') === 'مێ')>مێ</option>
            </select>
            <div class="invalid-feedback">تکایە ڕەگەز هەڵبژێرە.</div>
        </div>

        <div class="col-md-6">
            <label for="year" class="form-label">ساڵ <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="year" name="year" 
                   value="{{ old('year', date('Y')) }}" required min="2000" max="{{ date('Y') }}">
            <div class="invalid-feedback">تکایە ساڵی دروست بنووسە.</div>
        </div>

        <div class="col-md-6">
            <label for="referral_code" class="form-label">کۆدی بانگێشت</label>
            <input type="text" class="form-control" id="referral_code" name="referral_code" 
                   value="{{ auth()->user()->rand_code }}" >
        </div>

        <div class="col-md-6">
            <label for="queue" class="form-label">رێزبەندی کرد</label>
            <select class="form-select" id="queue" name="queue">
                <option value="">هەڵبژێرە...</option>
                <option value="yes" @selected(old('queue') === 'yes')>بەڵی</option>
                <option value="no" @selected(old('queue') === 'no')>نەخێر</option>
            </select>
        </div>
    </div>

    {{-- Queue Numbers Section --}}
    <div id="queue-numbers-section" class="{{ $showNum ? '' : 'd-none' }}">
        <hr class="my-4">
        
        <div class="row g-3">
            <div class="col-md-4">
                <label for="zankoline_num" class="form-label">زانکۆلاین</label>
                <input type="number" class="form-control" id="zankoline_num" name="zankoline_num" 
                       value="{{ old('zankoline_num') }}" step="0.01" min="0">
            </div>

            <div class="col-md-4">
                <label for="parallel_num" class="form-label">پارالیل</label>
                <input type="number" class="form-control" id="parallel_num" name="parallel_num" 
                       value="{{ old('parallel_num') }}" step="0.01" min="0">
            </div>

            <div class="col-md-4">
                <label for="evening_num" class="form-label">ئێواران</label>
                <input type="number" class="form-control" id="evening_num" name="evening_num" 
                       value="{{ old('evening_num') }}" step="0.01" min="0">
            </div>
        </div>
    </div>
</div>