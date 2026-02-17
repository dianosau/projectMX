<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    /**
     * แสดงรายการหมวดหมู่ทั้งหมด
     */
    public function categoryIndex()
    {
        $categories = ProductCategory::withCount('products')->get();
        return view('admin.products.categories', compact('categories'));
    }

    /**
     * แสดงรายการสินค้าตามหมวดหมู่
     */
    public function index($id)
    {
        $category = ProductCategory::findOrFail($id);
        $products = Product::where('category_id', $id)->latest()->get();
        return view('admin.products.index', compact('category', 'products'));
    }

    /**
     * บันทึกสินค้าใหม่
     */
    public function store(Request $request)
    {
        // 1. Data Validation
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:product_categories,id',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // ตรวจสอบไฟล์ภาพ
            'image_url'   => 'nullable|url', // ตรวจสอบรูปแบบ URL
        ], [
            'category_id.exists' => 'ไม่พบหมวดหมู่ที่ระบุในระบบ'
        ]);

        // 2. จัดการรูปภาพ (ลำดับความสำคัญ: ไฟล์อัปโหลด > URL)
        $imagePath = $request->image_url; // ใช้ URL เป็นค่าเริ่มต้น (ถ้ามี)

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // 3. บันทึกข้อมูล
        Product::create([
            'name'        => $validated['name'],
            'price'       => $validated['price'],
            'stock'       => $validated['stock'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'] ?? '',
            'image'       => $imagePath,
        ]);

        return back()->with('success', 'เพิ่มสินค้าใหม่เรียบร้อยแล้ว');
    }

    /**
     * อัปเดตข้อมูลสินค้า
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url'   => 'nullable|url',
        ]);

        $imagePath = $product->image; // ใช้รูปเดิมเป็นค่าเริ่มต้น

        // ถ้ามีการอัปโหลดไฟล์ใหม่ หรือใส่ URL ใหม่
        if ($request->filled('image_url')) {
            // ถ้าของเดิมเป็นไฟล์ ให้ลบไฟล์เก่าออกก่อน
            if ($product->image && !str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->image_url;
        } 
        
        if ($request->hasFile('image')) {
            // ลบไฟล์เก่าออกก่อนอัปโหลดใหม่
            if ($product->image && !str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name'        => $validated['name'],
            'price'       => $validated['price'],
            'stock'       => $validated['stock'],
            'description' => $validated['description'] ?? '',
            'image'       => $imagePath,
        ]);

        return back()->with('success', 'แก้ไขข้อมูลสินค้าสำเร็จ');
    }

    /**
     * ลบสินค้า
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // ลบไฟล์ภาพจาก Storage หากไม่ใช่ URL
        if ($product->image && !str_starts_with($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return back()->with('success', 'ลบสินค้าเรียบร้อยแล้ว');
    }

    /* -------------------------------------------------------------------------- */
    /* ส่วนจัดการหมวดหมู่ (Category Management) */
    /* -------------------------------------------------------------------------- */

    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:product_categories,name',
            'description' => 'nullable|string',
            'image'       => 'nullable|string', // URL รูปภาพหมวดหมู่
        ]);

        ProductCategory::create($validated);
        return back()->with('success', 'เพิ่มหมวดหมู่สำเร็จ');
    }

    public function categoryUpdate(Request $request, $id)
    {
        $category = ProductCategory::findOrFail($id);
        
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:product_categories,name,' . $id,
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
        ]);

        $category->update($validated);
        return back()->with('success', 'อัปเดตหมวดหมู่สำเร็จ');
    }

    public function categoryDestroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        
        // ตรวจสอบก่อนว่ามีสินค้าในหมวดนี้ไหม (Optional)
        if ($category->products()->count() > 0) {
            return back()->with('error', 'ไม่สามารถลบได้ เนื่องจากมีสินค้าอยู่ในหมวดหมู่นี้');
        }

        $category->delete();
        return back()->with('success', 'ลบหมวดหมู่เรียบร้อยแล้ว');
    }
}