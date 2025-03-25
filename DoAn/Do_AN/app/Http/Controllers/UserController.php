<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\NguoiDung;
use App\Models\VeMayBay;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function show($id)
    {
        $nguoidung = NguoiDung::findOrFail($id);
        $flights = VeMayBay::where('id_nguoi_dung', $id)->get(); // Lấy danh sách vé của user

        return view('users.user', compact('nguoidung', 'flights')); // Đảm bảo biến flights được truyền vào view
    }

    public function edit($id)
    {
        $nguoidung = NguoiDung::findOrFail($id);
        return view('users.update_user', compact('nguoidung'));
    }

    public function update(Request $request, $id)
    {
        $nguoidung = NguoiDung::findOrFail($id);

        $request->validate([
            'ho_ten' => 'required|string|max:100',
            'so_dien_thoai' => 'nullable|string|max:15',
        ]);

        $nguoidung->update([
            'ho_ten' => $request->ho_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
        ]);

        return redirect()->route('nguoidung.show', $id)->with('success', 'Cập nhật thành công!');
    }
    public function updatePassword(Request $request, $id)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', // Phải nhập lại new_password_confirmation
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        // Tìm người dùng theo ID
        $nguoidung = NguoiDung::findOrFail($id);

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $nguoidung->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        // Chỉ cập nhật mật khẩu, không chạm vào blocked_until hay failed_attempts
        $nguoidung->password = Hash::make($request->new_password);
        $nguoidung->blocked_until = null; // Hủy chặn người dùng
        $nguoidung->save();

        // Trả về thông báo thành công
        return redirect()->route('nguoidung.show', $id)->with('success', 'Đổi mật khẩu thành công!');
    }
}