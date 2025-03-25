@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Thêm Người Dùng</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.nguoi_dung.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="ho_ten">Họ Tên</label>
            <input type="text" name="ho_ten" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="so_dien_thoai">Số Điện Thoại</label>
            <input type="text" name="so_dien_thoai" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Thêm</button>
        <a href="{{ route('admin.nguoi_dung.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection