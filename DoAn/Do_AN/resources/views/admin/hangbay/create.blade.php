@extends('layouts.admin')

@section('content')
    <div class="recent-activity">
        <div class="activity-header">
            <h3 class="activity-title">Thêm hãng bay mới</h3>
        </div>

        <form action="{{ route('admin.hangbay.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="ten_hang_bay" class="form-label">Tên hãng bay</label>
                <input type="text" class="form-control" id="ten_hang_bay" name="ten_hang_bay" required>
                @error('ten_hang_bay')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                <input type="file" class="form-control" id="logo" name="logo">
                @error('logo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu
            </button>
            <a href="{{ route('admin.hangbay.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </form>
    </div>
@endsection