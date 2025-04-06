document.addEventListener("DOMContentLoaded", function() {
    const seatOptions = document.querySelectorAll(".seat-option");
    const totalPriceElement = document.getElementById("total-price");
    const originalTotalPriceElement = document.getElementById("original-total-price");
    const discountInfoElement = document.getElementById("discount-info");
    const numPassengers = parseInt(document.querySelector("input[name='num_passengers']").value);
    const PRICE_FORMAT = new Intl.NumberFormat('vi-VN');
    
    let currentSelection = null;
    
    seatOptions.forEach(option => {
        option.addEventListener("click", function() {
            // Xóa lựa chọn hiện tại
            if (currentSelection) {
                currentSelection.classList.remove("border-teal-500", "bg-teal-50");
                const oldRadio = currentSelection.querySelector(".seat-radio");
                oldRadio.checked = false;
                const oldBtn = currentSelection.querySelector(".select-btn");
                oldBtn.classList.remove("bg-teal-600", "text-white");
                oldBtn.classList.add("bg-gray-200", "text-gray-700");
            }
            
            // Thiết lập lựa chọn mới
            this.classList.add("border-teal-500", "bg-teal-50");
            const radio = this.querySelector(".seat-radio");
            radio.checked = true;
            const btn = this.querySelector(".select-btn");
            btn.classList.remove("bg-gray-200", "text-gray-700");
            btn.classList.add("bg-teal-600", "text-white");
            
            currentSelection = this;
            
            // Cập nhật tổng tiền
            const seatPrice = parseFloat(this.dataset.price);
            const discount = parseFloat(this.dataset.discount);
            const totalOriginalPrice = seatPrice * numPassengers;
            
            if (discount > 0) {
                const discountedPrice = totalOriginalPrice * (1 - discount/100);
                if (totalPriceElement) {
                    totalPriceElement.textContent = PRICE_FORMAT.format(discountedPrice) + " VND";
                }
                if (originalTotalPriceElement) {
                    originalTotalPriceElement.textContent = PRICE_FORMAT.format(totalOriginalPrice);
                }
                if (discountInfoElement) {
                    discountInfoElement.style.display = "block";
                }
            } else {
                if (totalPriceElement) {
                    totalPriceElement.textContent = PRICE_FORMAT.format(totalOriginalPrice) + " VND";
                }
                if (discountInfoElement) {
                    discountInfoElement.style.display = "none";
                }
            }
        });
    });
    
    // Tự động chọn loại ghế đầu tiên
    if (seatOptions.length > 0) {
        seatOptions[0].click();
    }
});
