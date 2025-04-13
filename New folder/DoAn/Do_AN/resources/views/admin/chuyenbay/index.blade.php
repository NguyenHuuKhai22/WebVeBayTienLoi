@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Quản lý chuyến bay</h1>
        
        <a href="{{ route('admin.chuyenbay.create') }}" class="btn btn-primary mb-3">Thêm chuyến bay</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã chuyến bay</th>
                    <th>Điểm đi</th>
                    <th>Điểm đến</th>
                    <th>Thời gian khởi hành</th>
                    <th>Thời gian đến</th>
                    <th>Giá vé</th>
                    <th>Số ghế trống</th>
                    <th>Hãng bay</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($flights as $flight)
                    <tr>
                        <td>{{ $flight->ma_chuyen_bay }}</td>
                        <td>{{ $flight->diem_di }}</td>
                        <td>{{ $flight->diem_den }}</td>
                        <td>{{ $flight->ngay_gio_khoi_hanh }}</td>
                        <td>{{ $flight->ngay_gio_den }}</td>
                        <td>
                            @php
                                $activePromotions = $flight->getActivePromotions();
                                $hasDiscount = false;
                                $discountPercent = 0;
                                
                                if ($activePromotions->isNotEmpty() && $flight->ngay_gio_khoi_hanh) {
                                    $thoiGianKhoiHanh = \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh);
                                    foreach ($activePromotions as $promo) {
                                        $thoiGianBatDau = \Carbon\Carbon::parse($promo->thoi_gian_bat_dau);
                                        $thoiGianKetThuc = \Carbon\Carbon::parse($promo->thoi_gian_ket_thuc);
                                        if ($promo->trang_thai && $thoiGianKhoiHanh->between($thoiGianBatDau, $thoiGianKetThuc)) {
                                            $hasDiscount = true;
                                            $discountPercent = $flight->getHighestDiscount();
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            @if($hasDiscount)
                                <div class="text-decoration-line-through text-muted">
                                    {{ number_format($flight->gia_ve_co_ban) }} VNĐ
                                </div>
                                <div class="text-danger">
                                    {{ number_format($flight->getDiscountedPrice()) }} VNĐ
                                    <span class="badge bg-danger text-white">-{{ $discountPercent }}%</span>
                                </div>
                            @else
                                {{ number_format($flight->gia_ve_co_ban) }} VNĐ
                            @endif
                        </td>
                        <td>{{ $flight->so_ghe_trong }}</td>
                        <td>{{ $flight->hangBay->ten_hang_bay }}</td>
                        <td>
                            <a href="{{ route('admin.chuyenbay.edit', $flight) }}" class="btn btn-sm btn-primary">Sửa</a>
                            <form action="{{ route('admin.chuyenbay.destroy', $flight) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $flights->links('pagination::bootstrap-4', ['class' => 'pagination pagination-sm']) }}
        </div>
    </div>
@endsection