<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'address' => 'nullable',
            'phone' => 'nullable|numeric',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone' => $request->phone,
            'role' => 'user' // ค่าเริ่มต้นเป็น user
        ]);

        $user->sendEmailVerificationNotification();

        return redirect('/email/verify')->with('success', 'สมัครสำเร็จ! กรุณายืนยันอีเมลของคุณ');
    }

    public function verifyEmail($id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals(sha1($user->email), $hash)) {
            abort(403, 'ลิงก์ยืนยันไม่ถูกต้อง');
        }

        if (!$user->hasVerifiedEmail()) {
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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // พยายามเข้าสู่ระบบ
        if (Auth::attempt($request->only('email', 'password'))) {
            
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // 1. เช็คสิทธิ์และสถานะการยืนยันอีเมล
            if ($user->role !== 'admin' && !$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->back()->with('error', 'กรุณายืนยันอีเมลก่อนเข้าสู่ระบบ');
            }

            $request->session()->regenerate();

            // 2. แยกทางไปตาม Role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'ยินดีต้อนรับผู้ดูแลระบบ');
            }

            return redirect('/')->with('success', 'เข้าสู่ระบบสำเร็จ');
        }

        // กรณีใส่ข้อมูลผิด
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