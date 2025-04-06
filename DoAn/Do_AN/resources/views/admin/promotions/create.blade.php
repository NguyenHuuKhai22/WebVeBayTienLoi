@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thêm khuyến mãi mới</h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.promotions.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="ten_khuyen_mai">Tên khuyến mãi</label>
                            <input type="text" class="form-control @error('ten_khuyen_mai') is-invalid @enderror" 
                                   id="ten_khuyen_mai" name="ten_khuyen_mai" value="{{ old('ten_khuyen_mai') }}" required>
                            @error('ten_khuyen_mai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="mo_ta">Mô tả</label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                      id="mo_ta" name="mo_ta" rows="3">{{ old('mo_ta') }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phan_tram_giam">Phần trăm giảm (%)</label>
                            <input type="number" class="form-control @error('phan_tram_giam') is-invalid @enderror" 
                                   id="phan_tram_giam" name="phan_tram_giam" value="{{ old('phan_tram_giam') }}" 
                                   min="0" max="100" step="0.01" required>
                            @error('phan_tram_giam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="thoi_gian_bat_dau">Thời gian bắt đầu</label>
                            <input type="datetime-local" class="form-control @error('thoi_gian_bat_dau') is-invalid @enderror" 
                                   id="thoi_gian_bat_dau" name="thoi_gian_bat_dau" 
                                   value="{{ old('thoi_gian_bat_dau') }}" required>
                            @error('thoi_gian_bat_dau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="thoi_gian_ket_thuc">Thời gian kết thúc</label>
                            <input type="datetime-local" class="form-control @error('thoi_gian_ket_thuc') is-invalid @enderror" 
                                   id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc" 
                                   value="{{ old('thoi_gian_ket_thuc') }}" required>
                            @error('thoi_gian_ket_thuc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Chọn chuyến bay</label>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Chọn</th>
                                            <th>Mã chuyến bay</th>
                                            <th>Điểm đi</th>
                                            <th>Điểm đến</th>
                                            <th>Thời gian khởi hành</th>
                                            <th>Giá vé cơ bản</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($flights as $flight)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="chuyen_bay_ids[]" 
                                                           value="{{ $flight->id_chuyen_bay }}"
                                                           {{ in_array($flight->id_chuyen_bay, old('chuyen_bay_ids', [])) ? 'checked' : '' }}>
                                                </td>
                                                <td>{{ $flight->ma_chuyen_bay }}</td>
                                                <td>{{ $flight->diem_di }}</td>
                                                <td>{{ $flight->diem_den }}</td>
                                                <td>{{ $flight->ngay_gio_khoi_hanh }}</td>
                                                <td>{{ number_format($flight->gia_ve_co_ban) }} VNĐ</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @error('chuyen_bay_ids')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Lưu khuyến mãi</button>
                            <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 