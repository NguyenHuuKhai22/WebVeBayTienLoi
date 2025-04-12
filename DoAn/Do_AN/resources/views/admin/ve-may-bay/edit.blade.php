@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa vé máy bay</h3>
                </div>
                <div class="card-body">
                    @php
                        $isOnSale = false;
                        if ($ticket->chuyenBay && $ticket->ngay_dat) {
                            $ngayDat = \Carbon\Carbon::parse($ticket->ngay_dat);
                            foreach ($ticket->chuyenBay->promotions as $promo) {
                                $thoiGianBatDau = \Carbon\Carbon::parse($promo->thoi_gian_bat_dau);
                                $thoiGianKetThuc = \Carbon\Carbon::parse($promo->thoi_gian_ket_thuc);
                                if ($promo->trang_thai && $ngayDat->between($thoiGianBatDau, $thoiGianKetThuc)) {
                                    $isOnSale = true;
                                    break;
                                }
                            }
                        }
                    @endphp

                    <form action="{{ route('admin.ve-may-bay.update', $ticket->id_ve) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="id_chuyen_bay">Chuyến bay</label>
                            <select name="id_chuyen_bay" id="id_chuyen_bay" class="form-control @error('id_chuyen_bay') is-invalid @enderror" required>
                                <option value="">Chọn chuyến bay</option>
                                @foreach($flights as $flight)
                                    <option value="{{ $flight->id_chuyen_bay }}" {{ old('id_chuyen_bay', $ticket->id_chuyen_bay) == $flight->id_chuyen_bay ? 'selected' : '' }}>
                                        {{ $flight->ma_chuyen_bay }} ({{ $flight->diem_di }} - {{ $flight->diem_den }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_chuyen_bay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="id_nguoi_dung">Người dùng</label>
                            <select name="id_nguoi_dung" id="id_nguoi_dung" class="form-control @error('id_nguoi_dung') is-invalid @enderror" required>
                                <option value="">Chọn người dùng</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id_nguoi_dung }}" {{ old('id_nguoi_dung', $ticket->id_nguoi_dung) == $user->id_nguoi_dung ? 'selected' : '' }}>
                                        {{ $user->ho_ten }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_nguoi_dung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="gia_ve">Giá vé</label>
                            @if($isOnSale)
                                <div class="alert alert-info mb-2">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <strong>Vé được mua trong thời gian khuyến mãi!</strong>
                                            <p class="mb-0">Giá gốc: <span class="text-decoration-line-through">{{ number_format($ticket->chuyenBay->gia_ve_co_ban) }} VNĐ</span></p>
                                            <p class="mb-0">Giảm giá: <span class="badge bg-danger">-{{ $ticket->chuyenBay->getHighestDiscount() }}%</span></p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <input type="number" name="gia_ve" id="gia_ve" class="form-control @error('gia_ve') is-invalid @enderror" 
                                   value="{{ old('gia_ve', $ticket->gia_ve) }}" required>
                            @error('gia_ve')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="khoi_luong_dang_ky">Khối lượng hành lý (kg)</label>
                            <input type="number" name="khoi_luong_dang_ky" id="khoi_luong_dang_ky" 
                                   class="form-control @error('khoi_luong_dang_ky') is-invalid @enderror" 
                                   value="{{ old('khoi_luong_dang_ky', $ticket->khoi_luong_dang_ky) }}"
                                   min="0" step="1"
                                   oninput="tinhPhiHanhLy(this.value)">
                            @error('khoi_luong_dang_ky')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phi_hanh_ly">Phí hành lý</label>
                            <input type="number" name="phi_hanh_ly" id="phi_hanh_ly" 
                                   class="form-control @error('phi_hanh_ly') is-invalid @enderror" 
                                   value="{{ old('phi_hanh_ly', $ticket->phi_hanh_ly) }}" readonly>
                            <small class="text-muted">Phí: 150,000 VNĐ/kg</small>
                            @error('phi_hanh_ly')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="trang_thai">Trạng thái</label>
                            <select name="trang_thai" id="trang_thai" class="form-control @error('trang_thai') is-invalid @enderror" required>
                                <option value="chờ thanh toán" {{ old('trang_thai', $ticket->trang_thai) == 'chờ thanh toán' ? 'selected' : '' }}>Chờ thanh toán</option>
                                <option value="đã thanh toán" {{ old('trang_thai', $ticket->trang_thai) == 'đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="đã hủy" {{ old('trang_thai', $ticket->trang_thai) == 'đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                            @error('trang_thai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="loai_ghe">Loại ghế</label>
                            <select name="loai_ghe" id="loai_ghe" class="form-control @error('loai_ghe') is-invalid @enderror" required>
                                <option value="Phổ thông" {{ old('loai_ghe', $ticket->loai_ghe) == 'Phổ thông' ? 'selected' : '' }}>Phổ thông</option>
                                <option value="Thương gia" {{ old('loai_ghe', $ticket->loai_ghe) == 'Thương gia' ? 'selected' : '' }}>Thương gia</option>
                            </select>
                            @error('loai_ghe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.ve-may-bay.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
const GIA_MOI_KG = 150000; // Giá 150,000 VNĐ/kg

function tinhPhiHanhLy(khoiLuong) {
    // Chuyển đổi input thành số và đảm bảo là số không âm
    khoiLuong = Math.max(0, parseFloat(khoiLuong) || 0);
    
    // Làm tròn đến 1 chữ số thập phân
    khoiLuong = Math.round(khoiLuong * 10) / 10;
    
    // Tính phí hành lý
    const phiHanhLy = Math.round(khoiLuong * GIA_MOI_KG);
    
    // Cập nhật giá trị vào input
    document.getElementById('khoi_luong_dang_ky').value = khoiLuong;
    document.getElementById('phi_hanh_ly').value = phiHanhLy;
}

// Tính phí hành lý khi trang được tải
document.addEventListener('DOMContentLoaded', function() {
    const khoiLuongInput = document.getElementById('khoi_luong_dang_ky');
    tinhPhiHanhLy(khoiLuongInput.value);
    
    // Thêm event listener cho input
    khoiLuongInput.addEventListener('input', function() {
        tinhPhiHanhLy(this.value);
    });
});
</script>


@endsection