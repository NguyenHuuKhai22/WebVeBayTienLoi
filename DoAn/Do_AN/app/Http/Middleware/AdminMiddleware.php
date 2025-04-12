<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NguoiDung;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NguoiDung;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var NguoiDung|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập để truy cập!');
        }

        if (!$user->isAdmin()) {
            Auth::logout(); // Đăng xuất nếu không phải admin
            return redirect()->route('admin.login')->with('error', 'Bạn không có quyền truy cập khu vực admin!');
        }

        return $next($request);
    }
}
