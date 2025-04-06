<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VeMayBay;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CheckinController extends Controller
{
    // Hiển thị form check-in
    public function showForm()
    {
        return view('checkins.checkin'); // Hiển thị form nhập mã vé & họ tên
    }

    public function processCheckin(Request $request)
    {
        $request->validate([
            'ma_ve' => 'required|string',
            'ho_ten' => 'required|string',
            'action' => 'required|string',
        ]);

        // Tìm vé dựa vào mã vé và họ tên
        $ve = VeMayBay::where('ma_ve', $request->ma_ve)
                    ->whereHas('nguoiDung', function ($query) use ($request) {
                        $query->where('ho_ten', $request->ho_ten);
                    })
                    ->with('chuyenBay')
                    ->first();

        if (!$ve) {
            return back()->withErrors(['message' => 'Mã vé hoặc họ tên không hợp lệ!']);
        }

        // Kiểm tra hành động
        if ($request->action === 'checkin') {
            if (!is_null($ve->so_ghe)) {
                return back()->withErrors(['message' => 'Bạn đã làm thủ tục rồi!']);
            }
            return redirect()->route('checkin.checkin-detail', ['ma_ve' => $ve->ma_ve]);
        }

        if ($request->action === 'cancel') {
            if (is_null($ve->so_ghe)) {
                return back()->withErrors(['message' => 'Bạn chưa làm thủ tục!']);
            }
            return redirect()->route('checkin.cancel-booking', ['ma_ve' => $ve->ma_ve]);
        }

        return back()->withErrors(['message' => 'Hành động không hợp lệ!']);
    }

    public function showCheckinDetail($ma_ve)
    {
        $ve = VeMayBay::where('ma_ve', $ma_ve)->with('chuyenBay')->first();

        if (!$ve) {
            return redirect()->route('checkin.form')->withErrors(['message' => 'Vé không tồn tại!']);
        }

        return view('checkins.checkin-detail', compact('ve')); // Điều hướng đến giao diện checkin-detail
    }

    // Hiển thị form chọn ghế
    public function selectSeatForm($ma_ve)
    {
        $ve = VeMayBay::where('ma_ve', $ma_ve)->with('chuyenBay')->first();

        if (!$ve) {
            return redirect()->route('checkin.form')->withErrors(['message' => 'Vé không tồn tại!']);
        }

        $da_chon = VeMayBay::where('id_chuyen_bay', $ve->chuyenBay->id_chuyen_bay)->pluck('so_ghe')->toArray();
        
        return view('checkins.select-seat', compact('ve', 'da_chon'));
    }

    // Xác nhận chọn ghế
    public function confirmSeat(Request $request, $ma_ve)
    {
        $request->validate([
            'so_ghe' => 'required|string',
        ]);

        $ve = VeMayBay::where('ma_ve', $ma_ve)->first();
        
        if (!$ve) {
            return redirect()->route('checkin.form')->withErrors(['message' => 'Vé không tồn tại!']);
        }

        $ve->so_ghe = $request->so_ghe;
        $ve->save();

        return redirect()->route('checkin.baggage', ['ma_ve' => $ve->ma_ve]);

    }

    // Hiển thị trang thêm dịch vụ
    public function baggage($ma_ve)
    {
        $ve = VeMayBay::where('ma_ve', $ma_ve)->first();
        if (!$ve) {
            return redirect()->route('checkin.form')->withErrors(['message' => 'Vé không tồn tại!']);
        }
        return view('checkins.baggage', compact('ve'));
    }


    // Hiển thị trang xác nhận check-in
    public function checkinSuccess($ma_ve)
    {
        $ve = VeMayBay::where('ma_ve', $ma_ve)->with('chuyenBay')->first();

        if (!$ve) {
            return redirect()->route('checkin.form')->withErrors(['message' => 'Vé không tồn tại!']);
        }

        return view('checkins.success', compact('ve'));
    }

    // Hiển thị thẻ lên máy bay
    public function showBoardingPass($ma_ve)
    {
        $ve = VeMayBay::where('ma_ve', $ma_ve)->with('chuyenBay')->firstOrFail();
        return view('checkins.boarding-pass', compact('ve'));
    }

    public function cancelBooking($ma_ve)
    {
        $ve = VeMayBay::where('ma_ve', $ma_ve)->first();

        if (!$ve) {
            return redirect()->route('checkin.form')->withErrors(['message' => 'Vé không tồn tại!']);
        }

        return view('checkins.cancel-booking', compact('ve'));
    }

    // Xử lý việc hủy đặt chỗ
    public function processCancelBooking(Request $request, $ma_ve)
    {
        $ve = VeMayBay::where('ma_ve', $ma_ve)->first();

        if (!$ve) {
            return redirect()->route('checkin.form')->withErrors(['message' => 'Vé không tồn tại!']);
        }

        // Hủy đặt chỗ nhưng giữ lại vé
        $ve->so_ghe = null;
        $ve->save();

        return redirect()->route('checkin.form')->with('success', 'Bạn đã hủy đặt chỗ thành công.');
    }


}
