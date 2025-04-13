<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChuyenBay;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class FlightController extends Controller
{
    /**
     * Show the flight search form
     */
    public function showSearchForm()
    {
        // Get distinct locations for dropdown menus
        $diemDi = ChuyenBay::distinct()->pluck('diem_di');
        $diemDen = ChuyenBay::distinct()->pluck('diem_den');
        $flights = new LengthAwarePaginator([], 0, 12);
        return view('flights.search', compact('diemDi', 'diemDen', 'flights'));
    }

    /**
     * Search for flights based on criteria
     */
    public function searchFlights(Request $request)
    {
        $request->validate([
            'diem_di' => 'required|string',
            'diem_den' => 'required|string',
            'ngay_di' => 'required|date',
            'so_hanh_khach' => 'required|integer|min:1'
        ]);

        $ngayDi = Carbon::parse($request->ngay_di)->toDateString();

        // Use paginate instead of get() to enable pagination methods
        $flights = ChuyenBay::where('diem_di', $request->diem_di)
            ->where('diem_den', $request->diem_den)
            ->whereDate('ngay_gio_khoi_hanh', $ngayDi)
            ->where('so_ghe_trong', '>=', $request->so_hanh_khach)
            ->with('hangBay')
            ->paginate(12); // <-- Use paginate() instead of get()

        session([
            'search_params' => [
                'diem_di' => $request->diem_di,
                'diem_den' => $request->diem_den,
                'ngay_di' => $ngayDi,
                'so_hanh_khach' => $request->so_hanh_khach
            ]
        ]);

        return view('flights.results', compact('flights'));
    }


}