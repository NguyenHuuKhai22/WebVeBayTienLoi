@extends('layouts.app')
<!-- Trong thẻ <head> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

<!-- Trước thẻ </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 animate__animated animate__fadeIn animate__duration-1s">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 fw-bold">Đặt Lại Mật Khẩu</h3>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__shakeX animate__duration-0s5" role="alert">
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-4 animate__animated animate__fadeInUp animate__delay-0s2">
                            <label class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                <div class="invalid-feedback">Vui lòng nhập email hợp lệ</div>
                            </div>
                        </div>

                        <div class="mb-4 animate__animated animate__fadeInUp animate__delay-0s4">
                            <label class="form-label fw-semibold">Mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" required>
                                <div class="invalid-feedback">Vui lòng nhập mật khẩu</div>
                            </div>
                        </div>

                        <div class="mb-4 animate__animated animate__fadeInUp animate__delay-0s6">
                            <label class="form-label fw-semibold">Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password_confirmation" class="form-control" required>
                                <div class="invalid-feedback">Vui lòng xác nhận mật khẩu</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 mt-3 fw-semibold transition-all animate__animated animate__pulse animate__infinite">
                            Đặt Lại Mật Khẩu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-all {
        transition: all 0.3s ease-in-out;
    }
    .transition-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .card { border-radius: 15px; overflow: hidden; }
    .input-group-text { background-color: #f8f9fa; }
    .btn-success { background-color: #28a745; border: none; }
    .btn-success:hover { background-color: #218838; }
</style>

<script>
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>
@endsection