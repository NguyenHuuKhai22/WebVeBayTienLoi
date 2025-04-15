<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeMayBay extends Model
{
    protected $table = 've_may_bay';
    protected $primaryKey = 'id_ve';
    public $timestamps = false;
    protected $fillable = [
        'id_nguoi_dung',
        'id_chuyen_bay',
        'ma_ve',
        'loai_ghe',
        'so_ghe',
        'gia_ve',
         'khoi_luong_dang_ky',
        'phi_hanh_ly',
        'ngay_dat',
        'trang_thai',
        'id_thanh_toan',
       
    ];

    // Đảm bảo eager loading các relationship
    protected $with = ['chuyenBay', 'nguoiDung'];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung', 'id_nguoi_dung');
    }

    public function chuyenBay()
    {
        return $this->belongsTo(ChuyenBay::class, 'id_chuyen_bay', 'id_chuyen_bay');
    }

    public function thanhToan()
    {
        return $this->belongsTo(ThanhToan::class, 'id_thanh_toan', 'id_thanh_toan');
    }
}