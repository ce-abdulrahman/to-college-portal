<!DOCTYPE html>
<html lang="ku" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>هەژماری ناچاڵاک</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login-container mt-4">
        <div class="login-card text-center">
            <div class="logo">
                <i class="fas fa-user-clock"></i>
            </div>

            <h1 class="title">هەژمارەکەت ناچاڵاکە</h1>
            <p class="subtitle">تا پەسەندکردن (چاڵاک) ناتوانیت چوونەژوورەوە بکەیت.</p>

            @php
                $resolvedReferrer = $inactiveAccount['referrer'] ?? null;

                if ($resolvedReferrer === null && !empty($referrer)) {
                    $resolvedReferrer = [
                        'name' => data_get($referrer, 'name'),
                        'phone' => data_get($referrer, 'phone'),
                        'role' => data_get($referrer, 'role'),
                        'roleLabel' => $referrerRoleLabel ?? null,
                        'randCode' => data_get($referrer, 'rand_code'),
                    ];
                }

                $showReferralOwnerContact = in_array($resolvedReferrer['role'] ?? null, ['center', 'teacher'], true);
            @endphp

            <div class="inactive-box mt-3 mb-3">
                <div class="inactive-badge">
                    <i class="fas fa-hourglass-half me-1"></i>
                    چاوەڕوانی چاڵاککردن
                </div>

                @if ($showReferralOwnerContact)
                <div class="owner-title mt-3">خاوەنی کۆدی تۆمارکردن</div>

                <div class="account-name mt-2">{{ $resolvedReferrer['name'] ?: '—' }}</div>

                <div class="meta-row">
                    <span class="meta-label">پیشە :</span>
                    <span class="meta-value">{{ $resolvedReferrer['roleLabel'] ?: '—' }}</span>
                </div>

                <div class="meta-row">
                    <span class="meta-label">Referral Code :</span>
                    <span class="meta-value">{{ $resolvedReferrer['randCode'] ?: '—' }}</span>
                </div>

                <div class="meta-row">
                    <span class="meta-label">مۆبایل :</span>
                    <span class="meta-value">{{ $resolvedReferrer['phone'] ?: '—' }}</span>
                </div>
                @else
                @php
                    $socialAccounts = [];
                    if (!empty($appSettings['social_accounts'])) {
                        $socialAccounts = json_decode($appSettings['social_accounts'], true) ?: [];
                    }
                    if (empty($socialAccounts)) {
                        $socialAccounts = [
                            ['name' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'url' => 'https://www.facebook.com/AghaAS7421'],
                            ['name' => 'Telegram', 'icon' => 'fab fa-telegram-plane', 'url' => 'https://t.me/AGHA_ACE'],
                            ['name' => 'Whatsapp', 'icon' => 'fab fa-whatsapp', 'url' => 'https://wa.me/9647504342452'],
                            ['name' => 'Instagram', 'icon' => 'fab fa-instagram', 'url' => 'https://www.instagram.com/agha_ace'],
                            ['name' => 'Viber', 'icon' => 'fab fa-viber', 'url' => 'viber://chat?number=9647504342452'],
                        ];
                    }
                @endphp

                <div class="fallback-contact-card mt-3">
                    <span class="fallback-contact-icon">
                        <i class="fa-solid fa-headset"></i>
                    </span>
                    <div class="fallback-contact-title">پەیوەندی بۆ چاڵاککردنی هەژمار</div>
                    <p class="fallback-contact-text mb-0">
                        تکایە لە ڕێگای ژمارەی خوارەوە پەیوەندی بکە تا هەژمارەکەت چاڵاک بکرێت.
                    </p>

                    <a href="tel:+9647504342452" class="fallback-phone-link mt-3" dir="ltr">
                        <i class="fa-solid fa-phone-volume"></i>
                        <span>+964 750 434 2452</span>
                    </a>

                    @if (!empty($socialAccounts))
                    <div class="fallback-social-links mt-3">
                        @foreach ($socialAccounts as $social)
                            @php
                                $socialName = trim($social['name'] ?? '');
                                $socialIcon = trim($social['icon'] ?? '');
                                $socialUrl = trim($social['url'] ?? '');
                            @endphp
                            @if ($socialUrl !== '')
                                <a href="{{ $socialUrl }}" target="_blank" rel="noopener noreferrer"
                                    class="fallback-social-link" title="{{ $socialName ?: 'Social' }}">
                                    <i class="{{ $socialIcon ?: 'fa-solid fa-link' }}"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <div class="alert alert-info mt-3">
                تکایە چاوەڕێی بەڕێوەبەر بکە تا هەژمارەکەت چاڵاک دەکرێت.
            </div>

            <a href="{{ route('login') }}" class="btn btn-primary mt-2">
                <i class="fas fa-arrow-right-to-bracket me-1"></i> گەڕانەوە بۆ چوونەژوورەوە
            </a>
        </div>
    </div>

    <style>
        .inactive-box {
            background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
            border: 1px solid #ffd7d7;
            border-radius: 14px;
            padding: 16px;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
        }

        .inactive-badge {
            display: inline-block;
            background: #ffe3e3;
            color: #a31621;
            border-radius: 999px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 700;
        }

        .account-name {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
        }

        .meta-row {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
            font-size: 15px;
        }

        .meta-label {
            color: #6b7280;
            font-weight: 600;
        }

        .meta-value {
            color: #111827;
            font-weight: 700;
        }

        .owner-title {
            color: #5b6474;
            font-size: 13px;
            font-weight: 700;
        }

        .fallback-contact-card {
            border: 1px dashed #bfdbfe;
            background: linear-gradient(135deg, #eff6ff 0%, #f8fbff 100%);
            border-radius: 12px;
            padding: 14px 12px;
        }

        .fallback-contact-icon {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 18px;
            margin-bottom: 6px;
        }

        .fallback-contact-title {
            font-size: 14px;
            font-weight: 700;
            color: #1e3a8a;
        }

        .fallback-contact-text {
            margin-top: 6px;
            color: #475569;
            font-size: 13px;
            line-height: 1.7;
        }

        .fallback-phone-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #0f172a;
            font-weight: 800;
            font-size: 14px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 999px;
            padding: 7px 14px;
            transition: all 0.2s ease;
        }

        .fallback-phone-link:hover {
            color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 8px 16px rgba(29, 78, 216, 0.12);
        }

        .fallback-social-links {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .fallback-social-link {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .fallback-social-link:hover {
            color: #1d4ed8;
            border-color: #93c5fd;
            transform: translateY(-2px);
        }

    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
