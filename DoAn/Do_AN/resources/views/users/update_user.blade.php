@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-teal-700 mb-6 text-center">Cập nhật thông tin</h1>    

        {{-- Form cập nhật thông tin --}}
        <form action="{{ route('nguoidung.update', $nguoidung->id_nguoi_dung) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Họ Tên</label>
                <input type="text" name="ho_ten" value="{{ $nguoidung->ho_ten }}" 
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#008060] focus:border-[#008060]">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ $nguoidung->email }}" readonly
                       class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Số Điện Thoại</label>
                <input type="text" name="so_dien_thoai" value="{{ $nguoidung->so_dien_thoai }}" 
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#008060] focus:border-[#008060]">
            </div>

            <div class="flex justify-between">
                <button type="submit" class="text-white bg-[#008060] hover:bg-[#006A52] px-4 py-2 rounded-md">
                    Cập nhật
                </button>
                <a href="{{ route('nguoidung.show', $nguoidung->id_nguoi_dung) }}" 
                   class="text-gray-600 hover:text-gray-900 px-4 py-2">Hủy</a>
            </div>
        </form>
    </div>

    {{-- Form đổi mật khẩu --}}
<div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto mt-8">
    <h2 class="text-xl font-bold text-red-600 mb-4 text-center">Đổi mật khẩu</h2>

    {{-- Hiển thị thông báo lỗi chung (nếu muốn) --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('nguoidung.updatePassword', $nguoidung->id_nguoi_dung) }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Mật khẩu hiện tại</label>
            <input type="password" name="current_password" required
                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#008060] focus:border-[#008060]">
            {{-- Thông báo lỗi mật khẩu hiện tại --}}
@error('current_password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
            <input type="password" name="new_password" required
                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#008060] focus:border-[#008060]">
            {{-- Thông báo lỗi mật khẩu mới --}}
            @error('new_password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu mới</label>
            <input type="password" name="new_password_confirmation" required
                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#008060] focus:border-[#008060]">
        </div>

        <div class="flex justify-between">
            <button type="submit" class="text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-md">
                Đổi mật khẩu
            </button>
        </div>
    </form>
</div>

</div>
@endsection
