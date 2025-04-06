@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết thanh toán</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Thông tin thanh toán</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID Thanh toán</th>
                                    <td>{{ $payment->id }}</td>
                                </tr>
                                <tr>
                                    <th>Mã vé</th>
                                    <td>{{ $payment->veMayBay->id }}</td>
                                </tr>
                                <tr>
                                    <th>Số tiền</th>
                                    <td>{{ number_format($payment->so_tien) }} VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Phương thức</th>
                                    <td>{{ $payment->phuong_thuc }}</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td>
                                        <span class="badge badge-{{ $payment->trang_thai === 'đã thanh toán' ? 'success' : ($payment->trang_thai === 'chờ xử lý' ? 'warning' : 'danger') }}">
                                            {{ $payment->trang_thai }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày thanh toán</th>
                                    <td>{{ $payment->ngay_thanh_toan }}</td>
                                </tr>
                                <tr>
                                    <th>Ghi chú</th>
                                    <td>{{ $payment->ghi_chu }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Thông tin vé</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Chuyến bay</th>
                                    <td>{{ $payment->veMayBay->chuyenBay->ten_chuyen_bay }}</td>
                                </tr>
                                <tr>
                                    <th>Người dùng</th>
                                    <td>{{ $payment->veMayBay->nguoiDung->ten }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày đặt</th>
                                    <td>{{ $payment->veMayBay->ngay_dat }}</td>
                                </tr>
                                <tr>
                                    <th>Loại vé</th>
                                    <td>{{ $payment->veMayBay->loai_ve }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.thanh-toan.edit', $payment->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <a href="{{ route('admin.thanh-toan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 