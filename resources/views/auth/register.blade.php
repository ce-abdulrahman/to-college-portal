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
                تکایە زانیاریەکانت بنووسە بۆ تۆمارکردنی قوتابی
            </p>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="student_code"><i class="fas fa-id-card"></i> کۆدی قوتابی</label>
                    <div class="input-icon">
                        <i class="fas fa-id-card"></i>
                        <input type="text" name="code" value="{{ old('code') }}" class="form-control"
                            placeholder="کۆدی قوتابی بنووسە" required>
                    </div>
                    @error('code')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="student_name"><i class="fas fa-id-card"></i> قوتابی ناوی</label>
                    <div class="input-icon">
                        <i class="fas fa-id-card"></i>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                            placeholder="ناو قوتابی بنووسە" required>
                    </div>
                    @error('name')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="student_phone"><i class="fas fa-phone"></i> ژمارەی مۆبایل</label>
                    <div class="input-icon">
                        <i class="fas fa-phone"></i>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control"
                            placeholder="ژمارەی مۆبایل (ئارەزوومەندە)" >
                    </div>
                    @error('phone')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="province"><i class="fas fa-map-marker-alt"></i> پارێزگا</label>
                    <div class="input-icon">
                        <i class="fas fa-map-marker-alt"></i>
                        <select name="province" class="form-control" required>
                            <option value="">پارێزگا هەڵبژێرە</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->name }}" @selected(old('province') === $province->name)>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('province')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type"><i class="fas fa-book"></i> جۆری خوێندن</label>
                    <div class="input-icon">
                        <i class="fas fa-book"></i>
                        <select name="type" class="form-control" required>
                            <option value="">جۆر هەڵبژێرە</option>
                            <option value="زانستی" @selected(old('type') === 'زانستی')>زانستی</option>
                            <option value="وێژەیی" @selected(old('type') === 'وێژەیی')>وێژەیی</option>
                        </select>
                    </div>
                    @error('type')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="gender"><i class="fas fa-venus-mars"></i> ڕەگەز</label>
                    <div class="input-icon">
                        <i class="fas fa-venus-mars"></i>
                        <select name="gender" class="form-control" required>
                            <option value="">ڕەگەز هەڵبژێرە</option>
                            <option value="نێر" @selected(old('gender') === 'نێر')>نێر</option>
                            <option value="مێ" @selected(old('gender') === 'مێ')>مێ</option>
                        </select>
                    </div>
                    @error('gender')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="year"><i class="fas fa-calendar"></i> چەند سال فۆرمی زانکۆلاین پێشکەش کردووە</label>
                    <div class="input-icon">
                        <i class="fas fa-calendar"></i>
                        <input type="number" name="year" value="{{ old('year') }}" class="form-control"
                            placeholder="ساڵ بنووسە" required>
                    </div>
                    @error('year')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="mark"><i class="fas fa-pen"></i> نمرە</label>
                    <div class="input-icon">
                        <i class="fas fa-pen"></i>
                        <input type="number" step="0.001" name="mark" value="{{ old('mark') }}" class="form-control"
                            placeholder="نمرە بنووسە" required>
                    </div>
                    @error('mark')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="referral_code"><i class="fas fa-link"></i> کۆدی پێشنیار</label>
                    <div class="input-icon">
                        <i class="fas fa-link"></i>
                        <input type="text" id="referral_code" name="referral_code"
                            value="{{ old('referral_code', $prefilledReferralCode ?? '') }}" class="form-control"
                            placeholder="کۆدی پێشنیار (ئارەزوومەندە)">
                    </div>
                    <div id="referral-owner" class="small mt-2
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
                            ئەم خانەیە ئارەزوومەندانەیە. ئەگەر center/teacher کۆد دانەنرێت، بە شێوەی خۆکارانە بۆ ئەدمین حساب دەکرێت.
                        @endif
                    </div>
                    @error('referral_code')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password"><i class="fas fa-lock"></i> وشەی نهێنی</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="وشەی نهێنی بنووسە"
                            required>
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
            const referralInput = document.getElementById('referral_code');
            const referralOwner = document.getElementById('referral-owner');
            const infoUrl = "{{ route('register.referrer-info') }}";

            if (!referralInput || !referralOwner) {
                return;
            }

            let debounceTimer = null;

            function setInfoText(text, className) {
                referralOwner.textContent = text;
                referralOwner.classList.remove('text-success', 'text-danger', 'text-muted', 'text-warning');
                referralOwner.classList.add(className);
            }

            async function resolveReferralOwner() {
                const code = (referralInput.value || '').trim();

                if (!code) {
                    setInfoText('ئەم خانەیە ئارەزوومەندانەیە. ئەگەر کۆد نەنووسیت، تۆمارکردن بۆ ئەدمین حساب دەکرێت.', 'text-muted');
                    return;
                }

                try {
                    const response = await fetch(`${infoUrl}?code=${encodeURIComponent(code)}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();

                    if (data.found) {
                        const phone = data.phone ? data.phone : 'نییە';
                        setInfoText(`تۆمارکردنەکەت لەژێر کۆدی ${data.name} (${data.role_label}) دەچێت. ژمارەی پەیوەندی: ${phone}`, 'text-success');
                    } else {
                        setInfoText('ئەم کۆدە نەدۆزرایەوە. تۆمارکردنەکەت خۆکارانە بۆ ئەدمین حساب دەکرێت.', 'text-warning');
                    }
                } catch (e) {
                    setInfoText('هەڵەیەک ڕوویدا لە دۆزینەوەی خاوەنی کۆد.', 'text-danger');
                }
            }

            referralInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(resolveReferralOwner, 300);
            });

            if (referralInput.value.trim() !== '') {
                resolveReferralOwner();
            }
        });
    </script>
</body>

</html>
