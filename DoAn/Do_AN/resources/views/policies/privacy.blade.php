@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6">Chính Sách Quyền Riêng Tư</h1>
        
        <div class="space-y-6 text-gray-600">
            <section>
                <h2 class="text-xl font-semibold mb-3">1. Thông Tin Chúng Tôi Thu Thập</h2>
                <p>Khi bạn sử dụng dịch vụ của VietnamAirline, chúng tôi có thể thu thập các thông tin sau:</p>
                <ul class="list-disc ml-6 mt-2">
                    <li>Thông tin cá nhân (họ tên, email, số điện thoại)</li>
                    <li>Thông tin đăng nhập và tài khoản</li>
                    <li>Thông tin đặt vé và giao dịch</li>
                    <li>Thông tin thiết bị và trình duyệt</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold mb-3">2. Cách Chúng Tôi Sử Dụng Thông Tin</h2>
                <p>Chúng tôi sử dụng thông tin thu thập được để:</p>
                <ul class="list-disc ml-6 mt-2">
                    <li>Cung cấp và cải thiện dịch vụ</li>
                    <li>Xử lý đặt vé và thanh toán</li>
                    <li>Liên lạc về đặt vé và dịch vụ</li>
                    <li>Bảo mật tài khoản</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold mb-3">3. Bảo Mật Thông Tin</h2>
                <p>Chúng tôi cam kết bảo vệ thông tin của bạn bằng cách:</p>
                <ul class="list-disc ml-6 mt-2">
                    <li>Sử dụng mã hóa SSL/TLS</li>
                    <li>Giới hạn quyền truy cập thông tin</li>
                    <li>Thường xuyên cập nhật biện pháp bảo mật</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold mb-3">4. Quyền Của Người Dùng</h2>
                <p>Bạn có quyền:</p>
                <ul class="list-disc ml-6 mt-2">
                    <li>Truy cập thông tin cá nhân</li>
                    <li>Yêu cầu chỉnh sửa thông tin</li>
                    <li>Yêu cầu xóa tài khoản</li>
                    <li>Từ chối tiếp thị trực tiếp</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold mb-3">5. Liên Hệ</h2>
                <p>Nếu bạn có câu hỏi về chính sách quyền riêng tư, vui lòng liên hệ:</p>
                <ul class="list-none mt-2">
                    <li>Email: privacy@vietnamairline.com</li>
                    <li>Điện thoại: 1900 xxxx</li>
                    <li>Địa chỉ: [Địa chỉ công ty]</li>
                </ul>
            </section>

            <section class="mt-8">
                <p class="text-sm">Cập nhật lần cuối: {{ date('d/m/Y') }}</p>
            </section>
        </div>
    </div>
</div>
@endsection