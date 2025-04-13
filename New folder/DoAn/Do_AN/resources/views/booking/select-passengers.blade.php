@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-teal-700 mb-6">Chọn Số Hành Khách</h1>

        <!-- Flight details -->
        <div class="bg-gray-100 p-4 rounded-md mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="mb-2 md:mb-0">
                    <div class="font-semibold text-lg">{{ $flight->ma_chuyen_bay }}</div>
                    <div>{{ $flight->hangBay ? $flight->hangBay->ten_hang_bay : 'Vietnam Airlines' }}</div>
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

        <!-- Passenger selection -->
        <form action="{{ route('booking.passenger') }}" method="POST">
            @csrf
            <input type="hidden" name="flight_id" value="{{ $flight->id_chuyen_bay }}">
            <input type="hidden" name="num_passengers" id="num_passengers" value="1">
            <input type="hidden" name="seat_type" id="selected_seat_type" value="pho_thong">
            <input type="hidden" id="base-price" value="{{ $flight->gia_ve_co_ban }}">
            
            @php
                $hasDiscount = false;
                $discountPercent = 0;
                $discountedPrice = $flight->gia_ve_co_ban;
                
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
                
                $discountedSpecialPrice = $discountedPrice * 1.4;
                $discountedBusinessPrice = $discountedPrice * 2.2;
            @endphp
            
            <input type="hidden" id="discount-percent" value="{{ $discountPercent }}">
            <input type="hidden" id="discounted-price" value="{{ $discountedPrice }}">

            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Chọn số hành khách</h2>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600">Số hành khách:</span>
                        <select name="num_passengers" id="passenger-select" class="border rounded-md px-3 py-1 focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }} hành khách</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border rounded-lg p-4 hover:shadow-md transition cursor-pointer">
                        <div class="font-semibold text-lg mb-2">Phổ thông</div>
                        @if($hasDiscount)
                            <div class="inline-block bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs font-semibold mb-1">-{{ $discountPercent }}%</div>
                            <div class="text-gray-500 line-through text-sm">
                                {{ number_format($flight->gia_ve_co_ban, 0, ',', '.') }} VND
                            </div>
                            <div class="text-red-600 font-bold text-xl mb-2">
                                {{ number_format($discountedPrice, 0, ',', '.') }} VND
                            </div>
                        @else
                            <div class="text-teal-700 font-bold text-xl mb-2">
                                {{ number_format($flight->gia_ve_co_ban, 0, ',', '.') }} VND
                            </div>
                        @endif
                        <div class="text-sm text-gray-600">Giá cho 1 hành khách</div>
                        <div class="mt-4">
                            <input type="radio" name="seat_type" id="seat_pho_thong" value="pho_thong" class="hidden seat-radio" checked>
                            <label for="seat_pho_thong" class="select-btn bg-teal-700 text-white py-1 px-3 rounded text-center block w-full cursor-pointer transition">
                                Chọn
                            </label>
                        </div>
                    </div>

                    <div class="border rounded-lg p-4 hover:shadow-md transition cursor-pointer">
                        <div class="font-semibold text-lg mb-2">Phổ thông đặc biệt</div>
                        @if($hasDiscount)
                            <div class="inline-block bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs font-semibold mb-1">-{{ $discountPercent }}%</div>
                            <div class="text-gray-500 line-through text-sm">
                                {{ number_format($flight->gia_ve_co_ban * 1.4, 0, ',', '.') }} VND
                            </div>
                            <div class="text-red-600 font-bold text-xl mb-2">
                                {{ number_format($discountedSpecialPrice, 0, ',', '.') }} VND
                            </div>
                        @else
                            <div class="text-teal-700 font-bold text-xl mb-2">
                                {{ number_format($flight->gia_ve_co_ban * 1.4, 0, ',', '.') }} VND
                            </div>
                        @endif
                        <div class="text-sm text-gray-600">Giá cho 1 hành khách</div>
                        <div class="mt-4">
                            <input type="radio" name="seat_type" id="seat_pho_thong_dac_biet" value="pho_thong_dac_biet" class="hidden seat-radio">
                            <label for="seat_pho_thong_dac_biet" class="select-btn bg-gray-200 text-gray-700 py-1 px-3 rounded text-center block w-full cursor-pointer transition">
                                Chọn
                            </label>
                        </div>
                    </div>

                    <div class="border rounded-lg p-4 hover:shadow-md transition cursor-pointer">
                        <div class="font-semibold text-lg mb-2">Thương gia</div>
                        @if($hasDiscount)
                            <div class="inline-block bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs font-semibold mb-1">-{{ $discountPercent }}%</div>
                            <div class="text-gray-500 line-through text-sm">
                                {{ number_format($flight->gia_ve_co_ban * 2.2, 0, ',', '.') }} VND
                            </div>
                            <div class="text-red-600 font-bold text-xl mb-2">
                                {{ number_format($discountedBusinessPrice, 0, ',', '.') }} VND
                            </div>
                        @else
                            <div class="text-teal-700 font-bold text-xl mb-2">
                                {{ number_format($flight->gia_ve_co_ban * 2.2, 0, ',', '.') }} VND
                            </div>
                        @endif
                        <div class="text-sm text-gray-600">Giá cho 1 hành khách</div>
                        <div class="mt-4">
                            <input type="radio" name="seat_type" id="seat_thuong_gia" value="thuong_gia" class="hidden seat-radio">
                            <label for="seat_thuong_gia" class="select-btn bg-gray-200 text-gray-700 py-1 px-3 rounded text-center block w-full cursor-pointer transition">
                                Chọn
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <div class="mb-4">
                    <div class="text-gray-600">Tổng tiền cho <span id="total-passengers">1</span> hành khách:</div>
                    <div class="text-2xl font-bold text-teal-700" id="total-price">
                        @if($hasDiscount)
                            {{ number_format($discountedPrice, 0, ',', '.') }} VND
                        @else
                            {{ number_format($flight->gia_ve_co_ban, 0, ',', '.') }} VND
                        @endif
                    </div>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passengerSelect = document.getElementById('passenger-select');
    const totalDisplay = document.getElementById('total-passengers');
    const totalPriceDisplay = document.getElementById('total-price');
    const basePrice = parseInt(document.getElementById('base-price').value);
    const discountPercent = parseInt(document.getElementById('discount-percent').value) || 0;
    const discountedBasePrice = parseInt(document.getElementById('discounted-price').value) || basePrice;
    const numPassengersInput = document.getElementById('num_passengers');
    const selectedSeatTypeInput = document.getElementById('selected_seat_type');

    // Cập nhật tổng tiền
    function updateTotalPrice() {
        const numPassengers = parseInt(passengerSelect.value);
        const selectedType = document.querySelector('input[name="seat_type"]:checked').value;
        let multiplier = 1;

        if (selectedType === 'pho_thong_dac_biet') {
            multiplier = 1.4;
        } else if (selectedType === 'thuong_gia') {
            multiplier = 2.2;
        }

        let total;
        if (discountPercent > 0) {
            total = discountedBasePrice * multiplier * numPassengers;
        } else {
            total = basePrice * multiplier * numPassengers;
        }

        totalPriceDisplay.textContent = total.toLocaleString('vi-VN') + ' VND';
        
        // Cập nhật các input hidden
        numPassengersInput.value = numPassengers;
        selectedSeatTypeInput.value = selectedType;
        totalDisplay.textContent = numPassengers;
    }

    // Xử lý khi thay đổi số lượng hành khách
    passengerSelect.addEventListener('change', updateTotalPrice);

    // Xử lý chọn hạng ghế
    const seatCards = document.querySelectorAll('.border');
    seatCards.forEach(card => {
        card.addEventListener('click', function() {
            // Tìm radio button trong card này
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;

                // Reset tất cả card về trạng thái không chọn
                seatCards.forEach(c => {
                    c.classList.remove('border-teal-500', 'bg-teal-50');
                    const btn = c.querySelector('.select-btn');
                    if (btn) {
                        btn.classList.remove('bg-teal-700', 'text-white');
                        btn.classList.add('bg-gray-200', 'text-gray-700');
                    }
                });

                // Đánh dấu card được chọn
                this.classList.add('border-teal-500', 'bg-teal-50');
                const selectBtn = this.querySelector('.select-btn');
                if (selectBtn) {
                    selectBtn.classList.remove('bg-gray-200', 'text-gray-700');
                    selectBtn.classList.add('bg-teal-700', 'text-white');
                }

                updateTotalPrice();
            }
        });
    });

    // Chọn ghế phổ thông mặc định
    const defaultSeat = document.querySelector('input[value="pho_thong"]');
    if (defaultSeat) {
        const defaultCard = defaultSeat.closest('.border');
        if (defaultCard) {
            defaultCard.click();
        }
    }
});
</script>
@endsection