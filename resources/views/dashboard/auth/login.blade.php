<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ __('Pharmacy Management') }} - Login</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}">
    
    <!-- Favicon -->
    @if(Helper::getStoreInfo()->favicon)
        <link rel="shortcut icon" href="{{ asset('uploads/images/settings/'.Helper::getStoreInfo()->favicon) }}" type="image/x-icon">
    @endif

    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #059669;
            --secondary-color: #0ea5e9;
            --bg-light: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-light);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .login-image {
            flex: 1;
            background: url("{{ asset('assets/images/pharmacy-bg.png') }}") no-repeat center center;
            background-size: cover;
            position: relative;
            display: none;
        }

        @media (min-width: 992px) {
            .login-image {
                display: block;
            }
        }

        .login-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.4) 0%, rgba(14, 165, 233, 0.4) 100%);
        }

        .image-content {
            position: absolute;
            bottom: 60px;
            left: 60px;
            z-index: 2;
            color: white;
            max-width: 500px;
        }

        .image-content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .image-content p {
            font-size: 1.25rem;
            opacity: 0.9;
            line-height: 1.6;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .login-form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: white;
            box-shadow: -10px 0 30px rgba(0,0,0,0.05);
            z-index: 3;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            animation: fadeInRight 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .logo-area {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 16px;
            color: white;
            font-size: 28px;
            margin-bottom: 20px;
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
            transition: transform 0.3s ease;
        }

        .logo-icon:hover {
            transform: scale(1.05) rotate(5deg);
        }

        .brand-name {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .brand-name span {
            color: var(--primary-color);
        }

        .sub-title {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-main);
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            transition: color 0.3s ease;
        }

        .form-control {
            height: 52px;
            padding: 10px 15px 10px 45px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            background-color: #fff;
        }

        .form-control:focus + i {
            color: var(--primary-color);
        }

        .btn-login {
            width: 100%;
            height: 52px;
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(16, 185, 129, 0.4);
            filter: brightness(1.05);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-msg {
            color: #ef4444;
            font-size: 13px;
            margin-top: 6px;
            display: block;
        }

        .footer-copyright {
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .login-form-side {
                padding: 20px;
            }
            .brand-name {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side: Image -->
        <div class="login-image">
            <div class="image-content">
                <h1>{{ Helper::getStoreInfo()->appname }}</h1>
                <p>Welcome to the next generation of Pharmacy Management. Manage your inventory, sales, and prescriptions with precision and ease.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="login-form-side">
            <div class="login-box">
                <div class="logo-area">
                    @if(Helper::getStoreInfo()->logo)
                        <img src="{{ asset('uploads/images/settings/'.Helper::getStoreInfo()->logo) }}" alt="{{ Helper::getStoreInfo()->appname }}" style="width: 140px; margin-bottom: 5px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
                    @else
                        <div class="logo-icon">
                            <i class="fa fa-plus-square"></i>
                        </div>
                    @endif
                    <h1 class="brand-name" style="margin-top: 10px;">
                        @php $nameParts = explode(' ', Helper::getStoreInfo()->appname); @endphp
                        <span>{{ $nameParts[0] ?? '' }}</span> {{ $nameParts[1] ?? '' }}
                    </h1>
                    <p class="sub-title">{{ __('Advanced Healthcare Management') }}</p>
                </div>

                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label" for="email">{{ __('Email') }}</label>
                        <div class="input-wrapper">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   placeholder="{{ __('Enter your email') }}" name="email" 
                                   value="{{ old('email') }}" required autocomplete="email" autofocus>
                            <i class="fa fa-envelope"></i>
                        </div>
                        @error('email')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">{{ __('Password') }}</label>
                        <div class="input-wrapper">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="{{ __('Enter your password') }}" name="password" required autocomplete="current-password">
                            <i class="fa fa-lock"></i>
                        </div>
                        @error('password')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-login">
                        {{ __('Sign In to Dashboard') }}
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    
                    <div class="text-center mt-3">
                        <small style="color: #94a3b8;">{{ __('Please enter your credentials to access the system.') }}</small>
                    </div>
                </form>

                <div class="footer-copyright">
                    &copy; {{ date('Y') }} {{ Helper::getStoreInfo()->appname }}.<br>
                    Crafted for Excellence in Healthcare Management.
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/jQuery/jquery-2.1.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    
    <script>
        jQuery(document).ready(function() {
            // Add focus class to input wrappers
            $('.form-control').on('focus', function() {
                $(this).closest('.input-wrapper').addClass('focused');
            }).on('blur', function() {
                $(this).closest('.input-wrapper').removeClass('focused');
            });
        });
    </script>
</body>
</html>