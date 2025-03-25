@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Danh sách chuyến bay đã xóa mềm</h1>
        
        <a href="{{ route('admin.chuyenbay.index') }}" class="btn btn-secondary mb-3">Quay lại danh sách chính</a>

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
                        <td>{{ $flight->hangBay->ten_hang_bay }}</td>
                        <td>
                            <form action="{{ route('admin.chuyenbay.restore', $flight->id_chuyen_bay) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Bạn có chắc muốn khôi phục?')">Khôi phục</button>
                            </form>
                           
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $flights->links() }}
    </div>
@endsection