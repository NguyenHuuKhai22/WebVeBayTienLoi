document.addEventListener('DOMContentLoaded', function() {
    const passengerSelect = document.getElementById('passenger-select');
    const totalDisplay = document.getElementById('total-passengers');
    const totalPriceDisplay = document.getElementById('total-price');
    const seatOptions = document.querySelectorAll('.border');
    const numPassengersInput = document.getElementById('num_passengers');
    const selectedSeatTypeInput = document.getElementById('selected_seat_type');
    let selectedSeatType = 'pho_thong';
    let priceFactor = 1.0;

    // Trigger initial selection for phổ thông
    const defaultSeat = document.querySelector('input[value="pho_thong"]');
    if (defaultSeat) {
        const defaultCard = defaultSeat.closest('.border');
        if (defaultCard) {
            defaultCard.click();
        }
    }

    function updatePrice() {
        const numPassengers = parseInt(passengerSelect.value);
        const basePrice = parseInt(document.getElementById('base-price').value);
        const totalPrice = basePrice * priceFactor * numPassengers;
        totalPriceDisplay.textContent = totalPrice.toLocaleString('vi-VN') + ' VND';
        numPassengersInput.value = numPassengers;
    }

    // Passenger select change
    passengerSelect.addEventListener('change', function() {
        totalDisplay.textContent = this.value;
        updatePrice();
    });

    // Seat type selection
    seatOptions.forEach(option => {
        const radio = option.querySelector('.seat-radio');
        const selectBtn = option.querySelector('.select-btn');

        // Handle click on the entire seat option
        option.addEventListener('click', function(e) {
            e.preventDefault();
            radio.checked = true;
            selectedSeatType = radio.value;
            selectedSeatTypeInput.value = selectedSeatType;
            
            // Update price factor
            switch(selectedSeatType) {
                case 'pho_thong':
                    priceFactor = 1.0;
                    break;
                case 'pho_thong_dac_biet':
                    priceFactor = 1.4;
                    break;
                case 'thuong_gia':
                    priceFactor = 2.2;
                    break;
            }

            // Update visual feedback
            document.querySelectorAll('.border').forEach(opt => {
                opt.classList.remove('border-teal-500', 'bg-teal-50');
                opt.querySelector('.select-btn').classList.remove('bg-teal-700', 'text-white');
                opt.querySelector('.select-btn').classList.add('bg-gray-200', 'text-gray-700');
            });

            option.classList.add('border-teal-500', 'bg-teal-50');
            selectBtn.classList.remove('bg-gray-200', 'text-gray-700');
            selectBtn.classList.add('bg-teal-700', 'text-white');

            updatePrice();
        });

        // Handle click on the select button
        selectBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            radio.checked = true;
            selectedSeatType = radio.value;
            selectedSeatTypeInput.value = selectedSeatType;
            
            // Update price factor
            switch(selectedSeatType) {
                case 'pho_thong':
                    priceFactor = 1.0;
                    break;
                case 'pho_thong_dac_biet':
                    priceFactor = 1.4;
                    break;
                case 'thuong_gia':
                    priceFactor = 2.2;
                    break;
            }

            // Update visual feedback
            document.querySelectorAll('.border').forEach(opt => {
                opt.classList.remove('border-teal-500', 'bg-teal-50');
                opt.querySelector('.select-btn').classList.remove('bg-teal-700', 'text-white');
                opt.querySelector('.select-btn').classList.add('bg-gray-200', 'text-gray-700');
            });

            option.classList.add('border-teal-500', 'bg-teal-50');
            selectBtn.classList.remove('bg-gray-200', 'text-gray-700');
            selectBtn.classList.add('bg-teal-700', 'text-white');

            updatePrice();
        });
    });

    // Form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!document.querySelector('input[name="seat_type"]:checked')) {
            e.preventDefault();
            alert('Vui lòng chọn hạng ghế');
        }
    });
}); 