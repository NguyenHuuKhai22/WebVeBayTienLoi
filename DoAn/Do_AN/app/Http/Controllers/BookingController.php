<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChuyenBay;
use App\Models\NguoiDung;
use App\Models\VeMayBay;
use Illuminate\Support\Str;
use App\Models\DiscountCode;
use Illuminate\Container\Attributes\Auth;

class BookingController extends Controller
{
    /**
     * Show the selected flight for booking
     */
    public function selectFlight($id)
    {
        // Check if user is authenticated
        if (!\Illuminate\Support\Facades\Auth::check()) {
            // Store the intended URL in session
            session(['url.intended' => url()->current()]);
            
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để đặt vé máy bay.')
                ->with('show_notification', true);
        }

        // Get the flight details
        $flight = ChuyenBay::with('hangBay')->findOrFail($id);

        // Get search parameters from session
        $searchParams = session('search_params', []);
        $soHanhKhach = $searchParams['so_hanh_khach'] ?? 1;

        // Available seat types and their prices (factor multipliers)
        $seatTypes = [
            'pho_thong' => [
                'name' => 'Phổ thông',
                'price_factor' => 1.0 // Base price
            ],
            'pho_thong_dac_biet' => [
                'name' => 'Phổ thông đặc biệt',
                'price_factor' => 1.4 // 40% more expensive
            ],
            'thuong_gia' => [
                'name' => 'Thương gia',
                'price_factor' => 2.2 // 120% more expensive
            ]
        ];

        return view('booking.select-flight', compact('flight', 'seatTypes', 'soHanhKhach'));
    }

