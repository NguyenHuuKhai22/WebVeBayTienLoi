<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChuyenBay extends Model
{
    use SoftDeletes; // Thêm trait SoftDeletes
    protected $table = 'Chuyen_Bay';
    protected $primaryKey = 'id_chuyen_bay';
    public $timestamps = false;
    protected $fillable = [
        'ma_chuyen_bay',
        'diem_di',
        'diem_den',
        'ngay_gio_khoi_hanh',
        'ngay_gio_den',
        'gia_ve_co_ban',
        'so_ghe_trong',
        'id_hang_bay'
    ];
    protected $dates = ['ngay_gio_khoi_hanh', 'ngay_gio_den', 'deleted_at']; // Thêm vào đây
    public function hangBay()
    {
        return $this->belongsTo(HangBay::class, 'id_hang_bay', 'id_hang_bay');
    }

    public function veList()
    {
        return $this->hasMany(VeMayBay::class, 'id_chuyen_bay', 'id_chuyen_bay');
    }
}
