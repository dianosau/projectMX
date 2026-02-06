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
        // ดึงที่อยู่ทั้งหมดของ User คนที่ Login อยู่มาโชว์ พร้อมข้อมูล User (เพื่อใช้ชื่อและเบอร์โทร)
        $addresses = UserAddress::with('user')->where('user_id', Auth::id())->get();
        
        return view('profile.index', compact('addresses'));
    }

    // 2. ฟังก์ชันบันทึกที่อยู่ใหม่ (ทำงานตอนกด Save ใน Modal)
    public function storeAddress(Request $request)
    {
        // ตรวจสอบข้อมูลให้ตรงกับฟิลด์ใน Form
        $request->validate([
            'address_detail' => 'required|string|max:255',
            'subdistrict'    => 'required|string|max:100',
            'district'       => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            'zipcode'        => 'required|string|max:10',
        ]);

        // ถ้า User เลือกให้เป็น "ที่อยู่หลัก" (is_default) 
        // ให้ไปยกเลิกที่อยู่หลักอันเก่าของ User คนนี้ก่อน
        if ($request->has('is_default')) {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => 0]);
        }

        // บันทึกลงฐานข้อมูลตามโครงสร้าง Model UserAddress
        UserAddress::create([
            'user_id'        => Auth::id(),
            'address_detail' => $request->address_detail,
            'subdistrict'    => $request->subdistrict,
            'district'       => $request->district,
            'province'       => $request->province,
            'zipcode'        => $request->zipcode,
            'is_default'     => $request->has('is_default') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'บันทึกที่อยู่ใหม่เรียบร้อยแล้ว!');
    }

    // 3. ฟังก์ชันตั้งค่าที่อยู่หลัก
    public function setDefault($id)
    {
        // ยกเลิกที่อยู่หลักเดิมทั้งหมดของ User นี้
        UserAddress::where('user_id', Auth::id())->update(['is_default' => 0]);

        // ตั้งอันที่เลือกเป็นที่อยู่หลัก (ตรวจสอบสิทธิ์เจ้าของที่อยู่ด้วย where user_id)
        $address = UserAddress::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $address->update(['is_default' => 1]);

        return back()->with('success', 'เปลี่ยนที่อยู่หลักเรียบร้อยแล้ว');
    }
}