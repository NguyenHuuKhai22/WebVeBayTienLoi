@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-teal-700 mb-6">Xác Nhận Thông Tin Đặt Vé</h1>

        <!-- Flight details -->
        <div class="border-b pb-4 mb-4">
            <h2 class="text-lg font-semibold mb-3">Thông tin chuyến bay</h2>

            <div class="bg-gray-100 p-4 rounded-md">
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
                                @if($bookingDetails['seat_type'] == 'pho_thong')
                                Phổ thông
                                @elseif($bookingDetails['seat_type'] == 'pho_thong_dac_biet')
                                Phổ thông đặc biệt
                                @elseif($bookingDetails['seat_type'] == 'thuong_gia')
                                Thương gia
                                @endif
                            </span>
                        </div>
                        <div class="font-semibold">{{ \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh)->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Passenger details -->
        <div class="border-b pb-4 mb-4">
            <h2 class="text-lg font-semibold mb-3">Thông tin hành khách</h2>

            <div class="space-y-3">
                @foreach($bookingDetails['passengers'] as $index => $passenger)
                <div class="p-3 bg-gray-50 rounded">
                    <div class="font-semibold">Hành khách {{ $index + 1 }}: {{ $passenger['name'] }}</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600">
                        <div>Email: {{ $passenger['email'] }}</div>
                        <div>Điện thoại: {{ $passenger['phone'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Price summary -->
        <div class="border-b pb-4 mb-4">
            <h2 class="text-lg font-semibold mb-3">Chi tiết giá vé</h2>
            <div class="space-y-3">
                <!-- Giá vé cơ bản -->
                <div class="flex justify-between items-start">
                    <div>
                        <div class="font-medium">Giá vé cơ bản ({{ count($bookingDetails['passengers']) }} hành khách)</div>
                        <div class="text-sm text-gray-600">{{ number_format($bookingDetails['price_per_seat'], 0, ',', '.') }} × {{ count($bookingDetails['passengers']) }}</div>
                    </div>
                    <div class="font-medium">{{ number_format($bookingDetails['price_per_seat'] * count($bookingDetails['passengers']), 0, ',', '.') }} VND</div>
                </div>

                <!-- Chi tiết hành lý -->
                @php
                    $totalBaggagePrice = 0;
                @endphp
                @foreach($bookingDetails['baggage_weights'] as $index => $weight)
                    @if($bookingDetails['baggage_prices'][$index] > 0)
                        @php
                            $totalBaggagePrice += $bookingDetails['baggage_prices'][$index];
                            $extraWeight = $weight - $bookingDetails['default_baggage'];
                        @endphp
                        <div class="flex justify-between items-start text-sm">
                            <div>
                                <div>Hành lý thêm - Hành khách {{ $index + 1 }}</div>
                                <div class="text-gray-600">Vượt quá {{ $extraWeight }} kg ({{ number_format(150000, 0, ',', '.') }} VND/kg)</div>
                            </div>
                            <div>{{ number_format($bookingDetails['baggage_prices'][$index], 0, ',', '.') }} VND</div>
                        </div>
                    @endif
                @endforeach

                @if($totalBaggagePrice > 0)
                    <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                        <div class="font-medium">Tổng phí hành lý thêm</div>
                        <div class="font-medium">{{ number_format($totalBaggagePrice, 0, ',', '.') }} VND</div>
                    </div>
                @endif

                <!-- Thuế và phí -->
                <div class="flex justify-between text-sm">
                    <div>Thuế và phí</div>
                    <div>Đã bao gồm</div>
                </div>

                <!-- Giảm giá nếu có -->
                <div id="discount-section" class="{{ (isset($bookingDetails['discount']) && $bookingDetails['discount'] > 0) ? '' : 'hidden' }} flex justify-between items-start text-green-600">
                    <div>
                        <div class="font-medium">Giảm giá</div>
                        <div class="text-sm discount-details">
                            @if(isset($bookingDetails['flight_discount']) && $bookingDetails['flight_discount'] > 0)
                                <div>Khuyến mãi chuyến bay: -{{ number_format($bookingDetails['flight_discount'], 0, ',', '.') }} VND</div>
                            @endif
                            
                            @if(isset($bookingDetails['discount_code']) && $bookingDetails['discount_code'])
                                <div>Mã giảm giá ({{ $bookingDetails['discount_code'] }}): -{{ number_format($bookingDetails['code_discount'], 0, ',', '.') }} VND</div>
                            @endif
                        </div>
                    </div>
                    <div class="font-medium" id="discount-amount">-{{ number_format($bookingDetails['discount'] ?? 0, 0, ',', '.') }} VND</div>
                </div>

                <!-- Tổng tiền trước giảm giá -->
                <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                    <div class="font-medium">Tổng tiền</div>
                    <div class="font-medium">{{ number_format($bookingDetails['total_price'], 0, ',', '.') }} VND</div>
                </div>

                <!-- Tổng tiền cuối cùng -->
                <div class="flex justify-between items-center pt-3 border-t border-gray-200 text-lg font-bold">
                    <div>Tổng tiền thanh toán</div>
                    @if((isset($bookingDetails['discount']) && $bookingDetails['discount'] > 0) && $hasDiscount)
                        <div>
                            <div class="text-gray-500 line-through">
                                {{ number_format($bookingDetails['total_price'], 0, ',', '.') }} VND
                            </div>
                            <div class="text-red-600" id="final-price">
                                {{ number_format($bookingDetails['final_price'], 0, ',', '.') }} VND
                            </div>
                        </div>
                    @else
                        <div class="text-teal-700" id="final-price">
                            {{ number_format($bookingDetails['total_price'], 0, ',', '.') }} VND
                        </div>
                    @endif
                </div>
            </div>

            <!-- Discount code input -->
            <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                <div class="flex items-center space-x-2">
                    <div class="flex-1">
                        <input type="text" 
                               id="discount-code" 
                               placeholder="Nhập mã giảm giá" 
                               value="{{ $bookingDetails['discount_code'] ?? '' }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <button id="apply-discount-btn" 
                            class="bg-teal-700 text-white px-6 py-2 rounded-lg hover:bg-teal-800 transition-colors duration-200">
                        Áp dụng
                    </button>
                </div>
                <p id="discount-message" class="mt-2 text-sm"></p>
            </div>
        </div>

       <!-- Payment methods -->
<form action="{{ route('payment.process') }}" method="POST" id="payment-form">
    @csrf

    <h2 class="text-xl font-semibold text-teal-700 mb-4">Phương thức thanh toán</h2>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        @foreach($paymentMethods as $key => $method)
            <div class="border rounded-xl p-4 bg-white hover:shadow-xl transition-all duration-300 cursor-pointer payment-option {{ $key === 'visa' ? 'border-teal-500 bg-teal-50 shadow-md' : '' }}" data-method="{{ $key }}">
                <input type="radio" name="payment_method" id="payment_{{ $key }}" value="{{ $key }}" {{ $key === 'visa' ? 'checked' : '' }} class="hidden payment-radio">
                <label for="payment_{{ $key }}" class="cursor-pointer flex flex-col items-center">
                    <div class="h-14 w-14 flex items-center justify-center mb-3">
                        @switch($key)
                            @case('visa')
                                <img src="https://t4.ftcdn.net/jpg/04/06/75/39/240_F_406753914_SFSBhjhp6kbHblNiUFZ1MXHcuEKe7e7P.jpg" alt="Visa" class="h-12 object-contain">
                                @break
                            @case('mastercard')
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="MasterCard" class="h-9 object-contain">
                                @break
                            @case('jcb')
                                <img src="https://upload.wikimedia.org/wikipedia/commons/4/40/JCB_logo.svg" alt="JCB" class="h-9 object-contain">
                                @break
                                @case('momo')
                        <img src="https://developers.momo.vn/v3/assets/images/square-logo-f8712a4d5be38f389e6bc94c70a33bf4.png" alt="MoMo" class="h-9 object-contain" onerror="this.src='https://via.placeholder.com/36?text=MoMo'">
                        @break
                            @case('vnpay')
                                <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-VNPAY-QR.png" alt="VNPay" class="h-9 object-contain">
                                @break
                            @case('zalopay')
                            <img src="https://scontent.fsgn5-9.fna.fbcdn.net/v/t39.30808-6/464566669_9094433807290212_1890508231940251059_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=0b6b33&_nc_ohc=Yr0C4cio__cQ7kNvgFAshyl&_nc_oc=AdjqjFukYfq4EXXMRKnLKJ1iQgWyoeTF1DA0HaPXf47EXjxqcYutaNRpc9EybKeIyE8&_nc_zt=23&_nc_ht=scontent.fsgn5-9.fna&_nc_gid=QA0ujGRcN1v_0jdlTn7fMA&oh=00_AYGgfJp0mHmIt8hdSmZCHgbG-gdZnTQeywwQR9FSaQOK_w&oe=67DAE7C3" alt="ZaloPay" class="h-9 object-contain" onerror="this.src='https://via.placeholder.com/36?text=ZaloPay'">
                                @break
                            @default
                                <i class="fas fa-wallet text-3xl text-gray-600"></i>
                        @endswitch
                    </div>
                    <div class="text-center text-gray-800 font-semibold text-sm">{{ $method }}</div>
                </label>
            </div>
        @endforeach
   


                
                
            </div>

            <div class="flex items-center justify-between mt-8">
                <a href="javascript:history.back()" class="text-teal-700 hover:underline">← Quay lại</a>

                <button type="submit" class="bg-teal-700 text-white py-2 px-6 rounded-md hover:bg-teal-800 transition"
                    id="payment-button">
                    Thanh toán <span id="payment-amount">
                        @if($hasDiscount || (isset($bookingDetails['discount']) && $bookingDetails['discount'] > 0))
                            {{ number_format($bookingDetails['final_price'], 0, ',', '.') }}
                        @else
                            {{ number_format($bookingDetails['total_price'], 0, ',', '.') }}
                        @endif
                    </span> VND
                </button>

            </div>
        </form>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#apply-discount-btn').click(function(e) {
                    e.preventDefault();

                    var discountCode = $('#discount-code').val();

                    $.ajax({
                        url: '{{ route("apply.discount.code") }}',
                        method: 'POST',
                        data: {
                            discount_code: discountCode,
                            flight_id: '{{ $flight->id }}',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                // Cập nhật giao diện
                                if (response.discount > 0) {
                                    $('#discount-section').removeClass('hidden');
                                    
                                    // Xây dựng thông tin chi tiết giảm giá
                                    let discountDetailsHTML = '';
                                    
                                    // Hiển thị giảm giá từ chuyến bay
                                    if (response.flight_discount > 0) {
                                        discountDetailsHTML += '<div>Khuyến mãi chuyến bay: -' + 
                                            response.flight_discount.toLocaleString('vi-VN') + ' VND</div>';
                                    }
                                    
                                    // Hiển thị giảm giá từ mã giảm giá
                                    if (response.code_discount > 0) {
                                        discountDetailsHTML += '<div>Mã giảm giá (' + discountCode + '): -' + 
                                            response.code_discount.toLocaleString('vi-VN') + ' VND</div>';
                                    }
                                    
                                    // Cập nhật khu vực hiển thị chi tiết giảm giá
                                    $('.discount-details').html(discountDetailsHTML);
                                    
                                    // Cập nhật tổng giảm giá
                                    $('#discount-amount').text('-' + response.discount.toLocaleString('vi-VN') + ' VND');
                                    
                                    // Hiển thị giá gốc và giá đã giảm
                                   // To this (only showing the final price):
                                    $('#final-price').html('<div class="text-red-600">' + 
                                        response.final_price.toLocaleString('vi-VN') + ' VND</div>');
                                } else {
                                    $('#discount-section').addClass('hidden');
                                    $('#final-price').html(response.final_price.toLocaleString('vi-VN') + ' VND');
                                    $('#final-price').removeClass('text-red-600').addClass('text-teal-700');
                                }
                                
                                // Cập nhật giá trong nút thanh toán
                                $('#payment-amount').text(response.final_price.toLocaleString('vi-VN'));
                                $('#discount-message').html(response.message).removeClass('text-red-600').addClass('text-green-600');
                            } else {
                                $('#discount-message').text(response.message).removeClass('text-green-600').addClass('text-red-600');
                            }
                        },
                        error: function(xhr) {
                            $('#discount-message').text('Có lỗi xảy ra, vui lòng thử lại.').addClass('text-red-600');
                        }
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const paymentOptions = document.querySelectorAll('.payment-option');
                const paymentForm = document.getElementById('payment-form');
                const paymentButton = document.getElementById('payment-button');

                // Vô hiệu hóa nút thanh toán ban đầu
                paymentButton.disabled = true;
                paymentButton.classList.add('opacity-50', 'cursor-not-allowed');

                paymentOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        paymentOptions.forEach(opt => {
                            opt.classList.remove('border-teal-500');
                            opt.querySelector('.payment-radio').checked = false;
                        });

                        const paymentMethod = this.dataset.method;
                        this.classList.add('border-teal-500');
                        this.querySelector('.payment-radio').checked = true;
                        paymentButton.disabled = false;
                        paymentButton.classList.remove('opacity-50', 'cursor-not-allowed');

                        if (paymentMethod === 'momo') {
                            paymentForm.action = "{{ route('momo.create-payment') }}";
                        } else if (paymentMethod === 'vnpay') {
                            paymentForm.action = "{{ route('vnpay.create-payment') }}";
                        } else {
                            paymentForm.action = "{{ route('payment.process') }}";
                        }
                    });
                });

                paymentForm.addEventListener('submit', function(event) {
                    const selectedPayment = document.querySelector('.payment-radio:checked');
                    if (!selectedPayment) {
                        event.preventDefault();
                        alert('Vui lòng chọn phương thức thanh toán trước khi tiếp tục.');
                    }
                });
            });
        </script>
        @endsection