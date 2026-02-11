<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * ==========================================
     * สำหรับ USER (ลูกค้า)
     * ==========================================
     */

    // หน้าแสดงรายการสั่งซื้อของลูกค้าเอง
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['orderItems.product'])
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    // ลูกค้ากดยืนยันได้รับสินค้า
    public function complete($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $order->update(['status' => 'completed']);

        return back()->with('success', 'ยืนยันการรับสินค้าเรียบร้อยแล้ว ขอบคุณที่ใช้บริการครับ');
    }


    /**
     * ==========================================
     * สำหรับ ADMIN (ผู้ดูแลระบบ)
     * ==========================================
     */

    // หน้าจัดการคำสั่งซื้อทั้งหมด (Admin Dashboard)
    public function adminIndex()
    {
        $orders = Order::with(['user', 'orderItems.product'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    // อัปเดตสถานะการจัดส่งและเลขพัสดุ
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,shipping,completed',
            'tracking_number' => 'nullable|string|max:50',
        ]);

        // อัปเดตข้อมูลลงตาราง orders
        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
            // หากมีการอัปเดตสถานะเป็น completed อาจจะอัปเดต payment_status เป็น paid อัตโนมัติ (ถ้าต้องการ)
            'payment_status' => ($request->status == 'completed') ? 'paid' : $order->payment_status,
        ]);

        return back()->with('success', "อัปเดตคำสั่งซื้อ #{$order->id} เรียบร้อยแล้ว");
    }
}
