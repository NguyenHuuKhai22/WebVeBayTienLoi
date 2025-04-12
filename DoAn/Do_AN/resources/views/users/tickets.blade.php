@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-teal-700 mb-6 text-center">Danh sách vé máy bay</h1>

        @if($veMayBay->isNotEmpty())
            <div class="grid grid-cols-1 gap-4">
                @foreach($veMayBay as $ve)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition flex flex-col md:flex-row md:items-center justify-between">
                        <div class="flex-1">
                            <p class="font-bold text-lg">Mã Vé: {{ $ve->ma_ve }}</p>
                            <p class="text-gray-700">Hạng Ghế: 
                                @if($ve->loai_ghe == 'pho_thong')
                                    Phổ thông
                                @elseif($ve->loai_ghe == 'thuong_gia')
                                    Thương gia
                                @elseif($ve->loai_ghe == 'pho_thong_dac_biet')
                                    Phổ thông đặc biệt
                                @else
                                    Chưa xác định
                                @endif
                            </p>
                            <p class="text-gray-700">Giá Vé: <span class="font-semibold text-teal-700">{{ number_format($ve->gia_ve, 0, ',', '.') }} VND</span></p>
                            <p class="text-gray-700">Ngày Đặt: {{ \Carbon\Carbon::parse($ve->ngay_dat)->format('d/m/Y') }}</p>
                            <p class="text-gray-700">Trạng Thái: 
                                <span class="{{ $ve->trang_thai == 'da_thanh_toan' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $ve->trang_thai }}
                                </span>
                            </p>
                        </div>

                        <div class="mt-4 md:mt-0">
                            @if($ve->trang_thai == 'da_thanh_toan')
                                @if(!empty($ve->so_ghe)) 
                                    <a href="{{ route('boarding.pass', ['ma_ve' => $ve->ma_ve]) }}" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition hover:bg-blue-700">
                                        📄 Xem thẻ lên máy bay
                                    </a>
                                @else
                                    <a href="{{ route('checkin.select-seat', ['ma_ve' => $ve->ma_ve]) }}" 
                                    class="bg-green-600 text-white px-4 py-2 rounded-md font-medium transition hover:bg-green-700">
                                        ✅ Check-in ngay
                                    </a>
                                @endif
                            @else
                                <span class="text-gray-500">Vé chưa thanh toán</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-600">Bạn chưa đặt vé nào.</p>
            </div>
        @endif
    </div>
</div>
@endsection
