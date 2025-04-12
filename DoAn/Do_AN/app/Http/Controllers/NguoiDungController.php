<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class NguoiDungController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
         // Lọc danh sách, chỉ lấy người dùng không phải admin
        $nguoiDung = NguoiDung::where('role', '!=', 'admin')->get();
        return view('admin.nguoi_dung.index', compact('nguoiDung','user'));
    }

    public function create()
    {
         $user = Auth::user();
        return view('admin.nguoi_dung.create', compact('user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        try {
            $validatedData = $request->validate([
                'ho_ten' => 'required|string|max:100',
                'email' => 'required|email|unique:nguoi_dung,email',
                'password' => 'required|min:6',
                'so_dien_thoai' => 'nullable|string|max:15'
            ]);

            NguoiDung::create([
                'ho_ten' => $validatedData['ho_ten'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'so_dien_thoai' => $validatedData['so_dien_thoai'] ?? null,
                'ngay_tao' => now(),
                'role' => 'user',
            ]);

            return redirect()->route('admin.nguoi_dung.index')->with('success', 'Người dùng đã được thêm.');
        } catch (\Exception $e) {
            return redirect()->route('admin.nguoi_dung.create')->with('error', 'Có lỗi xảy ra, vui lòng thử lại!');
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        $nguoiDung = NguoiDung::findOrFail($id);
        return view('admin.nguoi_dung.edit', compact('nguoiDung','user'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
    $request->validate([
        'ho_ten' => 'required|string|max:255',
        'email' => 'required|email|unique:nguoi_dung,email,'.$id.',id_nguoi_dung',
        'so_dien_thoai' => 'required|digits:10',
    ]);
   
    $nguoiDung = NguoiDung::findOrFail($id);
    $nguoiDung->ho_ten = $request->ho_ten;
    $nguoiDung->email = $request->email;
    $nguoiDung->so_dien_thoai = $request->so_dien_thoai;
    $nguoiDung->save();

    return redirect()->route('admin.nguoi_dung.index')->with('success', 'Cập nhật thành công!');
    }

    public function resetPassword($id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);

        // Tạo mật khẩu ngẫu nhiên
        $newPassword = substr(md5(uniqid()), 0, 8);

        // Cập nhật mật khẩu trong database (mã hóa với bcrypt)
        $nguoiDung->password = Hash::make($newPassword);
        $nguoiDung->save();

        // Gửi email mật khẩu mới
        Mail::to($nguoiDung->email)->send(new ResetPasswordMail($nguoiDung->ho_ten, $newPassword));

        return redirect()->route('admin.nguoi_dung.index')->with('success', 'Mật khẩu đã được reset và gửi đến email của người dùng.');
    }

    public function block($id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);
        $nguoiDung->blocked_until = now()->addHours(24); // Chặn trong 24h
        $nguoiDung->save();

        return redirect()->back()->with('success', 'Người dùng đã bị chặn trong 24 giờ.');
    }

    public function unblock($id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);
        $nguoiDung->blocked_until = NULL; // Hủy chặn người dùng
        $nguoiDung->save();

        return redirect()->back()->with('success', 'Người dùng đã được bỏ chặn.');
    }


    public function showTickets()
    {
        $nguoidung = Auth::user();

        $veMayBay = \App\Models\VeMayBay::where('id_nguoi_dung', $nguoidung->id_nguoi_dung)
                        ->where('trang_thai', 'da_thanh_toan')
                        ->get();

        return view('users.tickets', compact('nguoidung', 'veMayBay'));
    }


}

