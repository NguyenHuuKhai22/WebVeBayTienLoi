@extends('layouts.admin')

@section('content')
    <div class="recent-activity">
        <div class="activity-header">
            <h3 class="activity-title">Danh sách hãng bay đã xóa</h3>
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
                        <form action="{{ route('admin.hangbay.restore', ['id' => $hangBay->id_hang_bay]) }}" method="POST">

                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-undo"></i> Khôi phục</button>
                            </form>

                            
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Không có hãng bay nào đã xóa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
