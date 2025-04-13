@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-teal-700 mb-6 text-center">Tìm Chuyến Bay</h1>
        
        <form action="{{ route('flights.results') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="diem_di" class="block text-gray-700 mb-2">Điểm đi</label>
                    <select id="diem_di" name="diem_di" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">-- Chọn điểm đi --</option>
                        @foreach($diemDi as $diem)
                            <option value="{{ $diem }}">{{ $diem }}</option>
                        @endforeach
                    </select>
                    @error('diem_di')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label for="diem_den" class="block text-gray-700 mb-2">Điểm đến</label>
                    <select id="diem_den" name="diem_den" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">-- Chọn điểm đến --</option>
                        @foreach($diemDen as $diem)
                            <option value="{{ $diem }}">{{ $diem }}</option>
                        @endforeach
                    </select>
                    @error('diem_den')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="ngay_di" class="block text-gray-700 mb-2">Ngày đi</label>
                    <input type="date" id="ngay_di" name="ngay_di" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500" min="{{ date('Y-m-d') }}">

                    @error('ngay_di')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label for="so_hanh_khach" class="block text-gray-700 mb-2">Số hành khách</label>
                    <select id="so_hanh_khach" name="so_hanh_khach" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }} hành khách</option>
                        @endfor
                    </select>
                    @error('so_hanh_khach')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="text-center">
                <button type="submit" class="bg-teal-700 text-white py-2 px-6 rounded-md hover:bg-teal-800 transition">
                    Tìm Chuyến Bay
                </button>
            </div>
        </form>
    </div>
</div>
@endsection<script>
    document.addEventListener("DOMContentLoaded", function () {
        let ngayDiInput = document.getElementById("ngay_di");
        let today = new Date().toISOString().split("T")[0];
        ngayDiInput.setAttribute("min", today);

        ngayDiInput.addEventListener("input", function () {
            if (ngayDiInput.value < today) {
                alert("Ngày đi không hợp lệ!");
                ngayDiInput.value = today;
            }
        });
    });
</script>
