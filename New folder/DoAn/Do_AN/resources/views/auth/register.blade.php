@extends('layouts.app')

@section('content')
<div class="h-[80vh] flex items-center justify-center bg-gradient-to-b from-[#0f766e] to-[#0a524d] py-4 px-4">
    <div class="max-w-xs w-full bg-white rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
        <div class="bg-gray-50 px-4 py-4 border-b border-gray-200 text-center">
            <h2 class="text-xl font-bold text-gray-900">{{ __('Đăng Ký') }}</h2>
            <p class="text-xs text-gray-600">{{ __('Tạo tài khoản để tiếp tục') }}</p>
        </div>

        <div class="px-4 py-4">
            <form id="register-form" class="space-y-3">
                @csrf
                <div class="relative">
                    <input type="text" name="ho_ten"
                    required class="block w-full px-3 py-2 border border-gray-300 rounded-md
                                     text-gray-900 text-sm placeholder-gray-500
                                     focus:ring-[#0f766e] focus:border-[#0f766e]"
                    placeholder="{{ __('Họ Tên') }}">
                </div>

                <div class="relative">
                    <input type="email" name="email"
                    required class="block w-full px-3 py-2 border border-gray-300 rounded-md
                                     text-gray-900 text-sm placeholder-gray-500
                                     focus:ring-[#0f766e] focus:border-[#0f766e]"
                    placeholder="Email">
                </div>

                <div class="relative">
                    <input type="password" name="password"
                    required class="block w-full px-3 py-2 border border-gray-300 rounded-md
                                     text-gray-900 text-sm placeholder-gray-500
                                     focus:ring-[#0f766e] focus:border-[#0f766e]"
                    placeholder="{{ __('Mật khẩu') }}">
                </div>

                <div class="relative">
                    <input type="text" name="so_dien_thoai"
                    required class="block w-full px-3 py-2 border border-gray-300 rounded-md
                                    text-gray-900 text-sm placeholder-gray-500
                                    focus:ring-[#0f766e] focus:border-[#0f766e]"
                     placeholder="{{ __('Số điện thoại') }}">
                </div>

                <button type="submit" class="w-full py-2 text-sm font-medium text-white bg-[#0f766e] hover:bg-[#0a524d] rounded-md transition-all">
                    {{ __('Đăng Ký') }}
                </button>
            </form>
        </div>

        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 text-center text-xs">
            <p>{{ __('Đã có tài khoản?') }} <a href="{{ route('login') }}" class="text-[#0f766e] hover:text-[#0a524d]">{{ __('Đăng nhập') }}</a></p>
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "timeOut": "5000"
    };

    document.getElementById("register-form").addEventListener("submit", function(event) {
        event.preventDefault(); // Ngăn form gửi mặc định

        let formData = new FormData(this);

        fetch("{{ route('register') }}", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success("{{ __('Đăng ký thành công!') }}");
                setTimeout(() => {
                    window.location.href = "{{ route('vietnam-airlines') }}";
                }, 1000);
            } else if (data.errors) {
                // Nếu có lỗi validation, hiển thị từng lỗi
                Object.values(data.errors).forEach(messages => {
                    messages.forEach(message => {
                        toastr.error(message);
                    });
                });
            } else {
                toastr.error(data.message);
            }
        })
        .catch(error => {
            console.error("Lỗi:", error);
            toastr.error("{{ __('Có lỗi xảy ra, vui lòng thử lại!') }}");
        });
    });
});
</script>

@endsection
