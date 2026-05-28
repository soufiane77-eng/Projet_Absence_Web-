@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    <h4 class="text-center mb-4">Reset Password</h4>
    <p class="text-muted text-center small mb-4">Enter your email address and we'll send you a password reset link.</p>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="fa fa-paper-plane me-1"></i> Send Reset Link
        </button>
        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none small">
                <i class="fa fa-arrow-left me-1"></i> Back to Login
            </a>
        </div>
    </form>
@endsection
