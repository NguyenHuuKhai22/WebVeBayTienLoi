@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-teal-700 mb-6 text-center">Thông Tin Người Dùng</h1>     

        <div class="flex flex-col md:flex-row items-center md:items-start md:space-x-6">
            <!-- Ảnh đại diện -->
            <div class="flex-shrink-0">
                <img src="{{ auth()->user()->avatar ?? asset('img/default-avatar.png') }}" 
                     alt="Ảnh đại diện" 
                     class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-teal-500 shadow-md object-cover">
            </div>

            <!-- Thông tin người dùng -->
            <div class="flex-1 bg-gray-100 p-6 rounded-lg shadow-md w-full">
                <p class="text-lg font-semibold text-gray-800 mb-2">{{ auth()->user()->ho_ten }}</p>
                <p class="text-sm text-gray-700 mb-1"><strong>Email:</strong> {{ auth()->user()->email }}</p>
                <p class="text-sm text-gray-700 mb-1"><strong>Số điện thoại:</strong> {{ auth()->user()->so_dien_thoai }}</p>
                <p class="text-sm text-gray-700"><strong>Ngày tạo:</strong> {{ \Carbon\Carbon::parse(auth()->user()->ngay_tao)->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Nút chỉnh sửa -->
        <div class="mt-6 text-center">
            <a href="{{ route('nguoidung.edit', auth()->user()->id_nguoi_dung) }}" 
               class="w-full md:w-auto inline-block text-white bg-[#008060] hover:bg-[#006A52] px-6 py-3 rounded-md text-lg font-medium transition">
                Chỉnh sửa thông tin
            </a>
        </div>
    </div>

    
@endsection