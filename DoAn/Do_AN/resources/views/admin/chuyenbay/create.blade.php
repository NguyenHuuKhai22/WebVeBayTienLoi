@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Thêm chuyến bay mới</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.chuyenbay.store') }}" method="POST">
        @csrf
        <input type="hidden" name="is_testing" value="true">
        <div class="form-group">
            <label>Mã chuyến bay</label>
            <input type="text" name="ma_chuyen_bay" class="form-control @error('ma_chuyen_bay') is-invalid @enderror" value="{{ old('ma_chuyen_bay') }}">
            @error('ma_chuyen_bay')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Điểm đi</label>
            <input type="text" name="diem_di" class="form-control @error('diem_di') is-invalid @enderror" value="{{ old('diem_di') }}">
            @error('diem_di')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Điểm đến</label>
            <input type="text" name="diem_den" class="form-control @error('diem_den') is-invalid @enderror" value="{{ old('diem_den') }}">
            @error('diem_den')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Thời gian khởi hành</label>
            <input type="datetime-local" name="ngay_gio_khoi_hanh"
                class="form-control @error('ngay_gio_khoi_hanh') is-invalid @enderror"
                value="{{ old('ngay_gio_khoi_hanh') }}"
                id="ngay_gio_khoi_hanh">
            @error('ngay_gio_khoi_hanh')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Thời gian đến</label>
            <input type="datetime-local" name="ngay_gio_den"
                class="form-control @error('ngay_gio_den') is-invalid @enderror"
                value="{{ old('ngay_gio_den') }}"
                id="ngay_gio_den">
            @error('ngay_gio_den')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <script>
            // Lấy các phần tử input
            const startTimeInput = document.getElementById('ngay_gio_khoi_hanh');
            const endTimeInput = document.getElementById('ngay_gio_den');

            // Định dạng thời gian hiện tại cho input datetime-local
            const now = new Date();
            const currentDateTime = now.toISOString().slice(0, 16); // Cắt bỏ giây và millisecond

            // Đặt giá trị min cho thời gian khởi hành là thời gian hiện tại
            startTimeInput.setAttribute('min', currentDateTime);

            // Khi thay đổi thời gian khởi hành
            startTimeInput.addEventListener('change', function() {
                const startTime = this.value;
                if (startTime) {
                    // Đặt giá trị min cho thời gian đến là thời gian khởi hành
                    endTimeInput.setAttribute('min', startTime);

                    // Nếu thời gian đến hiện tại nhỏ hơn thời gian khởi hành, xóa nó
                    if (endTimeInput.value && endTimeInput.value < startTime) {
                        endTimeInput.value = '';
                    }
                }
            });

            // (Tùy chọn) Khởi tạo giá trị min cho thời gian đến khi tải trang
            window.onload = function() {
                if (startTimeInput.value) {
                    endTimeInput.setAttribute('min', startTimeInput.value);
                }
            };
        </script>

        <div class="form-group">
            <label>Giá vé cơ bản</label>
            <input type="number" name="gia_ve_co_ban" class="form-control @error('gia_ve_co_ban') is-invalid @enderror" value="{{ old('gia_ve_co_ban') }}">
            @error('gia_ve_co_ban')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Số ghế trống</label>
            <input type="number" name="so_ghe_trong" class="form-control @error('so_ghe_trong') is-invalid @enderror" value="{{ old('so_ghe_trong') }}">
            @error('so_ghe_trong')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Hãng bay</label>
            <select name="id_hang_bay" class="form-control @error('id_hang_bay') is-invalid @enderror">
                <option value="">Chọn hãng bay</option>
                @foreach ($airlines as $airline)
                <option value="{{ $airline->id_hang_bay }}" {{ old('id_hang_bay') == $airline->id_hang_bay ? 'selected' : '' }}>
                    {{ $airline->ten_hang_bay }}
                </option>
                @endforeach
            </select>
            @error('id_hang_bay')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary" id="hehehehe">Thêm chuyến bay</button>
        <a href="{{ route('admin.chuyenbay.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection