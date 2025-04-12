<div>
    <section class="bg-white py-8">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
            <div class="service-card p-4 transform transition-all duration-300 hover:scale-105 hover:shadow-lg rounded-lg">
                <i class="fas fa-suitcase-rolling text-4xl text-teal-700 mb-2 animate-bounce"></i>
                <h2 class="text-xl font-bold">{{ __('HÀNH LÝ TRÁC TRƯỚC') }}</h2>
            </div>
            <div class="service-card p-4 transform transition-all duration-300 hover:scale-105 hover:shadow-lg rounded-lg">
                <i class="fas fa-plane text-4xl text-teal-700 mb-2 animate-pulse"></i>
                <h2 class="text-xl font-bold">{{ __('NÂNG HẠNG') }} & {{ __('CHỌN CHỖ') }}</h2>
            </div>
            <div class="service-card p-4 transform transition-all duration-300 hover:scale-105 hover:shadow-lg rounded-lg">
                <i class="fas fa-shopping-bag text-4xl text-teal-700 mb-2 animate-bounce"></i>
                <h2 class="text-xl font-bold">{{ __('MUA SẮM') }}</h2>
            </div>
            <div class="service-card p-4 transform transition-all duration-300 hover:scale-105 hover:shadow-lg rounded-lg">
                <i class="fas fa-hotel text-4xl text-teal-700 mb-2 animate-pulse"></i>
                <h2 class="text-xl font-bold">{{ __('KHÁCH SẠN & TOUR') }}</h2>
            </div>
            <div class="service-card p-4 transform transition-all duration-300 hover:scale-105 hover:shadow-lg rounded-lg">
                <i class="fas fa-shield-alt text-4xl text-teal-700 mb-2 animate-bounce"></i>
                <h2 class="text-xl font-bold">{{ __('BẢO HIỂM') }}</h2>
            </div>
            <div class="service-card p-4 transform transition-all duration-300 hover:scale-105 hover:shadow-lg rounded-lg">
                <i class="fas fa-concierge-bell text-4xl text-teal-700 mb-2 animate-pulse"></i>
                <h2 class="text-xl font-bold">{{ __('DỊCH VỤ KHÁC') }}</h2>
            </div>
        </div>
    </section>

    <section class="bg-white text-gray-800 py-8">
        <div class="container mx-auto p-4">
            <h1 class="text-3xl font-semibold mb-8 text-center animate-fade-in">{{ __('Explore Our Most Popular Flights') }}</h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($flights as $index => $flight)
                @php
                    $destination = Str($flight->diem_den, '_');
                    $imagePath = asset("img/{$destination}_1.jpg");
                    
                    $hasDiscount = false;
                    $discountedPrice = $flight->gia_ve_co_ban;
                    $discountPercent = 0;
                    
                    if ($flight->ngay_gio_khoi_hanh) {
                        $thoiGianKhoiHanh = \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh);
                        foreach ($flight->getActivePromotions() as $promo) {
                            $thoiGianBatDau = \Carbon\Carbon::parse($promo->thoi_gian_bat_dau);
                            $thoiGianKetThuc = \Carbon\Carbon::parse($promo->thoi_gian_ket_thuc);
                            if ($promo->trang_thai && $thoiGianKhoiHanh->between($thoiGianBatDau, $thoiGianKetThuc)) {
                                $hasDiscount = true;
                                $discountPercent = $flight->getHighestDiscount();
                                $discountedPrice = $flight->getDiscountedPrice();
                                break;
                            }
                        }
                    }
                @endphp

                <a href="{{ route('booking.select-passengers', ['flight_id' => $flight->id_chuyen_bay]) }}" class="block">
                    <div class="flight-card relative transform transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-teal-500/30 border border-transparent hover:border-teal-500 rounded-lg overflow-hidden group">
                        <div class="relative overflow-hidden">
                            <img alt="Image of {{ $flight->diem_di }} to {{ $flight->diem_den }}"
                                class="w-full h-48 object-cover transform transition-transform duration-700 group-hover:scale-110" 
                                src="{{ $imagePath }}" />
                            
                            <div class="absolute top-0 left-0 bg-black bg-opacity-50 text-white p-2 rounded-br-lg">
                                {{ $index + 1 }}/{{ $flights->total() }}
                            </div>

                            @if($hasDiscount)
                            <div class="absolute top-0 right-0 bg-red-600 text-white p-2 rounded-bl-lg">
                                -{{ $discountPercent }}%
                            </div>
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        <div class="p-4 bg-white">
                            <h2 class="text-lg font-semibold text-gray-800 mb-2 group-hover:text-teal-700 transition-colors duration-300">
                                {{ $flight->diem_di }} → {{ $flight->diem_den }}
                            </h2>
                            <p class="text-sm text-gray-600 mb-2">
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ \Carbon\Carbon::parse($flight->ngay_gio_khoi_hanh)->format('d/m/Y') }}
                            </p>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-500">From</p>
                                    @if($hasDiscount)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 line-through">
                                            VND {{ number_format($flight->gia_ve_co_ban) }}
                                        </p>
                                        <p class="text-xl font-bold text-red-600">
                                            VND {{ number_format($discountedPrice) }}
                                        </p>
                                    </div>
                                    @else
                                    <p class="text-xl font-bold text-teal-700">
                                        VND {{ number_format($flight->gia_ve_co_ban) }}
                                    </p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Available</p>
                                    <p class="text-sm font-semibold text-teal-700">
                                        {{ $flight->so_ghe_trong }} seats
                                    </p>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-plane-departure mr-1"></i>
                                    Oneway / Economy
                                </span>
                                <button class="bg-teal-700 text-white px-4 py-1 rounded-full text-sm transform transition-all duration-300 hover:bg-teal-800 hover:scale-105">
                                    Book Now
                                </button>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($flights instanceof \Illuminate\Pagination\LengthAwarePaginator && $flights->hasPages())
                <div class="mt-8 flex justify-center">
                    <ul class="flex space-x-2">
                        @if ($flights->onFirstPage())
                            <li class="px-3 py-2 border border-gray-300 rounded text-gray-400 cursor-not-allowed transition-all duration-300">&laquo;</li>
                        @else
                            <li>
                                <a href="{{ $flights->previousPageUrl() }}"
                                   class="px-3 py-2 border border-gray-300 rounded hover:bg-teal-700 hover:text-white transition-all duration-300">&laquo;</a>
                            </li>
                        @endif

                        @foreach ($flights->getUrlRange(1, $flights->lastPage()) as $page => $url)
                            <li>
                                <a href="{{ $url }}"
                                   class="px-3 py-2 border border-gray-300 rounded transition-all duration-300 {{ $flights->currentPage() == $page ? 'bg-teal-700 text-white' : 'hover:bg-teal-100' }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endforeach

                        @if ($flights->hasMorePages())
                            <li>
                                <a href="{{ $flights->nextPageUrl() }}"
                                   class="px-3 py-2 border border-gray-300 rounded hover:bg-teal-700 hover:text-white transition-all duration-300">&raquo;</a>
                            </li>
                        @else
                            <li class="px-3 py-2 border border-gray-300 rounded text-gray-400 cursor-not-allowed transition-all duration-300">&raquo;</li>
                        @endif
                    </ul>
                </div>
            @endif

            <p class="text-sm text-gray-500 mt-6 text-center">
                *Fares displayed have been collected within the last 48hrs and may no longer be available at time of booking. Additional fees and charges for optional products and services may apply.
            </p>
        </div>
    </section>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fadeIn 0.8s ease-out;
    }

    .flight-card {
        animation: fadeIn 0.5s ease-out;
        animation-fill-mode: both;
    }

    .flight-card:nth-child(1) { animation-delay: 0.1s; }
    .flight-card:nth-child(2) { animation-delay: 0.2s; }
    .flight-card:nth-child(3) { animation-delay: 0.3s; }
    .flight-card:nth-child(4) { animation-delay: 0.4s; }
    .flight-card:nth-child(5) { animation-delay: 0.5s; }
    .flight-card:nth-child(6) { animation-delay: 0.6s; }
    .flight-card:nth-child(7) { animation-delay: 0.7s; }
    .flight-card:nth-child(8) { animation-delay: 0.8s; }

    .service-card {
        background: linear-gradient(145deg, #ffffff, #f3f4f6);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .service-card:hover {
        background: linear-gradient(145deg, #f3f4f6, #ffffff);
    }

    .service-card i {
        transition: all 0.3s ease;
    }

    .service-card:hover i {
        transform: scale(1.1);
    }
</style>
