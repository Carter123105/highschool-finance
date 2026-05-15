<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register - {{ config('app.name', 'School Finance System') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
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

        .register-wrapper {
            width: 100%;
            max-width: 1050px;
        }

        .register-card {
            border: 0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }

        .left-panel {
            background: linear-gradient(rgba(13,110,253,0.85), rgba(10,88,202,0.85)),
                        url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f')
                        center/cover;
            color: white;
            padding: 50px;
        }

        .left-panel h2 {
            font-weight: 700;
        }

        .left-panel p {
            opacity: 0.9;
        }

        .brand {
            width: 60px;
            height: 60px;
            background: white;
            color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            border-radius: 14px;
            font-size: 20px;
        }

        .right-panel {
            background: white;
            padding: 50px;
        }

        .form-control {
            height: 48px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }

        .btn-register {
            height: 48px;
            border-radius: 10px;
            font-weight: 600;
        }

        .small-text {
            font-size: 13px;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #eee;
        }

        .divider span {
            padding: 0 10px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>

<body>

<div class="register-wrapper">

    <div class="card register-card">

        <div class="row g-0">

            <!-- LEFT SIDE -->
            <div class="col-md-6 left-panel d-none d-md-block">

                <div class="h-100 d-flex flex-column justify-content-center">

                    <div class="brand mb-4">
                        SF
                    </div>

                    <h2>Create Account</h2>

                    <p class="mt-3">
                        Join the School Finance System to manage students, invoices, payments, and reports in one place.
                    </p>

                    <ul class="mt-4 small">
                        <li>✔ Student Management</li>
                        <li>✔ Invoice & Billing System</li>
                        <li>✔ Payment Tracking</li>
                        <li>✔ Role-Based Access Control</li>
                    </ul>

                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="col-md-6 right-panel">

                <div class="text-center mb-4">
                    <h4 class="fw-bold mb-1">Create Account</h4>
                    <p class="text-muted small-text">Register to get started</p>
                </div>

                <!-- FORM -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- NAME -->
                    <div class="mb-3">
                        <label class="form-label small-text">Full Name</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               required autofocus>

                        @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- EMAIL -->
                    <div class="mb-3">
                        <label class="form-label small-text">Email Address</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               required>

                        @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-3">
                        <label class="form-label small-text">Password</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required>

                        @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- CONFIRM PASSWORD -->
                    <div class="mb-3">
                        <label class="form-label small-text">Confirm Password</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               required>
                    </div>

                    <!-- BUTTON -->
                    <button type="submit" class="btn btn-primary w-100 btn-register">
                        Create Account
                    </button>

                </form>

                <!-- DIVIDER -->
                <div class="divider">
                    <span>OR</span>
                </div>

                <!-- LOGIN LINK -->
                <p class="text-center small">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                        Login here
                    </a>
                </p>

                <p class="text-center small text-muted mt-3">
                    © {{ date('Y') }} School Finance System
                </p>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>