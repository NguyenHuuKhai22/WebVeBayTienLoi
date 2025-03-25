@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Chỉnh Sửa Người Dùng</h2>
    <form action="{{ route('admin.nguoi_dung.update', $nguoiDung->id_nguoi_dung) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="ho_ten" class="form-label">Họ Tên</label>
            <input type="text" class="form-control" id="ho_ten" name="ho_ten" value="{{ $nguoiDung->ho_ten }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $nguoiDung->email }}" required>
        </div>
        <div class="mb-3">
            <label for="so_dien_thoai" class="form-label">Số Điện Thoại</label>
            <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai" value="{{ $nguoiDung->so_dien_thoai }}" required>
        </div>
        <button type="submit" class="btn btn-success">Cập Nhật</button>
        <a href="{{ route('admin.nguoi_dung.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection