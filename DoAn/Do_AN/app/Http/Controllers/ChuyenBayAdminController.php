<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChuyenBay;
use App\Models\HangBay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChuyenBayAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Lấy thông tin user
        $flights = ChuyenBay::with('hangBay')->paginate(10);
        return view('admin.chuyenbay.index', compact('flights','user')); 
    }

    public function create()
    {
        $user = Auth::user(); // Lấy thông tin user
        $airlines = HangBay::all();
        return view('admin.chuyenbay.create', compact('airlines','user')); 
    }

    public function store(Request $request)
    {
        // Bắt đầu transaction ngay từ đầu
        DB::beginTransaction();
        
        try {
            // Thêm validation messages tùy chỉnh
        $messages = [
            'ma_chuyen_bay.required' => 'Mã chuyến bay không được để trống',
            'ma_chuyen_bay.unique' => 'Mã chuyến bay đã tồn tại',
            'diem_di.required' => 'Điểm đi không được để trống',
            'diem_den.required' => 'Điểm đến không được để trống',
            'ngay_gio_khoi_hanh.required' => 'Thời gian khởi hành không được để trống',
            'ngay_gio_khoi_hanh.date' => 'Thời gian khởi hành không hợp lệ',
            'ngay_gio_den.required' => 'Thời gian đến không được để trống',
            'ngay_gio_den.date' => 'Thời gian đến không hợp lệ',
            'ngay_gio_den.after' => 'Thời gian đến phải sau thời gian khởi hành',
            'gia_ve_co_ban.required' => 'Giá vé không được để trống',
            'gia_ve_co_ban.numeric' => 'Giá vé phải là số',
            'gia_ve_co_ban.min' => 'Giá vé phải lớn hơn 0',
            'so_ghe_trong.required' => 'Số ghế trống không được để trống',
            'so_ghe_trong.integer' => 'Số ghế trống phải là số nguyên',
            'so_ghe_trong.min' => 'Số ghế trống phải lớn hơn 0',
            'id_hang_bay.required' => 'Vui lòng chọn hãng bay',
            'id_hang_bay.exists' => 'Hãng bay không tồn tại'
        ];

        $validated = $request->validate([
            'ma_chuyen_bay' => 'required|unique:Chuyen_Bay',
            'diem_di' => 'required',
            'diem_den' => 'required',
            'ngay_gio_khoi_hanh' => 'required|date',
            'ngay_gio_den' => 'required|date|after:ngay_gio_khoi_hanh',
            'gia_ve_co_ban' => 'required|numeric|min:0',
            'so_ghe_trong' => 'required|integer|min:0',
            'id_hang_bay' => 'required|exists:Hang_Bay,id_hang_bay'
        ], $messages);
    
            // Tạo chuyến bay
            $chuyenBay = ChuyenBay::create($validated);
    
            // Kiểm tra nếu là request test
            if ($request->has('is_testing')) {
                // Log để debug
                Log::info('Test request detected, rolling back transaction');
                
                // Đảm bảo rollback được gọi
                DB::rollBack();
                
                return redirect()->route('admin.chuyenbay.index')
                    ->with('success', 'Test thêm chuyến bay thành công (Đã rollback)');
            }
    
            // Nếu không phải test, commit transaction
            DB::commit();
            return redirect()->route('admin.chuyenbay.index')
                ->with('success', 'Thêm chuyến bay thành công');
    
        } catch (\Exception $e) {
            // Log lỗi để debug
            Log::error('Error in store method: ' . $e->getMessage());
            
            // Đảm bảo rollback khi có lỗi
            DB::rollBack();
            
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(ChuyenBay $flight)
    {
        $user = Auth::user(); // Lấy thông tin user
        $airlines = HangBay::all();
        return view('admin.chuyenbay.edit', compact('flight', 'airlines','user')); // Đổi thành admin.chuyenbay.edit
    }

    public function update(Request $request, ChuyenBay $flight)
    {
        $validated = $request->validate([
            'ma_chuyen_bay' => 'required|unique:Chuyen_Bay,ma_chuyen_bay,' . $flight->id_chuyen_bay . ',id_chuyen_bay',
            'diem_di' => 'required',
            'diem_den' => 'required',
            'ngay_gio_khoi_hanh' => 'required|date',
            'ngay_gio_den' => 'required|date|after:ngay_gio_khoi_hanh',
            'gia_ve_co_ban' => 'required|numeric|min:0',
            'so_ghe_trong' => 'required|integer|min:0',
            'id_hang_bay' => 'required|exists:Hang_Bay,id_hang_bay'
        ]);

        $flight->update($validated);
        return redirect()->route('admin.chuyenbay.index') // Sửa route redirect
            ->with('success', 'Cập nhật chuyến bay thành công');
    }

    public function destroy(ChuyenBay $flight)
    {
        $user = Auth::user(); // Lấy thông tin user
        $flight->delete(); // Sử dụng soft delete thay vì xóa cứng
        return redirect()->route('admin.chuyenbay.index')
            ->with('success', 'Đã xóa mềm chuyến bay thành công');
    }

    // (Tùy chọn) Thêm phương thức để xem danh sách đã xóa mềm
    public function trashed()
    { $user = Auth::user(); // Lấy thông tin user
        $flights = ChuyenBay::onlyTrashed()->with('hangBay')->paginate(10); // Lấy các bản ghi đã xóa mềm
        return view('admin.chuyenbay.trashed', compact('flights','user'));
    }

    // (Tùy chọn) Khôi phục bản ghi đã xóa mềm
    public function restore($id)
    {
        $user = Auth::user(); // Lấy thông tin user
        $flight = ChuyenBay::withTrashed()->findOrFail($id);
        $flight->restore();
        return redirect()->route('admin.chuyenbay.trashed')
            ->with('success', 'Đã khôi phục chuyến bay thành công');
    }
    public function getRandom() {
        // Trả về dữ liệu mẫu ngẫu nhiên
        return response()->json([
            'diem_di' => 'Hà Nội',
            'diem_den' => 'Hồ Chí Minh',
            'gia_ve_co_ban' => rand(1000000, 2000000),
            'so_ghe_trong' => rand(100, 200),
            'id_hang_bay' => 1
        ]);
    }
    // Trong ChuyenBayAdminController.php
public function getRandomFromDB()
{
    $randomFlight = ChuyenBay::inRandomOrder()->first();
    if ($randomFlight) {
        return response()->json([
            'diem_di' => $randomFlight->diem_di,
            'diem_den' => $randomFlight->diem_den,
            'gia_ve_co_ban' => (string)intval($randomFlight->gia_ve_co_ban), // Chuyển về số nguyên
            'so_ghe_trong' => (int)$randomFlight->so_ghe_trong, // Đảm bảo là số nguyên
            'id_hang_bay' => $randomFlight->id_hang_bay
        ]);
    }
    return response()->json([
        'diem_di' => 'Hà Nội',
        'diem_den' => 'Hồ Chí Minh',
        'gia_ve_co_ban' => rand(1000000, 2000000),
        'so_ghe_trong' => rand(100, 200),
        'id_hang_bay' => 1
    ]);
}
}
