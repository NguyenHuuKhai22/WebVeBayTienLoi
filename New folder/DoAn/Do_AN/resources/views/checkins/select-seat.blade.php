@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-teal-700 text-center">Chọn Chỗ Ngồi</h1>

        <!-- Thông tin chuyến bay -->
        <div class="mt-4 p-4 bg-gray-100 rounded-lg">
            <p><strong>Chuyến bay:</strong> {{ $ve->chuyenBay->ma_chuyen_bay }}</p>
            <p><strong>Điểm đi:</strong> {{ $ve->chuyenBay->diem_di }}</p>
            <p><strong>Điểm đến:</strong> {{ $ve->chuyenBay->diem_den }}</p>
            <p><strong>Thời gian khởi hành:</strong> {{ $ve->chuyenBay->ngay_gio_khoi_hanh }}</p>
            <p><strong>Hạng ghế:</strong> 
                @if($ve->loai_ghe == 'pho_thong')
                    Phổ thông
                @elseif($ve->loai_ghe == 'thuong_gia')
                    Thương gia
                @elseif($ve->loai_ghe == 'pho_thong_dac_biet')
                    Phổ thông đặc biệt
                @else
                    Chưa xác định
                @endif
            </p>
        </div>

        <!-- Layout chọn ghế -->
        <div class="mt-6 flex justify-center gap-6">
            <!-- Bảng chú thích -->
            <div class="w-1/3 rounded-lg overflow-hidden">
                <div class="bg-teal-900 text-white p-4 text-lg font-semibold">
                    Chú thích sơ đồ chỗ ngồi
                </div>
                <div class="bg-white p-4 space-y-2 border border-gray-300 rounded-b-lg">
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <span class="block w-6 h-6 bg-blue-800 text-white flex items-center justify-center font-bold mr-2">AB</span> 
                            Ghế đã chọn
                        </li>
                        <li class="flex items-center">
                            <span class="block w-6 h-6 bg-green-500 mr-2"></span> 
                            Ghế trống (Phổ thông)
                        </li>
                        <li class="flex items-center">
                            <span class="block w-6 h-6 bg-yellow-500 mr-2"></span> 
                            Ghế trống (Thương gia)
                        </li>
                        <li class="flex items-center">
                            <span class="block w-6 h-6 bg-gray-400 mr-2"></span> 
                            Không còn trống
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Sơ đồ ghế -->
            <div class="w-2/3">
                <h2 class="text-lg font-semibold mb-2 text-center">Sơ đồ ghế ngồi</h2>
                @php
                $da_chon = array_filter($da_chon); // Loại bỏ giá trị null trong danh sách ghế đã chọn
            @endphp

            <div class="grid grid-cols-7 gap-2 justify-center items-center">
                @php
                    $rows = range(1, 15);
                    $left_cols = ['A', 'B', 'C'];
                    $right_cols = ['D', 'E', 'F'];
                @endphp

                @foreach ($rows as $row)
                    @foreach ($left_cols as $col)
                        @php 
                            $ghe = $col . $row;
                            $isBusinessClass = $row <= 2; // Hàng 1-2 là thương gia
                            $daDat = in_array($ghe, $da_chon); // Kiểm tra ghế đã đặt
                            $btnClass = 'bg-green-500 hover:bg-green-700'; 
                            $dataClass = 'pho_thong';

                            if ($isBusinessClass) {
                                $btnClass = 'bg-yellow-500 hover:bg-yellow-600';
                                $dataClass = 'thuong_gia';
                                if ($ve->loai_ghe !== 'thuong_gia') { 
                                    $btnClass = 'bg-yellow-300 cursor-not-allowed'; // Khóa ghế thương gia nếu vé không phải thương gia
                                }
                            } elseif ($ve->loai_ghe === 'thuong_gia') {
                                $btnClass = 'bg-green-300 cursor-not-allowed'; // Khóa ghế phổ thông nếu vé là thương gia
                            }

                            // ✅ Nếu ghế đã đặt, đổi sang màu xám
                            if ($daDat) {
                                $btnClass = 'bg-gray-400 cursor-not-allowed';
                            }
                        @endphp

                        <button 
                            class="seat w-16 h-16 border rounded-md text-center font-semibold {{ $btnClass }}"
                            data-seat="{{ $ghe }}" 
                            data-class="{{ $dataClass }}"
                            {{ $daDat ? 'disabled' : '' }}>
                            {{ $ghe }}
                        </button>
                    @endforeach

                    <div class="w-6 h-16 bg-gray-300"></div>

                    @foreach ($right_cols as $col)
                        @php 
                            $ghe = $col . $row;
                            $isBusinessClass = $row <= 2; // Hàng 1-2 là thương gia
                            $daDat = in_array($ghe, $da_chon);
                            $btnClass = 'bg-green-500 hover:bg-green-700'; 
                            $dataClass = 'pho_thong';

                            if ($isBusinessClass) {
                                $btnClass = 'bg-yellow-500 hover:bg-yellow-600';
                                $dataClass = 'thuong_gia';
                                if ($ve->loai_ghe !== 'thuong_gia') { 
                                    $btnClass = 'bg-yellow-300 cursor-not-allowed';
                                }
                            } elseif ($ve->loai_ghe === 'thuong_gia') {
                                $btnClass = 'bg-green-300 cursor-not-allowed';
                            }

                            if ($daDat) {
                                $btnClass = 'bg-gray-400 cursor-not-allowed';
                            }
                        @endphp

                        <button 
                            class="seat w-16 h-16 border rounded-md text-center font-semibold {{ $btnClass }}"
                            data-seat="{{ $ghe }}" 
                            data-class="{{ $dataClass }}"
                            {{ $daDat ? 'disabled' : '' }}>
                            {{ $ghe }}
                        </button>
                    @endforeach
                @endforeach
            </div>

            </div>
        </div>

        <!-- Form chọn ghế -->
        <form action="{{ route('checkin.confirm-seat', ['ma_ve' => $ve->ma_ve]) }}" method="POST" class="mt-6 text-center">
            @csrf
            <input type="hidden" name="so_ghe" id="selectedSeat" value="">
            <p class="text-gray-700 mt-2">Ghế đã chọn: <span id="seatLabel" class="font-bold text-teal-600"></span></p>
            <form action="{{ route('checkins.baggage') }}" method="GET">
                <button type="submit" class="mt-4 bg-teal-600 text-white py-2 px-6 rounded-lg text-lg font-semibold hover:bg-teal-700 disabled:bg-gray-400" id="confirmBtn" disabled>
                    Xác Nhận Chỗ Ngồi
                </button>
            </form>
            
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    let selectedSeat = "";
    let ticketClass = "{{ strtolower(trim($ve->loai_ghe)) }}"; // Lấy loại vé

    document.querySelectorAll(".seat").forEach(button => {
        button.addEventListener("click", function () {
            if (this.classList.contains("bg-gray-400") || this.classList.contains("cursor-not-allowed")) return;

            let seatType = this.dataset.class.trim(); // Loại ghế

            // Chặn chọn ghế thương gia nếu vé không phải thương gia
            if (seatType === "thuong_gia" && ticketClass !== "thuong_gia") {
                alert("Bạn không thể chọn ghế thương gia vì vé của bạn không phải hạng thương gia!");
                return;
            }

            // Chặn chọn ghế phổ thông nếu vé là thương gia
            if (seatType === "pho_thong" && ticketClass === "thuong_gia") {
                alert("Bạn không thể chọn ghế phổ thông vì vé của bạn là hạng thương gia!");
                return;
            }

            if (selectedSeat) {
                let prevSeat = document.querySelector(`[data-seat="${selectedSeat}"]`);
                prevSeat.classList.remove("bg-blue-500");

                // Khôi phục màu ghế theo loại của nó
                if (prevSeat.dataset.class.trim() === "thuong_gia") {
                    prevSeat.classList.add("bg-yellow-500");
                } else {
                    prevSeat.classList.add("bg-green-500");
                }
            }

            if (selectedSeat === this.getAttribute("data-seat")) {
                selectedSeat = "";
                document.getElementById("selectedSeat").value = "";
                document.getElementById("seatLabel").textContent = "";
                document.getElementById("confirmBtn").disabled = true;
            } else {
                this.classList.remove("bg-green-500", "bg-yellow-500");
                this.classList.add("bg-blue-500");
                selectedSeat = this.getAttribute("data-seat");
                document.getElementById("selectedSeat").value = selectedSeat;
                document.getElementById("seatLabel").textContent = selectedSeat;
                document.getElementById("confirmBtn").disabled = false;
            }
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".seat").forEach(button => {
            if (button.disabled) {
                console.log("Ghế đã đặt (JS):", button.dataset.seat);
                button.classList.add("bg-gray-400", "cursor-not-allowed");
            }
        });
    });

});

</script>

@endsection
