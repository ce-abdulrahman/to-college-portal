<!DOCTYPE html>
<html lang="ku" dir="rtl">

<head>
    @php
        $siteName = trim($appSettings['site_name'] ?? '') ?: 'کۆلێژ پڵەس';
        $siteLogo = $appSettings['site_logo'] ?? null;
        $fontKu = $appSettings['font_ku'] ?? null;
        $fontAr = $appSettings['font_ar'] ?? null;
        $fontEn = $appSettings['font_en'] ?? null;
        $copyrightText = trim($appSettings['copyright'] ?? '') ?: '© ٢٠٢٥ هەموو مافەکان پارێزراون';
        $socialAccounts = json_decode($appSettings['social_accounts'] ?? '[]', true);
        if (!is_array($socialAccounts)) {
            $socialAccounts = [];
        }
        if (count($socialAccounts) === 0) {
            $socialAccounts = [
                ['name' => 'Facebook', 'icon' => 'ri-facebook-fill', 'url' => 'https://www.facebook.com'],
                ['name' => 'Instagram', 'icon' => 'ri-instagram-fill', 'url' => 'https://www.instagram.com'],
                ['name' => 'X', 'icon' => 'ri-twitter-x-fill', 'url' => 'https://www.twitter.com'],
                ['name' => 'LinkedIn', 'icon' => 'ri-linkedin-fill', 'url' => 'https://www.linkedin.com'],
                ['name' => 'Telegram', 'icon' => 'ri-telegram-fill', 'url' => 'https://www.telegram.org'],
            ];
        }

        // Theme colors
        $primaryColor = $appSettings['primary_color'] ?? '#6366f1';
        $secondaryColor = $appSettings['secondary_color'] ?? '#8b5cf6';
        $accentColor = $appSettings['accent_color'] ?? '#ec4899';
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="سیستەمی هەڵبژاردنی بەشەکانی زانکۆ - پلاتفۆڕمی زیرەک بۆ ڕێنمایی قوتابیان">
    <meta name="keywords" content="زانکۆ, کۆلێژ, ڕێزبەندی, هەڵبژاردنی بەش, ڕێنمایی قوتابی">
    <meta name="author" content="کۆلێژ پڵەس">

    <title>{{ $siteName }} | ڕێنمایی زیرەکی قوتابیان</title>

    <!-- Bootstrap 5.3 RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts - Vazirmatn for Kurdish/Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: {{ $primaryColor }};
            --primary-dark: #4f46e5;
            --primary-light: #a5b4fc;
            --secondary: {{ $secondaryColor }};
            --accent: {{ $accentColor }};
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark: #0f172a;
            --light: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;

            --gradient-1: linear-gradient(135deg, {{ $primaryColor }}, {{ $secondaryColor }});
            --gradient-2: linear-gradient(225deg, {{ $secondaryColor }}, {{ $accentColor }});
            --gradient-3: linear-gradient(45deg, {{ $primaryColor }}, {{ $accentColor }});
            --gradient-4: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

            --shadow-sm: 0 2px 4px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.03);
            --shadow-md: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.02);
            --shadow-lg: 0 20px 35px -8px rgba(0,0,0,0.1), 0 10px 15px -6px rgba(0,0,0,0.02);
            --shadow-xl: 0 25px 50px -12px rgba(0,0,0,0.15);
            --shadow-inner: inset 0 2px 4px 0 rgba(0,0,0,0.03);

            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --radius-xl: 30px;
            --radius-full: 9999px;

            --font-main: 'Vazirmatn', sans-serif;
        }

        body {
            font-family: var(--font-main);
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-1);
            border-radius: var(--radius-full);
            border: 2px solid var(--gray-100);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gradient-3);
        }

        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .glass-dark {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Navbar */
        .navbar-modern {
            position: fixed;
            top: 20px;
            left: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: var(--radius-full);
            padding: 0.5rem 1.5rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.5);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar-modern.scrolled {
            top: 10px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: var(--shadow-xl);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-img {
            height: 45px;
            width: auto;
            border-radius: var(--radius-sm);
            transition: transform 0.3s ease;
        }

        .logo-img:hover {
            transform: scale(1.05) rotate(2deg);
        }

        .logo-text {
            font-size: 1.4rem;
            font-weight: 800;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-btn {
            padding: 0.7rem 1.8rem;
            border-radius: var(--radius-full);
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .nav-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .nav-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-login {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-login:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px var(--primary);
        }

        .btn-register {
            background: var(--gradient-1);
            border: none;
            color: white;
            box-shadow: 0 8px 15px -3px var(--primary);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px var(--primary);
        }

        /* Hero Section */
        .hero-modern {
            min-height: 90vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            margin-top: 80px;
        }

        .hero-video-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .hero-video-bg video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: saturate(1.05) contrast(1.02);
        }

        .hero-video-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(15, 23, 42, 0.65) 0%,
                rgba(15, 23, 42, 0.45) 45%,
                rgba(15, 23, 42, 0.72) 100%
            );
        }

        .hero-particles {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
            z-index: 1;
        }

        .hero-modern .container {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .hero-title span {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
        }

        .hero-description {
            font-size: 1.2rem;
            color: var(--gray-500);
            margin-bottom: 2rem;
            max-width: 600px;
        }

        /* Floating Cards */
        .floating-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1rem;
            box-shadow: var(--shadow-lg);
            position: absolute;
            animation: float 6s ease-in-out infinite;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            z-index: 2;
        }

        .card-1 {
            top: 20%;
            right: 10%;
            animation-delay: 0s;
        }

        .card-2 {
            bottom: 20%;
            right: 5%;
            animation-delay: 2s;
        }

        .card-3 {
            top: 40%;
            left: 5%;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* Stats Section */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin: 4rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            border: 1px solid var(--gray-200);
        }

        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .stat-label {
            color: var(--gray-500);
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Feature Cards Modern */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .feature-card-modern {
            background: white;
            border-radius: var(--radius-xl);
            padding: 2.5rem 2rem;
            box-shadow: var(--shadow-md);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--gray-200);
        }

        .feature-card-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient-1);
            transform: translateX(-100%);
            transition: transform 0.4s ease;
        }

        .feature-card-modern:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-xl);
            border-color: transparent;
        }

        .feature-card-modern:hover::before {
            transform: translateX(0);
        }

        .feature-icon-wrapper {
            width: 70px;
            height: 70px;
            background: var(--gradient-1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.8rem;
            color: white;
            font-size: 2rem;
            position: relative;
            z-index: 1;
        }

        .feature-icon-wrapper::after {
            content: '';
            position: absolute;
            inset: -3px;
            background: var(--gradient-1);
            border-radius: inherit;
            opacity: 0.3;
            z-index: -1;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.1); opacity: 0.1; }
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .feature-description {
            color: var(--gray-500);
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }

        /* Gradient Cards */
        .gradient-card {
            background: var(--gradient-1);
            border-radius: var(--radius-xl);
            padding: 3rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }

        .gradient-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 60%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .gradient-card-content {
            position: relative;
            z-index: 1;
        }

        /* Timeline/Features List */
        .feature-list-modern {
            list-style: none;
            padding: 0;
        }

        .feature-list-modern li {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .feature-list-modern li:last-child {
            border-bottom: none;
        }

        .feature-list-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* About Section */
        .about-section {
            background: white;
            border-radius: var(--radius-xl);
            padding: 3rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-200);
        }

        /* Footer Modern */
        .footer-modern {
            background: var(--dark);
            color: white;
            padding: 3rem 0;
            margin-top: 5rem;
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
            position: relative;
            overflow: hidden;
        }

        .footer-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-1);
        }

        .social-links-modern {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .social-link-modern {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.1);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .social-link-modern:hover {
            background: var(--gradient-1);
            transform: translateY(-5px) rotate(360deg);
            border-color: transparent;
        }

        .copyright-modern {
            text-align: center;
            color: var(--gray-400);
            font-size: 0.95rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeInUp 0.8s ease forwards;
        }

        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }

        /* Responsive */
        @media (max-width: 991px) {
            .navbar-modern {
                top: 10px;
                left: 10px;
                right: 10px;
                padding: 0.5rem 1rem;
            }

            .hero-modern {
                min-height: auto;
                padding: 4rem 0;
            }

            .floating-card {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .logo-text {
                font-size: 1.1rem;
            }

            .nav-btn {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }

            .gradient-card {
                padding: 2rem;
            }
        }

        /* Custom Cursor */
        .custom-cursor {
            width: 30px;
            height: 30px;
            border: 2px solid var(--primary);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            transition: transform 0.2s ease;
            transform: translate(-50%, -50%);
            mix-blend-mode: difference;
        }

        /* Loading Animation */
        .loading-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: var(--gradient-1);
            width: 0%;
            z-index: 10000;
            transition: width 0.3s ease;
        }
    </style>

    @if ($fontKu || $fontAr || $fontEn)
        <style>
            @if ($fontKu)
                @font-face {
                    font-family: 'CustomKu';
                    src: url('{{ asset($fontKu) }}') format('truetype');
                    font-display: swap;
                }

                html[lang="ku"] body {
                    font-family: 'CustomKu', 'Vazirmatn', sans-serif;
                }
            @endif

            @if ($fontAr)
                @font-face {
                    font-family: 'CustomAr';
                    src: url('{{ asset($fontAr) }}') format('truetype');
                    font-display: swap;
                }

                html[lang="ar"] body {
                    font-family: 'CustomAr', 'Vazirmatn', sans-serif;
                }
            @endif

            @if ($fontEn)
                @font-face {
                    font-family: 'CustomEn';
                    src: url('{{ asset($fontEn) }}') format('truetype');
                    font-display: swap;
                }

                html[lang="en"] body {
                    font-family: 'CustomEn', 'Vazirmatn', sans-serif;
                }
            @endif
        </style>
    @endif
