@extends('layouts.admin')

@section('content')
<div class="recent-activity">
    <div class="activity-header">
        <h3 class="activity-title">Danh sách hãng bay</h3>
        <a href="{{ route('admin.hangbay.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm hãng bay
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên hãng bay</th>
                <th>Logo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($hangBays as $hangBay)
            <tr>
                <td>{{ $hangBay->id_hang_bay }}</td>
                <td>{{ $hangBay->ten_hang_bay }}</td>
                <td>
                    @if ($hangBay->logo)
                    <img src="{{ asset('uploads/logos/' . $hangBay->logo) }}" alt="{{ $hangBay->ten_hang_bay }}" style="max-width: 100px;">

                    @else
                    Không có logo
                    @endif
                </td>
                <td>
    <div class="d-flex gap-2">
        <!-- Nút Sửa -->
        <a href="{{ route('admin.hangbay.edit', ['id_hang_bay' => $hangBay->id_hang_bay]) }}" 
           class="btn btn-warning btn-sm d-flex align-items-center">
            <i class="fas fa-edit me-1"></i> Sửa
        </a>

        <!-- Form Xóa -->
        <form action="{{ route('admin.hangbay.destroy', ['id_hang_bay' => $hangBay->id_hang_bay]) }}" 
              method="POST" 
              onsubmit="return confirm('Bạn có chắc muốn xóa?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center">
                <i class="fas fa-trash me-1"></i> Xóa
            </button>
        </form>
    </div>
</td>

            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Không có dữ liệu hãng bay</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection