<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    // Hiển thị form đăng nhập admin
    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    // Xử lý đăng nhập admin
    public function adminLogin(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            $credentials = $request->only('email', 'password');
    
            if (Auth::attempt($credentials)) {
                 /** @var \App\Models\NguoiDung $user */
                $user = Auth::user();
    
                // Kiểm tra role trước khi cho phép vào admin
                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập admin thành công!');
                } else {
                    Auth::logout();
                    return redirect()->route('admin.login')->with('error', 'Bạn không có quyền truy cập khu vực admin!');
                }
            }
    
            return redirect()->route('admin.login')->with('error', 'Thông tin đăng nhập không chính xác!');
        } catch (ValidationException $e) {
            return redirect()->route('admin.login')->withErrors($e->errors())->with('error', 'Dữ liệu không hợp lệ!');
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->with('error', 'Có lỗi xảy ra, vui lòng thử lại!');
        }
    }
    

    // Hiển thị dashboard admin
    // Dashboard cho admin
    public function dashboard()
    {
        /** @var \App\Models\NguoiDung $user */
        $user = Auth::user();
        $usersCount = \App\Models\NguoiDung::count(); // Ví dụ: đếm số người dùng
        return view('admin.dashboard', compact('user', 'usersCount'));
    }
    //hãng bay
   
    // Đăng xuất admin
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'Đăng xuất thành công!');
    }
}