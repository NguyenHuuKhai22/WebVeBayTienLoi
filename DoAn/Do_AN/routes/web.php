<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VietnamAirlinesController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChuyenBayAdminController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\NguoiDungController;
use App\Http\Controllers\SocialAuthController;
Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware; // Import middleware
use App\Http\Middleware\UserMiddleware; // Import middleware
use App\Http\Controllers\VNPayController;
use App\Http\Controllers\HangBayAdminController;
use App\Http\Controllers\ZaloPayController;
use App\Http\Controllers\MoMoPaymentController;
use App\Http\Controllers\DashboardController; // Add this line
use Illuminate\Support\Facades\Session;

// Áp dụng middleware trực tiếp bằng class
Route::get('locale/{locale}', function ($locale) {

    // Check if the passed locale is available in our configuration
    if (in_array($locale, array_values(config('app.available_locales')))) {

         // If valid, store the locale in the session
         Session::put('locale', $locale);
    }
    // Redirect back to the previous page
    return redirect()->back();
});

// Thêm route API cho chuyến bay

Route::get('/chuyenbay/random', [ChuyenBayAdminController::class, 'getRandom']);
// Thêm route DB cho chuyến bay
Route::get('/chuyenbay/random-db', [ChuyenBayAdminController::class, 'getRandomFromDB']);

Route::post('/payment/momo/create', [MoMoPaymentController::class, 'createPayment'])->name('momo.create-payment');
Route::get('/payment/momo/callback', [MoMoPaymentController::class, 'callback'])->name('momo.callback');
Route::post('/payment/momo/notify', [MoMoPaymentController::class, 'notify'])->name('momo.notify');


