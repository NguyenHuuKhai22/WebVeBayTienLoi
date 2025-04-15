@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý vé máy bay</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Chuyến bay</th>
                                    <th>Người dùng</th>
                                    <th>Giá vé</th>
                                    <th>Hành lý</th>
                                    <th>Phí hành lý</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                    <th>Loại ghế</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->id_ve }}</td>
                                        <td>
                                            @if($ticket->chuyenBay)
                                                {{ $ticket->chuyenBay->ma_chuyen_bay }} <br>
                                                <small>{{ $ticket->chuyenBay->diem_di }} - {{ $ticket->chuyenBay->diem_den }}</small>
                                            @else
                                                <span class="text-muted">Không có dữ liệu</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ticket->nguoiDung)
                                                {{ $ticket->nguoiDung->ho_ten }} <br>
                                                <small>{{ $ticket->nguoiDung->email }}</small>
                                            @else
                                                <span class="text-muted">Không có dữ liệu</span>
                                            @endif
                                        </td>
                                        <td>
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
<!--                                             
                                            @if($isOnSale)
                                                <div class="text-decoration-line-through text-muted">
                                                    {{ number_format($ticket->chuyenBay->gia_ve_co_ban) }} VNĐ
                                                </div>
                                                <div class="text-danger">
                                                    {{ number_format($ticket->gia_ve) }} VNĐ
                                                    <span class="badge bg-danger text-white">-{{ $ticket->chuyenBay->getHighestDiscount() }}%</span>
                                                </div>
                                            @else -->
                                                
                                            <!-- @endif -->
                                            {{ number_format($ticket->gia_ve) }} VNĐ
                                        </td>
                                        <td>{{ $ticket->khoi_luong_dang_ky ?? 0 }} kg</td>
                                        <td>{{ number_format($ticket->phi_hanh_ly ?? 0) }} VNĐ</td>
                                        <td>
                                            <span style="color: red;" class="badge badge-{{ $ticket->trang_thai === 'đã thanh toán' ? 'success' : ($ticket->trang_thai === 'chờ thanh toán' ? 'warning' : 'danger') }}">
                                                {{ $ticket->trang_thai }}
                                            </span>
                                        </td>
                                        <td>{{ $ticket->ngay_dat }}</td>
                                        <td>{{ $ticket->loai_ghe }}</td>
                                        <td>
                                            <a href="{{ route('admin.ve-may-bay.edit', $ticket->id_ve) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.ve-may-bay.destroy', $ticket->id_ve) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa vé này?')">
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
            {{ $tickets->links('pagination::bootstrap-4', ['class' => 'pagination pagination-sm']) }}
        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection