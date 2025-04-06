@extends('layouts.app')

@section('content')

<div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl mx-auto">
    <h1 class="text-lg font-semibold text-center text-teal-700">
        <p>Quý khách Đã làm thủ tục thành công</p>
    </h1>
</div>
<div class="container mx-auto p-6">

    <div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl mx-auto">
        <h1 class="text-lg font-semibold text-center text-teal-700">
            Hành trình của quý khách
        </h1>

        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
            <h2 class="text-xl font-bold text-gray-800">
                {{ $ve->chuyenBay->diem_di }} đến {{ $ve->chuyenBay->diem_den }}
            </h2>
            <p class="text-sm text-gray-600">
                Ngày {{ \Carbon\Carbon::parse($ve->chuyenBay->ngay_gio_khoi_hanh)->format('d') }} tháng 
                {{ \Carbon\Carbon::parse($ve->chuyenBay->ngay_gio_khoi_hanh)->format('m') }}
            </p>

            <div class="flex justify-between items-center mt-3">
                <div class="text-center">
                    <p class="text-lg font-semibold">
                        {{ \Carbon\Carbon::parse($ve->chuyenBay->ngay_gio_khoi_hanh)->format('H:i') }}
                    </p>
                    <p class="text-sm text-gray-500">{{ $ve->chuyenBay->diem_di }}</p>
                    <p class="text-xs text-gray-500">Nhà ga 1</p>
                </div>

                <!-- Đường gạch nối với icon máy bay -->
                <div class="flex items-center text-gray-500">
                    <div class="w-20 h-[2px] bg-gray-400"></div> <!-- Đường gạch ngang -->
                    <span class="mx-2 text-xl">✈</span> <!-- Icon máy bay -->
                    <div class="w-20 h-[2px] bg-gray-400"></div> <!-- Đường gạch ngang -->
                </div>

                <div class="text-center">
                    <p class="text-lg font-semibold">
                        {{ \Carbon\Carbon::parse($ve->chuyenBay->ngay_gio_ket_thuc)->format('H:i') }}
                    </p>
                    <p class="text-sm text-gray-500">{{ $ve->chuyenBay->diem_den }}</p>
                    <p class="text-xs text-gray-500">Nhà ga 1</p>
                </div>
            </div>

            <p class="text-sm mt-3 text-gray-600">
                ✈ {{ $ve->chuyenBay->ma_chuyen_bay }} được khai thác bởi Vietnam Airlines ☀️
            </p>
            <a href="#" class="text-blue-600 text-sm font-semibold underline">Xem chi tiết hành trình</a>
        </div>

        <a href="{{ route('boarding.pass', $ve->ma_ve)}}" 
            class="block mt-6 bg-teal-600 text-white py-3 rounded-lg text-center text-lg font-semibold hover:bg-teal-700">
            Xem thẻ lên máy bay
         </a>         

    </div>
</div>

<div class="container mx-auto p-6">

    <div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl mx-auto">
        <h1 class="text-lg font-semibold text-center text-teal-700">
            Hành khách
        </h1>
        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
            <h2 class="text-xl font-bold text-gray-800">
                <p>{{ $ve->nguoiDung->ho_ten }}<br><span style="color: #28a745;">✔</span> Đã làm thủ tục</p>
            </h2>
        </div>
    </div>
</div>
@endsection
