<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // แก้ไขจุดนี้แล้ว

class OrderController extends Controller
{
    // --- สำหรับ USER ---
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $orders = Order::where('user_id', Auth::id())
                        ->with(['items.product']) 
                        ->latest()
                        ->get();

        return view('orders.index', compact('orders'));
    }

    // สำหรับ User กดยืนยันว่าได้รับสินค้าแล้ว
    public function complete($id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'ไม่มีสิทธิ์เข้าถึงรายการนี้');
        }

        $order->update(['status' => 'completed']);
        return back()->with('success', 'ยืนยันการรับสินค้าเรียบร้อยแล้ว');
    }

    // --- สำหรับ ADMIN ---
    public function adminIndex()
    {
        $orders = Order::with(['user', 'items.product'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,shipping,completed',
            'tracking_number' => 'nullable|string'
        ]);

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number
        ]);

        return back()->with('success', 'อัปเดตสถานะคำสั่งซื้อเรียบร้อย');
    }
}