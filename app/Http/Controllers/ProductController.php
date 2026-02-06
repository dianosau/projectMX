<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showAllProduct(Request $request)
    {
        // 1. เริ่มต้น Query Builder (ยังไม่ได้ดึงข้อมูลจริง)
        $query = Product::query();

        // 2. ตรวจสอบว่ามีการค้นหา (ตัวแปร query) ส่งมาจาก Navbar หรือไม่
        if ($request->filled('query')) {
            $search = $request->input('query');

            // เพิ่มเงื่อนไขการค้นหา
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");        // ค้นจากชื่อ
            });
        }

        // 3. เพิ่มเงื่อนไขพื้นฐาน (เช่น ต้องเป็นสินค้าที่ Active) และเรียงลำดับ
        // คำสั่ง get() จะทำการดึงข้อมูลตามเงื่อนไขข้างบนทั้งหมด
        $products = $query->where('is_active', true)
            ->latest()
            ->get();

        // 4. ส่งข้อมูลไปที่ View เดิม (all-product.blade.php)
        return view('products.all-product', compact('products'));
    }

    public function showByCategory($id)
    {
        $category = ProductCategory::findOrFail($id);
        $products = Product::where('category_id', $id)->get();

        return view('products.by-category', compact('category', 'products'));
    }

    public function show($id)
    {
        // ดึงข้อมูลสินค้าพร้อมหมวดหมู่
        $product = Product::with('category')->findOrFail($id);

        // แนะนำสินค้าใกล้เคียง (ตัวเลือกเสริมสำหรับร้านค้าจริงจัง)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
