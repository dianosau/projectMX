<?php

namespace App\Http\Controllers\Admin; // ตรวจสอบตัวพิมพ์ใหญ่

use App\Http\Controllers\Controller;
use App\Models\User; // เพิ่มการเรียกใช้ Model User
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    // เพิ่ม Method index เพื่อแสดงรายชื่อสมาชิก
    public function index(Request $request)
    {
        $query = User::query();

        // ค้นหาชื่อ/อีเมล
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        // กรองสถานะ
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // เพิ่ม Method destroy เพื่อลบสมาชิก
    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'ไม่สามารถลบผู้ดูแลระบบได้');
        }
        $user->delete();

        return back()->with('success', 'ลบสมาชิกเรียบร้อยแล้ว');
    }

    public function toggleRole(Request $request, User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'ห้ามเปลี่ยนสิทธิ์ตัวเอง');
        }

        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'เปลี่ยนบทบาทเรียบร้อย');
    }

    public function toggleStatus(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'ห้ามแบนตัวเอง');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        return back()->with('success', 'ปรับปรุงสถานะเรียบร้อย');
    }
}
