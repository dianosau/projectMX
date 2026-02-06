<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|numeric|digits_between:9,10',
            'address_detail' => 'required|string',
            'subdistrict' => 'required|string',
            'district' => 'required|string',
            'province' => 'required|string',
            'zipcode' => 'required|string',
        ], [
            'name.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.unique' => 'อีเมลนี้ถูกใช้งานไปแล้ว',
            'password.required' => 'กรุณากำหนดรหัสผ่าน',
            'password.confirmed' => 'รหัสผ่านยืนยันไม่ตรงกัน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.numeric' => 'เบอร์โทรศัพท์ต้องเป็นตัวเลขเท่านั้น',
            'phone.digits_between' => 'เบอร์โทรศัพท์ต้องมีความยาว 9 ถึง 10 หลัก',
            'address_detail.required' => 'กรุณากรอกบ้านเลขที่/ถนน',
            'subdistrict.required' => 'กรุณากรอกตำบล',
            'district.required' => 'กรุณากรอกอำเภอ',
            'province.required' => 'กรุณากรอกจังหวัด',
            'zipcode.required' => 'กรุณากรอกรหัสไปรษณีย์',
        ]);

        try {
            // ใช้ Transaction เพื่อความปลอดภัย
            $user = DB::transaction(function () use ($request) {

                // 1. บันทึกข้อมูล User
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'role' => 'user',
                ]);

                // 2. บันทึกข้อมูลลงตาราง user_addresses
                UserAddress::create([
                    'user_id' => $user->id,
                    'address_detail' => $request->address_detail,
                    'subdistrict' => $request->subdistrict,
                    'district' => $request->district,
                    'province' => $request->province,
                    'zipcode' => $request->zipcode,
                    'is_default' => 1,
                ]);

                return $user;
            });

            $user->sendEmailVerificationNotification();

            return redirect('/email/verify')->with('success', 'สมัครสมาชิกสำเร็จ! กรุณายืนยันอีเมล');

        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: '.$e->getMessage())->withInput();
        }
    }

    public function verifyEmail($id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals(sha1($user->email), $hash)) {
            abort(403, 'ลิงก์ยืนยันไม่ถูกต้อง');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect('/login')->with('success', 'ยืนยันอีเมลสำเร็จ! กรุณาเข้าสู่ระบบ');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'กรุณากรอกอีเมล',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role !== 'admin' && ! $user->hasVerifiedEmail()) {
                Auth::logout();

                return redirect()->back()->with('error', 'กรุณายืนยันอีเมลก่อนเข้าสู่ระบบ');
            }
            $request->session()->regenerate();

            return redirect()->intended('/')->with('success', 'เข้าสู่ระบบสำเร็จ');
        }

        return redirect()->back()->with('error', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง')->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'ออกจากระบบแล้ว');
    }
}