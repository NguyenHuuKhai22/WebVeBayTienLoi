@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-teal-700 mb-6">Chọn Hạng Vé</h1>

        <!-- Flight details -->
        <div class="bg-gray-100 p-4 rounded-md mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="mb-2 md:mb-0">
                    <div class="font-semibold text-lg">{{ $flight->ma_chuyen_bay }}</div>
                    <div>{{ $flight->hangBay ? $flight->hangBay->ten_hang_bay : 'Vietnam Airlines' }}</div>
                    @php
                        $hasDiscount = false;
                        $discountPercent = 0;
                        
                        if ($flight->ngay_gio_khoi_hanh) {
                            $departureTime = \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh);
                            foreach ($flight->getActivePromotions() as $promo) {
                                $startTime = \Carbon\Carbon::parse($promo->thoi_gian_bat_dau);
                                $endTime = \Carbon\Carbon::parse($promo->thoi_gian_ket_thuc);
                                if ($promo->trang_thai && $departureTime->between($startTime, $endTime)) {
                                    $hasDiscount = true;
                                    $discountPercent = $flight->getHighestDiscount();
                                    break;
                                }
                            }
                        }
                    @endphp
                    @if($hasDiscount)
                        <div class="inline-block bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs font-semibold mt-1">
                            Giảm giá {{ $discountPercent }}%
                        </div>
                    @endif
                </div>

                <div class="flex-1 text-center mb-2 md:mb-0">
                    <div class="flex items-center justify-center">
                        <div class="text-right mr-3">
                            <div class="font-bold">{{ \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh)->format('H:i') }}</div>
                            <div class="text-sm">{{ $flight->diem_di }}</div>
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
                            <div class="text-sm">{{ $flight->diem_den }}</div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-sm">Ngày khởi hành:</div>
                    <div class="font-semibold">{{ \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh)->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Seat selection -->
        <form action="{{ route('booking.passenger') }}" method="POST">
            @csrf
            <input type="hidden" name="flight_id" value="{{ $flight->id_chuyen_bay }}">
            <input type="hidden" name="num_passengers" value="{{ $soHanhKhach }}">

            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-4">Chọn hạng ghế ({{ $soHanhKhach }} hành khách)</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($seatTypes as $key => $type)
                    <div class="border rounded-lg p-4 hover:shadow-md transition cursor-pointer seat-option"
                        data-type="{{ $key }}"
                        data-price="{{ $flight->gia_ve_co_ban * $type['price_factor'] }}"
                        data-discount="{{ $hasDiscount ? $discountPercent : 0 }}">
                        <div class="font-semibold text-lg mb-2">{{ $type['name'] }}</div>
                        @if($hasDiscount)
                            <div class="text-gray-500 line-through text-sm">
                                {{ number_format($flight->gia_ve_co_ban * $type['price_factor'], 0, ',', '.') }} VND
                            </div>
                            <div class="text-red-600 font-bold text-xl mb-2">
                                {{ number_format($flight->gia_ve_co_ban * $type['price_factor'] * (1 - $discountPercent/100), 0, ',', '.') }} VND
                            </div>
                        @else
                            <div class="text-teal-700 font-bold text-xl mb-2">
                                {{ number_format($flight->gia_ve_co_ban * $type['price_factor'], 0, ',', '.') }} VND
                            </div>
                        @endif
                        <div class="text-sm text-gray-600">Giá cho 1 hành khách</div>
                        <div class="mt-4">
                            <input type="radio" name="seat_type" id="seat_{{ $key }}" value="{{ $key }}" class="hidden seat-radio">
                            <label for="seat_{{ $key }}" class="select-btn bg-gray-200 text-gray-700 py-1 px-3 rounded text-center block w-full cursor-pointer transition">
                                Chọn
                            </label>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

            <div class="text-center mt-8">
                <div class="mb-4">
                    <div class="text-gray-600">Tổng tiền cho {{ $soHanhKhach }} hành khách:</div>
                    @if($hasDiscount)
                        <div class="text-gray-500 line-through">
                            <span id="original-total-price">{{ number_format($flight->gia_ve_co_ban * $soHanhKhach, 0, ',', '.') }}</span> VND
                        </div>
                        <div class="text-2xl font-bold text-red-600" id="total-price">
                            {{ number_format($flight->gia_ve_co_ban * $soHanhKhach * (1 - $discountPercent/100), 0, ',', '.') }} VND
                        </div>
                        <div class="text-sm text-gray-600" id="discount-info">
                            Đã áp dụng giảm giá: {{ $discountPercent }}%
                        </div>
                    @else
                        <div class="text-2xl font-bold text-teal-700" id="total-price">
                            {{ number_format($flight->gia_ve_co_ban * $soHanhKhach, 0, ',', '.') }} VND
                        </div>
                        <div class="text-sm text-gray-600" id="discount-info" style="display: none;">
                            Đã áp dụng giảm giá: 0%
                        </div>
                    @endif
                </div>

                <button type="submit" class="bg-teal-700 text-white py-2 px-6 rounded-md hover:bg-teal-800 transition">
                    Tiếp tục
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
    <script src="{{ asset('js/seat-selection.js') }}"></script>
@endsection
