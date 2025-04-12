<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\ChuyenBay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class PromotionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $promotions = Promotion::with('chuyenBays')->latest()->get();
        return view('admin.promotions.index', compact('promotions', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        $flights = ChuyenBay::where('so_ghe_trong', '>', 0)->get();
        return view('admin.promotions.create', compact('flights', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_khuyen_mai' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'phan_tram_giam' => 'required|numeric|min:0|max:100',
            'thoi_gian_bat_dau' => 'required|date',
            'thoi_gian_ket_thuc' => 'required|date|after:thoi_gian_bat_dau',
            'chuyen_bay_ids' => 'required|array',
            'chuyen_bay_ids.*' => 'exists:chuyen_bay,id_chuyen_bay'
        ]);

        DB::beginTransaction();
        try {
            $promotion = Promotion::create([
                'ten_khuyen_mai' => $request->ten_khuyen_mai,
                'mo_ta' => $request->mo_ta,
                'phan_tram_giam' => $request->phan_tram_giam,
                'thoi_gian_bat_dau' => Carbon::parse($request->thoi_gian_bat_dau),
                'thoi_gian_ket_thuc' => Carbon::parse($request->thoi_gian_ket_thuc),
                'trang_thai' => true
            ]);

            $promotion->chuyenBays()->attach($request->chuyen_bay_ids);
            DB::commit();

            return redirect()->route('admin.promotions.index')
                ->with('success', 'Khuyến mãi đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi tạo khuyến mãi: ' . $e->getMessage());
        }
    }

    public function edit(Promotion $promotion)
    {
        $user = Auth::user();
        $flights = ChuyenBay::where('so_ghe_trong', '>', 0)->get();
        $selectedFlights = $promotion->chuyenBays->pluck('id_chuyen_bay')->toArray();
        return view('admin.promotions.edit', compact('promotion', 'flights', 'selectedFlights', 'user'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'ten_khuyen_mai' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'phan_tram_giam' => 'required|numeric|min:0|max:100',
            'thoi_gian_bat_dau' => 'required|date',
            'thoi_gian_ket_thuc' => 'required|date|after:thoi_gian_bat_dau',
            'chuyen_bay_ids' => 'required|array',
            'chuyen_bay_ids.*' => 'exists:chuyen_bay,id_chuyen_bay'
        ]);

        DB::beginTransaction();
        try {
            $promotion->update([
                'ten_khuyen_mai' => $request->ten_khuyen_mai,
                'mo_ta' => $request->mo_ta,
                'phan_tram_giam' => $request->phan_tram_giam,
                'thoi_gian_bat_dau' => Carbon::parse($request->thoi_gian_bat_dau),
                'thoi_gian_ket_thuc' => Carbon::parse($request->thoi_gian_ket_thuc),
                'trang_thai' => $request->has('trang_thai')
            ]);

            $promotion->chuyenBays()->sync($request->chuyen_bay_ids);
            DB::commit();

            return redirect()->route('admin.promotions.index')
                ->with('success', 'Khuyến mãi đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật khuyến mãi: ' . $e->getMessage());
        }
    }

    public function destroy(Promotion $promotion)
    {
        try {
            $promotion->delete();
            return redirect()->route('admin.promotions.index')
                ->with('success', 'Khuyến mãi đã được xóa thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xóa khuyến mãi: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Promotion $promotion)
    {
        try {
            $promotion->update(['trang_thai' => !$promotion->trang_thai]);
            return redirect()->route('admin.promotions.index')
                ->with('success', 'Trạng thái khuyến mãi đã được cập nhật.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái: ' . $e->getMessage());
        }
    }
} 