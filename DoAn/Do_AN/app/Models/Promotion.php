<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Promotion extends Model
{
    use SoftDeletes;

    protected $table = 'promotions';
    protected $primaryKey = 'id_khuyen_mai';

    protected $fillable = [
        'ten_khuyen_mai',
        'mo_ta',
        'phan_tram_giam',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'trang_thai'
    ];

    protected $casts = [
        'thoi_gian_bat_dau' => 'datetime',
        'thoi_gian_ket_thuc' => 'datetime',
        'trang_thai' => 'boolean',
        'phan_tram_giam' => 'decimal:2'
    ];

    public function chuyenBays()
    {
        return $this->belongsToMany(ChuyenBay::class, 'chuyen_bay_khuyen_mai', 'id_khuyen_mai', 'id_chuyen_bay')
                    ->withTimestamps();
    }

    public function isActive()
    {
        return $this->trang_thai && 
               $this->thoi_gian_bat_dau <= now() && 
               $this->thoi_gian_ket_thuc >= now();
    }
    
    public function getThoi_gian_bat_dauFormatAttribute()
    {
        return $this->thoi_gian_bat_dau ? Carbon::parse($this->thoi_gian_bat_dau)->format('Y-m-d\TH:i') : '';
    }
    
    public function getThoi_gian_ket_thucFormatAttribute()
    {
        return $this->thoi_gian_ket_thuc ? Carbon::parse($this->thoi_gian_ket_thuc)->format('Y-m-d\TH:i') : '';
    }
} 