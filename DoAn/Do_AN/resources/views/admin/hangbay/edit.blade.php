@extends('layouts.admin')

@section('content')
    <div class="recent-activity">
        <div class="activity-header">
            <h3 class="activity-title">Chỉnh sửa hãng bay</h3>
        </div>

        <form action="{{ route('admin.hangbay.update', ['id_hang_bay' => $hangBay->id_hang_bay]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="ten_hang_bay" class="form-label">Tên hãng bay</label>
        <input type="text" class="form-control" id="ten_hang_bay" name="ten_hang_bay" value="{{ $hangBay->ten_hang_bay }}" required>
    </div>

    <div class="mb-3">
        <label for="logo" class="form-label">Logo</label>
        <input type="file" class="form-control" id="logo" name="logo">
        @if ($hangBay->logo)
            <img src="{{ asset('uploads/logos/' . $hangBay->logo) }}" alt="{{ $hangBay->ten_hang_bay }}" style="max-width: 100px;">
        @endif
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>

    </div>
@endsection
