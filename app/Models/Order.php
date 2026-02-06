<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'payment_status',  // สำหรับเช็คว่าจ่ายเงินหรือยัง (เช่น pending, paid)
        'status',          // เพิ่ม: สำหรับสถานะโปรเจค (pending, processing, shipping, completed)
        'shipping_address',
        'payment_method',
        'tracking_number',  // เพิ่ม: สำหรับใส่เลขพัสดุ
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}