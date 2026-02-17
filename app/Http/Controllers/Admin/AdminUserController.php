<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * แสดงรายการสมาชิกพร้อมระบบค้นหาและกรองข้อมูล
     */
    public function index(Request $request)
    {
        // 1. Validation สำหรับค่าที่ใช้ในการกรองข้อมูล
        $request->validate([
            'status' => ['nullable', 'in:active,inactive'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $query = User::query();

        // 2. ระบบค้นหา (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 3. ตัวกรองสถานะ (Filter)
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // 4. การจัดเรียงและแบ่งหน้า (Pagination)
        $users = $query->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * ลบสมาชิกออกจากระบบ
     */
    public function destroy(User $user)
    {
        // ตรวจสอบความปลอดภัย: ห้ามลบ Admin หรือลบตัวเอง
        if ($user->role === 'admin') {
            return back()->with('error', 'ไม่สามารถลบผู้ดูแลระบบได้เพื่อความปลอดภัยของระบบ');
        }

        if (auth()->id() === $user->id) {
            return back()->with('error', 'คุณไม่สามารถลบบัญชีที่กำลังใช้งานอยู่ได้');
        }

        $user->delete();

        return back()->with('success', "ลบสมาชิก {$user->name} เรียบร้อยแล้ว");
    }

    /**
     * เปลี่ยนบทบาทสมาชิก (Admin <-> User)
     */
    public function toggleRole(Request $request, User $user)
    {
        // 1. Validate ข้อมูลบทบาทที่ส่งมา
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        // 2. ป้องกันการเปลี่ยนสิทธิ์ตัวเอง
        if (auth()->id() === $user->id) {
            return back()->with('error', 'คุณไม่สามารถเปลี่ยนบทบาทของตัวเองได้');
        }

        $user->update([
            'role' => $validated['role'],
        ]);

        return back()->with('success', "เปลี่ยนบทบาทของ {$user->name} เป็น ".strtoupper($validated['role']).' สำเร็จ');
    }

    /**
     * สลับสถานะการใช้งาน (แบน / ปลดแบน)
     */
    public function toggleStatus(User $user)
    {
        // ป้องกันการแบนตัวเอง
        if (auth()->id() === $user->id) {
            return back()->with('error', 'คุณไม่สามารถระงับการใช้งานบัญชีของตัวเองได้');
        }

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        $statusText = $user->is_active ? 'เปิดใช้งาน' : 'ระงับการใช้งาน';

        return back()->with('success', "{$statusText} บัญชีของคุณ {$user->name} เรียบร้อยแล้ว");
    }
}
