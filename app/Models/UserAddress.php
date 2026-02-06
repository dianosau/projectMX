<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    // กำหนดชื่อตารางให้ตรงกับ SQL ที่เราสร้าง
    protected $table = 'user_addresses';

    // อนุญาตให้บันทึกข้อมูลในคอลัมน์เหล่านี้
    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'address_detail',
        'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}