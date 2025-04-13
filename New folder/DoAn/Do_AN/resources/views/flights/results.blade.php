@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-teal-700 mb-6">Kết Quả Tìm Kiếm</h1>
        
        @if(session('search_params'))
            <div class="bg-gray-100 p-4 rounded-md mb-6">
                <div class="flex flex-wrap md:flex-nowrap justify-between items-center">
                    <div class="mb-2 md:mb-0">
                        <span class="font-semibold">Hành trình:</span> 
                        {{ session('search_params')['diem_di'] }} → {{ session('search_params')['diem_den'] }}
                    </div>
                    <div class="mb-2 md:mb-0">
                        <span class="font-semibold">Ngày đi:</span> 
                        {{ \Carbon\Carbon::parse(session('search_params')['ngay_di'])->format('d/m/Y') }}
                    </div>
                    <div>
                        <span class="font-semibold">Số hành khách:</span> 
                        {{ session('search_params')['so_hanh_khach'] }}
                    </div>
                </div>
            </div>
        @endif
        
        @if($flights->count() > 0)
            <div class="grid grid-cols-1 gap-4">
                @foreach($flights as $flight)
                    @php
                        $hasDiscount = false;
                        $discountedPrice = $flight->gia_ve_co_ban;
                        $discountPercent = 0;
                        
                        if ($flight->ngay_gio_khoi_hanh) {
                            $thoiGianKhoiHanh = \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh);
                            foreach ($flight->getActivePromotions() as $promo) {
                                $thoiGianBatDau = \Carbon\Carbon::parse($promo->thoi_gian_bat_dau);
                                $thoiGianKetThuc = \Carbon\Carbon::parse($promo->thoi_gian_ket_thuc);
                                if ($promo->trang_thai && $thoiGianKhoiHanh->between($thoiGianBatDau, $thoiGianKetThuc)) {
                                    $hasDiscount = true;
                                    $discountPercent = $flight->getHighestDiscount();
                                    $discountedPrice = $flight->getDiscountedPrice();
                                    break;
                                }
                            }
                        }
                    @endphp
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition flex flex-col md:flex-row md:items-center justify-between">
                        <div class="mb-4 md:mb-0">
                            <div class="flex items-center mb-2">
                                @if($flight->hangBay && $flight->hangBay->logo)
                                    <img src="{{ $flight->hangBay->logo }}" alt="{{ $flight->hangBay->ten_hang_bay }}" class="h-8 mr-2">
                                @endif
                                <span class="font-semibold">{{ $flight->hangBay ? $flight->hangBay->ten_hang_bay : 'Vietnam Airlines' }}</span>
                            </div>
                            <div class="text-sm text-gray-600">{{ $flight->ma_chuyen_bay }}</div>
                        </div>
                        
                        <div class="flex-1 mb-4 md:mb-0 md:text-center">
                            <div class="flex items-center justify-center">
                                <div class="text-right mr-3">
                                    <div class="font-bold">{{ \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh)->format('H:i') }}</div>
                                    <div class="text-sm text-gray-600">{{ $flight->diem_di }}</div>
                                </div>
                                
                                <div class="flex flex-col items-center mx-2">
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh)->diff(\Carbon\Carbon::parse($flight->ngay_gio_den))->format('%hh %im') }}
                                    </div>
                                    <div class="w-20 h-px bg-gray-300 my-1"></div>
                                    <div class="text-xs text-gray-500">Bay thẳng</div>
                                </div>
                                
                                <div class="text-left ml-3">
                                    <div class="font-bold">{{ \Carbon\Carbon::parse($flight->ngay_gio_den)->format('H:i') }}</div>
                                    <div class="text-sm text-gray-600">{{ $flight->diem_den }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4 md:mb-0 text-center">
                            @if($hasDiscount)
                                <div class="inline-block bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs font-semibold mb-1">-{{ $discountPercent }}%</div>
                                <div class="font-medium text-gray-500 line-through text-sm">{{ number_format($flight->gia_ve_co_ban, 0, ',', '.') }} VND</div>
                                <div class="font-bold text-red-600 text-xl">{{ number_format($discountedPrice, 0, ',', '.') }} VND</div>
                            @else
                                <div class="font-bold text-teal-700 text-xl">{{ number_format($flight->gia_ve_co_ban, 0, ',', '.') }} VND</div>
                            @endif
                            <div class="text-sm text-gray-600">{{ $flight->so_ghe_trong }} chỗ còn trống</div>
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ route('booking.select', $flight->id_chuyen_bay) }}" class="bg-teal-700 text-white py-2 px-4 rounded inline-block hover:bg-teal-800 transition">
                                Chọn
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="text-gray-600 mb-4">Không tìm thấy chuyến bay phù hợp với tiêu chí của bạn.</div>
                <a href="{{ route('flights.search') }}" class="bg-teal-700 text-white py-2 px-4 rounded inline-block hover:bg-teal-800 transition">
                    Tìm kiếm lại
                </a>
            </div>
        @endif
    </div>
</div>
@endsection