<!DOCTYPE html>
<html lang="ku" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="ئەم سیستەمە بۆ هەڵبژاردنی بەشەکانی زانکۆ لە هەر چوار پارێزگادابگەڕی. قوتابی دەتوانێت بە ئاسانی بەشەکان هەڵبژێرد و ڕێزبەندیەک بۆ خۆی دروست بکات..">
    <meta name="keywords"
        content="zankoline,kolizh,colej,university,college,rezbande, بەرەو زانکۆ, زانکۆلاین, رێزبەندی فۆرمی زانکۆلاین, پۆلی ١٢, poli 12">
    <meta name="author" content="Abdulrahman">

    <title>خۆتۆمارکردن</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>

<body>
    <div class="login-container mt-4 ">
        <div class="login-card">

            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
            </div>

            <h1 class="title">خۆتۆمارکردن <i class="fa-regular fa-registered"></i></h1>
            <p class="subtitle">
                تکایە ڕۆڵ دیاری بکە و زانیاری پێویست بنووسە
            </p>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                @php
                    $selectedRole = old('role', 'student');
                @endphp

                <div class="mb-3">
                    <label for="role"><i class="fas fa-user-tag"></i> پیشە </label>
                    <div class="input-icon">
                        <i class="fas fa-user-tag"></i>
                        <select id="role" name="role" class="form-control" required>
                            <option value="center" @selected($selectedRole === 'center')>سەنتەر</option>
                            <option value="teacher" @selected($selectedRole === 'teacher')>مامۆستا</option>
                            <option value="student" @selected($selectedRole === 'student')>قوتابی</option>
                        </select>
                    </div>
                    @error('role')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="code"><i class="fas fa-id-card"></i> کۆدی بەکارهێنەر بۆ چوونەژوورەوە *</label>
                    <div class="input-icon">
                        <i class="fas fa-id-card"></i>
                        <input id="code" type="text" name="code" value="{{ old('code') }}"
                            class="form-control" placeholder="کۆد بنووسە" required>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <button id="generate-code" type="button" class="btn btn-sm btn-outline-secondary">
                            <i class="fa-solid fa-dice me-1"></i> دروستکردنی کۆدی ٤ ژمارەیی
                        </button>
                    </div>
                    @error('code')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name"><i class="fas fa-id-card"></i> ناو</label>
                    <div class="input-icon">
                        <i class="fas fa-id-card"></i>
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                            class="form-control" placeholder="ناو بنووسە" required>
                    </div>
                    @error('name')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone"><i class="fas fa-phone"></i> ژمارەی مۆبایل</label>
                    <div class="input-icon">
                        <i class="fas fa-phone"></i>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                            class="form-control" placeholder="ژمارەی مۆبایل (ئارەزوومەندە)">
                    </div>
                    @error('phone')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="role-group" data-role-group="center">
                    <div class="mb-3">
                        <label for="center_province"><i class="fas fa-map-marker-alt"></i> پارێزگا</label>
                        <div class="input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                            <select id="center_province" name="center_province" class="form-control" data-required="1">
                                <option value="">پارێزگا هەڵبژێرە</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->name }}" @selected(old('center_province') === $province->name)>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('center_province')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="center_address"><i class="fas fa-location-dot"></i> ناونیشان</label>
                        <textarea id="center_address" name="center_address" class="form-control"
                            placeholder="ناونیشانی سەنتەر (ئارەزوومەندە)">{{ old('center_address') }}</textarea>
                        @error('center_address')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="center_description"><i class="fas fa-align-right"></i> وەسف</label>
                        <textarea id="center_description" name="center_description" class="form-control"
                            placeholder="وەسفی سەنتەر (ئارەزوومەندە)">{{ old('center_description') }}</textarea>
                        @error('center_description')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="role-group" data-role-group="teacher">
                    <div class="mb-3">
                        <label for="teacher_province"><i class="fas fa-map-marker-alt"></i> پارێزگا</label>
                        <div class="input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                            <select id="teacher_province" name="teacher_province" class="form-control"
                                data-required="1">
                                <option value="">پارێزگا هەڵبژێرە</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->name }}" @selected(old('teacher_province') === $province->name)>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('teacher_province')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="role-group" data-role-group="student">
                    <div class="mb-3">
                        <label for="student_province"><i class="fas fa-map-marker-alt"></i> پارێزگا</label>
                        <div class="input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                            <select id="student_province" name="student_province" class="form-control"
                                data-required="1">
                                <option value="">پارێزگا هەڵبژێرە</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->name }}" @selected(old('student_province') === $province->name)>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('student_province')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="student_type"><i class="fas fa-book"></i> جۆری خوێندن</label>
                        <div class="input-icon">
                            <i class="fas fa-book"></i>
                            <select id="student_type" name="student_type" class="form-control" data-required="1">
                                <option value="">جۆر هەڵبژێرە</option>
                                <option value="زانستی" @selected(old('student_type') === 'زانستی')>زانستی</option>
                                <option value="وێژەیی" @selected(old('student_type') === 'وێژەیی')>وێژەیی</option>
                            </select>
                        </div>
                        @error('student_type')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="student_gender"><i class="fas fa-venus-mars"></i> ڕەگەز</label>
                        <div class="input-icon">
                            <i class="fas fa-venus-mars"></i>
                            <select id="student_gender" name="student_gender" class="form-control"
                                data-required="1">
                                <option value="">ڕەگەز هەڵبژێرە</option>
                                <option value="نێر" @selected(old('student_gender') === 'نێر')>نێر</option>
                                <option value="مێ" @selected(old('student_gender') === 'مێ')>مێ</option>
                            </select>
                        </div>
                        @error('student_gender')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        @php
                            $oldStudentYear = old('student_year');
                            $selectedStudentYear =
                                $oldStudentYear === null || $oldStudentYear === ''
                                    ? ''
                                    : ((int) $oldStudentYear > 1
                                        ? '2'
                                        : '1');
                            $isStudentYearOne = $selectedStudentYear === '1';
                        @endphp
                        <label for="student_year"><i class="fas fa-calendar"></i> پڕکردنەوەی فۆرم</label>
                        <div class="input-icon">
                            <i class="fas fa-calendar"></i>
                            <select id="student_year" name="student_year" class="form-control" data-required="1">
                                <option value="" @selected($selectedStudentYear === '')>هەڵبژێرە</option>
                                <option value="1" @selected($selectedStudentYear === '1')>1</option>
                                <option value="2" @selected($selectedStudentYear === '2')>زیاتر لە 2</option>
                            </select>
                        </div>
                        @error('student_year')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                        <div id="register-year-system-tip" class="small mt-2">
                            @if ($isStudentYearOne)
                                دەتوانی سیستەمی <span class="badge bg-success">زانکۆلاین</span> و <span
                                    class="badge bg-danger">پاڕالێل</span> و <span
                                    class="badge bg-dark">ئێواران</span> هەڵبژێری
                            @else
                                بەس سیستەمی <span class="badge bg-danger">پاڕالێل</span> و <span
                                    class="badge bg-dark">ئێواران</span> هەڵبژێری
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="student_mark"><i class="fas fa-pen"></i> نمرە</label>
                        <div class="input-icon">
                            <i class="fas fa-pen"></i>
                            <input id="student_mark" type="number" step="0.001" name="student_mark"
                                value="{{ old('student_mark') }}" class="form-control" placeholder="نمرە بنووسە"
                                data-required="1">
                        </div>
                        @error('student_mark')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if ($prefilledReferralCode)
                    <div class="mb-3">
                        <label for="referral_code"><i class="fas fa-link"></i> کۆدی پێشنیار</label>
                        <div class="input-icon">
                            <i class="fas fa-link"></i>
                            <input type="text" id="referral_code" name="referral_code"
                                value="{{ old('referral_code', $prefilledReferralCode ?? '') }}" class="form-control"
                                placeholder="کۆدی پێشنیار (ئارەزوومەندە)" readonly>
                        </div>
                        <div id="referral-owner"
                            class="small mt-2
                            @if (!empty($referrer)) text-success @else text-muted @endif">
                            @if (!empty($referrer))
                                تۆمارکردنەکەت لەژێر کۆدی
                                <strong>{{ $referrer->name }}</strong>
                                ({{ $referrer->role === 'admin' ? 'ئەدمین' : ($referrer->role === 'center' ? 'سەنتەر' : 'مامۆستا') }})
                                دەچێت.
                                <span class="d-block mt-1">
                                    ژمارەی پەیوەندی:
                                    <strong>{{ $referrer->phone ?: 'نییە' }}</strong>
                                </span>
                            @else
                                ئەم خانەیە ئارەزوومەندانەیە.
                            @endif
                        </div>
                        @error('referral_code')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="mb-3">
                    <label for="password"><i class="fas fa-lock"></i> وشەی نهێنی</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" name="password" class="form-control"
                            placeholder="وشەی نهێنی بنووسە" required>
                    </div>
                    @error('password')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info small mb-3">
                    هەژمارەکەت پاش پەسەندکردنی ئەدمین چالاک دەبێت.
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-out-alt"></i> خۆتۆمارکردن
                </button>
            </form>

            <div class="register-link">
                <p>ئەگەر ئەژمارت هەیە دەتوانی کلیک لەم بەستەرەی خوارەوە بکەی ؟
                    <a href="{{ route('login') }}">ئەژمارم هەیە.</a>
                </p>
            </div>

        </div>

        <div class="footer">
            <p>ھەموو مافەکان پارێزراوە © ٢٠٢٥ بۆ گرووپی کۆس</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const roleGroups = document.querySelectorAll('.role-group[data-role-group]');
            const codeInput = document.getElementById('code');
            const generateCodeBtn = document.getElementById('generate-code');
            const studentYearSelect = document.getElementById('student_year');
            const registerYearSystemTip = document.getElementById('register-year-system-tip');
            const referralInput = document.getElementById('referral_code');
            const referralOwner = document.getElementById('referral-owner');
            const infoUrl = "{{ route('register.referrer-info') }}";
            let debounceTimer = null;

            function intGeneratorCode(length = 4) {
                const min = Math.pow(10, length - 1);
                const max = Math.pow(10, length) - 1;

                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            function updateRoleFields() {
                const selectedRole = roleSelect ? roleSelect.value : 'student';

                roleGroups.forEach(function(group) {
                    const isActive = group.dataset.roleGroup === selectedRole;
                    group.style.display = isActive ? '' : 'none';

                    group.querySelectorAll('input, select, textarea').forEach(function(field) {
                        if (field.dataset.required === '1') {
                            field.required = isActive;
                        }
                    });
                });
            }

            function setInfoText(text, className) {
                if (!referralOwner) {
                    return;
                }

                referralOwner.textContent = text;
                referralOwner.classList.remove('text-success', 'text-danger', 'text-muted', 'text-warning');
                referralOwner.classList.add(className);
            }

            function emptyReferralMessage(role) {
                if (role === 'center') {
                    return 'بێ کۆدی پێشنیار، تۆمارکردنەکەت بۆ ئەدمین حساب دەکرێت.';
                }

                if (role === 'teacher') {
                    return 'بێ کۆدی پێشنیار، تۆمارکردنەکەت بۆ ئەدمین حساب دەکرێت.';
                }

                return 'ئەم خانەیە ئارەزوومەندانەیە. ئەگەر کۆد نەنووسیت، تۆمارکردن بۆ ئەدمین حساب دەکرێت.';
            }

            function syncRegisterYearTip() {
                if (!studentYearSelect || !registerYearSystemTip) {
                    return;
                }

                if (String(studentYearSelect.value) === '1') {
                    registerYearSystemTip.innerHTML =
                        'دەتوانی سیستەمی <span class="badge bg-success">زانکۆلاین</span> و <span class="badge bg-danger">پاڕالێل</span> و <span class="badge bg-dark">ئێواران</span> هەڵبژێری';
                    return;
                }

                registerYearSystemTip.innerHTML =
                    'بەس سیستەمی <span class="badge bg-danger">پاڕالێل</span> و <span class="badge bg-dark">ئێواران</span> هەڵبژێری';
            }

            async function resolveReferralOwner() {
                if (!referralInput || !referralOwner) {
                    return;
                }

                const code = (referralInput.value || '').trim();
                const role = roleSelect ? roleSelect.value : 'student';

                if (!code) {
                    setInfoText(emptyReferralMessage(role), 'text-muted');
                    return;
                }

                try {
                    const params = new URLSearchParams({
                        code,
                        role
                    });

                    const response = await fetch(`${infoUrl}?${params.toString()}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();

                    if (data.found) {
                        const phone = data.phone ? data.phone : 'نییە';
                        setInfoText(
                            `تۆمارکردنەکەت لەژێر کۆدی ${data.name} (${data.role_label}) دەچێت. ژمارەی پەیوەندی: ${phone}`,
                            'text-success');
                    } else {
                        setInfoText('ئەم کۆدە بۆ ئەم ڕۆڵە نەدۆزرایەوە.', 'text-warning');
                    }
                } catch (e) {
                    setInfoText('هەڵەیەک ڕوویدا لە دۆزینەوەی خاوەنی کۆد.', 'text-danger');
                }
            }

            updateRoleFields();

            if (generateCodeBtn && codeInput) {
                generateCodeBtn.addEventListener('click', function() {
                    codeInput.value = intGeneratorCode(4).toString();
                    codeInput.focus();
                });
            }

            if (roleSelect) {
                roleSelect.addEventListener('change', function() {
                    updateRoleFields();
                    syncRegisterYearTip();
                    resolveReferralOwner();
                });
            }

            if (studentYearSelect) {
                studentYearSelect.addEventListener('change', syncRegisterYearTip);
            }

            if (referralInput) {
                referralInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(resolveReferralOwner, 300);
                });
            }

            if (referralInput && referralInput.value.trim() !== '') {
                resolveReferralOwner();
            } else if (referralOwner) {
                setInfoText(emptyReferralMessage(roleSelect ? roleSelect.value : 'student'), 'text-muted');
            }

            syncRegisterYearTip();
        });
    </script>
</body>

</html>
