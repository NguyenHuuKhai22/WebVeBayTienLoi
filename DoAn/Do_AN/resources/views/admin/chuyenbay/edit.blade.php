@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Chỉnh sửa chuyến bay</h1>

    @php
        $activePromotions = $flight->getActivePromotions();
        $hasDiscount = false;
        $discountPercent = 0;
        $discountedPrice = $flight->gia_ve_co_ban;
        $applicablePromotions = collect([]);
        
        if ($activePromotions->isNotEmpty() && $flight->ngay_gio_khoi_hanh) {
            $thoiGianKhoiHanh = \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh);
            foreach ($activePromotions as $promo) {
                $thoiGianBatDau = \Carbon\Carbon::parse($promo->thoi_gian_bat_dau);
                $thoiGianKetThuc = \Carbon\Carbon::parse($promo->thoi_gian_ket_thuc);
                if ($promo->trang_thai && $thoiGianKhoiHanh->between($thoiGianBatDau, $thoiGianKetThuc)) {
                    $applicablePromotions->push($promo);
                    $hasDiscount = true;
                }
            }
            
            if ($hasDiscount) {
                $discountPercent = $flight->getHighestDiscount();
                $discountedPrice = $flight->getDiscountedPrice();
            }
        }
    @endphp

    <form action="{{ route('admin.chuyenbay.update', $flight->id_chuyen_bay) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Mã chuyến bay</label>
            <input type="text" name="ma_chuyen_bay" class="form-control @error('ma_chuyen_bay') is-invalid @enderror"
                value="{{ old('ma_chuyen_bay', $flight->ma_chuyen_bay) }}">
            @error('ma_chuyen_bay')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Điểm đi</label>
            <input type="text" name="diem_di" class="form-control @error('diem_di') is-invalid @enderror"
                value="{{ old('diem_di', $flight->diem_di) }}">
            @error('diem_di')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Điểm đến</label>
            <input type="text" name="diem_den" class="form-control @error('diem_den') is-invalid @enderror"
                value="{{ old('diem_den', $flight->diem_den) }}">
            @error('diem_den')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Thời gian khởi hành</label>
            <input type="datetime-local" name="ngay_gio_khoi_hanh"
                class="form-control @error('ngay_gio_khoi_hanh') is-invalid @enderror"
                value="{{ old('ngay_gio_khoi_hanh', $flight->ngay_gio_khoi_hanh ? date('Y-m-d\TH:i', strtotime($flight->ngay_gio_khoi_hanh)) : '') }}"
                id="ngay_gio_khoi_hanh">
            @error('ngay_gio_khoi_hanh')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Thời gian đến</label>
            <input type="datetime-local" name="ngay_gio_den"
                class="form-control @error('ngay_gio_den') is-invalid @enderror"
                value="{{ old('ngay_gio_den', $flight->ngay_gio_den ? date('Y-m-d\TH:i', strtotime($flight->ngay_gio_den)) : '') }}"
                id="ngay_gio_den">
            @error('ngay_gio_den')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <script>
            const startTimeInput = document.getElementById('ngay_gio_khoi_hanh');
            const endTimeInput = document.getElementById('ngay_gio_den');
            const now = new Date();
            const currentDateTime = now.toISOString().slice(0, 16);

            startTimeInput.setAttribute('min', currentDateTime);

            startTimeInput.addEventListener('change', function() {
                const startTime = this.value;
                if (startTime) {
                    endTimeInput.setAttribute('min', startTime);
                    if (endTimeInput.value && endTimeInput.value < startTime) {
                        endTimeInput.value = '';
                    }
                }
            });

            window.onload = function() {
                if (startTimeInput.value) {
                    endTimeInput.setAttribute('min', startTimeInput.value);
                }
            };
        </script>

        <div class="form-group">
            <label>Giá vé cơ bản</label>
            <input type="number" name="gia_ve_co_ban"
                class="form-control @error('gia_ve_co_ban') is-invalid @enderror"
                value="{{ old('gia_ve_co_ban', $flight->gia_ve_co_ban) }}">
            @error('gia_ve_co_ban')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            
            @if($hasDiscount)
            <div class="alert alert-info mt-2">
                <strong>Thông tin khuyến mãi đang hoạt động:</strong> 
                <p>Giá sau giảm giá: <span class="text-danger font-weight-bold">{{ number_format($discountedPrice) }} VNĐ</span> (giảm {{ $discountPercent }}%)</p>
                <p class="mb-0">
                    <strong>Khuyến mãi áp dụng tại thời điểm khởi hành:</strong> 
                    <ul class="mb-0">
                        @foreach($applicablePromotions as $promo)
                        <li>{{ $promo->ten_khuyen_mai }} ({{ $promo->phan_tram_giam }}%) - 
                            <small>Từ {{ \Carbon\Carbon::parse($promo->thoi_gian_bat_dau)->format('d/m/Y H:i') }} 
                            đến {{ \Carbon\Carbon::parse($promo->thoi_gian_ket_thuc)->format('d/m/Y H:i') }}</small>
                        </li>
                        @endforeach
                    </ul>
                </p>
            </div>
            @endif
        </div>

        <div class="form-group">
            <label>Số ghế trống</label>
            <input type="number" name="so_ghe_trong"
                class="form-control @error('so_ghe_trong') is-invalid @enderror"
                value="{{ old('so_ghe_trong', $flight->so_ghe_trong) }}">
            @error('so_ghe_trong')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Hãng bay</label>
            <select name="id_hang_bay" class="form-control @error('id_hang_bay') is-invalid @enderror">
                <option value="">Chọn hãng bay</option>
                @foreach ($airlines as $airline)
                <option value="{{ $airline->id_hang_bay }}"
                    {{ old('id_hang_bay', $flight->id_hang_bay) == $airline->id_hang_bay ? 'selected' : '' }}>
                    {{ $airline->ten_hang_bay }}
                </option>
                @endforeach
            </select>
            @error('id_hang_bay')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.chuyenbay.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection