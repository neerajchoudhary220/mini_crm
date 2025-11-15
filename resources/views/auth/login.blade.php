@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="auth-page d-flex justify-content-center align-items-center" style="min-height: 100vh; background: #f5f6fa;">

    <div class="card shadow border-0" style="width: 380px; border-radius: 12px;">
        <div class="card-body p-4">

            <h3 class="text-center mb-3">Welcome Back</h3>
            <p class="text-center text-muted mb-4">Login to continue to Dashboard</p>

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="#">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email"
                        name="email"
                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                        placeholder="Enter your email"
                        value="{{ old('email') }}"
                        required autofocus>

                    @error('email')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password"
                        name="password"
                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                        placeholder="Enter your password"
                        required>

                    @error('password')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Remember + Forgot -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    <a href="#" class="text-decoration-none small">Forgot password?</a>
                </div>

                <!-- Login Button -->
                <button class="btn btn-primary w-100 btn-lg" type="submit">Login</button>
            </form>

        </div>
    </div>

</div>
@endsection