<!-- resources/views/vietnam_airlines.blade.php -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
   <meta charset="utf-8" />
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>Vietnam Airlines</title>
   <script src="https://cdn.tailwindcss.com"></script>
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
   <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
</head>
<style>
   #chat-container {
       position: absolute;
       bottom: 70px;
       right: 0;
       width: 320px;
       box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
       border-radius: 10px;
       transition: all 0.3s ease;
   }
   
   #chat-messages {
       height: 300px;
       overflow-y: auto;
       padding: 15px;
   }
   
   #chat-input {
       border: 1px solid #ddd;
       border-right: none;
   }
   
   .typing-indicator {
       display: flex;
       align-items: center;
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
   
   @media (max-width: 640px) {
       #chat-container {
           width: 290px;
       }
   }
</style>
<body class="font-roboto">
  <!-- Header cố định -->
  <div class="fixed top-0 left-0 w-full z-50 bg-white shadow-md">
       @include('partials.header')
   </div>

   <main>
       <section class="relative">
           <img src="https://storage.googleapis.com/a1aa/image/PZkvokKsyEKNQbLRS4fNUF2KroZx7WkpIa--mL4CEcc.jpg"
               alt="Cityscape with fireworks" class="w-full h-96 object-cover">
       </section>

       <section class="bg-teal-700 py-4">
           <div class="container mx-auto flex justify-center space-x-4">
               <a href="http://127.0.0.1:8000/search" class="inline-block bg-teal-800 text-white py-2 px-4 rounded hover:bg-teal-900 transition-colors">
               {{ __('MUA VÉ') }}
               </a>
               <a href="http://127.0.0.1:8000/checkin" class="inline-block bg-teal-800 text-white py-2 px-4 rounded hover:bg-teal-900 transition-colors">
                {{ __('QUẢN LÝ ĐẶT CHỖ') }}
                </a>
               <button class="bg-teal-800 text-white py-2 px-4 rounded">{{ __(key: 'LÀM THỦ TỤC') }}
               </button>
           </div>
       </section>

       @include('partials.flights', ['flights' => $flights])
   </main>
   @include('partials.footer')
   <!-- Chat Section -->
   <section class="fixed bottom-4 right-4 z-50">
        <!-- Nút chat -->
        <button id="chat-button" class="bg-teal-700 text-white py-2 px-4 rounded-full flex items-center space-x-2 shadow-lg hover:bg-teal-800 transition-colors">
            <i class="fas fa-comments"></i>
            <span>{{ __('Chat với NEO') }}</span>
        </button>
        
        <!-- Chat box -->
        <div id="chat-container" class="hidden bg-white rounded-lg shadow-xl w-80 md:w-96 max-h-[500px] flex flex-col">
            <div class="bg-teal-700 text-white p-3 flex justify-between items-center rounded-t-lg">
                <div class="flex items-center">
                    <i class="fas fa-robot mr-2"></i>
                    <span>NEO - Trợ lý Vietnam Airlines</span>
                </div>
                <div id="close-chat" class="cursor-pointer hover:bg-teal-800 p-1 rounded">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            
            <div id="chat-messages" class="p-3 overflow-y-auto flex-grow" style="max-height: 300px;">
                <div class="flex mb-3">
                    <div class="bg-teal-100 rounded-lg py-2 px-3 max-w-[80%]">
                        <p>Xin chào! Tôi là NEO, trợ lý ảo của Vietnam Airlines. Tôi có thể giúp gì cho bạn hôm nay?</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t p-3">
                <form id="chat-form" class="flex">
                    @csrf
                    <input type="text" id="chat-input" class="flex-grow border rounded-l-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Nhập tin nhắn...">
                    <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded-r-lg hover:bg-teal-800 transition">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>
   <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scroll indicator
            window.addEventListener('scroll', function() {
                const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrolled = (winScroll / height) * 100;
                document.getElementById("scrollIndicator").style.width = scrolled + "%";
            });
            
            // Smooth scroll to sections when clicking navigation links
            document.querySelectorAll('.nav-link').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                        
                        // Update active link
                        document.querySelectorAll('.nav-link').forEach(link => {
                            link.classList.remove('active');
                        });
                        this.classList.add('active');
                    }
                });
            });
            
            // Add scroll event listener to highlight current section in navigation
            window.addEventListener('scroll', function() {
                const scrollPosition = window.scrollY;
                
                // Check which section is currently in view
                const sections = document.querySelectorAll('section[id]');
                sections.forEach(section => {
                    const sectionTop = section.offsetTop - 100; // Adjust offset as needed
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
            
            // Mở chat khi nhấn vào nút chat
            chatButton.addEventListener('click', function() {
                chatContainer.classList.remove('hidden');
                chatButton.classList.add('hidden');
                chatInput.focus();
            });
            
            // Đóng chat
            closeChat.addEventListener('click', function() {
                chatContainer.classList.add('hidden');
                chatButton.classList.remove('hidden');
            });
            
            // Xử lý submit form
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const message = chatInput.value.trim();
                if (!message) return;
                
                // Hiển thị tin nhắn của người dùng
                appendMessage(message, 'user');
                chatInput.value = '';
                
                // Hiển thị trạng thái đang nhập
                showTypingIndicator();
                
                // Gọi API để lấy phản hồi
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
                    // Ẩn trạng thái đang nhập
                    hideTypingIndicator();
                    
                    if (data.success) {
                        appendMessage(data.message, 'bot');
                    } else {
                        appendMessage('Xin lỗi, tôi gặp sự cố khi xử lý tin nhắn của bạn. Vui lòng thử lại sau.', 'bot');
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    // Ẩn trạng thái đang nhập
                    hideTypingIndicator();
                    
                    appendMessage('Xin lỗi, đã xảy ra lỗi khi kết nối với máy chủ. Vui lòng thử lại sau.', 'bot');
                    console.error('Error:', error);
                });
            });
            
            // Hiển thị trạng thái đang nhập
            function showTypingIndicator() {
                const typingEl = document.createElement('div');
                typingEl.classList.add('flex', 'mb-3');
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
            }
            
            // Ẩn trạng thái đang nhập
            function hideTypingIndicator() {
                const typingEl = document.getElementById('typing-indicator');
                if (typingEl) {
                    typingEl.remove();
                }
            }
            
            // Thêm tin nhắn vào khung chat
            function appendMessage(message, sender) {
                const messageEl = document.createElement('div');
                messageEl.classList.add('flex', 'mb-3');
                
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
            }
        });
    </script>   
</body>

</html>