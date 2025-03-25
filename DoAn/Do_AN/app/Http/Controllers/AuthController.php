<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\NguoiDung;
use App\Models\DiscountCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Mail\DiscountMail;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('vietnam-airlines'); // Chuyển hướng đến trang chính
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            // Validate dữ liệu
            $validatedData = $request->validate([
                'ho_ten' => 'required|string|max:100',
                'email' => 'required|email|unique:nguoi_dung,email',
                'password' => 'required|min:6',
                'so_dien_thoai' => 'nullable|string|max:15'
            ]);

            // Dùng transaction để đảm bảo dữ liệu đồng bộ
            return DB::transaction(function () use ($validatedData) {
                // Tạo user
                $user = NguoiDung::create([
                    'ho_ten' => $validatedData['ho_ten'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                    'so_dien_thoai' => $validatedData['so_dien_thoai'] ?? null,
                    'ngay_tao' => now(),
                    'role' => 'user',
                    'first_time_discount' => 1,
                    'blocked_until' => null, // Đảm bảo người dùng mới không bị chặn
                ]);

                // Tạo mã giảm giá
                $discountCode = 'FIRST10-' . strtoupper(uniqid());
                DiscountCode::create([
                    'user_id' => $user->id_nguoi_dung,
                    'code' => $discountCode,
                ]);

                // Gửi email qua Gmail
                try {
                    Log::info('Gửi email tới: ' . $user->email);
                    Mail::to($user->email)->send(new DiscountMail($discountCode));
                    Log::info('Email gửi thành công qua Gmail!');
                } catch (\Exception $e) {
                    Log::error('Lỗi gửi email: ' . $e->getMessage());
                    throw new \Exception('Không thể gửi email: ' . $e->getMessage());
                }

                // Đăng nhập user
                Auth::login($user);

                return response()->json([
                    'success' => true,
                    'message' => 'Đăng ký thành công! Mã giảm giá đã được gửi qua email.'
                ]);
            });
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('vietnam-airlines'); // Chuyển hướng đến trang chính
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = NguoiDung::where('email', $validatedData['email'])->first();
            
            if ($user && $user->blocked_until && now()->lessThan($user->blocked_until)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản của bạn đang bị khóa, vui lòng thử lại sau.'
                ]);
            }

            if (!$user || !Hash::check($validatedData['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thông tin đăng nhập không chính xác'
                ]);
            }

            Auth::login($user);
            
            // Clear any existing session data
            session()->forget(['error', 'warning']);
            
            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công!',
                'redirect' => route('vietnam-airlines')
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại!'
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }
}
