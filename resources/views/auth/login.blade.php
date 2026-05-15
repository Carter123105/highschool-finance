<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
    use App\Models\Setting;

    $setting = Setting::first();

    $schoolName = $setting->school_name ?? config('app.name', 'School Financial System');
    $logo = $setting->logo ?? null;

    if ($logo) {
        $logoUrl = str_starts_with($logo, 'http')
            ? $logo
            : asset('storage/' . $logo);
    } else {
        $logoUrl = null;
    }
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login - {{ $schoolName }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />

    <style>
        body {
            font-family: Figtree, sans-serif;
            background: radial-gradient(circle at top, #0d6efd, #0a58ca);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1000px;
        }

        .login-card {
            border: 0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }

        .login-left {
            background: linear-gradient(rgba(13,110,253,0.85), rgba(10,88,202,0.85)),
                        url('https://images.unsplash.com/photo-1523240795612-9a054b0db644')
                        center/cover;
            color: white;
            padding: 50px;
        }

        .brand {
            width: 70px;
            height: 70px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            overflow: hidden;
            font-weight: 800;
            color: #0d6efd;
        }

        .brand img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .login-right {
            background: #ffffff;
            padding: 50px;
        }

        .form-control {
            height: 48px;
            border-radius: 10px;
        }

        .btn-login {
            height: 48px;
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <div class="card login-card">

        <div class="row g-0">

            <!-- LEFT PANEL -->
            <div class="col-md-6 login-left d-none d-md-block">

                <div class="h-100 d-flex flex-column justify-content-center">

                    <!-- LOGO -->
                    <div class="brand mb-4">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="Logo">
                        @else
                            <span>SF</span>
                        @endif
                    </div>

                    <h2>{{ $schoolName }}</h2>

                    <p class="mt-3">
                        Manage students, invoices, payments, receipts, and reports in one powerful platform.
                    </p>

                </div>

            </div>

            <!-- RIGHT PANEL -->
            <div class="col-md-6 login-right">

                <div class="text-center mb-3">
                    <h4 class="fw-bold">Welcome Back</h4>
                    <p class="text-muted">Login to your dashboard</p>
                </div>

                {{-- ================= SUCCESS MESSAGE ================= --}}
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- ================= LOGIN ERROR MESSAGE ================= --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ================= OPTIONAL CUSTOM LOGIN ERROR ================= --}}
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required>
                    </div>

                    <button class="btn btn-primary w-100 btn-login">
                        Sign In
                    </button>

                </form>

                <p class="text-center text-muted mt-4">
                    © {{ date('Y') }} {{ $schoolName }}
                </p>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>