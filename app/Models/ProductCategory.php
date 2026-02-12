<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

   public function products()
{
    // ระบุ 'category_id' หากในฐานข้อมูลใช้ชื่อนี้
    return $this->hasMany(Product::class, 'category_id'); 
}
}