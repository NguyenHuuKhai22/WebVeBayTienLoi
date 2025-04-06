@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-md mx-auto">
        <h1 class="text-2xl font-bold text-center text-teal-700">Check-in Online</h1>

        <form action="{{ route('checkin.process') }}" method="POST" class="mt-6">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Mã Vé</label>
                <input type="text" name="ma_ve" required class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Họ Tên</label>
                <input type="text" name="ho_ten" required class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Chọn Hành Động</label>
                <select name="action" required class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="checkin">Làm Thủ Tục</option>
                    <option value="cancel">Quản lý thông tin đặt chỗ</option>
                </select>
            </div>

            @if ($errors->any())
                <p class="text-red-600 text-sm">{{ $errors->first() }}</p>
            @endif

            <button type="submit" class="w-full bg-teal-600 text-white py-2 rounded-lg hover:bg-teal-700">Tiếp Tục</button>
        </form>
    </div>
</div>
@endsection
