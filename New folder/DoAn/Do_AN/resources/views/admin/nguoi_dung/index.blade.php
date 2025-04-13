@extends('layouts.admin')

@section('content')
    <div class="container">
    <div class="activity-header">
        <h3 class="activity-title">Thêm người dùng </h3>
        <a href="{{ route('admin.nguoi_dung.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm người dùng 
        </a>
    </div>
    </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ Tên</th>
                    <th>Email</th>
                    <th>Mật khẩu</th>
                    <th>Số Điện Thoại</th>
                    <th>Ngày Tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nguoiDung as $nguoi)
                    @if(strtolower($nguoi->role) !== 'admin') <!-- Ẩn admin -->
                        <tr>
                            <td>{{ $nguoi->id_nguoi_dung }}</td>
                            <td>{{ $nguoi->ho_ten }}</td>
                            <td>{{ $nguoi->email }}</td>
                            <td>{{ substr($nguoi->password, 0, 5) }}***</td>
                            <td>{{ $nguoi->so_dien_thoai }}</td>
                            <td>{{ $nguoi->ngay_tao }}</td>
                            <td>
                                <a href="{{ route('admin.nguoi_dung.edit', $nguoi->id_nguoi_dung) }}" class="btn btn-primary btn-sm">Sửa</a>
                            
                                @if($nguoi->blocked_until && now()->lessThan($nguoi->blocked_until))
                                    <!-- Nếu user đang bị chặn, hiển thị nút "Bỏ chặn" -->
                                    <form action="{{ route('admin.nguoi_dung.unblock', $nguoi->id_nguoi_dung) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Bạn có chắc muốn bỏ chặn người dùng này?')">
                                            Bỏ Chặn
                                        </button>
                                    </form>
                                @else
                                    <!-- Nếu user chưa bị chặn, hiển thị nút "Chặn" -->
                                    <form action="{{ route('admin.nguoi_dung.block', $nguoi->id_nguoi_dung) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn chặn người dùng này trong 24 giờ?')">
                                            Chặn
                                        </button>
                                    </form>
                                @endif
<form action="{{ route('admin.nguoi_dung.reset_password', $nguoi->id_nguoi_dung) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Bạn có chắc muốn reset mật khẩu cho người dùng này?')">
                                        Reset Mật Khẩu
                                    </button>
                                </form>
                            </td>
                            
                        </tr>
                    @endif
                @endforeach            
            </tbody>
        </table>
    
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

        </div>
    @endsection
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $(".alert").fadeOut("slow");
        }, 3000);
    });
</script>
