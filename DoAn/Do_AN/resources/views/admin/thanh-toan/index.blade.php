@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý thanh toán</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Thông tin vé</th>
                                    <th>Số tiền</th>
                                    <th>Phương thức</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày thanh toán</th>
                                    <th>Mã giao dịch</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->id_thanh_toan }}</td>
                                        <td>
                                            @foreach($payment->veMayBay as $ticket)
                                                <div class="mb-2">
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
                                                    <hr class="my-2">
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{ number_format($payment->so_tien) }} VNĐ</td>
                                        <td>{{ $payment->phuong_thuc }}</td>
                                        <td>
                                            <span style="color: red;" class="badge badge-{{ $payment->trang_thai === 'đã thanh toán' ? 'success' : ($payment->trang_thai === 'chờ xử lý' ? 'warning' : 'danger') }}">
                                                {{ $payment->trang_thai }}
                                            </span>
                                        </td>
                                        <td>{{ $payment->ngay_thanh_toan }}</td>
                                        <td>{{ $payment->ma_giao_dich }}</td>
                                        <td>
                                            <a href="{{ route('admin.thanh-toan.edit', $payment->id_thanh_toan) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.thanh-toan.destroy', $payment->id_thanh_toan) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa thanh toán này?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
            {{ $payments->links('pagination::bootstrap-4', ['class' => 'pagination pagination-sm']) }}
        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 