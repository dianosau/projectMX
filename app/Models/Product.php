<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

   protected $fillable = [
    'name',
    'color',      // เพิ่ม
    'color_hex',  // เพิ่ม
    'description',
    'price',
    'stock',
    'is_active',  // เพิ่ม
    'category_id',
    'image'
];

    // ความสัมพันธ์กับ Category
    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}