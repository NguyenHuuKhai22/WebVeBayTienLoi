<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\VeMayBay;
use App\Models\ChuyenBay;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VeMayBayAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tickets = VeMayBay::with(['chuyenBay', 'nguoiDung'])
            ->orderBy('ngay_dat', 'desc')
            ->paginate(10);
        return view('admin.ve-may-bay.index', compact('tickets', 'user'));
    }

    public function edit($id)
    {       
        $user = Auth::user();
        $ticket = VeMayBay::where('id_ve', $id)->firstOrFail();
        $flights = ChuyenBay::all();
        $users = NguoiDung::all();
        return view('admin.ve-may-bay.edit', compact('ticket', 'flights', 'users', 'user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_chuyen_bay' => 'required|exists:chuyen_bay,id_chuyen_bay',
            'id_nguoi_dung' => 'required|exists:nguoi_dung,id_nguoi_dung',
            'gia_ve' => 'required|numeric|min:0',
            'trang_thai' => 'required|in:chờ thanh toán,đã thanh toán,đã hủy',
            'loai_ghe' => 'required|in:Phổ thông,Thương gia',
            'khoi_luong_dang_ky' => 'nullable|numeric|min:0',
            'phi_hanh_ly' => 'nullable|numeric|min:0',
        ]);

        $ticket = VeMayBay::where('id_ve', $id)->firstOrFail();
        
        $ticket->update([
            'id_chuyen_bay' => $request->id_chuyen_bay,
            'id_nguoi_dung' => $request->id_nguoi_dung,
            'gia_ve' => $request->gia_ve,
            'trang_thai' => $request->trang_thai,
            'loai_ghe' => $request->loai_ghe,
            'khoi_luong_dang_ky' => $request->khoi_luong_dang_ky,
            'phi_hanh_ly' => $request->phi_hanh_ly,
        ]);

        return redirect()->route('admin.ve-may-bay.index')
            ->with('success', 'Cập nhật thông tin vé thành công');
    }

    public function show($id)
    {           
        $user = Auth::user();
        $ticket = VeMayBay::with(['chuyenBay', 'nguoiDung'])->where('id_ve', $id)->firstOrFail();
        return view('admin.ve-may-bay.show', compact('ticket', 'user'));
    }

    public function destroy($id)
    {
        $ticket = VeMayBay::where('id_ve', $id)->firstOrFail();
        $ticket->delete();

        return redirect()->route('admin.ve-may-bay.index')
            ->with('success', 'Xóa vé thành công');
    }
} 