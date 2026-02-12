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

    public function complete($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $order->update(['shipping_status' => 'completed']);

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
        $orders = Order::with(['user', 'orderItems.product', 'payment'])->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

    // อัปเดตสถานะการจัดส่งและเลขพัสดุ
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // ตรวจสอบข้อมูล (สถานะต้องตรงกับ 4 ค่าที่เรากำหนด)
        $request->validate([
            'shipping_status' => 'required|in:pending,processing,shipping,completed',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        // อัปเดตข้อมูล
        $order->update([
            'shipping_status' => $request->shipping_status,
            'tracking_number' => $request->tracking_number,
        ]);

        return back()->with('success', 'อัปเดตสถานะคำสั่งซื้อ #'.$order->id.' เรียบร้อยแล้ว');
    }

    public function bulkUpdate(Request $request)
    {
        $ordersData = $request->input('orders');

        foreach ($ordersData as $id => $data) {
            $order = Order::find($id);
            if ($order) {
                $order->update([
                    'tracking_number' => $data['tracking_number'],
                    'shipping_status' => $data['shipping_status'],
                ]);
            }
        }

        return back()->with('success', 'บันทึกข้อมูลทั้งหมดเรียบร้อยแล้ว');
    }
}
