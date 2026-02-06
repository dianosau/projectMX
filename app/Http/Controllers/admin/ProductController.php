<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // อย่าลืมเรียก Model สินค้า
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 1. หน้าแสดงรายการสินค้าทั้งหมด
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.products.index', compact('products'));
    }

    // 2. หน้าฟอร์มสำหรับเพิ่มสินค้า
    public function create()
    {
        return view('admin.products.create');
    }

    // 3. บันทึกข้อมูลสินค้าใหม่
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // บังคับใส่รูป
        ]);

        // จัดการอัปโหลดรูปภาพ
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'เพิ่มสินค้าสำเร็จ!');
    }

    // 4. ลบสินค้า
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // ลบรูปภาพออกจาก Storage ด้วย
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return back()->with('success', 'ลบสินค้าเรียบร้อยแล้ว');
    }
}