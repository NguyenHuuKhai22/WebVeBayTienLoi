<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    protected $table = 'thanh_toan';
    protected $primaryKey = 'id_thanh_toan';
    public $timestamps = false;
    protected $fillable = [
        'phuong_thuc',
        'so_tien',
        'ngay_thanh_toan',
        'trang_thai',
        'ma_giao_dich',
        'meta_data'
    ];

    public function veMayBay()
    {
        return $this->hasMany(VeMayBay::class, 'id_thanh_toan', 'id_thanh_toan');
    }
}