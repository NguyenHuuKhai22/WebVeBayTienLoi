<header class="bg-white shadow-md fixed top-0 left-0 w-full z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-4">
            <!-- Logo - Left Section -->
            <div class="flex-shrink-0">
                <img src="https://storage.googleapis.com/a1aa/image/jy_WetrVFJx9_BsIHJscZSM2UO6S2R6N1xfhkrZmKpY.jpg"
                    alt="{{ __('Vietnam Airlines Logo') }}" class="h-12 transform transition duration-300 hover:scale-110">
            </div>

            <!-- Main Navigation - Center Section -->
            <nav class="hidden lg:flex flex-grow justify-center mx-4">
                <div class="flex items-center space-x-6">
                    <a class="text-gray-600 hover:text-[#0f766e] whitespace-nowrap transition duration-300 ease-in-out transform hover:scale-105" href="#" data-translate="Lên kế hoạch">{{ __('Lên kế hoạch') }}</a>
                    <a class="text-gray-600 hover:text-[#0f766e] whitespace-nowrap transition duration-300 ease-in-out transform hover:scale-105" href="#" data-translate="Thông tin hành trình">{{ __('Thông tin hành trình') }}</a>
                    <a class="text-gray-600 hover:text-[#0f766e] whitespace-nowrap transition duration-300 ease-in-out transform hover:scale-105" href="#" data-translate="Mua vé & Sản phẩm khác">
                        {{ __('Mua vé') }} & {{ __('Sản phẩm khác') }}
                    </a>
                    <a class="text-gray-600 hover:text-[#0f766e] whitespace-nowrap transition duration-300 ease-in-out transform hover:scale-105" href="#" data-translate="Lotusmiles">{{ __('Lotusmiles') }}</a>
                </div>
            </nav>

            <!-- Right Section: Language & User Actions -->
            <div class="flex items-center space-x-4">
                <!-- Language Selector -->
                <div class="flex items-center space-x-2">
                    <img id="viFlag" src="{{ asset('img/vietnam.png') }}" alt="Vietnamese"
                        class="h-8 w-8 hover:ring-2 hover:ring-blue-500 rounded-sm transition duration-300 cursor-pointer"
                        onclick="changeLanguage('vi')">
                    <img id="enFlag" src="{{ asset('img/english.png') }}" alt="English"
                        class="h-8 w-8 hover:ring-2 hover:ring-blue-500 rounded-sm transition duration-300 cursor-pointer"
                        onclick="changeLanguage('en')">
                </div>

                <!-- Divider -->
                <div class="hidden lg:block h-6 w-px bg-gray-300"></div>

                <!-- User Actions -->
                <div class="flex items-center space-x-4">
                    @auth
                    <div class="flex items-center space-x-4">
                        <!-- 👤 Tên người dùng là nút mở dropdown -->
                        <a href="javascript:void(0)" 
                        id="userDropdownButton"
                        class="text-gray-600 hover:text-[#0f766e] transition duration-300 ease-in-out transform hover:scale-105 whitespace-nowrap flex items-center"
                        data-translate="Chào">
                            <span class="hidden lg:inline">{{ __('Chào') }}, {{ Auth::user()->ho_ten }}</span>
                            <span class="lg:hidden">{{ Auth::user()->ho_ten }}</span>
                            <!-- Mũi tên chỉ hướng -->
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>

                    </div>

                    <!-- 🔽 Dropdown menu -->
                    <div class="relative">
                        <div id="userDropdownMenu"
                            class="hidden absolute right-0 mt-2 bg-white border rounded-lg shadow-md w-48 z-50">
                            <a href="{{ route('nguoidung.show', Auth::user()->id_nguoi_dung) }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">👤 Thông tin cá nhân</a>
                            <a href="{{ route('user.tickets') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">🎫 Danh sách vé</a>
                            <form action="{{ route('logout') }}" method="POST" class="block">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">🚪 Đăng xuất</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}"
                        class="text-gray-600 hover:text-[#0f766e] transition duration-300 ease-in-out transform hover:scale-105 whitespace-nowrap">
                            <span class="hidden lg:inline">ĐĂNG NHẬP</span>
                            <i class="fas fa-sign-in-alt lg:hidden"></i>
                        </a>
                        <a href="{{ route('register') }}"
                        class="bg-[#0f766e] text-white px-4 py-2 rounded-full hover:bg-[#0d6b63] transition duration-300 ease-in-out transform hover:scale-105 whitespace-nowrap">
                            <span class="hidden lg:inline">ĐĂNG KÝ</span>
                            <i class="fas fa-user-plus lg:hidden"></i>
                        </a>
                    </div>
                    @endauth
                </div>

        <!-- Mobile Navigation Menu -->
        <div class="lg:hidden flex overflow-x-auto py-2 no-scrollbar">
            <div class="flex space-x-4 px-2">
                <a class="text-gray-600 hover:text-[#0f766e] whitespace-nowrap transition duration-300 ease-in-out transform hover:scale-105 text-sm" href="#" data-translate="Lên kế hoạch">{{ __('Lên kế hoạch') }}</a>
                <a class="text-gray-600 hover:text-[#0f766e] whitespace-nowrap transition duration-300 ease-in-out transform hover:scale-105 text-sm" href="#" data-translate="Thông tin hành trình">{{ __('Thông tin hành trình') }}</a>
                <a class="text-gray-600 hover:text-[#0f766e] whitespace-nowrap transition duration-300 ease-in-out transform hover:scale-105 text-sm" href="#" data-translate="Mua vé & Sản phẩm khác">
                    {{ __('Mua vé') }} & {{ __('Sản phẩm khác') }}
                </a>
                <a class="text-gray-600 hover:text-[#0f766e] whitespace-nowrap transition duration-300 ease-in-out transform hover:scale-105 text-sm" href="#" data-translate="Lotusmiles">{{ __('Lotusmiles') }}</a>
            </div>
        </div>
    </div>
</header>

<style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>

<script>
    function changeLanguage(locale) {
        console.log("Changing language to:", locale);
        const currentLocale = document.documentElement.lang || 'vi';
        console.log("Current page language:", currentLocale);

        if (locale === currentLocale) {
            console.log("Language is already selected, no change needed.");
            return;
        }

        const viFlag = document.getElementById('viFlag');
        const enFlag = document.getElementById('enFlag');
        if (locale === 'vi') {
            viFlag.classList.add('ring-2', 'ring-blue-500');
            enFlag.classList.remove('ring-2', 'ring-blue-500');
        } else {
            enFlag.classList.add('ring-2', 'ring-blue-500');
            viFlag.classList.remove('ring-2', 'ring-blue-500');
        }

        window.location.href = '/locale/' + locale;
    }

    document.addEventListener("DOMContentLoaded", function() {
        console.log("Page Loaded");
        try {
            const currentLocale = document.documentElement.lang || 'vi';
            console.log("Detected locale:", currentLocale);
            const currentFlag = document.getElementById(currentLocale + 'Flag');
            if (currentFlag) {
                currentFlag.classList.add('ring-2', 'ring-blue-500');
            }
        } catch (error) {
            console.log("Could not highlight current language flag:", error);
        }
    });
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownBtn = document.getElementById('userDropdownButton');
        const dropdownMenu = document.getElementById('userDropdownMenu');

        if (dropdownBtn && dropdownMenu) {
            dropdownBtn.addEventListener('click', function () {
                dropdownMenu.classList.toggle('hidden');
            });

            // Ẩn dropdown khi click ra ngoài
            document.addEventListener('click', function (event) {
                if (!dropdownBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        }
    });
</script>