<!DOCTYPE html>
<html lang="ku" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="ئەم سیستەمە بۆ هەڵبژاردنی بەشەکانی زانکۆ لە هەر چوار پارێزگادابگەڕی. قوتابی دەتوانێت بە ئاسانی بەشەکان هەڵبژێرد و ڕێزبەندیەک بۆ خۆی دروست بکات.">
    <meta name="keywords"
        content="zankoline,kolizh,colej,university,college,rezbande, بەرەو زانکۆ, زانکۆلاین, رێزبەندی فۆرمی زانکۆلاین, پۆلی ١٢, poli 12">
    <meta name="author" content="Abdulrahman">

    <title>چاوەڕێکردن</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login-container mt-4">
        <div class="login-card text-center">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
            </div>

            <h1 class="title">تۆمارکردن سەرکەوتوو بوو</h1>
            <p class="subtitle">هەژمارەکەت لە دۆخی چاوەڕوانییە (`status = 0`) تا ئەدمین چاڵاکی بکات.</p>

            <div class="pending-box mt-3 mb-3">
                <div class="pending-title">
                    <i class="fas fa-hourglass-half me-1"></i>
                    دۆخی هەژمار: چاوەڕوان
                </div>

                @if (!empty($referrer))
                    <div class="referrer-box mt-3">
                        <div class="referrer-label">ئەم هەژمارە لەژێر کۆدی کام کەس تۆمارکراوە؟</div>
                        <div class="referrer-role mt-2">{{ $referrerRoleLabel ?? strtoupper($referrer->role) }}</div>
                        <div class="referrer-name mt-2">{{ $referrer->name }}</div>
                        <div class="referrer-phone mt-1">
                            <i class="fas fa-phone-alt me-1"></i>
                            {{ $referrer->phone ?: 'ژمارەی مۆبایل نییە' }}
                        </div>
                    </div>
                @else
                    <div class="alert alert-light border mt-3 mb-0">
                        ئەم هەژمارە بە referral code تۆمار نەکراوە.
                    </div>
                @endif
            </div>

            <div class="alert alert-info mt-3">
                کاتێک هەژمارەکەت پەسەند کرێت دەتوانیت چوونەژوورەوە بکەیت.
            </div>

            <a href="{{ route('login') }}" class="btn btn-primary mt-2">
                <i class="fas fa-sign-in-alt"></i> چوونەژوورەوە
            </a>
        </div>

        <div class="footer">
            <div class="social-box">
                <div class="social-links d-flex gap-3 justify-content-center">
                    <a href="https://www.facebook.com/AghaAS7421" target="_blank" class="text-dark" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://t.me/AGHA_ACE" target="_blank" class="text-dark" title="Telegram">
                        <i class="fab fa-telegram-plane"></i>
                    </a>
                    <a href="https://wa.me/9647504342452" target="_blank" class="text-dark" title="Whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://www.instagram.com/agha_ace" target="_blank" class="text-dark" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="viber://chat?number=9647504342452" target="_blank" class="text-dark" title="Viber">
                        <i class="fab fa-viber"></i>
                    </a>
                </div>
            </div>
            <p>ھەموو مافەکان پارێزراوە © ٢٠٢٥ بۆ گرووپی کۆس</p>
        </div>
    </div>

    <style>
        .footer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .social-box {
            background: #ffffff;
            border-radius: 12px;
            padding: 12px 18px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .social-links a {
            font-size: 18px;
        }

        .pending-box {
            background: linear-gradient(135deg, #eef6ff 0%, #f7fbff 100%);
            border: 1px solid #d8e8ff;
            border-radius: 14px;
            padding: 16px;
        }

        .pending-title {
            color: #0b4d9a;
            font-weight: 700;
            font-size: 15px;
        }

        .referrer-box {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e9edf3;
            padding: 14px;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
        }

        .referrer-label {
            color: #5d6778;
            font-size: 13px;
        }

        .referrer-role {
            display: inline-block;
            background: #ecf3ff;
            color: #0d47a1;
            border-radius: 999px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 700;
        }

        .referrer-name {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
        }

        .referrer-phone {
            color: #334155;
            font-size: 15px;
            font-weight: 600;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
