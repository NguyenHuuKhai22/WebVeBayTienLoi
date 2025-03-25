@extends('layouts.app')

@section('title', __('Xác nhận đặt vé'))

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <div class="inline-block p-3 rounded-full bg-green-100 text-green-600 mb-4">
                <i class="fas fa-check-circle text-4xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-teal-700">{{ __('Đặt vé thành công') }}</h1>
            <p class="text-gray-600 mt-2">Cảm ơn bạn đã sử dụng dịch vụ của Vietnam Airlines</p>
        </div>
        
        <!-- Ticket details -->
        <div class="border border-teal-200 rounded-lg p-6 mb-6 bg-teal-50">
            <div class="flex justify-between items-start border-b border-teal-200 pb-4 mb-4">
                <div>
                    <div class="text-sm text-gray-600">{{ __('Mã đặt chỗ') }}(vé đầu tiên)</div>
                    <div class="text-xl font-bold text-teal-700">{{ $ticket->ma_ve }}</div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600">{{ __('Ngày đặt') }}</div>
                    <div>{{ \Carbon\Carbon::parse($ticket->ngay_dat)->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            
            <!-- Thông tin hành khách -->
            <div class="mb-4">
                <h3 class="font-semibold mb-2">Thông tin hành khách</h3>
                <div class="space-y-2">
                    @foreach($tickets as $t)
                        <div class="p-3 bg-gray-50 rounded border flex justify-between items-center">
                            <div>
                                <div class="font-semibold">{{ $t->nguoiDung->ho_ten }}</div>
                                <div class="text-sm text-gray-600">Mã vé: {{ $t->ma_ve }}</div>
                            </div>
                            <div class="text-teal-700">
                                {{ number_format($t->gia_ve, 0, ',', '.') }} VND
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Thông tin thanh toán -->
            <div>
                <h3 class="font-semibold mb-2">Thông tin thanh toán</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600">Phương thức thanh toán</div>
                        <div>{{ ucfirst($ticket->thanhToan->phuong_thuc) }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">Tổng tiền đã thanh toán</div>
                        <div class="font-bold text-teal-700">{{ number_format($ticket->thanhToan->so_tien, 0, ',', '.') }} VND</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="border-t pt-6 mt-6">
            <div class="flex flex-col md:flex-row md:justify-between gap-3">
                <a href="{{ route('flights.search') }}" class="bg-gray-200 text-gray-700 py-2 px-6 rounded-md hover:bg-gray-300 transition text-center">
                    Tìm chuyến bay khác
                </a>
                <div class="flex gap-3">
                    <button onclick="window.print()" class="bg-teal-700 text-white py-2 px-6 rounded-md hover:bg-teal-800 transition flex items-center justify-center">
                        <i class="fas fa-print mr-2"></i> In vé
                    </button>
                    <a href="{{ route('vietnam-airlines') }}" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 transition text-center">
                        Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        header, footer, button, .fixed, nav, a {
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
        .border-t, .pt-6, .mt-6 {
            display: none !important;
        }
    }
</style>
@endsection