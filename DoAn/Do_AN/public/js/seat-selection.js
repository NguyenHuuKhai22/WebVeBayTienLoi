document.addEventListener("DOMContentLoaded", function() {
    const seatOptions = document.querySelectorAll(".seat-option");
    const seatRadios = document.querySelectorAll(".seat-radio");
    const totalPriceElement = document.getElementById("total-price");

    // Lấy số hành khách từ input ẩn
    const numPassengers = document.querySelector("input[name='num_passengers']").value;

    seatOptions.forEach(option => {
        option.addEventListener("click", function() {
            // Bỏ chọn tất cả radio
            seatRadios.forEach(radio => radio.checked = false);

            // Lấy radio trong phần tử được chọn
            const radio = this.querySelector(".seat-radio");
            radio.checked = true;

            // Cập nhật giao diện nút chọn
            seatOptions.forEach(opt => {
                opt.querySelector(".select-btn").classList.remove("bg-teal-700", "text-white");
                opt.querySelector(".select-btn").classList.add("bg-gray-200", "text-gray-700");
            });

            this.querySelector(".select-btn").classList.remove("bg-gray-200", "text-gray-700");
            this.querySelector(".select-btn").classList.add("bg-teal-700", "text-white");

            // Cập nhật tổng tiền
            const pricePerSeat = parseFloat(this.getAttribute("data-price"));
            const totalPrice = pricePerSeat * numPassengers;

            totalPriceElement.innerText = new Intl.NumberFormat('vi-VN').format(totalPrice) + " VND";
        });
    });
});