    /**
     * Collect passenger information
     */
    public function passengerInfo(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|exists:Chuyen_Bay,id_chuyen_bay',
            'seat_type' => 'required|string',
            'num_passengers' => 'required|integer|min:1'
        ]);

        $flight = ChuyenBay::findOrFail($request->flight_id);
        $seatType = $request->seat_type;
        $numPassengers = $request->num_passengers;

        // Calculate price based on seat type
        $priceFactor = 1.0; // Default for economy class
        if ($seatType == 'pho_thong_dac_biet') {
            $priceFactor = 1.4;
        } else if ($seatType == 'thuong_gia') {
            $priceFactor = 2.2;
        }

        // Default baggage allowance based on seat type (in kg)
        $defaultBaggageAllowance = [
            'pho_thong' => 20,
            'pho_thong_dac_biet' => 25,
            'thuong_gia' => 40
        ];

        $basePrice = $flight->gia_ve_co_ban * $priceFactor;
        $totalPrice = $basePrice * $numPassengers;
        
        // Kiểm tra xem có giảm giá không và ngày khởi hành có nằm trong thời gian khuyến mãi không
        $discountPercent = 0;
        $finalPrice = $totalPrice;
        $discount = 0;
        $isInPromotionPeriod = false;
        
        // Kiểm tra xem thời gian khởi hành có nằm trong thời gian khuyến mãi không
        if ($flight->ngay_gio_khoi_hanh) {
            $departureTime = \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh);
            foreach ($flight->getActivePromotions() as $promo) {
                $startTime = \Carbon\Carbon::parse($promo->thoi_gian_bat_dau);
                $endTime = \Carbon\Carbon::parse($promo->thoi_gian_ket_thuc);
                if ($promo->trang_thai && $departureTime->between($startTime, $endTime)) {
                    $isInPromotionPeriod = true;
                    $discountPercent = $flight->getHighestDiscount();
                    $discount = ($totalPrice * $discountPercent) / 100;
                    $finalPrice = $totalPrice - $discount;
                    break;
                }
            }
        }

        // Store booking details in session
        $bookingDetails = [
            'flight_id' => $flight->id_chuyen_bay,
            'seat_type' => $seatType,
            'num_passengers' => $numPassengers,
            'price_per_seat' => $basePrice,
            'total_price' => $totalPrice,
            'default_baggage' => $defaultBaggageAllowance[$seatType],
            'baggage_price_per_kg' => 150000, // Price per kg for extra baggage (150,000 VND)
            'baggage_weights' => array_fill(0, $numPassengers, $defaultBaggageAllowance[$seatType]), // Initialize baggage weights for each passenger
            'baggage_prices' => array_fill(0, $numPassengers, 0), // Initialize baggage prices for each passenger
            'final_price' => $finalPrice, // Initialize final price with discount applied
            'discount' => $discount, // Initialize discount based on flight promotion
            'discount_code' => null, // Initialize discount code as null
            'original_price' => $totalPrice, // Initialize original price same as total price
            'discount_percent' => $discountPercent // Initialize discount percent from flight
        ];

        session(['booking_details' => $bookingDetails]);

        return view('booking.passenger-info', compact('flight', 'numPassengers', 'seatType', 'bookingDetails'));
    }

    /**
     * Review booking details before payment
     */
    public function reviewBooking(Request $request)
    {
        $request->validate([
            'passenger_name.*' => 'required|string|max:100',
            'passenger_email.*' => 'required|email',
            'passenger_phone.*' => 'required|string|max:15',
        ]);

        $bookingDetails = session('booking_details');
        if (!$bookingDetails) {
            return redirect()->route('flights.search')
                ->with('error', 'Booking session expired. Please start again.');
        }

        $flight = ChuyenBay::with('hangBay')->findOrFail($bookingDetails['flight_id']);
        $passengers = [];
        for ($i = 0; $i < count($request->passenger_name); $i++) {
            $passengers[] = [
                'name' => $request->passenger_name[$i],
                'email' => $request->passenger_email[$i],
                'phone' => $request->passenger_phone[$i]
            ];
        }

        $bookingDetails['passengers'] = $passengers;
        $bookingDetails['discount'] = $bookingDetails['discount'] ?? 0; // Giá trị mặc định
        $bookingDetails['final_price'] = $bookingDetails['final_price'] ?? $bookingDetails['total_price'];
        session(['booking_details' => $bookingDetails]);

        $paymentMethods = [
            'visa' => 'Thẻ Visa',
            'mastercard' => 'Thẻ MasterCard',
            'jcb' => 'Thẻ JCB',
            'momo' => 'Ví MoMo',
            'vnpay' => 'VNPay',
            'zalopay' => 'ZaloPay'
        ];

        return view('booking.review', compact('flight', 'bookingDetails', 'paymentMethods'));
    }

    // Method để xử lý Ajax request
    public function applyDiscountCode(Request $request)
    {
        $bookingDetails = session('booking_details');
        if (!$bookingDetails) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên đặt vé đã hết hạn'
            ], 400);
        }

        $flight = ChuyenBay::findOrFail($bookingDetails['flight_id']);
        $discountCode = $request->input('discount_code');
        $flightDiscount = 0;
        $codeDiscount = 0;
        $message = '';
        
        // Lấy thông tin người dùng hiện tại
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Kiểm tra xem thời gian khởi hành có nằm trong thời gian khuyến mãi không
        $isInPromotionPeriod = false;
        $flightDiscountPercent = 0;
        
        if ($flight->ngay_gio_khoi_hanh) {
            $departureTime = \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh);
            foreach ($flight->getActivePromotions() as $promo) {
                $startTime = \Carbon\Carbon::parse($promo->thoi_gian_bat_dau);
                $endTime = \Carbon\Carbon::parse($promo->thoi_gian_ket_thuc);
                if ($promo->trang_thai && $departureTime->between($startTime, $endTime)) {
                    $isInPromotionPeriod = true;
                    $flightDiscountPercent = $flight->getHighestDiscount();
                    break;
                }
            }
        }

        // Lưu lại giá gốc bao gồm hành lý nếu chưa có
        if (!isset($bookingDetails['original_price'])) {
            $totalBaggagePrice = array_sum($bookingDetails['baggage_prices']);
            $bookingDetails['original_price'] = $bookingDetails['total_price'] + $totalBaggagePrice;
        }

        // Tính giảm giá từ khuyến mãi chuyến bay (nếu có)
        if ($isInPromotionPeriod) {
            $flightDiscount = ($bookingDetails['original_price'] * $flightDiscountPercent) / 100;
            $message = "Áp dụng giảm giá từ chương trình khuyến mãi: -" . number_format($flightDiscount, 0, ',', '.') . " VND";
        }

        // Áp dụng mã giảm giá (nếu có)
        $codeDiscountPercent = 0;
        if ($discountCode) {
            $coupon = DiscountCode::where('code', $discountCode)
                ->where('is_used', 0)
                ->first();

            if ($coupon) {
                // Kiểm tra xem mã có thuộc về người dùng hiện tại không
                if ($coupon->user_id && (!$user || $coupon->user_id != $user->id)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mã giảm giá này không thuộc về bạn hoặc bạn chưa đăng nhập.'
                    ]);
                }
                
                $codeDiscountPercent = $coupon->discount_percent;
                $codeDiscount = ($bookingDetails['original_price'] * $codeDiscountPercent) / 100;
                
                if (!empty($message)) {
                    $message .= "<br>";
                }
                $message .= "Áp dụng mã giảm giá người mới thành công: -" . number_format($codeDiscount, 0, ',', '.') . " VND";
                
                $bookingDetails['discount_code'] = $discountCode;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không hợp lệ hoặc đã được sử dụng.'
                ]);
            }
        }

        // Tổng số tiền giảm giá (kết hợp cả hai loại giảm giá)
        $totalDiscount = $flightDiscount + $codeDiscount;
        
        // Cập nhật thông tin session
        $bookingDetails['flight_discount_percent'] = $flightDiscountPercent;
        $bookingDetails['flight_discount'] = $flightDiscount;
        $bookingDetails['discount_code_percent'] = $codeDiscountPercent;
        $bookingDetails['code_discount'] = $codeDiscount;
        $bookingDetails['discount'] = $totalDiscount;
        $bookingDetails['final_price'] = $bookingDetails['original_price'] - $totalDiscount;
        
        session(['booking_details' => $bookingDetails]);

        return response()->json([
            'success' => true,
            'discount' => $totalDiscount,
            'flight_discount' => $flightDiscount,
            'code_discount' => $codeDiscount,
            'final_price' => $bookingDetails['final_price'],
            'original_price' => $bookingDetails['original_price'],
            'message' => $message,
            'is_in_promotion' => ($isInPromotionPeriod || $codeDiscount > 0)
        ]);
    }

    public function processPayment(Request $request)
    {
        $bookingDetails = session('booking_details');
        if (!$bookingDetails) {
            return redirect()->route('flights.search')->with('error', 'Phiên đặt vé đã hết hạn');
        }

        $paymentMethod = $request->payment_method;

        // Đánh dấu mã giảm giá đã được sử dụng nếu có
        if (!empty($bookingDetails['discount_code'])) {
            $coupon = DiscountCode::where('code', $bookingDetails['discount_code'])
                ->where('is_used', 0)
                ->first();
                
            if ($coupon) {
                // Kiểm tra xem mã có thuộc về người dùng hiện tại không
                $user = \Illuminate\Support\Facades\Auth::user();
                
                if (!$coupon->user_id || ($user && $coupon->user_id == $user->id)) {
                    $coupon->is_used = 1;
                    $coupon->save();
                }
            }
        }

        if ($paymentMethod === 'momo') {
            return app(MoMoPaymentController::class)->createPayment($request);
        } elseif ($paymentMethod === 'vnpay') {
            return app(VNPayController::class)->createPayment($request);
        } elseif ($paymentMethod === 'zalopay') {
            return app(VNPayController::class)->createPayment($request);
        }

        return redirect()->back()->with('error', 'Phương thức thanh toán không được hỗ trợ');
    }

    /**
     * Show the passenger selection page
     */
    public function selectPassengers($flight_id)
    {
        // Check if user is authenticated
        if (!\Illuminate\Support\Facades\Auth::check()) {
            // Store the intended URL in session
            session(['url.intended' => url()->current()]);
            
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để đặt vé máy bay.')
                ->with('show_notification', true);
        }

        $flight = ChuyenBay::with('hangBay')->findOrFail($flight_id);
        return view('booking.select-passengers', compact('flight'));
    }

    // Add new method to handle baggage weight calculation
    public function calculateBaggagePrice(Request $request)
    {
        $bookingDetails = session('booking_details');
        if (!$bookingDetails) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên đặt vé đã hết hạn'
            ], 400);
        }

        $request->validate([
            'passenger_index' => 'required|integer|min:0|max:' . ($bookingDetails['num_passengers'] - 1),
            'baggage_weight' => 'required|numeric|min:0'
        ]);

        $passengerIndex = $request->passenger_index;
        $baggageWeight = $request->baggage_weight;
        $defaultBaggage = $bookingDetails['default_baggage'];
        $pricePerKg = $bookingDetails['baggage_price_per_kg'];

        // Đảm bảo khối lượng hành lý không nhỏ hơn mức mặc định
        if ($baggageWeight < $defaultBaggage) {
            return response()->json([
                'success' => false,
                'message' => 'Khối lượng hành lý không được nhỏ hơn ' . $defaultBaggage . ' kg'
            ], 400);
        }

        // Tính toán phí hành lý thêm
        $extraWeight = max(0, $baggageWeight - $defaultBaggage);
        $baggagePrice = $extraWeight * $pricePerKg;

        // Cập nhật thông tin hành lý cho hành khách cụ thể
        $bookingDetails['baggage_weights'][$passengerIndex] = $baggageWeight;
        $bookingDetails['baggage_prices'][$passengerIndex] = $baggagePrice;

        // Tính lại tổng phí hành lý và cập nhật giá cuối cùng
        $totalBaggagePrice = array_sum($bookingDetails['baggage_prices']);
        $baseTicketPrice = $bookingDetails['price_per_seat'] * $bookingDetails['num_passengers']; // Giá vé cơ bản
        $bookingDetails['original_price'] = $baseTicketPrice + $totalBaggagePrice; // Giá gốc = giá vé + phí hành lý
        
        // Áp dụng giảm giá nếu có
        $flightDiscountPercent = 0;
        $codeDiscountPercent = $bookingDetails['discount_code_percent'] ?? 0;
        $discountCode = $bookingDetails['discount_code'] ?? null;
        
        // Lấy thông tin chuyến bay để đảm bảo phần trăm giảm giá đồng bộ
        $flight = ChuyenBay::findOrFail($bookingDetails['flight_id']);
        
        // Kiểm tra xem thời gian khởi hành có nằm trong thời gian khuyến mãi không
        if ($flight->ngay_gio_khoi_hanh) {
            $departureTime = \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh);
            foreach ($flight->getActivePromotions() as $promo) {
                $startTime = \Carbon\Carbon::parse($promo->thoi_gian_bat_dau);
                $endTime = \Carbon\Carbon::parse($promo->thoi_gian_ket_thuc);
                if ($promo->trang_thai && $departureTime->between($startTime, $endTime)) {
                    $flightDiscountPercent = $flight->getHighestDiscount();
                    break;
                }
            }
        }

        // Tính toán lại giảm giá từ chuyến bay
        $flightDiscount = 0;
        if ($flightDiscountPercent > 0) {
            $flightDiscount = ($bookingDetails['original_price'] * $flightDiscountPercent) / 100;
        }
        
        // Tính toán lại giảm giá từ mã giảm giá nếu có
        $codeDiscount = 0;
        if ($codeDiscountPercent > 0 && $discountCode) {
            $coupon = DiscountCode::where('code', $discountCode)
                ->where('is_used', 0)
                ->first();
                
            if ($coupon) {
                $codeDiscount = ($bookingDetails['original_price'] * $codeDiscountPercent) / 100;
            } else {
                // Mã đã được sử dụng, xóa khỏi session
                $codeDiscountPercent = 0;
                $discountCode = null;
            }
        }
        
        // Tổng giảm giá
        $totalDiscount = $flightDiscount + $codeDiscount;
        
        // Cập nhật thông tin session
        $bookingDetails['flight_discount_percent'] = $flightDiscountPercent;
        $bookingDetails['flight_discount'] = $flightDiscount;
        $bookingDetails['discount_code_percent'] = $codeDiscountPercent;
        $bookingDetails['code_discount'] = $codeDiscount;
        $bookingDetails['discount_code'] = $discountCode;
        $bookingDetails['discount'] = $totalDiscount;
        $bookingDetails['final_price'] = $bookingDetails['original_price'] - $totalDiscount;
        
        // Lưu lại thông tin đặt vé vào session
        session(['booking_details' => $bookingDetails]);

        return response()->json([
            'success' => true,
            'baggage_weight' => $baggageWeight,
            'extra_weight' => $extraWeight,
            'baggage_price' => $baggagePrice,
            'total_baggage_price' => $totalBaggagePrice,
            'final_price' => $bookingDetails['final_price'],
            'original_price' => $bookingDetails['original_price'],
            'discount' => $totalDiscount,
            'flight_discount' => $flightDiscount,
            'code_discount' => $codeDiscount,
            'is_in_promotion' => ($flightDiscountPercent > 0 || $codeDiscountPercent > 0)
        ]);
    }
}
