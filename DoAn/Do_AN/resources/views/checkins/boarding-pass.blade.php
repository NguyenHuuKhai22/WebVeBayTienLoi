@extends('layouts.app')

@section('title', 'Thẻ lên máy bay')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <div class="inline-block p-3 rounded-full bg-green-100 text-green-600 mb-4">
                <i class="fas fa-plane text-4xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-teal-700">Thẻ lên máy bay</h1>
            <p class="text-gray-600 mt-2">{{ $ve->chuyenBay->diem_di }} → {{ $ve->chuyenBay->diem_den }}</p>
        </div>
        
        <!-- Ticket details -->
        <div class="border border-teal-200 rounded-lg p-6 mb-6 bg-teal-50">
            <div class="flex justify-between items-start border-b border-teal-200 pb-4 mb-4">
                <div>
                    <div class="text-sm text-gray-600">Mã vé</div>
                    <div class="text-xl font-bold text-teal-700">{{ $ve->ma_ve }}</div>
                </div>
                <!-- Hiển thị Loại ghế với điều kiện -->
                <div>
                    <div class="text-sm text-gray-600 mt-2">Hạng ghế</div>
                    <div class="font-semibold text-gray-800">
                        @if($ve->loai_ghe == 'pho_thong')
                            Phổ thông
                        @elseif($ve->loai_ghe == 'thuong_gia')
                            Thương gia
                        @elseif($ve->loai_ghe == 'pho_thong_dac_biet')
                            Phổ thông đặc biệt
                        @else
                            Chưa xác định
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600">Thời gian khởi hành</div>
                    <div>{{ \Carbon\Carbon::parse($ve->chuyenBay->ngay_gio_khoi_hanh)->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            
            <!-- Thông tin hành khách -->
            <div class="mb-4">
                <h3 class="font-semibold mb-2">Thông tin hành khách</h3>
                <div class="p-3 bg-gray-50 rounded border">
                    <div class="font-semibold">{{ $ve->nguoiDung->ho_ten }}</div>
                    <div class="text-sm text-gray-600">Số ghế: {{ $ve->so_ghe ?? 'Chưa chọn' }}</div>
                </div>
            </div>

            <!-- Thông tin chuyến bay -->
            <div>
                <h3 class="font-semibold mb-2">Thông tin chuyến bay</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600">Mã chuyến bay</div>
                        <div class="font-bold text-teal-700">{{ $ve->chuyenBay->ma_chuyen_bay }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code -->
        <div class="mt-6 flex justify-center">
            {!! QrCode::size(150)->generate($ve->ma_ve) !!}
        </div>

        <!-- Các nút hành động (Ẩn khi tải xuống PDF) -->
        <div class="border-t pt-6 mt-6">
            <div class="flex flex-col md:flex-row md:justify-between gap-3">
                <div class="flex gap-3">
                    <button onclick="window.print()" class="bg-teal-700 text-white py-2 px-6 rounded-md hover:bg-teal-800 transition flex items-center justify-center">
                        🖨️ IN VÉ
                    </button>

                    <a href="{{ route('checkin.success', ['ma_ve' => $ve->ma_ve]) }}" 
                       class="bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold transition hover:bg-gray-600">
                        ⬅️ QUAY LẠI HÀNH TRÌNH
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Style để in vé và tải xuống PDF -->
<style>
    @media print {
        header, footer, .fixed, nav {
            display: none !important;
        }
        body {
            background-color: white !important;
        }
        .container {
            max-width: 100% !important;
            padding: 0 !important;
        }
        .bg-white, .bg-teal-50 {
            background-color: white !important;
            box-shadow: none !important;
        }
        .border, .border-teal-200 {
            border-color: #ddd !important;
        }
        .text-teal-700 {
            color: #000 !important;
        }
    }
</style>

@endsection
