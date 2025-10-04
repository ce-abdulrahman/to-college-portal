<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="ئەم سیستەمە بۆ هەڵبژاردنی بەشەکانی زانکۆ لە هەر چوار پارێزگادابگەڕی. قوتابی دەتوانێت بە ئاسانی بەشەکان هەڵبژێرد و ڕێزبەندیەک بۆ خۆی دروست بکات..">
    <meta name="keywords"
        content="zankoline,kolizh,colej,university,college,rezbande, بەرەو زانکۆ, زانکۆلاین, رێزبەندی فۆرمی زانکۆلاین, پۆلی ١٢, poli 12">
    <meta name="author" content="Abdulrahman">

    <title>چوونەژوورەوە</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --card-bg: #ffffff;
            --text-color: #333333;
            --border-radius: 12px;
            --box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--text-color);
        }

        .login-container {
            max-width: 450px;
            width: 100%;
        }

        .login-card {
            background: var(--card-bg);
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            text-align: center;
        }

        .logo {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 1rem;
            color: var(--text-color);
            margin-bottom: 30px;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e2e8f0;
            border-radius: var(--border-radius);
            background: #fff;
            color: var(--dark-color);
            margin-bottom: 1.2rem;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            outline: none;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--secondary-color);
            text-align: right;
        }

        .btn-primary {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            width: 100%;
            padding: 1rem;
            border-radius: var(--border-radius);
            font-weight: 700;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
        }

        .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-danger {
            background-color: #feeaea;
            color: #c53030;
            border: 1px solid #feb2b2;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        .input-icon .form-control {
            padding-left: 45px;
        }

        .register-link {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: var(--border-radius);
            text-align: center;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 700;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-card {
                padding: 2rem 1.5rem;
            }

            .logo {
                font-size: 2.5rem;
            }

            .title {
                font-size: 1.5rem;
            }

            .form-control {
                padding: 0.9rem 1rem;
            }
        }

        .footer {
            text-align: center;
            color: #718096;
            font-size: 0.9rem;
            margin-top: 30px;
        }
    </style>

</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1 class="title">چوونەژوورەوە</h1>
            <p class="subtitle">تکایە زانیاریەکانی خۆت بنووسە بۆ چوونەژوورەوە</p>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="student_code"><i class="fas fa-id-card"></i> کۆدی قوتابی</label>
                    <div class="input-icon">
                        <i class="fas fa-id-card"></i>
                        <input type="text" name="code" value="{{ old('code') }}" class="form-control" placeholder="کۆدی قوتابی بنووسە" required>
                    </div>
                    @error('code')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password"><i class="fas fa-lock"></i> وشەی نهێنی</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="وشەی نهێنی بنووسە" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> چوونەژوورەوە
                </button>
            </form>

            <div class="register-link">
                <p>ئەگەر ئەمژمێرت نیە دەتوانی لێرە دروست بکەی ؟
                    <a href="{{ route('register') }}">ئەژمێری نوێ</a>
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
