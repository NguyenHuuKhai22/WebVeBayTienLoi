<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vietnam Airlines')</title>
    
    <!-- Tải jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- CSS & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
    
    <!-- Thư viện Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <style>
        html {
            scroll-behavior: smooth;
        }
        
        .header-container {
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95);
            transition: all 0.3s ease;
        }
        
        .header-container.scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .nav-section {
            scroll-margin-top: 80px;
            background: linear-gradient(135deg, #0d9488 0%, #115e59 100%);
            position: relative;
            overflow: hidden;
        }
        
        .nav-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 25%, transparent 25%),
                        linear-gradient(-45deg, rgba(255,255,255,0.1) 25%, transparent 25%),
                        linear-gradient(45deg, transparent 75%, rgba(255,255,255,0.1) 75%),
                        linear-gradient(-45deg, transparent 75%, rgba(255,255,255,0.1) 75%);
            background-size: 20px 20px;
            opacity: 0.1;
            animation: patternMove 20s linear infinite;
        }
        
        @keyframes patternMove {
            0% { background-position: 0 0; }
            100% { background-position: 40px 40px; }
        }
        
        .hero-section {
            scroll-margin-top: 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
            pointer-events: none;
        }
        
        .hero-section img {
            transition: transform 0.5s ease;
        }
        
        .hero-section:hover img {
            transform: scale(1.05);
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: #0d9488;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::before {
            width: 100%;
        }
        
        .nav-link.active::before {
            width: 100%;
        }
        
        .scroll-indicator {
            height: 3px;
            background: linear-gradient(90deg, #0d9488 0%, #115e59 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            width: 0%;
            transition: width 0.2s ease;
            box-shadow: 0 0 10px rgba(13, 148, 136, 0.5);
        }
        
        .btn-hover-effect {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            z-index: 1;
        }
        
        .btn-hover-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: all 0.5s ease;
            z-index: -1;
        }
        
        .btn-hover-effect:hover::before {
            left: 100%;
        }
        
        .btn-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        /* Chat styles */
        #chat-container {
            position: fixed;
            bottom: 70px;
            right: 20px;
            width: 320px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            transform: translateY(20px) scale(0.95);
            opacity: 0;
            visibility: hidden;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        #chat-container.show {
            transform: translateY(0) scale(1);
            opacity: 1;
            visibility: visible;
        }
        
        #chat-messages {
            height: 300px;
            overflow-y: auto;
            padding: 15px;
            scroll-behavior: smooth;
        }
        
        #chat-input {
            border: 1px solid #ddd;
            border-right: none;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        #chat-input:focus {
            box-shadow: 0 0 0 2px rgba(13, 148, 136, 0.2);
            transform: translateY(-1px);
        }
        
        .typing-indicator {
            display: flex;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }
        
        .typing-indicator span {
            height: 8px;
            width: 8px;
            margin: 0 2px;
            background-color: #3b82f6;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.6;
        }
        
        .typing-indicator span:nth-child(1) {
            animation: bounce 1s infinite;
        }
        
        .typing-indicator span:nth-child(2) {
            animation: bounce 1s infinite 0.2s;
        }
        
        .typing-indicator span:nth-child(3) {
            animation: bounce 1s infinite 0.4s;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        .message {
            animation: fadeIn 0.3s ease;
            transform-origin: bottom;
            opacity: 0;
            transform: translateY(10px);
        }

        .message.show {
            opacity: 1;
            transform: translateY(0);
        }

        .message.user {
            animation: slideIn 0.3s ease;
        }

        .message.bot {
            animation: slideIn 0.3s ease;
        }

        #chat-button {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: scale(1);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        #chat-button:hover {
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        #chat-button.hidden {
            transform: scale(0);
            opacity: 0;
        }

        .chat-header {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #0d9488 0%, #115e59 100%);
        }

        .chat-header:hover {
            background: linear-gradient(135deg, #115e59 0%, #0d9488 100%);
        }

        .send-button {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #0d9488 0%, #115e59 100%);
        }

        .send-button:hover {
            transform: scale(1.1);
            background: linear-gradient(135deg, #115e59 0%, #0d9488 100%);
            box-shadow: 0 4px 15px rgba(13, 148, 136, 0.3);
        }

        .footer {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #0d9488, transparent);
        }
        
        @media (max-width: 640px) {
            #chat-container {
                width: 290px;
                right: 10px;
                bottom: 60px;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body class="font-roboto flex flex-col min-h-screen">
    <!-- Scroll Progress Indicator -->
    <div class="scroll-indicator" id="scrollIndicator"></div>

    <!-- Fixed Header -->
    <div class="header-container">
        <header>
            @include('partials.header')
        </header>
    </div>
    
    <!-- Hero Image Section -->
    @if(request()->route()->getName() == 'vietnam-airlines' || request()->route()->getName() == 'home')
    <section id="hero-section" class="relative hero-section">
        <img src="https://storage.googleapis.com/a1aa/image/PZkvokKsyEKNQbLRS4fNUF2KroZx7WkpIa--mL4CEcc.jpg"
            alt="Cityscape with fireworks" class="w-full h-96 object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
            <div class="text-center text-white p-4 transform transition-all duration-500 hover:scale-105">
                <h1 class="text-4xl font-bold mb-2 animate-fade-in">Chào mừng đến với Vietnam Airlines</h1>
                <p class="text-xl animate-fade-in" style="animation-delay: 0.2s">Hãng hàng không quốc gia Việt Nam</p>
            </div>
        </div>
    </section>
    @endif
    
    <!-- Navigation Buttons Section -->
    <section id="nav-section" class="bg-teal-700 py-4 nav-section">
        <div class="container mx-auto flex justify-center space-x-4">
            <a href="{{ route('flights.search') }}" class="inline-block bg-teal-800 text-white py-2 px-4 rounded hover:bg-teal-900 transition-colors btn-hover-effect">
                MUA VÉ
            </a>
            <button class="bg-teal-800 text-white py-2 px-4 rounded btn-hover-effect">QUẢN LÝ ĐẶT CHỖ</button>
            <button class="bg-teal-800 text-white py-2 px-4 rounded btn-hover-effect">LÀM THỦ TỤC</button>
        </div>
    </section>
    
    <!-- Main Content -->
    <main class="container mx-auto mt-4 flex-grow">
        @yield('content')
    </main>
    
    <!-- Chat Section -->
    <section class="fixed bottom-4 right-4 z-50">
        <!-- Nút chat -->
        <button id="chat-button" class="bg-teal-700 text-white py-2 px-4 rounded-full flex items-center space-x-2 shadow-lg hover:bg-teal-800 transition-colors">
            <i class="fas fa-comments"></i>
            <span>{{ __('Chat với NEO') }}</span>
        </button>
        
        <!-- Chat box -->
        <div id="chat-container" class="bg-white rounded-lg shadow-xl w-80 md:w-96 max-h-[500px] flex flex-col">
            <div class="chat-header text-white p-3 flex justify-between items-center rounded-t-lg">
                <div class="flex items-center">
                    <i class="fas fa-robot mr-2"></i>
                    <span>NEO - Trợ lý Vietnam Airlines</span>
                </div>
                <div id="close-chat" class="cursor-pointer hover:bg-teal-800 p-1 rounded transition-colors duration-300">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            
            <div id="chat-messages" class="p-3 overflow-y-auto flex-grow" style="max-height: 300px;">
                <div class="message bot flex mb-3 show">
                    <div class="bg-teal-100 rounded-lg py-2 px-3 max-w-[80%]">
                        <p>Xin chào! Tôi là NEO, trợ lý ảo của Vietnam Airlines. Tôi có thể giúp gì cho bạn hôm nay?</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t p-3">
                <form id="chat-form" class="flex">
                    @csrf
                    <input type="text" id="chat-input" class="flex-grow border rounded-l-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Nhập tin nhắn...">
                    <button type="submit" class="send-button text-white px-4 py-2 rounded-r-lg transition">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>
    
    <footer class="footer text-center mt-4 py-4">
        <p>&copy; {{ date('Y') }} - Vietnam Airlines - Hệ thống đặt vé máy bay</p>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Header scroll effect
            const header = document.querySelector('.header-container');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });

            // Scroll indicator
            window.addEventListener('scroll', function() {
                const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrolled = (winScroll / height) * 100;
                document.getElementById("scrollIndicator").style.width = scrolled + "%";
            });
            
            // Smooth scroll to sections
            document.querySelectorAll('.nav-link').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                        
                        document.querySelectorAll('.nav-link').forEach(link => {
                            link.classList.remove('active');
                        });
                        this.classList.add('active');
                    }
                });
            });
            
            // Section highlight on scroll
            window.addEventListener('scroll', function() {
                const scrollPosition = window.scrollY;
                
                document.querySelectorAll('section[id]').forEach(section => {
                    const sectionTop = section.offsetTop - 100;
                    const sectionHeight = section.offsetHeight;
                    const sectionId = section.getAttribute('id');
                    
                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        document.querySelectorAll('.nav-link').forEach(link => {
                            link.classList.remove('active');
                            if (link.getAttribute('href') === '#' + sectionId) {
                                link.classList.add('active');
                            }
                        });
                    }
                });
            });

            // Chat functionality
            const chatButton = document.getElementById('chat-button');
            const chatContainer = document.getElementById('chat-container');
            const closeChat = document.getElementById('close-chat');
            const chatForm = document.getElementById('chat-form');
            const chatInput = document.getElementById('chat-input');
            const chatMessages = document.getElementById('chat-messages');
            
            chatButton.addEventListener('click', function() {
                chatContainer.classList.add('show');
                chatButton.classList.add('hidden');
                chatInput.focus();
            });
            
            closeChat.addEventListener('click', function() {
                chatContainer.classList.remove('show');
                setTimeout(() => {
                    chatButton.classList.remove('hidden');
                }, 300);
            });
            
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const message = chatInput.value.trim();
                if (!message) return;
                
                appendMessage(message, 'user');
                chatInput.value = '';
                
                showTypingIndicator();
                
                fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideTypingIndicator();
                    
                    if (data.success) {
                        appendMessage(data.message, 'bot');
                    } else {
                        appendMessage('Xin lỗi, tôi gặp sự cố khi xử lý tin nhắn của bạn. Vui lòng thử lại sau.', 'bot');
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    hideTypingIndicator();
                    
                    appendMessage('Xin lỗi, đã xảy ra lỗi khi kết nối với máy chủ. Vui lòng thử lại sau.', 'bot');
                    console.error('Error:', error);
                });
            });
            
            function showTypingIndicator() {
                const typingEl = document.createElement('div');
                typingEl.classList.add('flex', 'mb-3', 'message', 'bot');
                typingEl.id = 'typing-indicator';
                typingEl.innerHTML = `
                    <div class="bg-teal-100 rounded-lg py-2 px-3">
                        <div class="typing-indicator">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                `;
                chatMessages.appendChild(typingEl);
                chatMessages.scrollTop = chatMessages.scrollHeight;
                setTimeout(() => typingEl.classList.add('show'), 10);
            }
            
            function hideTypingIndicator() {
                const typingEl = document.getElementById('typing-indicator');
                if (typingEl) {
                    typingEl.classList.remove('show');
                    setTimeout(() => typingEl.remove(), 300);
                }
            }
            
            function appendMessage(message, sender) {
                const messageEl = document.createElement('div');
                messageEl.classList.add('flex', 'mb-3', 'message', sender);
                
                if (sender === 'user') {
                    messageEl.classList.add('justify-end');
                    messageEl.innerHTML = `
                        <div class="bg-blue-100 rounded-lg py-2 px-3 max-w-[80%] ml-auto">
                            <p>${message}</p>
                        </div>
                    `;
                } else {
                    messageEl.innerHTML = `
                        <div class="bg-teal-100 rounded-lg py-2 px-3 max-w-[80%]">
                            <p>${message}</p>
                        </div>
                    `;
                }
                
                chatMessages.appendChild(messageEl);
                chatMessages.scrollTop = chatMessages.scrollHeight;
                setTimeout(() => messageEl.classList.add('show'), 10);
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>