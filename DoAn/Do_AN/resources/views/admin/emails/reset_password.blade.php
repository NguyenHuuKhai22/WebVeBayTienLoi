<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mật khẩu mới của bạn</title>
</head>
<body>
    <h2>Xin chào {{ $hoTen }},</h2>
    <p>Quản trị viên đã reset mật khẩu của bạn. Đây là mật khẩu mới:</p>
    <h3>{{ $newPassword }}</h3>
    <p>Hãy đăng nhập và đổi mật khẩu ngay lập tức để bảo vệ tài khoản của bạn.</p>
    <p>Trân trọng,</p>
    <p>Đội ngũ quản trị</p>
</body>
</html>