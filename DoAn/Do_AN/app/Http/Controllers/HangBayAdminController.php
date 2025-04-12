<?php

namespace App\Http\Controllers;

use App\Models\HangBay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HangBayAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Lấy thông tin user
        $hangBays = HangBay::whereNull('deleted_at')->get(); // Chỉ lấy hãng bay chưa xóa
        return view('admin.hangbay.index', compact('hangBays', 'user'));
    }

    public function create()
    {
        $user = Auth::user(); // Lấy thông tin user
        return view('admin.hangbay.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_hang_bay' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only('ten_hang_bay');

        if ($request->hasFile('logo')) {
            $fileName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('uploads/logos'), $fileName);
            $data['logo'] = $fileName; // Lưu chỉ tên file vào database
        }

        HangBay::create($data);
        $user = Auth::user(); // Lấy thông tin user
        return redirect()->route('admin.hangbay.index')->with('success', 'Thêm hãng bay thành công!')->with(compact('user'));
    }
    public function edit($id_hang_bay)
    {
        $user = Auth::user();
        $hangBay = HangBay::where('id_hang_bay', $id_hang_bay)->firstOrFail();
        return view('admin.hangbay.edit', compact('hangBay', 'user'));
    }



    public function update(Request $request, $id_hang_bay)
    {
        $request->validate([
            'ten_hang_bay' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $hangBay = HangBay::where('id_hang_bay', $id_hang_bay)->firstOrFail();
        $hangBay->ten_hang_bay = $request->ten_hang_bay;

        if ($request->hasFile('logo')) {
            // Xóa logo cũ nếu có
            if ($hangBay->logo && file_exists(public_path('uploads/logos/' . $hangBay->logo))) {
                unlink(public_path('uploads/logos/' . $hangBay->logo));
            }

            // Upload logo mới
            $fileName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('uploads/logos'), $fileName);
            $hangBay->logo = $fileName;
        }

        $hangBay->save();

        return redirect()->route('admin.hangbay.index')->with('success', 'Cập nhật hãng bay thành công!');
    }
    public function destroy($id_hang_bay)
    {
        $hangBay = HangBay::findOrFail($id_hang_bay);
        $hangBay->delete(); // Xóa mềm (cập nhật deleted_at)

        return redirect()->route('admin.hangbay.index')->with('success', 'Xóa hãng bay thành công!');
    }
    public function deleteAt()
    {
        $user = Auth::user();
        $hangBays = HangBay::onlyTrashed()->get(); // Lấy danh sách hãng bay đã xóa mềm
        return view('admin.hangbay.deleted', compact('hangBays', 'user'));
    }
    public function restore($id)
    {
        $hangBay = HangBay::withTrashed()->findOrFail($id);
        $hangBay->restore(); // Khôi phục
        return redirect()->route('admin.hangbay.deleteAt')->with('success', 'Hãng bay đã được khôi phục.');
    }
}