Route::post('/apply-discount-code', [BookingController::class, 'applyDiscountCode'])->name('apply.discount.code');
// Routes cho admin
Route::get('/admin/login', [AdminController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
// Áp dụng middleware trực tiếp bằng class

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware(AdminMiddleware::class)
    ->name('admin.dashboard');

Route::get('/admin/hangbay', [HangBayAdminController::class, 'index'])
    ->middleware(AdminMiddleware::class) // Sử dụng class thay vì tên 'admin'
    ->name('admin.hangbay.index');


    Route::get('/vietnam-airlines', [VietnamAirlinesController::class, 'index'])->middleware(UserMiddleware::class);
Route::get('/vietnam-airlines', [VietnamAirlinesController::class, 'index'])->middleware(UserMiddleware::class) // Sử dụng class thay vì tên 'admin'
->name('vietnam-airlines');

Route::prefix('admin')->group(function () {

    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
});



// Thanh toán VNPay
Route::post('/vnpay-payment', [VNPayController::class, 'createPayment'])->name('vnpay.create-payment');
Route::get('/vnpay-return', [VNPayController::class, 'vnpayReturn'])->name('vnpay.return');

Route::get('/vnpay/callback', [VNPayController::class, 'callback'])->name('vnpay.callback');
Route::post('/vnpay/notify', [VNPayController::class, 'notify'])->name('vnpay.notify');

//hãng bay
Route::prefix('admin')->group(function () {
    // Route::get('/hangbay', [HangBayAdminController::class, 'index'])->name('admin.hangbay.index');
    Route::get('/hangbay/create', [HangBayAdminController::class, 'create'])->name('admin.hangbay.create');
    Route::post('/hangbay', [HangBayAdminController::class, 'store'])->name('admin.hangbay.store');
    Route::get('/hangbay/{id_hang_bay}/edit', [HangBayAdminController::class, 'edit'])->name('admin.hangbay.edit');
    Route::put('/hangbay/{id_hang_bay}', [HangBayAdminController::class, 'update'])->name('admin.hangbay.update');
    Route::delete('/hangbay/{id_hang_bay}', [HangBayAdminController::class, 'destroy'])->name('admin.hangbay.destroy');
    Route::get('/hangbay-deleted', [HangBayAdminController::class, 'deleteAt'])->name('admin.hangbay.deleteAt');
    Route::put('/hangbay/{id}/restore', [HangBayAdminController::class, 'restore'])->name('admin.hangbay.restore');
});

//chuyenbay

Route::prefix('admin')->group(function () {
    Route::get('/chuyenbay', [ChuyenBayAdminController::class, 'index'])->name('admin.chuyenbay.index');
    Route::get('/chuyenbay/create', [ChuyenBayAdminController::class, 'create'])->name('admin.chuyenbay.create');
    Route::post('/chuyenbay', [ChuyenBayAdminController::class, 'store'])->name('admin.chuyenbay.store');
    Route::get('/chuyenbay/{flight}/edit', [ChuyenBayAdminController::class, 'edit'])->name('admin.chuyenbay.edit');
    Route::put('/chuyenbay/{flight}', [ChuyenBayAdminController::class, 'update'])->name('admin.chuyenbay.update');
    Route::delete('/chuyenbay/{flight}', [ChuyenBayAdminController::class, 'destroy'])->name('admin.chuyenbay.destroy');

    // Route cho xóa mềm
    Route::get('/chuyenbay/trashed', [ChuyenBayAdminController::class, 'trashed'])->name('admin.chuyenbay.trashed');
    Route::post('/chuyenbay/{id}/restore', [ChuyenBayAdminController::class, 'restore'])->name('admin.chuyenbay.restore');
    Route::delete('/chuyenbay/{id}/force-delete', [ChuyenBayAdminController::class, 'forceDelete'])->name('admin.chuyenbay.force-delete');
    
    
});
// User
use App\Http\Controllers\UserController;
Route::get('/nguoidung/{id}', [UserController::class, 'show'])->name('nguoidung.show');
Route::get('/nguoidung/{id}/edit', [UserController::class, 'edit'])->name('nguoidung.edit');
Route::post('/nguoidung/{id}/update', [UserController::class, 'update'])->name('nguoidung.update');
Route::post('/nguoidung/{id}/doi-mat-khau', [UserController::class, 'updatePassword'])->name('nguoidung.updatePassword');
Route::get('/user/tickets', [NguoiDungController::class, 'showTickets'])->name('user.tickets');
//nguoi_dung_admin

Route::prefix('admin')->group(function () {
    Route::get('/nguoi-dung', [NguoiDungController::class, 'index'])->name('admin.nguoi_dung.index');
    Route::get('/nguoi-dung/create', [NguoiDungController::class, 'create'])->name('admin.nguoi_dung.create'); // GET form
    Route::post('/nguoi-dung', [NguoiDungController::class, 'store'])->name('admin.nguoi_dung.store'); // POST lưu dữ liệu
    Route::get('/nguoi-dung/{id}/edit', [NguoiDungController::class, 'edit'])->name('admin.nguoi_dung.edit');
    Route::post('/nguoi-dung/{id}/update', [NguoiDungController::class, 'update'])->name('admin.nguoi_dung.update');
    Route::post('/nguoi_dung/{id}/reset_password', [NguoiDungController::class, 'resetPassword'])->name('admin.nguoi_dung.reset_password');
    Route::post('/nguoi_dung/block/{id}', [NguoiDungController::class, 'block'])->name('admin.nguoi_dung.block');
    Route::post('/nguoi-dung/{id}/unblock', [NguoiDungController::class, 'unblock'])->name('admin.nguoi_dung.unblock');

});



use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Flight search and results
Route::get('/search', [FlightController::class, 'showSearchForm'])->name('flights.search');
Route::post('/search-results', [FlightController::class, 'searchFlights'])->name('flights.results');

// Booking process
Route::get('/select-flight/{id}', [BookingController::class, 'selectFlight'])->name('booking.select');
Route::match(['get', 'post'],'/passenger-info', [BookingController::class, 'passengerInfo'])->name('booking.passenger');
Route::match(['get', 'post'], '/review-booking', [BookingController::class, 'reviewBooking'])->name('booking.review');

// Payment
Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/booking-confirmation/{id}', [PaymentController::class, 'showConfirmation'])->name('booking.confirmation');


use App\Http\Controllers\ChatController;
Route::post('/chat', [ChatController::class, 'chat']);

Route::get('/', function () {
    return redirect()->route('vietnam-airlines');
})->name('home');

Route::get('/booking/select-passengers/{flight_id}', [BookingController::class, 'selectPassengers'])->name('booking.select-passengers');
Route::post('/booking/process-passengers', [BookingController::class, 'processPassengers'])->name('booking.process-passengers');
Route::post('/booking/select-flight', [BookingController::class, 'selectFlight'])->name('booking.select-flight');
Route::post('/booking/calculate-baggage', [BookingController::class, 'calculateBaggagePrice'])->name('booking.calculate-baggage');


// Admin routes for ticket management
Route::prefix('admin')->group(function () {
    Route::get('/ve-may-bay', [App\Http\Controllers\VeMayBayAdminController::class, 'index'])
        ->name('admin.ve-may-bay.index');
    Route::get('/ve-may-bay/{id}/edit', [App\Http\Controllers\VeMayBayAdminController::class, 'edit'])
        ->name('admin.ve-may-bay.edit');
    Route::put('/ve-may-bay/{id}', [App\Http\Controllers\VeMayBayAdminController::class, 'update'])
        ->name('admin.ve-may-bay.update');
    Route::delete('/ve-may-bay/{id}', [App\Http\Controllers\VeMayBayAdminController::class, 'destroy'])
        ->name('admin.ve-may-bay.destroy');
});

// Admin routes for payment management
Route::prefix('admin')->group(function () {
    Route::get('/thanh-toan', [App\Http\Controllers\ThanhToanAdminController::class, 'index'])
        ->name('admin.thanh-toan.index');
        Route::get('/thanh-toan/{id}/edit', [App\Http\Controllers\ThanhToanAdminController::class, 'edit'])
        ->name('admin.thanh-toan.edit');
    Route::get('/thanh-toan/{id}', [App\Http\Controllers\ThanhToanAdminController::class, 'show'])
        ->name('admin.thanh-toan.show');
    Route::put('/thanh-toan/{id}', [App\Http\Controllers\ThanhToanAdminController::class, 'update'])
        ->name('admin.thanh-toan.update');
    Route::delete('/thanh-toan/{id}', [App\Http\Controllers\ThanhToanAdminController::class, 'destroy'])
        ->name('admin.thanh-toan.destroy');
});

// Admin routes for promotion management
Route::prefix('admin')->group(function () {
    Route::get('/khuyen-mai', [App\Http\Controllers\Admin\PromotionController::class, 'index'])
        ->name('admin.promotions.index');
    Route::get('/khuyen-mai/create', [App\Http\Controllers\Admin\PromotionController::class, 'create'])
        ->name('admin.promotions.create');
    Route::post('/khuyen-mai', [App\Http\Controllers\Admin\PromotionController::class, 'store'])
        ->name('admin.promotions.store');
    Route::get('/khuyen-mai/{promotion}/edit', [App\Http\Controllers\Admin\PromotionController::class, 'edit'])
        ->name('admin.promotions.edit');
    Route::put('/khuyen-mai/{promotion}', [App\Http\Controllers\Admin\PromotionController::class, 'update'])
        ->name('admin.promotions.update');
    Route::delete('/khuyen-mai/{promotion}', [App\Http\Controllers\Admin\PromotionController::class, 'destroy'])
        ->name('admin.promotions.destroy');
    Route::post('/khuyen-mai/{promotion}/toggle-status', [App\Http\Controllers\Admin\PromotionController::class, 'toggleStatus'])
        ->name('admin.promotions.toggle-status');
});


// Check-in Routes
Route::get('/checkin', [CheckinController::class, 'showForm'])->name('checkin.form');
Route::post('/checkin', [CheckinController::class, 'processCheckin'])->name('checkin.process');
Route::get('/checkin-detail/{ma_ve}', [CheckinController::class, 'showCheckinDetail'])->name('checkin.checkin-detail');
Route::get('/checkin/select-seat/{ma_ve}', [CheckinController::class, 'selectSeatForm'])->name('checkin.select-seat');
Route::post('/checkin/select-seat/{ma_ve}', [CheckinController::class, 'confirmSeat'])->name('checkin.confirm-seat');
Route::get('/checkin/baggage/{ma_ve}', [CheckinController::class, 'baggage'])->name('checkin.baggage');

Route::get('/checkins/baggage', [CheckinController::class, 'baggage'])->name('checkins.baggage');
Route::get('/checkin/success/{ma_ve}', [CheckinController::class, 'checkinSuccess'])->name('checkin.success');
Route::get('/boarding-pass/{ma_ve}', [CheckinController::class, 'showBoardingPass'])->name('boarding.pass');

// Route cho trang hủy đặt chỗ
Route::get('/checkin/cancel-booking/{ma_ve}', [CheckinController::class, 'cancelBooking'])->name('checkin.cancel-booking');

// Route xử lý hủy đặt chỗ
Route::post('/checkin/cancel-booking/{ma_ve}', [CheckinController::class, 'processCancelBooking'])->name('checkin.cancel-booking.process');

// Social Login Routes
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);


Use App\Http\Controllers\PolicyController;
// Routes cho chính sách
Route::get('/privacy-policy', [PolicyController::class, 'privacy'])->name('privacy.policy');
Route::get('/terms-of-service', [PolicyController::class, 'terms'])->name('terms.service');