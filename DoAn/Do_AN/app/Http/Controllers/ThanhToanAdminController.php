<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ThanhToan;
use App\Models\VeMayBay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class ThanhToanAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $payments = ThanhToan::with(['veMayBay' => function($query) {
            $query->with(['chuyenBay', 'nguoiDung']);
        }])
        ->orderBy('ngay_thanh_toan', 'desc')
        ->paginate(10);

        return view('admin.thanh-toan.index', compact('payments','user'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $payment = ThanhToan::with(['veMayBay' => function($query) {
            $query->with(['chuyenBay', 'nguoiDung']);
        }])->findOrFail($id);

        return view('admin.thanh-toan.edit', compact('payment','user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'phuong_thuc' => 'required|in:momo,tiền mặt,chuyển khoản,thẻ tín dụng',
            'so_tien' => 'required|numeric|min:0',
            'trang_thai' => 'required|in:thanh_cong,chờ xử lý,đã thanh toán,thất bại',
            'ma_giao_dich' => 'nullable|string|max:255',
        ]);

        $payment = ThanhToan::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Cập nhật thông tin thanh toán
            $payment->update([
                'phuong_thuc' => $request->phuong_thuc,
                'so_tien' => $request->so_tien,
                'trang_thai' => $request->trang_thai,
                'ma_giao_dich' => $request->ma_giao_dich,
            ]);

            // Cập nhật trạng thái vé máy bay
            if ($request->trang_thai === 'đã thanh toán' || $request->trang_thai === 'thanh_cong') {
                VeMayBay::where('id_thanh_toan', $payment->id_thanh_toan)
                    ->update(['trang_thai' => 'đã thanh toán']);
            } elseif ($request->trang_thai === 'thất bại') {
                VeMayBay::where('id_thanh_toan', $payment->id_thanh_toan)
                    ->update(['trang_thai' => 'chờ thanh toán']);
            }

            DB::commit();
            return redirect()->route('admin.thanh-toan.index')
                ->with('success', 'Cập nhật thông tin thanh toán thành công');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật thanh toán: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {   
        $user = Auth::user();
        $payment = ThanhToan::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Cập nhật các vé liên quan về trạng thái chờ thanh toán
            VeMayBay::where('id_thanh_toan', $payment->id_thanh_toan)
                ->update([
                    'trang_thai' => 'chờ thanh toán',
                    'id_thanh_toan' => null
                ]);

            $payment->delete();
            
            DB::commit();
            return redirect()->route('admin.thanh-toan.index', compact('user'))
                ->with('success', 'Xóa thanh toán thành công');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra khi xóa thanh toán');
        }
    }
} 