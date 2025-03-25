<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $table = 'discount_codes';
    
    protected $fillable = [
        'user_id',
        'code',
        'discount_percent',
        'is_used',
    ];

    public function user()
    {
        return $this->belongsTo(NguoiDung::class, 'user_id', 'id_nguoi_dung');
    }
}
