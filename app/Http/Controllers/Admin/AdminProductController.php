<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // อย่าลืมเรียก Model สินค้า
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function categoryIndex()
    {
        // ดึงหมวดหมู่ทั้งหมด และนับจำนวนสินค้าในแต่ละหมวดหมู่ (withCount)
        $categories = ProductCategory::withCount('products')->get();

        return view('admin.products.categories', compact('categories'));
    }

    public function index($id)
    {
        $category = ProductCategory::findOrFail($id);
        $products = Product::where('category_id', $id)->latest()->get();

        return view('admin.products.index', compact('category', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return back()->with('success', 'เพิ่มสินค้าสำเร็จ');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        // ... validate คล้ายๆ store ...
        $product->update($request->all());

        return back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
}
