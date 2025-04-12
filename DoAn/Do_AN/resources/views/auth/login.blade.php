@extends('layouts.app')

@section('content')
@if(Auth::check())
    <script>
        window.location.href = "{{ route('vietnam-airlines') }}";
    </script>
@endif

<div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-[#0f766e] to-[#0a524d] py-6 px-4 animate-gradient">
    <div class="max-w-sm w-full bg-white rounded-lg shadow-2xl overflow-hidden transform transition-all duration-500 hover:scale-[1.02] animate-fadeIn">
        <div class="bg-gray-50 px-5 py-6 border-b border-gray-200 text-center relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-2xl font-bold text-gray-900 animate-slideDown">{{ __('Đăng Nhập') }}</h2>
                <p class="text-xs text-gray-600 mt-1 animate-fadeIn delay-200">{{ __('Vui lòng đăng nhập để tiếp tục') }}</p>
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent shine-effect"></div>
        </div>

        <div class="px-5 py-6">
            <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-4">
                @csrf
                <div class="relative group">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                        class="block w-full pl-3 pr-3 py-2.5 border border-gray-300 rounded-md text-gray-900 text-sm placeholder-transparent focus:ring-2 focus:ring-[#0f766e] focus:border-transparent transition-all peer"
                        placeholder="Email">
                    <label for="email" class="absolute left-3 -top-2.5 text-sm text-gray-600 transition-all bg-white px-1
                        peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2.5
                        peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-[#0f766e]">Email</label>
                    <div class="input-focus-effect"></div>
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="relative group">
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                        class="block w-full pl-3 pr-3 py-2.5 border border-gray-300 rounded-md text-gray-900 text-sm placeholder-transparent focus:ring-2 focus:ring-[#0f766e] focus:border-transparent transition-all peer"
                        placeholder="{{ __('Mật khẩu') }}">
                    <label for="password" class="absolute left-3 -top-2.5 text-sm text-gray-600 transition-all bg-white px-1
                        peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2.5
                        peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-[#0f766e]">{{ __('Mật khẩu') }}</label>
                    <div class="input-focus-effect"></div>
                    @error('password')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs animate-fadeIn delay-300">
                    <label class="flex items-center group cursor-pointer">
                        <input id="remember_me" name="remember_me" type="checkbox" 
                            class="h-3 w-3 text-[#0f766e] focus:ring-[#0f766e] border-gray-300 rounded transition-all group-hover:border-[#0f766e]">
                        <span class="ml-1 text-gray-900 group-hover:text-[#0f766e] transition-colors">{{ __('Ghi nhớ') }}</span>
                    </label>
                    <a href="{{ route('password.request') }}" 
                        class="text-[#0f766e] hover:text-[#0a524d] transition-colors relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-[#0a524d] after:left-0 after:-bottom-0.5 after:transition-all hover:after:w-full">
                        {{ __('Quên mật khẩu?') }}
                    </a>
                </div>

                <button type="submit" id="login-button"
                    class="relative w-full py-2.5 text-sm font-medium text-white bg-[#0f766e] hover:bg-[#0a524d] rounded-md transition-all overflow-hidden group">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <span class="button-text">{{ __('Đăng Nhập') }}</span>
                        <svg class="w-0 h-5 transition-all duration-300 loading-spinner" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <div class="absolute inset-0 bg-white/10 transform translate-y-full transition-transform group-hover:translate-y-0"></div>
                </button>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">{{ __('Hoặc đăng nhập bằng') }}</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <a href="{{ route('auth.google') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                    </a>
                    <a href="{{ route('auth.facebook') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#1877F2">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="px-5 py-4 bg-gray-50 border-t border-gray-200 text-center text-xs animate-fadeIn delay-500">
            <p>{{ __('Chưa có tài khoản?') }} 
                <a href="{{ route('register') }}" 
                    class="text-[#0f766e] hover:text-[#0a524d] transition-colors relative after:content-[''] after:absolute after:w-0 after:h-0.5 after:bg-[#0a524d] after:left-0 after:-bottom-0.5 after:transition-all hover:after:w-full">
                    {{ __('Đăng ký ngay') }}
                </a>
            </p>
        </div>
    </div>
</div>

<style>
@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes shine {
    from { transform: translateX(-100%); }
    to { transform: translateX(100%); }
}

@keyframes slideDown {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 15s ease infinite;
}

.animate-fadeIn {
    animation: fadeIn 0.6s ease-out forwards;
}

.animate-slideDown {
    animation: slideDown 0.5s ease-out forwards;
}

.shine-effect {
    animation: shine 3s infinite;
}

.loading .loading-spinner {
    width: 1.25rem;
    animation: spin 1s linear infinite;
}

.loading .button-text {
    opacity: 0;
}

.input-focus-effect {
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background: #0f766e;
    transition: 0.3s ease;
    transform: translateX(-50%);
}

.group:focus-within .input-focus-effect {
    width: 100%;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>

<script>
// Ngăn chặn nút back
history.pushState(null, null, location.href);
window.onpopstate = function () {
    history.go(1);
};

document.addEventListener("DOMContentLoaded", function() {
    // Xóa lịch sử trình duyệt
    window.history.replaceState({}, '', window.location.href);
    
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "timeOut": "5000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    const loginForm = document.getElementById('login-form');
    const loginButton = document.getElementById('login-button');

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();

        // Add loading state
        loginButton.classList.add('loading');
        loginButton.disabled = true;

        // Get form data
        const formData = new FormData(this);

        // Send request using fetch
        fetch(this.action, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                toastr.success("{{ __('Đăng nhập thành công!') }}");
                window.location.href = "{{ route('vietnam-airlines') }}";
            } else {
                toastr.error(data.message || "{{ __('Đăng nhập thất bại!') }}");
                loginButton.classList.remove('loading');
                loginButton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error("{{ __('Có lỗi xảy ra, vui lòng thử lại!') }}");
            loginButton.classList.remove('loading');
            loginButton.disabled = false;
        });
    });

    // Add input animation effects
    const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            input.closest('.group').classList.add('focused');
        });
        input.addEventListener('blur', () => {
            if (!input.value) {
                input.closest('.group').classList.remove('focused');
            }
        });
        if (input.value) {
            input.closest('.group').classList.add('focused');
        }
    });
});
</script>

@endsection
