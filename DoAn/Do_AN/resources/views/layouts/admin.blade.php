<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/styleAdmin.css') }}?v={{ time() }}" rel="stylesheet">
    <style>

    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-chart-line"></i>
            <span>Admin Panel</span>
        </div>
    </div>
    <div class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-chevron-left"></i>
    </div>
    <ul class="sidebar-nav">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" data-toggle="submenu">
                <i class="fas fa-users"></i>
                <span class="nav-text">Người dùng</span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.nguoi_dung.index') }}" class="nav-link {{ request()->routeIs('admin.nguoi_dung.index') ? 'active' : '' }}">Danh sách người dùng</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" data-toggle="submenu">
                <i class="fas fa-shopping-cart"></i>
                <span class="nav-text">Quản lý chuyến bay</span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.chuyenbay.index') }}" class="nav-link {{ request()->routeIs('admin.chuyenbay.index') ? 'active' : '' }}" class="nav-link">Danh sách chuyến bay</a></li>
                <li> <a href="{{ route('admin.chuyenbay.trashed') }}" class="nav-link {{ request()->routeIs('admin.chuyenbay.trashed') ? 'active' : '' }}" class="nav-link">Xem danh sách đã xóa</a></li>
            </ul>
           
           
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" data-toggle="submenu">
                <i class="fas fa-box"></i>
                <span class="nav-text">Quản Lý Hãng Bay</span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.hangbay.index') }}" class="nav-link {{ request()->routeIs('admin.hangbay.index') ? 'active' : '' }}">Danh sách hãng bay</a></li>
                <li><a href="{{ route('admin.hangbay.deleteAt') }}" class="nav-link {{ request()->routeIs('admin.hangbay.deleteAt') ? 'active' : '' }}">Danh sách đã xóa</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" data-toggle="submenu">
                <i class="fas fa-ticket-alt"></i>
                <span class="nav-text">Quản lý vé</span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.ve-may-bay.index') }}" class="nav-link {{ request()->routeIs('admin.ve-may-bay.index') ? 'active' : '' }}">Danh sách vé</a></li>
                <li><a href="{{ route('admin.thanh-toan.index') }}" class="nav-link {{ request()->routeIs('admin.thanh-toan.index') ? 'active' : '' }}">Quản lý thanh toán</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" data-toggle="submenu">
                <i class="fas fa-percent"></i>
                <span class="nav-text">Quản lý khuyến mãi</span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.promotions.index') }}" class="nav-link {{ request()->routeIs('admin.promotions.index') ? 'active' : '' }}">Danh sách khuyến mãi</a></li>
                <li><a href="{{ route('admin.promotions.create') }}" class="nav-link {{ request()->routeIs('admin.promotions.create') ? 'active' : '' }}">Thêm khuyến mãi</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" data-toggle="submenu">
                <i class="fas fa-cogs"></i>
                <span class="nav-text">Cài đặt</span>
            </a>
            <ul class="submenu">
                <li><a href="#" class="nav-link">Cấu hình hệ thống</a></li>
                <li><a href="#" class="nav-link">Quản lý tài khoản</a></li>
            </ul>
        </li>
    </ul>
</div>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <div class="dashboard-header">
                <div class="welcome-text">
                    <h2>Chào mừng trở lại, Admin!</h2>
                    <p>Đây là bảng điều khiển của bạn.</p>
                </div>
                <div class="user-actions">
                    <div class="user-profile">
                        <div class="user-avatar">A</div>
                        <div class="user-info">
                            <span class="user-name">{{ $user->ho_ten }}!</span>
                            <span class="user-role">{{ $user->role }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-danger">Đăng xuất</button>
                    </form>
                </div>
            </div>

            @yield('content')
        </div>

        <script>
document.addEventListener("DOMContentLoaded", function() {
    // Sidebar toggle
    document.getElementById("sidebarToggle").addEventListener("click", function() {
        document.getElementById("sidebar").classList.toggle("collapsed");
        document.getElementById("mainContent").classList.toggle("expanded");
    });

    // Submenu toggle
    document.querySelectorAll('.nav-link[data-toggle="submenu"]').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            const submenu = parent.querySelector(".submenu");
            
            if (submenu) {
                submenu.style.display = submenu.style.display === "block" ? "none" : "block";
            }
            
            // Đóng các submenu khác
            document.querySelectorAll('.nav-item').forEach(otherItem => {
                if (otherItem !== parent) {
                    const otherSubmenu = otherItem.querySelector(".submenu");
                    if (otherSubmenu) {
                        otherSubmenu.style.display = "none";
                    }
                }
            });

            // Xóa class active từ tất cả các link
            document.querySelectorAll('.nav-link').forEach(nav => {
                nav.classList.remove('active');
            });

            // Thêm class active cho menu cha khi click vào nó
            this.classList.add('active');
        });
    });

    // Xử lý click vào các link trong submenu để tô đậm cả cha và con
    document.querySelectorAll('.submenu .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            // Xóa class active từ tất cả các link
            document.querySelectorAll('.nav-link').forEach(nav => {
                nav.classList.remove('active');
            });

            // Thêm class active cho link con được click
            this.classList.add('active');

            // Thêm class active cho link cha
            const parentItem = this.closest('.nav-item');
            if (parentItem) {
                const parentLink = parentItem.querySelector('.nav-link[data-toggle="submenu"]');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
            }
        });
    });

    // Xử lý các link không có submenu (như Dashboard)
    document.querySelectorAll('.nav-link:not([data-toggle="submenu"])').forEach(link => {
        if (!link.closest('.submenu')) {
            link.addEventListener('click', function(e) {
                // Xóa class active từ tất cả các link
                document.querySelectorAll('.nav-link').forEach(nav => {
                    nav.classList.remove('active');
                });

                // Thêm class active cho link được click
                this.classList.add('active');
            });
        }
    });

    // Kiểm tra route hiện tại để tô đậm cả cha và con khi tải trang
    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.classList.contains('active')) {
            // Nếu link là menu con, tô đậm cả menu cha
            const parentItem = link.closest('.nav-item');
            if (parentItem && link.closest('.submenu')) {
                const parentLink = parentItem.querySelector('.nav-link[data-toggle="submenu"]');
                if (parentLink) {
                    parentLink.classList.add('active');
                    // Đảm bảo submenu mở
                    const submenu = parentItem.querySelector('.submenu');
                    if (submenu) {
                        submenu.style.display = 'block';
                    }
                }
            }
        }
    });
});
</script>
</body>

</html>