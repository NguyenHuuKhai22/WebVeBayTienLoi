<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VeMayBay;
use App\Models\ThanhToan;
use App\Models\NguoiDung;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\DiscountCode;
use App\Models\ChuyenBay;
use Illuminate\Support\Facades\DB; // Add this line

class MoMoPaymentController extends Controller
{
    //khai bao key MOMO
    private $partnerCode = "MOMOOJOI20210710";
    private $accessKey = "iPXneGmrJH0G8FOP";
    private $secretKey = "sFcbSGRSJjwGxwhhcEktCHWYUuTuPNDB";
    private $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    public function __construct()
    {
        $this->partnerCode = env('MOMO_PARTNER_CODE');
        $this->accessKey = env('MOMO_ACCESS_KEY');
        $this->secretKey = env('MOMO_SECRET_KEY');
    }
    //tao don hang 
    public function createPayment(Request $request)
    {
        $bookingDetails = session('booking_details');
        if (!$bookingDetails) {
            return redirect()->route('flights.search')->with('error', 'Phiên đặt vé đã hết hạn');
        }
        //tao orderId va requestId
        $orderId = time() . "_" . Str::random(6);
        $requestId = time() . "_" . Str::random(6);
        $amount = $bookingDetails['final_price']; // Sử dụng final_price
        $orderInfo = "Thanh toán vé máy bay Vietnam Airlines";
        $redirectUrl = route('momo.callback');
        $ipnUrl = route('momo.notify');
        $extraData = base64_encode(json_encode($bookingDetails));

        //tao signature
        $rawHash = "accessKey=" . $this->accessKey .
            "&amount=" . $amount .
            "&extraData=" . $extraData .
            "&ipnUrl=" . $ipnUrl .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&partnerCode=" . $this->partnerCode .
            "&redirectUrl=" . $redirectUrl .
            "&requestId=" . $requestId .
            "&requestType=captureWallet";

        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);
        //tao data
        $data = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => "Vietnam Airlines",
            'storeId' => "VietnamAirlines",
            'requestType' => "captureWallet",
            'ipnUrl' => $ipnUrl,
            'redirectUrl' => $redirectUrl,
            'orderId' => $orderId,
            'amount' => $amount,
            'lang' => 'vi',
            'orderInfo' => $orderInfo,
            'requestId' => $requestId,
            'extraData' => $extraData,
            'signature' => $signature
        ];
        //goi api tao don hang
        $response = $this->execPostRequest($this->endpoint, json_encode($data));
        $result = json_decode($response, true);

        if (isset($result['payUrl'])) {
            return redirect($result['payUrl']);
        }
        return redirect()->back()->with('error', 'Không thể tạo thanh toán MoMo');
    }
    //callback
    public function callback(Request $request)
    {
        
        $response = $request->all();
        //kiem tra ket qua thanh toan
        if ($response['resultCode'] == 0) {
            $bookingDetails = json_decode(base64_decode($response['extraData']), true);
            $tickets = $this->saveBooking($bookingDetails, $response);

            return view('booking.confirmation', [
                'ticket' => $tickets[0], // Vé đầu tiên để lấy thông tin chung
                'tickets' => $tickets    // Danh sách tất cả vé
            ]);
        }

        return redirect()->route('flights.search')
            ->with('error', 'Thanh toán thất bại: ' . $response['message']);
    }

    public function notify(Request $request)
    {
        //lay ket qua thanh toan
        $response = $request->all();
        //tao signature
        $rawHash = "accessKey=" . $this->accessKey .
            "&amount=" . $response['amount'] .
            "&extraData=" . $response['extraData'] .
            "&message=" . $response['message'] .
            "&orderId=" . $response['orderId'] .
            "&orderInfo=" . $response['orderInfo'] .
            "&orderType=" . $response['orderType'] .
            "&partnerCode=" . $response['partnerCode'] .
            "&payType=" . $response['payType'] .
            "&requestId=" . $response['requestId'] .
            "&responseTime=" . $response['responseTime'] .
            "&resultCode=" . $response['resultCode'] .
            "&transId=" . $response['transId'];

        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

        if ($signature !== $response['signature']) {
            return response()->json(['status' => 'error', 'message' => 'Invalid signature']);
        }

        if ($response['resultCode'] == 0) {
            $this->saveBooking(json_decode(base64_decode($response['extraData']), true), $response);
        }

        return response()->json(['status' => 'success']);
    }

    private function saveBooking($bookingDetails, $paymentResponse)
    {
        $tickets = [];

        // Get the flight and check available seats
        $flight = ChuyenBay::findOrFail($bookingDetails['flight_id']);
        $numPassengers = count($bookingDetails['passengers']);

        // Check if there are enough seats
        if ($flight->so_ghe_trong < $numPassengers) {
            throw new \Exception('Không đủ ghế trống cho chuyến bay này');
        }

        // Start transaction to ensure data consistency
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

            // Update available seats
            $flight->so_ghe_trong -= $numPassengers;
            $flight->save();

            $finalPrice = $bookingDetails['total_price'];
            $totalPrice = $bookingDetails['final_price'];

            $existingPayment = ThanhToan::where('ma_giao_dich', $paymentResponse['transId'])->first();
            if (!$existingPayment) {
                $payment = ThanhToan::create([
                    'phuong_thuc' => 'momo',
                    'so_tien' => $totalPrice,
                    'ngay_thanh_toan' => Carbon::now(),
                    'trang_thai' => 'thanh_cong',
                    'ma_giao_dich' => $paymentResponse['transId'],
                    'meta_data' => json_encode([
                        'order_id' => $paymentResponse['orderId'],
                        'trans_id' => $paymentResponse['transId'],
                        'pay_type' => $paymentResponse['payType'],
                        'response_time' => $paymentResponse['responseTime'],
                        'num_passengers' => $numPassengers
                    ])
                ]);

                if (isset($bookingDetails['discount_code']) && $bookingDetails['discount_code']) {
                    $coupon = DiscountCode::where('code', $bookingDetails['discount_code'])
                        ->where('is_used', 0)
                        ->first();
                    if ($coupon) {
                        $coupon->is_used = 1;
                        $coupon->save();
                    }
                }

                // Link all tickets to payment transaction
                foreach ($tickets as $ticket) {
                    $ticket->id_thanh_toan = $payment->id_thanh_toan;
                    $ticket->save();
                }
            }

            // If everything succeeds, commit the transaction
            DB::commit();

            session()->forget('booking_details');
            return $tickets;
        } catch (\Exception $e) {
            // If anything fails, rollback all changes
            DB::rollback();
            throw $e;
        }
    }
    //goi api
    private function execPostRequest($url, $data)
    {
        //khai bao url
        $ch = curl_init($url);
        //khai bao method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //khai bao data
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //khai bao return
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //khai bao header
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        //khai bao timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        //khai bao connect timeout
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //thuc thi api
        $result = curl_exec($ch);
        //dong ket noi
        curl_close($ch);
        //tra ve ket qua    
        return $result;
    }
}
