@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa thanh toán</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.thanh-toan.update', $payment->id_thanh_toan) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Thông tin vé</label>
                            <div class="card">
                                <div class="card-body">
                                    @foreach($payment->veMayBay as $ticket)
                                        <div class="mb-3">
                                            @if($ticket->chuyenBay)
                                                <strong>Chuyến bay:</strong> {{ $ticket->chuyenBay->ma_chuyen_bay }}<br>
                                                <small>{{ $ticket->chuyenBay->diem_di }} - {{ $ticket->chuyenBay->diem_den }}</small><br>
                                            @endif
                                            @if($ticket->nguoiDung)
                                                <strong>Hành khách:</strong> {{ $ticket->nguoiDung->ho_ten }}<br>
                                            @endif
                                            <strong>Loại ghế:</strong> {{ $ticket->loai_ghe }}
                                        </div>
                                        @if(!$loop->last)
                                            <hr>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="so_tien">Số tiền</label>
                            <input type="number" name="so_tien" id="so_tien" class="form-control @error('so_tien') is-invalid @enderror" 
                                   value="{{ old('so_tien', $payment->so_tien) }}" required>
                            @error('so_tien')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phuong_thuc">Phương thức thanh toán</label>
                            <select name="phuong_thuc" id="phuong_thuc" class="form-control @error('phuong_thuc') is-invalid @enderror" required>
                                <option value="">Chọn phương thức</option>
                                <option value="momo" {{ $payment->phuong_thuc == 'momo' ? 'selected' : '' }}>Momo</option>
                                <option value="tiền mặt" {{ $payment->phuong_thuc == 'tiền mặt' ? 'selected' : '' }}>Tiền mặt</option>
                                <option value="chuyển khoản" {{ $payment->phuong_thuc == 'chuyển khoản' ? 'selected' : '' }}>Chuyển khoản</option>
                                <option value="thẻ tín dụng" {{ $payment->phuong_thuc == 'thẻ tín dụng' ? 'selected' : '' }}>Thẻ tín dụng</option>
                            </select>
                            @error('phuong_thuc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="trang_thai">Trạng thái</label>
                            <select name="trang_thai" id="trang_thai" class="form-control @error('trang_thai') is-invalid @enderror" required>
                                <option value="">Chọn trạng thái</option>
                                <option value="thanh_cong" {{ $payment->trang_thai == 'thanh_cong' ? 'selected' : '' }}>Thành công</option>
                                <option value="chờ xử lý" {{ $payment->trang_thai == 'chờ xử lý' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="đã thanh toán" {{ $payment->trang_thai == 'đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="thất bại" {{ $payment->trang_thai == 'thất bại' ? 'selected' : '' }}>Thất bại</option>
                            </select>
                            @error('trang_thai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <!-- Debug info -->
                            <small class="text-muted">Current status: {{ $payment->trang_thai }}</small>
                        </div>

                        <div class="form-group">
                            <label for="ma_giao_dich">Mã giao dịch</label>
                            <input type="text" name="ma_giao_dich" id="ma_giao_dich" class="form-control @error('ma_giao_dich') is-invalid @enderror" 
                                   value="{{ old('ma_giao_dich', $payment->ma_giao_dich) }}">
                            @error('ma_giao_dich')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.thanh-toan.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 