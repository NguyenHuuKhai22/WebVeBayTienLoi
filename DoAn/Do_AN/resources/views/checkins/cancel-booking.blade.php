@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-md mx-auto">
        <h1 class="text-2xl font-bold text-center text-red-700">Hủy Làm Thủ Tục</h1>
        <p class="text-gray-600 text-center">Hủy làm thủ tục không ảnh hưởng đến thông tin đặt chỗ của bạn. Bạn có thể làm thủ tục lại sau.</p>

        <form action="{{ route('checkin.cancel-booking.process', ['ma_ve' => $ve->ma_ve]) }}" method="POST" class="mt-6">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Hành khách:</label>
                <p class="p-3 border rounded-lg bg-gray-100">{{ $ve->nguoiDung->ho_ten }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Xác nhận hủy làm thủ tục</label>
                <input type="checkbox" name="confirm" required class="mr-2"> Tôi xác nhận muốn hủy làm thủ tục.
            </div>

            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">Hủy Làm Thủ Tục</button>
            <a href="{{ route('checkin.form') }}" class="block text-center mt-4 text-gray-600">Quay lại</a>
        </form>
    </div>
</div>
@endsection
