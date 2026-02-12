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
        'shipping_status',          // เพิ่ม: สำหรับสถานะโปรเจค (pending, processing, shipping, completed)
        'shipping_address',
        'payment_method',
        'tracking_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

       public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }
}