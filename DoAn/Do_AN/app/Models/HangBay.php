<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes; // Thêm SoftDeletes

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class HangBay extends Model
{
    use HasFactory, SoftDeletes; // Kích hoạt SoftDeletes
    protected $table = 'Hang_Bay';
    protected $primaryKey = 'id_hang_bay';
    public $timestamps = false;
    protected $fillable = ['ten_hang_bay', 'logo']; 
  
    protected $dates = ['deleted_at']; // Cột deleted_at để xóa mềm
    public function chuyenBayList()
    {
        return $this->hasMany(ChuyenBay::class, 'id_hang_bay', 'id_hang_bay');
    }
}
