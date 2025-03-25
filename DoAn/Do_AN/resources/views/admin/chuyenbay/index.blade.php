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
                        <td>{{ number_format($flight->gia_ve_co_ban) }} VNĐ</td>
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