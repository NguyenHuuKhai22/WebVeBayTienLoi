@extends('layouts.app')

@section('styles')
<style>
    .reset-container {
        max-width: 500px;
        margin: 3rem auto;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(149, 157, 165, 0.2);
        background-color: #fff;
        transform: translateY(20px);
        opacity: 0;
        animation: fadeInUp 0.6s ease forwards;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .reset-header {
        margin-bottom: 1.5rem;
        opacity: 0;
        animation: fadeIn 0.6s ease forwards 0.3s;
    }

    .reset-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .reset-subtitle {
        color: #718096;
        font-size: 0.95rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        opacity: 0;
        animation: fadeIn 0.5s ease forwards 0.5s;
    }

    .alert-success {
        background-color: #ebf8ef;
        color: #1f8b4d;
        border-left: 4px solid #2ecc71;
    }

    .alert-danger {
        background-color: #fdf3f3;
        color: #d63031;
        border-left: 4px solid #e74c3c;
    }

    .form-group {
        margin-bottom: 1.5rem;
        opacity: 0;
        animation: fadeIn 0.6s ease forwards 0.7s;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #4a5568;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        line-height: 1.5;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
    }

    .btn-submit {
        display: block;
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        font-weight: 500;
        color: #fff;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 0;
        animation: fadeIn 0.6s ease forwards 0.9s;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .login-link {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.9rem;
        opacity: 0;
        animation: fadeIn 0.6s ease forwards 1.1s;
    }

    .login-link a {
        color: #4f46e5;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .login-link a:hover {
        color: #4338ca;
        text-decoration: underline;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
        }
    }

    /* Ripple effect for button */
    .btn-submit {
        position: relative;
        overflow: hidden;
    }

    .btn-submit:after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 10px;
        height: 10px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: scale(0);
        opacity: 1;
        transition: 0s;
    }

    .btn-submit:active:after {
        transform: scale(40);
        opacity: 0;
        transition: all 0.8s;
    }

    /* Shake animation for error */
    .shake {
        animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both;
    }

    @keyframes shake {
        10%, 90% { transform: translate3d(-1px, 0, 0); }
        20%, 80% { transform: translate3d(2px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
        40%, 60% { transform: translate3d(4px, 0, 0); }
    }
</style>
@endsection

@section('content')
<div class="reset-container">
    <div class="reset-header">
        <h1 class="reset-title">Quên mật khẩu</h1>
        <p class="reset-subtitle">Nhập email của bạn và chúng tôi sẽ gửi link đặt lại mật khẩu.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" id="resetForm">
        @csrf
        <div class="form-group">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <button type="submit" class="btn-submit">Gửi email đặt lại mật khẩu</button>
    </form>

    <div class="login-link">
        <a href="{{ route('login') }}">Quay lại đăng nhập</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('resetForm');
        const emailInput = document.getElementById('email');

        form.addEventListener('submit', function(event) {
            if (!emailInput.validity.valid) {
                event.preventDefault();
                emailInput.classList.add('shake');
                setTimeout(() => {
                    emailInput.classList.remove('shake');
                }, 820);
            }
        });

        // Focus animation
        emailInput.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-5px)';
            this.parentElement.style.transition = 'transform 0.3s ease';
        });

        emailInput.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
</script>
@endsection