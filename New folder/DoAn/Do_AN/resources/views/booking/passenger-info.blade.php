@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-teal-700 mb-6">Thông Tin Hành Khách</h1>
        
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
                            $thoiGianKhoiHanh = \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh);
                            foreach ($flight->getActivePromotions() as $promo) {
                                $thoiGianBatDau = \Carbon\Carbon::parse($promo->thoi_gian_bat_dau);
                                $thoiGianKetThuc = \Carbon\Carbon::parse($promo->thoi_gian_ket_thuc);
                                if ($promo->trang_thai && $thoiGianKhoiHanh->between($thoiGianBatDau, $thoiGianKetThuc)) {
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
                    <div class="text-sm">
                        Hạng ghế: 
                        <span class="font-semibold">
                            @if($seatType == 'pho_thong')
                                Phổ thông
                            @elseif($seatType == 'pho_thong_dac_biet')
                                Phổ thông đặc biệt
                            @elseif($seatType == 'thuong_gia')
                                Thương gia
                            @endif
                        </span>
                    </div>
                    <div class="font-semibold">{{ \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh)->format('d/m/Y') }}</div>
                    <div class="mt-1">
                        @if($hasDiscount)
                            <div class="text-gray-500 line-through text-xs">
                                {{ number_format($flight->gia_ve_co_ban, 0, ',', '.') }} VND
                            </div>
                            <div class="text-red-600 font-semibold">
                                {{ number_format($flight->getDiscountedPrice(), 0, ',', '.') }} VND
                            </div>
                        @else
                            <div class="text-teal-700 font-semibold">
                                {{ number_format($flight->gia_ve_co_ban, 0, ',', '.') }} VND
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Passenger form -->
        <form action="{{ route('booking.review') }}" method="POST">
            @csrf
            
            <h2 class="text-lg font-semibold mb-4">Nhập thông tin {{ $numPassengers }} hành khách</h2>
            
            @for($i = 0; $i < $numPassengers; $i++)
                <div class="mb-6 p-4 border rounded-lg">
                    <h3 class="font-semibold mb-3">Hành khách {{ $i + 1 }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="passenger_name_{{ $i }}" class="block text-gray-700 mb-1">Họ tên</label>
                            <input type="text" id="passenger_name_{{ $i }}" name="passenger_name[]" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                            @error('passenger_name.'.$i)
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="passenger_email_{{ $i }}" class="block text-gray-700 mb-1">Email</label>
                            <input type="email" id="passenger_email_{{ $i }}" name="passenger_email[]" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                            @error('passenger_email.'.$i)
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="passenger_phone_{{ $i }}" class="block text-gray-700 mb-1">Số điện thoại</label>
                        <input type="tel" id="passenger_phone_{{ $i }}" name="passenger_phone[]" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                        @error('passenger_phone.'.$i)
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Baggage selection for each passenger -->
                    <div class="border-t pt-4">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                            <div class="w-full md:w-1/2">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label for="baggage_weight_{{ $i }}" class="block text-gray-700 font-medium mb-2">
                                        Khối lượng hành lý
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        <div class="relative flex-1">
                                            <input type="number" 
                                                id="baggage_weight_{{ $i }}" 
                                                name="baggage_weight[]" 
                                                min="{{ session('booking_details.default_baggage') }}"
                                                step="0.5"
                                                value="{{ session('booking_details.baggage_weights.' . $i) ?? session('booking_details.default_baggage') }}"
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all baggage-input"
                                                data-passenger-index="{{ $i }}"
                                                data-default-weight="{{ session('booking_details.default_baggage') }}">
                                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">kg</span>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Hành lý mặc định: {{ session('booking_details.default_baggage') }} kg</span>
                                    </div>
                                    <div class="mt-1 text-sm text-red-500" id="baggage_error_{{ $i }}"></div>
                                </div>
                            </div>

                            <div class="w-full md:w-1/2 md:pl-6">
                                <div class="bg-teal-50 p-4 rounded-lg">
                                    <div class="mb-3">
                                        <div class="flex items-center text-sm text-gray-600 mb-2">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>Phí hành lý thêm: 150,000 VND/kg</span>
                                        </div>
                                        <div id="baggage_price_display_{{ $i }}" class="text-lg font-semibold text-teal-700">
                                            Phí hành lý: {{ number_format(session('booking_details.baggage_prices.' . $i) ?? 0, 0, ',', '.') }} VND
                                        </div>
                                    </div>
                                    <div id="baggage_extra_info_{{ $i }}" class="text-sm text-gray-600 mt-1 flex items-center">
                                        @if(session('booking_details.baggage_weights.' . $i) > session('booking_details.default_baggage'))
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                            <span>{{ session('booking_details.baggage_weights.' . $i) - session('booking_details.default_baggage') }} kg vượt quá hành lý mặc định</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
            
            <div class="flex items-center justify-between mt-8">
                <div>
                    <div class="text-gray-600">Tổng tiền:</div>
                    @if(isset($bookingDetails) && !empty($bookingDetails))
                        @if($hasDiscount)
                            <div class="text-gray-500 line-through" id="original-price">
                                {{ number_format($bookingDetails['original_price'] ?? $bookingDetails['total_price'], 0, ',', '.') }} VND
                            </div>
                            <div class="text-red-600 text-2xl font-bold" id="final-price">
                                @php
                                    $discountedPrice = $bookingDetails['original_price'] * (1 - $discountPercent/100);
                                @endphp
                                {{ number_format($discountedPrice, 0, ',', '.') }} VND
                            </div>
                            <div class="text-sm text-gray-600" id="discount-info">
                                Đã áp dụng giảm giá: {{ $discountPercent }}%
                            </div>
                        @else
                            <div class="text-teal-700 text-2xl font-bold" id="final-price">
                                {{ number_format($bookingDetails['original_price'], 0, ',', '.') }} VND
                            </div>
                        @endif
                    @endif
                </div>
                
                <div>
                    <button type="submit" class="bg-teal-700 text-white py-2 px-6 rounded-md hover:bg-teal-800 transition">
                        Tiếp tục
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const PRICE_FORMAT = new Intl.NumberFormat('vi-VN');
    
    // Khởi tạo cho mỗi input hành lý
    $('.baggage-input').each(function() {
        const $input = $(this);
        const defaultWeight = parseFloat($input.data('default-weight'));
        
        // Set giá trị mặc định nếu trống
        if (!$input.val()) {
            $input.val(defaultWeight);
        }

        // Xử lý khi thay đổi giá trị
        $input.on('change', function() {
            validateAndCalculate($(this));
        });

        // Xử lý khi nhấn Enter
        $input.on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                validateAndCalculate($(this));
            }
        });
    });

    function validateAndCalculate($input) {
        const weight = parseFloat($input.val());
        const defaultWeight = parseFloat($input.data('default-weight'));
        const passengerIndex = $input.data('passenger-index');
        const $error = $(`#baggage_error_${passengerIndex}`);
        const $priceDisplay = $(`#baggage_price_display_${passengerIndex}`);
        
        // Kiểm tra giá trị hợp lệ
        if (isNaN(weight)) {
            $error.text('Vui lòng nhập số hợp lệ');
            $input.addClass('border-red-500');
            $input.val(defaultWeight);
            calculateBaggagePrice($input);
            return false;
        }
        
        if (weight < defaultWeight) {
            $error.text(`Khối lượng hành lý không được nhỏ hơn ${defaultWeight} kg`);
            $input.addClass('border-red-500');
            $input.val(defaultWeight);
            calculateBaggagePrice($input);
            return false;
        }

        // Nếu giá trị hợp lệ
        $error.text('');
        $input.removeClass('border-red-500');
        calculateBaggagePrice($input);
        return true;
    }

    function calculateBaggagePrice($input) {
        const weight = parseFloat($input.val());
        const passengerIndex = $input.data('passenger-index');
        const $priceDisplay = $(`#baggage_price_display_${passengerIndex}`);
        const $extraInfo = $(`#baggage_extra_info_${passengerIndex}`);
        
        // Hiển thị trạng thái loading
        $priceDisplay.html('<span class="text-gray-500">Đang tính...</span>');

        $.ajax({
            url: '{{ route("booking.calculate-baggage") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({
                baggage_weight: weight,
                passenger_index: passengerIndex
            }),
            success: function(response) {
                if (response.success) {
                    // Cập nhật hiển thị phí hành lý
                    if (response.baggage_price > 0) {
                        $priceDisplay.html(`Phí hành lý thêm: ${PRICE_FORMAT.format(response.baggage_price)} VND`);
                        $extraInfo.html(`<div class="text-sm text-gray-600">(${response.extra_weight} kg vượt quá hành lý mặc định)</div>`);
                    } else {
                        $priceDisplay.html('Phí hành lý: 0 VND');
                        $extraInfo.html('');
                    }

                    // Cập nhật tổng tiền
                    updateTotalPrice(response.final_price, response.original_price);
                } else {
                    handleError($input, $priceDisplay, $extraInfo, response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                handleError($input, $priceDisplay, $extraInfo, 'Có lỗi xảy ra khi tính phí hành lý');
            }
        });
    }

    function handleError($input, $priceDisplay, $extraInfo, message) {
        $extraInfo.html(`<div class="text-sm text-red-500">${message}</div>`);
        $input.addClass('border-red-500');
        $priceDisplay.html('Phí hành lý: Không thể tính toán');
    }

    function updateTotalPrice(finalPrice, originalPrice) {
        // Kiểm tra xem có phần tử #original-price không (chỉ có khi có giảm giá)
        const hasDiscount = $('#original-price').length > 0;
        
        if (hasDiscount) {
            // Trường hợp có giảm giá
            // Cập nhật giá gốc (có gạch ngang)
            $('#original-price').text(`${PRICE_FORMAT.format(originalPrice)} VND`);
            
            // Cập nhật giá cuối cùng (màu đỏ)
            $('#final-price').text(`${PRICE_FORMAT.format(finalPrice)} VND`);
            
            // Cập nhật thông tin giảm giá
            const discountAmount = originalPrice - finalPrice;
            if (discountAmount > 0) {
                const discountPercent = Math.round((discountAmount / originalPrice) * 100);
                $('#discount-info').text(`Đã áp dụng giảm giá: ${discountPercent}%`);
                $('#discount-info').removeClass('hidden');
            }
        } else {
            // Trường hợp không có giảm giá
            // Trong trường hợp không có giảm giá, originalPrice là giá vé cơ bản + phí hành lý
            // và finalPrice sẽ bằng originalPrice (vì không có giảm giá)
            // Nên chỉ cần dùng một trong hai giá trị này để hiển thị
            $('#final-price').text(`${PRICE_FORMAT.format(originalPrice)} VND`);
        }
        
        // Log để debug
        console.log(`Cập nhật giá: Gốc=${originalPrice}, Sau giảm=${finalPrice}, Giảm=${originalPrice - finalPrice}`);
    }
});
</script>
@endsection

@endsection