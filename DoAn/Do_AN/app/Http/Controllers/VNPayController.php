<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VeMayBay;
use App\Models\ThanhToan;
use App\Models\NguoiDung;
use App\Models\ChuyenBay;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VNPayController extends Controller
{
    private $tmnCode;
    private $hashSecret;
    private $vnpayUrl;

    public function __construct()
    {
        $this->tmnCode = env('VNPAY_TMN_CODE', 'LKM7C1K2');
        $this->hashSecret = env('VNPAY_HASH_SECRET', '6K6FOBSHTC1ECV3SBSFPM22AWSWU9WQT');
        $this->vnpayUrl = env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
    }

    public function createPayment(Request $request)
    {
        $bookingDetails = session('booking_details');
        if (!$bookingDetails) {
            return redirect()->route('flights.search')->with('error', 'Phiên đặt vé đã hết hạn');
        }

        $orderId = time() . "_" . Str::random(6);
        $amount = $bookingDetails['final_price'] * 100; // VNPay requires amount x 100
        $orderInfo = "Thanh toán vé máy bay Vietnam Airlines";
        $returnUrl = route('vnpay.callback');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->tmnCode,
            "vnp_Amount" => $amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => $orderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $returnUrl,
            "vnp_TxnRef" => $orderId,
        );

        ksort($inputData);
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
        $inputData['vnp_SecureHash'] = $vnpSecureHash;

        $vnp_Url = $this->vnpayUrl . "?" . http_build_query($inputData);

        // Store temporary data in session
        session([
            'vnpay_order_id' => $orderId,
            'vnpay_amount' => $bookingDetails['final_price']
        ]);

        return redirect($vnp_Url);
    }

    public function callback(Request $request)
    {
        $response = $request->all();

        try {
            // Verify the signature
            if (!$this->verifySignature($response)) {
                return redirect()->route('flights.search')
                    ->with('error', 'Chữ ký không hợp lệ');
            }

            if ($response['vnp_ResponseCode'] == '00') {
                $bookingDetails = session('booking_details');
                if (!$bookingDetails) {
                    return redirect()->route('flights.search')
                        ->with('error', 'Phiên đặt vé đã hết hạn');
                }

                $tickets = $this->saveBooking($bookingDetails, $response);

                return view('booking.confirmation', [
                    'ticket' => $tickets[0],
                    'tickets' => $tickets
                ]);
            }

            $errorMsg = $this->getErrorMessage($response['vnp_ResponseCode']);
            return redirect()->route('flights.search')
                ->with('error', 'Thanh toán thất bại: ' . $errorMsg);
        } catch (\Exception $e) {
            Log::error('VNPay Callback Error: ' . $e->getMessage());
            return redirect()->route('flights.search')
                ->with('error', 'Lỗi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    public function notify(Request $request)
    {
        $response = $request->all();

        if (!$this->verifySignature($response)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid signature']);
        }

        if ($response['vnp_ResponseCode'] == '00') {
            $bookingDetails = session('booking_details');
            if ($bookingDetails) {
                $this->saveBooking($bookingDetails, $response);
            }
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Payment failed']);
    }

    private function saveBooking($bookingDetails, $paymentResponse)
    {
        $tickets = [];
        $flight = ChuyenBay::findOrFail($bookingDetails['flight_id']);
        $numPassengers = count($bookingDetails['passengers']);

        if ($flight->so_ghe_trong < $numPassengers) {
            throw new \Exception('Không đủ ghế trống cho chuyến bay này');
        }

        DB::beginTransaction();
        try {
            foreach ($bookingDetails['passengers'] as $passenger) {
                $user = NguoiDung::firstOrCreate(
                    ['email' => $passenger['email']],
                    [
                        'ho_ten' => $passenger['name'],
                        'so_dien_thoai' => $passenger['phone'],
                        'password' => bcrypt(Str::random(10))
                    ]
                );

                $ticket = VeMayBay::create([
                    'id_nguoi_dung' => $user->id_nguoi_dung,
                    'id_chuyen_bay' => $bookingDetails['flight_id'],
                    'ma_ve' => 'VA' . time() . Str::random(4),
                    'loai_ghe' => $bookingDetails['seat_type'],
                    'gia_ve' => $bookingDetails['price_per_seat'],
                    'ngay_dat' => Carbon::now(),
                    'trang_thai' => 'da_thanh_toan'
                ]);

                $tickets[] = $ticket->load(['nguoiDung', 'chuyenBay']);
            }

            $flight->so_ghe_trong -= $numPassengers;
            $flight->save();

            $finalPrice = $bookingDetails['final_price'];

            $existingPayment = ThanhToan::where('ma_giao_dich', $paymentResponse['vnp_TransactionNo'])->first();
            if (!$existingPayment) {
                $payment = ThanhToan::create([
                    'phuong_thuc' => 'vnpay',
                    'so_tien' => $finalPrice,
                    'ngay_thanh_toan' => Carbon::now(),
                    'trang_thai' => 'thanh_cong',
                    'ma_giao_dich' => $paymentResponse['vnp_TransactionNo'],
                    'meta_data' => json_encode([
                        'order_id' => $paymentResponse['vnp_TxnRef'],
                        'bank_code' => $paymentResponse['vnp_BankCode'],
                        'card_type' => $paymentResponse['vnp_CardType'],
                        'pay_date' => $paymentResponse['vnp_PayDate'],
                        'num_passengers' => $numPassengers
                    ])
                ]);

                foreach ($tickets as $ticket) {
                    $ticket->id_thanh_toan = $payment->id_thanh_toan;
                    $ticket->save();
                }
            }

            DB::commit();
            session()->forget(['booking_details', 'vnpay_order_id', 'vnpay_amount']);
            return $tickets;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('VNPay Save Booking Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function verifySignature($data)
    {
        $vnp_SecureHash = $data['vnp_SecureHash'];
        unset($data['vnp_SecureHash']);
        
        ksort($data);
        $hashdata = "";
        $i = 0;
        foreach ($data as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
        return $vnp_SecureHash === $secureHash;
    }

    private function getErrorMessage($code)
    {
        $errorMessages = [
            '01' => 'Giao dịch đã tồn tại',
            '02' => 'Merchant không hợp lệ',
            '03' => 'Dữ liệu gửi sang không đúng định dạng',
            '04' => 'Khởi tạo GD không thành công do Website đang bị tạm khóa',
            '05' => 'Giao dịch không thành công do nhập sai mật khẩu quá số lần quy định',
            '06' => 'Giao dịch không thành công do nhập sai mật khẩu xác thực',
            '07' => 'Giao dịch bị nghi ngờ là gian lận',
            '09' => 'Thẻ/Tài khoản chưa đăng ký dịch vụ InternetBanking',
            '10' => 'Xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Đã hết hạn chờ thanh toán',
            '12' => 'Thẻ bị khóa',
            '51' => 'Tài khoản không đủ số dư',
            '65' => 'Vượt quá hạn mức giao dịch trong ngày',
            '75' => 'Ngân hàng thanh toán đang bảo trì',
            '99' => 'Người dùng hủy giao dịch',
        ];

        return $errorMessages[$code] ?? 'Lỗi không xác định';
    }
}