<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    // กำหนดฟิลด์ที่อนุญาตให้บันทึกข้อมูลได้
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];

    // เชื่อมความสัมพันธ์ไปที่ Model Product 
    // เพื่อให้เราดึง ชื่อสินค้า, ราคา, หรือรูปภาพ มาโชว์ในหน้าตะกร้าได้
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // เชื่อมความสัมพันธ์กลับไปที่ User (เผื่อต้องใช้เช็คเจ้าของ)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}