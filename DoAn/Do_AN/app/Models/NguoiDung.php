<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'nguoi_dung';
    protected $primaryKey = 'id_nguoi_dung';
    public $timestamps = false; // Vì bảng không có `created_at` và `updated_at`

    protected $fillable = [
        'ho_ten',
        'email',
        'password', // Đúng tên cột cần lưu
        'so_dien_thoai',
        'ngay_tao',
        'role', // Đảm bảo Laravel nhận role
        'blocked_until', // Thêm cột này vào fillable
    ];
    
    protected $casts = [
        'blocked_until' => 'datetime', // Laravel sẽ tự động chuyển thành kiểu Carbon
    ];

    protected $hidden = ['password'];
    public function isAdmin()
{
    return strtolower($this->role) === 'admin';
}

    public function isUser()
    {
        return strtolower($this->role) === 'user';
    }
    // Đảm bảo Laravel sử dụng đúng mật khẩu để xác thực
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Đảm bảo cột `ngay_tao` luôn có giá trị
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->ngay_tao = now();
            $user->role = $user->role ?? 'User'; // Nếu chưa có role, đặt mặc định là user
        });
    }

    public function isBlocked()
    {
        return $this->blocked_until && Carbon::now()->lessThan($this->blocked_until);
    }

}



