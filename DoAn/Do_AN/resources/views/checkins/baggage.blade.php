@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-teal-700 text-center">Xác Nhận Thủ Tục</h1>

        <!-- Thông tin chuyến bay -->
        <div class="mt-4 p-4 bg-gray-100 rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700">Thông tin chuyến bay</h2>
            <p><strong>Chuyến bay:</strong> {{ $ve->chuyenBay->ma_chuyen_bay }}</p>
            <p><strong>Điểm đi:</strong> {{ $ve->chuyenBay->diem_di }}</p>
            <p><strong>Điểm đến:</strong> {{ $ve->chuyenBay->diem_den }}</p>
            <p><strong>Thời gian khởi hành:</strong> {{ \Carbon\Carbon::parse($ve->chuyenBay->ngay_gio_khoi_hanh)->format('H:i d/m/Y') }}</p>
            <p><strong>Hạng ghế:</strong> 
                @if($ve->loai_ghe == 'pho_thong') Phổ thông
                @elseif($ve->loai_ghe == 'thuong_gia') Thương gia
                @elseif($ve->loai_ghe == 'pho_thong_dac_biet') Phổ thông đặc biệt
                @else Chưa xác định
                @endif
            </p>
        </div>

        <!-- Thông tin hành khách -->
        <div class="mt-6 p-4 bg-gray-100 rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700">Thông tin hành khách</h2>
            <p><strong>Họ tên:</strong> {{ $ve->nguoiDung->ho_ten }}</p>
            <p><strong>Email:</strong> {{ $ve->nguoiDung->email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $ve->nguoiDung->so_dien_thoai }}</p>
        </div>

        <!-- Sơ đồ ghế -->
        <div class="mt-6 flex justify-center">
            <div class="text-center">
                <h2 class="text-lg font-semibold text-gray-700">Ghế đã chọn</h2>
                <div class="inline-block px-6 py-4 bg-blue-500 text-white font-bold text-xl rounded-lg shadow-md">
                    {{ $ve->so_ghe }}
                </div>
            </div>
        </div>

        <!-- Xác nhận -->
        <!-- Nút tiếp tục -->
        <form action="{{ route('checkin.success', ['ma_ve' => $ve->ma_ve]) }}" method="GET" class="mt-6 text-center">
            <button type="submit" id="btnContinue" 
                    class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg text-lg font-semibold transition">
                XÁC NHẬN
            </button>
        </form>
    </div>
</div>
@endsection
