<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChuyenBay;
use App\Models\NguoiDung;
use App\Models\VeMayBay;
use App\Models\ThanhToan;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Process payment and create booking
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string'
        ]);

        // Get booking details from session
        $bookingDetails = session('booking_details');
        if (!$bookingDetails) {
            return redirect()->route('flights.search')
                ->with('error', 'Booking session expired. Please start again.');
        }

        try {
            // Start database transaction
            DB::beginTransaction();

            // Get the flight
            $flight = ChuyenBay::findOrFail($bookingDetails['flight_id']);

            // Check if there are enough seats available
            if ($flight->so_ghe_trong < $bookingDetails['num_passengers']) {
                throw new \Exception('Not enough seats available');
            }

            // Update available seats
            $flight->so_ghe_trong -= $bookingDetails['num_passengers'];
            $flight->save();

            // Process passengers
            $tickets = [];
            foreach ($bookingDetails['passengers'] as $passenger) {
                // Create or find user
                $user = NguoiDung::firstOrCreate(
                    ['email' => $passenger['email']],
                    [
                        'ho_ten' => $passenger['name'],
                        'so_dien_thoai' => $passenger['phone'],
                        'mat_khau' => bcrypt(Str::random(10)) // Generate random password
                    ]
                );

                // Create ticket
                $ticket = VeMayBay::create([
                    'id_nguoi_dung' => $user->id_nguoi_dung,
                    'id_chuyen_bay' => $flight->id_chuyen_bay,
                    'ma_ve' => 'TK' . strtoupper(Str::random(8)),
                    'loai_ghe' => $bookingDetails['seat_type'],
                    'gia_ve' => $bookingDetails['price_per_seat'],
                    'ngay_dat' => Carbon::now(),
                    'trang_thai' => 'da_thanh_toan'
                ]);

                // Create payment record
                ThanhToan::create([
                    'id_ve' => $ticket->id_ve,
                    'phuong_thuc' => $request->payment_method,
                    'so_tien' => $bookingDetails['price_per_seat'],
                    'ngay_thanh_toan' => Carbon::now(),
                    'trang_thai' => 'thanh_cong'
                ]);

                $tickets[] = $ticket;
            }

            // Commit the transaction
            DB::commit();

            // Clear booking session
            session()->forget('booking_details');

            // Store the tickets in session for confirmation page
            session(['tickets' => $tickets]);

            // Redirect to confirmation page with the first ticket ID
            return redirect()->route('booking.confirmation', ['id' => $tickets[0]->id_ve]);
        } catch (\Exception $e) {
            // Roll back the transaction if something goes wrong
            DB::rollBack();

            return redirect()->back()->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Show the booking confirmation page
     */
    public function showConfirmation($id)
    {
        $ticket = VeMayBay::with(['chuyenBay', 'nguoiDung', 'thanhToan'])->findOrFail($id);
        $tickets = session('tickets', []);

        return view('booking.confirmation', compact('ticket', 'tickets'));
    }
}
