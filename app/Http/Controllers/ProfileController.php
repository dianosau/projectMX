<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // App/Http/Controllers/ProfileController.php

    public function index()
    {
        // ดึงที่อยู่ทั้งหมดของผู้ใช้ โดยเรียงลำดับให้ที่อยู่หลักขึ้นก่อน
        $addresses = \App\Models\UserAddress::where('user_id', auth()->id())->get();

        return view('profile.index', compact('addresses'));
    }

    public function updateInfo(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return back()->with('success', 'อัปเดตข้อมูลโปรไฟล์เรียบร้อยแล้ว');
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'address_detail' => 'required',
            'subdistrict' => 'required',
            'district' => 'required',
            'province' => 'required',
            'zipcode' => 'required',
        ]);

        // ถ้าตั้งเป็นที่อยู่หลัก ให้ยกเลิกที่อยู่หลักเดิมก่อน
        if ($request->has('is_default')) {
            \App\Models\UserAddress::where('user_id', auth()->id())->update(['is_default' => 0]);
        }

        \App\Models\UserAddress::create([
            'user_id' => auth()->id(),
            'recipient_name' => auth()->user()->name,
            'phone' => auth()->user()->phone ?? '-',
            'address_detail' => $request->address_detail,
            'subdistrict' => $request->subdistrict,
            'district' => $request->district,
            'province' => $request->province,
            'zipcode' => $request->zipcode,
            'is_default' => $request->has('is_default') ? 1 : 0,
        ]);

        return back()->with('success', 'เพิ่มที่อยู่ใหม่เรียบร้อยแล้ว');
    }

    public function updateAddress(Request $request, $id)
    {
        $address = \App\Models\UserAddress::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($request->has('is_default')) {
            \App\Models\UserAddress::where('user_id', auth()->id())->update(['is_default' => 0]);
        }

        $address->update($request->all() + ['is_default' => $request->has('is_default') ? 1 : 0]);

        return back()->with('success', 'อัปเดตที่อยู่เรียบร้อยแล้ว');
    }

    public function deleteAddress($id)
    {
        \App\Models\UserAddress::where('id', $id)->where('user_id', auth()->id())->delete();

        return back()->with('success', 'ลบที่อยู่เรียบร้อยแล้ว');
    }

    public function setDefaultAddress($id)
    {
        \App\Models\UserAddress::where('user_id', auth()->id())->update(['is_default' => 0]);
        \App\Models\UserAddress::where('id', $id)->where('user_id', auth()->id())->update(['is_default' => 1]);

        return back()->with('success', 'ตั้งเป็นที่อยู่หลักเรียบร้อยแล้ว');
    }
}
