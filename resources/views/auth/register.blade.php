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

    <link rel="stylesheet" href="{{ asset('assets/student/css/style.css') }}">

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
                    <label for="student_name"><i class="fas fa-id-card"></i> کۆدی ناوی</label>
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
</body>

</html>
