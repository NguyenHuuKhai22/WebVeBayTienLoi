@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Statistics Cards Row -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                        <span class="badge bg-success">Tổng</span>
                    </div>
                    <h3 class="counter mb-1" data-target="{{ $totalUsers }}">0</h3>
                    <p class="text-muted mb-0">Tổng số người dùng</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fas fa-plane-departure fa-2x text-success"></i>
                        </div>
                        <span class="badge bg-info">Hôm nay</span>
                    </div>
                    <h3 class="counter mb-1" data-target="{{ $totalFlightsToday }}">0</h3>
                    <p class="text-muted mb-0">Chuyến bay hôm nay</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fas fa-ticket-alt fa-2x text-warning"></i>
                        </div>
                        <span class="badge bg-success">Tổng</span>
                    </div>
                    <h3 class="counter mb-1" data-target="{{ $totalTickets }}">0</h3>
                    <p class="text-muted mb-0">Vé đã bán</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="fas fa-dollar-sign fa-2x text-info"></i>
                        </div>
                        <span class="badge {{ $revenueGrowth >= 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ number_format($revenueGrowth, 1) }}%
                        </span>
                    </div>
                    <h3 class="counter mb-1" data-target="{{ $totalRevenue }}">0</h3>
                    <p class="text-muted mb-0">Doanh thu (VNĐ)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Revenue Chart -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0">Thống kê doanh thu</h5>
                </div>
                <div class="card-body">
                    <div id="revenue-data" data-revenue="{{ json_encode(array_values($revenueData)) }}"></div>
                    <canvas id="revenueChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <!-- Pie Chart -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0">Phân bố vé theo tuyến</h5>
                </div>
                <div class="card-body">
                    <div id="distribution-data" 
                         data-labels="{{ json_encode($ticketDistribution->pluck('route')) }}"
                         data-values="{{ json_encode($ticketDistribution->pluck('total')) }}">
                    </div>
                    <canvas id="distributionChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0">Đặt vé gần đây</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Khách hàng</th>
                                    <th>Chuyến bay</th>
                                    <th>Ngày thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th>Giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                <tr class="align-middle">
                                    <td>#{{ $booking->id_thanh_toan }}</td>
                                    <td>
                                        @if($booking->veMayBay->isNotEmpty() && $booking->veMayBay->first()->nguoiDung)
                                        <div class="d-flex align-items-center">
                                            @php
                                                $initials = collect(explode(' ', $booking->veMayBay->first()->nguoiDung->ho_ten))
                                                    ->map(function($word) { return substr($word, 0, 1); })
                                                    ->take(2)
                                                    ->join('');
                                            @endphp
                                            <div class="avatar-sm me-2 bg-primary text-white rounded-circle">
                                                {{ $initials }}
                                            </div>
                                            <div>{{ $booking->veMayBay->first()->nguoiDung->ho_ten }}</div>
                                        </div>
                                        @else
                                        <span class="text-muted">Không có thông tin</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->veMayBay->isNotEmpty() && $booking->veMayBay->first()->chuyenBay)
                                        {{ $booking->veMayBay->first()->chuyenBay->diem_di }} - {{ $booking->veMayBay->first()->chuyenBay->diem_den }}
                                        @else
                                        <span class="text-muted">Không có thông tin</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($booking->ngay_thanh_toan)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge {{ $booking->trang_thai === 'thanh_cong' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($booking->trang_thai) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ number_format($booking->so_tien, 0, ',', '.') }} VNĐ
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .stat-card {
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .counter {
        font-size: 2rem;
        font-weight: 600;
    }
    .avatar-sm {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 600;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .card {
        animation: fadeInUp 0.5s ease;
    }
    .card:nth-child(1) { animation-delay: 0.1s; }
    .card:nth-child(2) { animation-delay: 0.2s; }
    .card:nth-child(3) { animation-delay: 0.3s; }
    .card:nth-child(4) { animation-delay: 0.4s; }
</style>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>


@endsection
