<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChuyenBay;
use App\Models\NguoiDung;
use App\Models\VeMayBay;
use Illuminate\Support\Str;
use App\Models\DiscountCode;

class BookingController extends Controller
{
    /**
     * Show the selected flight for booking
     */
    public function selectFlight($id)
    {
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
            'final_price' => $totalPrice, // Initialize final price same as total price
            'discount' => 0, // Initialize discount as 0
            'discount_code' => null // Initialize discount code as null
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

        $discountCode = $request->input('discount_code');
        $discount = 0;
        $message = '';

        if ($discountCode) {
            $coupon = DiscountCode::where('code', $discountCode)
                ->where('is_used', 0)
                ->first();

            if ($coupon) {
                $discount = ($bookingDetails['total_price'] * $coupon->discount_percent) / 100;
                $message = "Áp dụng mã giảm giá thành công: -" . number_format($discount, 0, ',', '.') . " VND";
                $bookingDetails['discount'] = $discount;
                $bookingDetails['discount_code'] = $discountCode;
            } else {
                $message = "Mã giảm giá không hợp lệ hoặc đã được sử dụng.";
                $bookingDetails['discount'] = 0;
                $bookingDetails['discount_code'] = null;
            }
        } else {
            $bookingDetails['discount'] = 0;
            $bookingDetails['discount_code'] = null;
        }

        $bookingDetails['final_price'] = $bookingDetails['total_price'] - $discount;
        session(['booking_details' => $bookingDetails]);

        return response()->json([
            'success' => true,
            'discount' => $discount,
            'final_price' => $bookingDetails['final_price'],
            'message' => $message
        ]);
    }

    public function processPayment(Request $request)
    {
        $bookingDetails = session('booking_details');
        if (!$bookingDetails) {
            return redirect()->route('flights.search')->with('error', 'Phiên đặt vé đã hết hạn');
        }

        $paymentMethod = $request->payment_method;

        if ($bookingDetails['discount_code']) {
            $coupon = DiscountCode::where('code', $bookingDetails['discount_code'])
                ->where('is_used', 0)
                ->first();
            if ($coupon) {
                $coupon->is_used = 1;
                $coupon->save();
            }
        }

        if ($paymentMethod === 'momo') {
            return app(MoMoPaymentController::class)->createPayment($request);
        }elseif ($paymentMethod === 'vnpay') {
            return app(VNPayController::class)->createPayment($request);
        }elseif ($paymentMethod === 'zalopay') {
            return app(VNPayController::class)->createPayment($request);
        }

        return redirect()->back()->with('error', 'Phương thức thanh toán không được hỗ trợ');
    }

    /**
     * Show the passenger selection page
     */
    public function selectPassengers($flight_id)
    {
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
                'message' => 'Khối lượng hành lý không được nhỏ hơn mức mặc định (' . $defaultBaggage . ' kg)',
                'baggage_weight' => $defaultBaggage
            ], 400);
        }

        // Tính phí hành lý thêm
        $extraWeight = max(0, $baggageWeight - $defaultBaggage);
        $baggagePrice = $extraWeight * $pricePerKg;

        // Cập nhật thông tin hành lý cho hành khách này
        $bookingDetails['baggage_weights'][$passengerIndex] = $baggageWeight;
        $bookingDetails['baggage_prices'][$passengerIndex] = $baggagePrice;

        // Tính tổng phí hành lý và tổng giá
        $totalBaggagePrice = array_sum($bookingDetails['baggage_prices']);
        $basePrice = $bookingDetails['price_per_seat'] * $bookingDetails['num_passengers'];
        $totalPrice = $basePrice + $totalBaggagePrice;
        
        // Cập nhật tổng giá trong session
        $bookingDetails['total_price'] = $totalPrice;
        if (isset($bookingDetails['discount'])) {
            $bookingDetails['final_price'] = $totalPrice - $bookingDetails['discount'];
        } else {
            $bookingDetails['final_price'] = $totalPrice;
        }

        session(['booking_details' => $bookingDetails]);

        return response()->json([
            'success' => true,
            'baggage_price' => $baggagePrice,
            'total_baggage_price' => $totalBaggagePrice,
            'total_price' => $totalPrice,
            'final_price' => $bookingDetails['final_price'],
            'extra_weight' => $extraWeight,
            'message' => 'Cập nhật phí hành lý thành công'
        ]);
    }
}