</head>

<body>

    <!-- Loading Bar -->
    <div class="loading-bar" id="loadingBar"></div>

    <!-- Custom Cursor -->
    <div class="custom-cursor" id="customCursor"></div>

    <!-- Navbar -->
    <nav class="navbar-modern" id="navbar">
        <div class="container-fluid d-flex flex-wrap align-items-center justify-content-around px-0">
            <div class="logo-container">
                @if ($siteLogo)
                    <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" class="logo-img">
                @else
                    <div class="logo-img d-flex align-items-center justify-content-center" style="background: var(--gradient-1); color: white; width: 45px;">
                        <i class="ri-graduation-cap-fill"></i>
                    </div>
                @endif
                <span class="logo-text">{{ $siteName }}</span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('login') }}" class="nav-btn btn-login">
                    <i class="ri-login-box-line me-2"></i>
                    چوونەژوورەوە
                </a>
                <a href="{{ route('register') }}" class="nav-btn btn-register">
                    <i class="ri-user-add-line me-2"></i>
                    خۆتۆمارکردن
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>

        <!-- Hero Section -->
        <section class="hero-modern">
            <div class="hero-video-bg">
                <video autoplay muted loop playsinline preload="metadata">
                    <source src="{{ asset('bg/map-animation.mp4') }}" type="video/mp4">
                    گەڕۆکەکەت پشتگیری ڤیدیۆ ناکات.
                </video>
                <div class="hero-video-overlay"></div>
            </div>
            <div class="hero-particles"></div>

            <!-- Floating Cards -->
            <div class="floating-card card-1">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-user-star-fill" style="color: var(--primary);"></i>
                    <span class="fw-bold">+1000 قوتابی</span>
                </div>
            </div>
            <div class="floating-card card-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-building-fill" style="color: var(--secondary);"></i>
                    <span class="fw-bold">200 بەش</span>
                </div>
            </div>
            <div class="floating-card card-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-heart-fill" style="color: var(--accent);"></i>
                    <span class="fw-bold">90٪ ڕەزایی</span>
                </div>
            </div>

            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-7">
                        <h1 class="hero-title animate-fade-up">
                            ڕێگای تۆ بۆ <span>داهاتوویەکی ڕوون</span>
                        </h1>
                        <p class="hero-description animate-fade-up delay-1">
                            سیستەمی زیرەکی هەڵبژاردنی بەشەکانی زانکۆ، یارمەتی قوتابیان دەدات بۆ دۆزینەوەی گونجاوترین بەش لەسەر بنەمای تواناکان و ئارەزووەکانیان.
                        </p>
                        <div class="d-flex gap-3 animate-fade-up delay-2">
                            <a href="{{ route('register') }}" class="nav-btn btn-register" style="padding: 1rem 2.5rem;">
                                <i class="ri-rocket-line me-2"></i>
                                دەستپێبکە
                            </a>
                            <a href="#features" class="nav-btn btn-login">
                                <i class="ri-information-line me-2"></i>
                زانیاری زیاتر
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <!-- Interactive 3D Card -->
                        <div class="" style="background: transparent;">
                            <div class="gradient-card-content text-center">
                                <i class="ri-cpu-line" style="font-size: 4rem; margin-bottom: 1.5rem;"></i>
                                <h3 class="h4 mb-3">سیستەمی زیرەکی دەستکرد</h3>
                                <p class="mb-0 opacity-75">پێشنیاری گونجاوترین بەشەکان بە پشتگیری AI</p>
                                <div class="mt-4">
                                    <span class="badge bg-white text-dark p-2 px-3 rounded-pill">زیرەکی 90٪</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="container">
            <div class="stats-grid">
                <div class="stat-item animate-fade-up">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">قوتابی هاوکار</div>
                </div>
                <div class="stat-item animate-fade-up delay-1">
                    <div class="stat-number">5</div>
                    <div class="stat-label">بەشی زانکۆ</div>
                </div>
                <div class="stat-item animate-fade-up delay-2">
                    <div class="stat-number">-</div>
                    <div class="stat-label">زانکۆ هاوکار</div>
                </div>
                <div class="stat-item animate-fade-up delay-3">
                    <div class="stat-number">90٪</div>
                    <div class="stat-label">ڕەزایی پێشنیار</div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="container" style="margin: 5rem auto;">
            <div class="text-center mb-5">
                <span class="badge bg-primary-subtle text-primary p-3 px-4 rounded-pill mb-3">
                    <i class="ri-flashlight-fill me-2"></i>
                    تایبەتمەندییەکان
                </span>
                <h2 class="display-5 fw-bold mb-3">هەموو ئەو شتانەی پێویستت پێیەتی</h2>
                <p class="text-muted fs-5">سیستەمێکی تەواو بۆ ڕێنمایی قوتابیان لە هەڵبژاردنی بەشەکان</p>
            </div>

            <div class="feature-grid">
                <!-- Feature 1 -->
                <div class="feature-card-modern animate-fade-up text-center">
                    <div class="feature-icon-wrapper mx-auto">
                        <i class="ri-map-pin-line"></i>
                    </div>
                    <h3 class="feature-title">سیستەمی نەخشە</h3>
                    <p class="feature-description">نیشاندانی شوێنی زانکۆ و بەشەکان لەسەر نەخشەی ڕاستەقینە بە تەکنەلۆژیای GIS</p>
                    <a href="#" class="text-decoration-none" style="color: var(--primary); font-weight: 600;">
                        زیاتر بزانە <i class="ri-arrow-left-line me-1"></i>
                    </a>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card-modern animate-fade-up delay-1  text-center">
                    <div class="feature-icon-wrapper mx-auto">
                        <i class="ri-brain-line"></i>
                    </div>
                    <h3 class="feature-title">زیرەکی دەستکرد</h3>
                    <p class="feature-description">سیستەمی AI پێشنیاری گونجاوترین بەشەکان دەکات بەپێی تواناکانی قوتابی</p>
                    <a href="#" class="text-decoration-none" style="color: var(--primary); font-weight: 600;">
                        زیاتر بزانە <i class="ri-arrow-left-line me-1"></i>
                    </a>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card-modern animate-fade-up delay-2  text-center">
                    {{--  feature-icon-wrapper for middle center  --}}
                    <div class="feature-icon-wrapper mx-auto">
                        <i class="ri-user-heart-line"></i>
                    </div>
                    <h3 class="feature-title">جۆری کەسایەتی</h3>
                    <p class="feature-description">دیاریکردنی جۆری کەسایەتی (MBTI) و پێشنیاری بەشە گونجاوەکان</p>
                    <a href="#" class="text-decoration-none" style="color: var(--primary); font-weight: 600;">
                        زیاتر بزانە <i class="ri-arrow-left-line me-1"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="container">
            <div class="about-section animate-fade-up">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <span class="badge bg-primary-subtle text-primary p-2 px-3 rounded-pill mb-3">
                            <i class="ri-information-line me-2"></i>
                            دەربارە
                        </span>
                        <h2 class="display-6 fw-bold mb-3">دەربارەی {{ $siteName }}</h2>
                        <p class="text-muted fs-5 mb-4">
                            ئێمە پلاتفۆڕمێکی پێشکەوتووین بۆ ڕێنمایی قوتابیان لە هەڵبژاردنی بەشەکانی زانکۆ،
                            بە بەکارهێنانی دوایین تەکنەلۆژیاکانی زیرەکی دەستکرد و شیکاری داتا.
                        </p>
                        <div class="feature-list-modern">
                            <li>
                                <div class="feature-list-icon">
                                    <i class="ri-check-line text-center"></i>
                                </div>
                                <span>زیاتر لە ٢٥٠٠ قوتابی سوودمەند بوون</span>
                            </li>
                            <li>
                                <div class="feature-list-icon">
                                    <i class="ri-check-line"></i>
                                </div>
                                <span>هاوکاری لەگەڵ ١٥ زانکۆ ناسراو</span>
                            </li>
                            <li>
                                <div class="feature-list-icon">
                                    <i class="ri-check-line"></i>
                                </div>
                                <span>سیستەمی پشتگیری ٢٤/٧</span>
                            </li>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="gradient-card" style="background: var(--gradient-4);">
                            <div class="gradient-card-content text-center">
                                <i class="ri-team-line" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                                <h4>پێکهاتووین لە</h4>
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <div class="h2 fw-bold mb-0">١٢+</div>
                                        <small>پسپۆڕ</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="h2 fw-bold mb-0">٥+</div>
                                        <small>ساڵ ئەزموون</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="container" style="margin: 5rem auto;">
            <div class="gradient-card text-center">
                <div class="gradient-card-content">
                    <h2 class="display-6 fw-bold mb-3">ئامادەیت بۆ دەستپێکردن؟</h2>
                    <p class="fs-5 mb-4 opacity-90">تۆمار بکە و دەستبکە بە دیاریکردنی ڕێگای داهاتووت</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold" style="color: var(--primary);">
                            <i class="ri-user-add-line me-2"></i>
                            تۆمارکردن
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill fw-bold">
                            <i class="ri-login-box-line me-2"></i>
                            چوونەژوورەوە
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="footer-modern">
        <div class="container">
            <div class="social-links-modern">
                @foreach ($socialAccounts as $social)
                    @php
                        $socialName = trim($social['name'] ?? '');
                        $socialIcon = trim($social['icon'] ?? '');
                        $socialUrl = trim($social['url'] ?? '');
                    @endphp
                    @if ($socialUrl !== '' && $socialIcon !== '')
                        <a href="{{ $socialUrl }}" class="social-link-modern" target="_blank" rel="noopener noreferrer"
                            title="{{ $socialName ?: 'Social' }}">
                            <i class="{{ $socialIcon }}"></i>
                        </a>
                    @endif
                @endforeach
            </div>
            <div class="copyright-modern">
                <i class="ri-copyright-line me-2"></i>
                {{ $copyrightText }} - گرووپی کۆس
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        const loadingBar = document.getElementById('loadingBar');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            // Loading bar effect
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            loadingBar.style.width = scrolled + '%';
        });

        // Custom cursor
        const cursor = document.getElementById('customCursor');

        document.addEventListener('mousemove', (e) => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
        });

        document.addEventListener('mouseenter', () => {
            cursor.style.opacity = '1';
        });

        document.addEventListener('mouseleave', () => {
            cursor.style.opacity = '0';
        });

        // Hover effect on interactive elements
        const interactiveElements = document.querySelectorAll('a, button, .feature-card-modern, .stat-item');

        interactiveElements.forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursor.style.transform = 'translate(-50%, -50%) scale(1.5)';
                cursor.style.backgroundColor = 'rgba(99, 102, 241, 0.1)';
            });

            el.addEventListener('mouseleave', () => {
                cursor.style.transform = 'translate(-50%, -50%) scale(1)';
                cursor.style.backgroundColor = 'transparent';
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-fade-up').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s ease';
            observer.observe(el);
        });

        // Parallax effect
        document.addEventListener('mousemove', (e) => {
            const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
            const moveY = (e.clientY - window.innerHeight / 2) * 0.01;

            document.querySelectorAll('.floating-card').forEach((card, index) => {
                const speed = index + 1;
                card.style.transform = `translate(${moveX * speed}px, ${moveY * speed}px)`;
            });
        });

        // Loading animation
        window.addEventListener('load', () => {
            document.body.classList.add('loaded');
        });
    </script>
</body>

</html>
