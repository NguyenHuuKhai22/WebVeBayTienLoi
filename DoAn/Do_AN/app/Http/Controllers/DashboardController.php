<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use App\Models\ChuyenBay;
use App\Models\VeMayBay;
use App\Models\ThanhToan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Thống kê tổng quan
        $totalUsers = NguoiDung::count();
        $totalFlightsToday = ChuyenBay::whereDate('ngay_gio_khoi_hanh', Carbon::today())->count();
        $totalTickets = VeMayBay::count();
        $totalRevenue = ThanhToan::where('trang_thai', 'thanh_cong')->sum('so_tien');

        // Thống kê % tăng trưởng
        $lastMonthRevenue = ThanhToan::where('trang_thai', 'thanh_cong')
            ->whereMonth('ngay_thanh_toan', Carbon::now()->subMonth()->month)
            ->sum('so_tien');
        $thisMonthRevenue = ThanhToan::where('trang_thai', 'thanh_cong')
            ->whereMonth('ngay_thanh_toan', Carbon::now()->month)
            ->sum('so_tien');
        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        // Doanh thu theo tháng
        $monthlyRevenue = ThanhToan::where('trang_thai', 'thanh_cong')
            ->whereYear('ngay_thanh_toan', Carbon::now()->year)
            ->select(DB::raw('MONTH(ngay_thanh_toan) as month'), DB::raw('SUM(so_tien) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Điền đầy đủ 12 tháng với giá trị 0 nếu không có dữ liệu
        $revenueData = array_replace(array_fill(1, 12, 0), $monthlyRevenue);

        // Phân bố vé theo tuyến bay
        $ticketDistribution = DB::table('ve_may_bay')
            ->join('chuyen_bay', 've_may_bay.id_chuyen_bay', '=', 'chuyen_bay.id_chuyen_bay')
            ->select(
                DB::raw("CONCAT(chuyen_bay.diem_di, ' - ', chuyen_bay.diem_den) as route"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('route')
            ->orderByDesc('total')
            ->get();

        // Đặt vé gần đây
        $recentBookings = VeMayBay::with(['nguoiDung', 'chuyenBay', 'thanhToan'])
            ->whereHas('thanhToan')
            ->latest('ngay_dat')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'totalUsers',
            'totalFlightsToday',
            'totalTickets',
            'totalRevenue',
            'revenueGrowth',
            'revenueData',
            'ticketDistribution',
            'recentBookings'
        ));
    }
} 