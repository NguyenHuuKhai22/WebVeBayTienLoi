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
