<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // 1. หน้าแสดง Profile และรายการที่อยู่
    public function index()
    {
        // ดึงที่อยู่ทั้งหมดของ User คนที่ Login อยู่มาโชว์
        $addresses = UserAddress::where('user_id', Auth::id())->get();
        
        return view('profile.index', compact('addresses'));
    }

    // 2. ฟังก์ชันบันทึกที่อยู่ใหม่ (ทำงานตอนกด Save ใน Modal)
    public function storeAddress(Request $request)
    {
        // ตรวจสอบข้อมูล
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_detail' => 'required|string',
        ]);

        // ถ้า User เลือกให้เป็น "ที่อยู่หลัก" (is_default) 
        // เราต้องไปยกเลิกที่อยู่หลักอันเก่าก่อน (ให้เหลืออันเดียว)
        if ($request->has('is_default')) {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => 0]);
        }

        // บันทึกลงฐานข้อมูล
        UserAddress::create([
            'user_id' => Auth::id(),
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'address_detail' => $request->address_detail,
            'is_default' => $request->has('is_default') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'บันทึกที่อยู่ใหม่เรียบร้อยแล้ว!');
    }
    public function setDefault($id)
{
    // 1. ยกเลิกที่อยู่หลักเดิมทั้งหมด
    UserAddress::where('user_id', Auth::id())->update(['is_default' => 0]);

    // 2. ตั้งอันที่เลือกเป็นที่อยู่หลัก
    $address = UserAddress::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
    $address->update(['is_default' => 1]);

    return back()->with('success', 'เปลี่ยนที่อยู่หลักเรียบร้อยแล้ว');
}
}