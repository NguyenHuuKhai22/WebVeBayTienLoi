@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý khuyến mãi</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm khuyến mãi mới
                        </a>
                    </div>

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

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên khuyến mãi</th>
                                    <th>Mô tả</th>
                                    <th>Phần trăm giảm</th>
                                    <th>Thời gian bắt đầu</th>
                                    <th>Thời gian kết thúc</th>
                                    <th>Trạng thái</th>
                                    <th>Số chuyến bay</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($promotions as $promotion)
                                    <tr>
                                        <td>{{ $promotion->id_khuyen_mai }}</td>
                                        <td>{{ $promotion->ten_khuyen_mai }}</td>
                                        <td>{{ Str::limit($promotion->mo_ta, 50) }}</td>
                                        <td>{{ $promotion->phan_tram_giam }}%</td>
                                        <td>{{ $promotion->thoi_gian_bat_dau ? \Carbon\Carbon::parse($promotion->thoi_gian_bat_dau)->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td>{{ $promotion->thoi_gian_ket_thuc ? \Carbon\Carbon::parse($promotion->thoi_gian_ket_thuc)->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <form action="{{ route('admin.promotions.toggle-status', $promotion) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-sm {{ $promotion->trang_thai ? 'btn-success' : 'btn-danger' }}">
                                                    {{ $promotion->trang_thai ? 'Đang hoạt động' : 'Đã tắt' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ $promotion->chuyenBays->count() }}</td>
                                        <td>
                                            <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Không có khuyến mãi nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 