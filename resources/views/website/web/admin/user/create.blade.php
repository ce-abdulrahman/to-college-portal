@extends('website.web.admin.layouts.app')

@section('page_name', 'users')
@section('view_name', 'create')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">داشبۆرد</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">بەکارهێنەران</a></li>
                        <li class="breadcrumb-item active">زیادکردنی بەکارهێنەری نوێ</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="fas fa-user-plus me-1"></i>
                    زیادکردنی بەکارهێنەری نوێ
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">
            <div class="card glass fade-in">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fa-solid fa-user-plus me-2"></i> دروستکردنی بەکارهێنەری نوێ
                    </h4>

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> هەڵە هەیە:
                            <ul class="mb-0 mt-2 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        {{-- Basic Information --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    <i class="fa-solid fa-user me-1 text-muted"></i> ناو <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" required placeholder="ناوی بەکارهێنەر...">
                                <div class="invalid-feedback">تکایە ناو بنووسە.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    <i class="fa-solid fa-phone me-1 text-muted"></i> ژمارەی مۆبایل
                                </label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ old('phone') }}" placeholder="٠٧٧٠١٢٣٤٥٦٧">
                                <div class="form-text">بەشێوەی کرداری ٠٧٧٠١٢٣٤٥٦٧</div>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    <i class="fa-solid fa-lock me-1 text-muted"></i> تێپەڕەوشە <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="invalid-feedback">تکایە تێپەڕەوشە بنووسە.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fa-solid fa-lock me-1 text-muted"></i> دووبارەکردنەوەی تێپەڕەوشە <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                                <div class="invalid-feedback">تکایە تێپەڕەوشە دووبارە بکەرەوە.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="code" class="form-label">
                                    <i class="fa-solid fa-hashtag me-1 text-muted"></i> کۆد چوونەژوورەوە <span
                                        class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="code" name="code" readonly>
                                    <button type="button" class="btn btn-outline-secondary" id="generate-code">
                                        <i class="fa-solid fa-rotate"></i>
                                    </button>
                                </div>
                                <div class="form-text">کۆدی خۆکار دروست دەکرێت</div>
                            </div>

                            <div class="col-md-6">
                                <label for="rand_code" class="form-label">
                                    <i class="fa-solid fa-key me-1 text-muted"></i> کۆدی ڕیلەیشن
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="rand_code" name="rand_code" readonly>
                                    <button type="button" class="btn btn-outline-secondary" id="generate-rand-code">
                                        <i class="fa-solid fa-rotate"></i>
                                    </button>
                                </div>
                                <div class="form-text">کۆدی ڕیلەیشن بۆ بانگێشتکردن</div>
                            </div>

                            <div class="col-md-6">
                                <label for="role" class="form-label">
                                    <i class="fa-solid fa-user-tag me-1 text-muted"></i> پیشە <span
                                        class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">هەڵبژێرە...</option>
                                    <option value="admin" @selected(old('role') === 'admin')>ئەدمین</option>
                                    <option value="center" @selected(old('role') === 'center')>سەنتەر</option>
                                    <option value="teacher" @selected(old('role') === 'teacher')>مامۆستا</option>
                                    <option value="student" @selected(old('role') === 'student')>قوتابی</option>
                                </select>
                                <div class="invalid-feedback">تکایە پیشە هەڵبژێرە.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    <i class="fa-solid fa-toggle-on me-1 text-muted"></i> دۆخ <span
                                        class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1" @selected(old('status') === '1')>چاڵاک</option>
                                    <option value="0" @selected(old('status') === '0')>ناچاڵاک</option>
                                </select>
                                <div class="invalid-feedback">تکایە دۆخ هەڵبژێرە.</div>
                            </div>
                        </div>

                        {{-- Feature Flags (AI, GIS, All Departments) --}}
                        <div id="features-section" class="row g-3 mt-1 d-none">
                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label mb-2">
                                    <i class="fa-solid fa-star me-1 text-muted"></i> تایبەتمەندییەکان
                                </label>
                                <div class="d-flex gap-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="ai_rank" name="ai_rank"
                                            value="1" @checked(old('ai_rank'))>
                                        <label class="form-check-label" for="ai_rank">AI Rank</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="gis" name="gis"
                                            value="1" @checked(old('gis'))>
                                        <label class="form-check-label" for="gis">GIS</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="all_departments"
                                            name="all_departments" value="1" @checked(old('all_departments'))>
                                        <label class="form-check-label" for="all_departments">All Departments (50)</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Student Information (hidden by default) --}}
                        <div id="student-info-section" class="mt-4 d-none">
                            <hr>
                            <h5 class="mb-4">
                                <i class="fa-solid fa-graduation-cap me-2"></i> زانیاری قوتابی
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="mark" class="form-label">نمرەی قوتابی <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="mark" name="mark"
                                        step="0.01" min="0" max="100" placeholder="نموونە: ٨٩.٥٠">
                                    <div class="invalid-feedback">تکایە نمرەی دروست بنووسە (٠-١٠٠).</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="province" class="form-label">پارێزگا <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="province" name="province">
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
                                    <label for="type" class="form-label">لق <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="type" name="type">
                                        <option value="زانستی">زانستی</option>
                                        <option value="وێژەیی">وێژەیی</option>
                                    </select>
                                    <div class="invalid-feedback">تکایە لق هەڵبژێرە.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="gender" class="form-label">ڕەگەز <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="نێر">نێر</option>
                                        <option value="مێ">مێ</option>
                                    </select>
                                    <div class="invalid-feedback">تکایە ڕەگەز هەڵبژێرە.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="year" class="form-label">ساڵ <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="year" name="year"
                                        min="2000" max="{{ date('Y') }}" value="{{ date('Y') }}">
                                    <div class="invalid-feedback">تکایە ساڵی دروست بنووسە.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="referral_code" class="form-label">کۆدی بانگێشت</label>
                                    <input type="text" class="form-control" id="referral_code" name="referral_code"
                                        value="{{ auth()->user()->rand_code }}" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label for="queue" class="form-label">رێزبەندی کرد</label>
                                    <select class="form-select" id="queue" name="queue">
                                        <option value="">هەڵبژێرە...</option>
                                        <option value="yes">بەڵی</option>
                                        <option value="no">نەخێر</option>
                                    </select>
                                </div>

                                {{-- Queue Numbers (hidden by default) --}}
                                <div id="queue-numbers-section" class="row g-3 mt-3 d-none">
                                    <div class="col-md-4">
                                        <label for="zankoline_num" class="form-label">زانکۆلاین</label>
                                        <input type="number" class="form-control" id="zankoline_num"
                                            name="zankoline_num" step="0.01" min="0">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="parallel_num" class="form-label">پارالیل</label>
                                        <input type="number" class="form-control" id="parallel_num" name="parallel_num"
                                            step="0.01" min="0">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="evening_num" class="form-label">ئێواران</label>
                                        <input type="number" class="form-control" id="evening_num" name="evening_num"
                                            step="0.01" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                                <i class="fa-solid fa-xmark me-1"></i> پاشگەزبوونەوە
                            </a>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation
            const forms = document.querySelectorAll('.needs-validation');
            forms.forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            });

            // Generate random codes
            function generateRandomCode(length = 6) {
                return Math.floor(Math.pow(10, length - 1) + Math.random() * 9 * Math.pow(10, length - 1));
            }

            function generateRandomShortCode(length = 4) {
                return Math.floor(Math.pow(10, length - 1) + Math.random() * 9 * Math.pow(10, length - 1));
            }

            // Generate code buttons
            const codeInput = document.getElementById('code');
            const randCodeInput = document.getElementById('rand_code');
            const generateCodeBtn = document.getElementById('generate-code');
            const generateRandCodeBtn = document.getElementById('generate-rand-code');

            if (codeInput) {
                codeInput.value = generateRandomCode(6);

                generateCodeBtn.addEventListener('click', function() {
                    codeInput.value = generateRandomCode(6);
                });
            }

            if (randCodeInput) {
                randCodeInput.value = generateRandomShortCode(4);

                generateRandCodeBtn.addEventListener('click', function() {
                    randCodeInput.value = generateRandomShortCode(4);
                });
            }

            // Show/hide student information based on role
            const roleSelect = document.getElementById('role');
            const studentInfoSection = document.getElementById('student-info-section');
            const featuresSection = document.getElementById('features-section');
            const queueSelect = document.getElementById('queue');
            const queueNumbersSection = document.getElementById('queue-numbers-section');

            function toggleStudentInfo() {
                const role = roleSelect.value;

                if (role === 'student') {
                    studentInfoSection.classList.remove('d-none');

                    // Make student fields required
                    const studentFields = studentInfoSection.querySelectorAll('input, select');
                    studentFields.forEach(field => {
                        if (field.id !== 'referral_code' && field.id !== 'queue') {
                            field.required = true;
                        }
                    });
                } else {
                    studentInfoSection.classList.add('d-none');

                    // Remove required from student fields
                    const studentFields = studentInfoSection.querySelectorAll('input, select');
                    studentFields.forEach(field => {
                        field.required = false;
                    });
                }

                // Toggle Features Section (Center, Teacher, Student)
                if (['center', 'teacher', 'student'].includes(role)) {
                    featuresSection.classList.remove('d-none');
                } else {
                    featuresSection.classList.add('d-none');
                }
            }

            function toggleQueueNumbers() {
                if (queueSelect.value === 'yes') {
                    queueNumbersSection.classList.remove('d-none');

                    // Make queue number fields required
                    const queueFields = queueNumbersSection.querySelectorAll('input');
                    queueFields.forEach(field => {
                        field.required = true;
                    });
                } else {
                    queueNumbersSection.classList.add('d-none');

                    // Remove required from queue number fields
                    const queueFields = queueNumbersSection.querySelectorAll('input');
                    queueFields.forEach(field => {
                        field.required = false;
                    });
                }
            }

            // Initial setup
            if (roleSelect) {
                toggleStudentInfo();
                roleSelect.addEventListener('change', toggleStudentInfo);
            }

            if (queueSelect) {
                toggleQueueNumbers();
                queueSelect.addEventListener('change', toggleQueueNumbers);
            }

            // Set referral code to current user's rand_code
            const referralCodeInput = document.getElementById('referral_code');
            if (referralCodeInput) {
                referralCodeInput.value = '{{ auth()->user()->rand_code }}';
            }
        });
    </script>
@endpush
