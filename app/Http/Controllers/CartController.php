<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress; // เพิ่มการเรียกใช้ Model ที่อยู่
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    public function index() {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request, $productId) {
        $cartItem = CartItem::where('user_id', Auth::id())->where('product_id', $productId)->first();
        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity ?? 1);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $request->quantity ?? 1
            ]);
        }
        return redirect()->route('cart.index')->with('success', 'เพิ่มลงตะกร้าแล้ว');
    }

    public function remove($id) {
        CartItem::where('user_id', Auth::id())->where('id', $id)->delete();
        return back()->with('success', 'ลบสินค้าแล้ว');
    }

    // แก้ไขฟังก์ชัน checkout เพื่อดึงที่อยู่มาแสดงอัตโนมัติ
    public function checkout() {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) return redirect()->route('cart.index');
        
        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        // ดึงที่อยู่หลักของผู้ใช้ (is_default = 1)
        $defaultAddress = UserAddress::where('user_id', Auth::id())
                                     ->where('is_default', 1)
                                     ->first();

        return view('cart.checkout', compact('cartItems', 'total', 'defaultAddress'));
    }

    public function processCheckout(Request $request) {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        $cartItems = CartItem::where('user_id', Auth::id())->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'ไม่มีสินค้าในตะกร้า');
        }

        try {
            DB::transaction(function () use ($cartItems, $request) {
                $total = $cartItems->sum(function($item) {
                    return $item->product->price * $item->quantity;
                });

                // บันทึกคำสั่งซื้อลงตาราง orders
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_amount' => $total,
                    'payment_status' => 'pending',
                    'shipping_address' => $request->address,
                    'payment_method' => 'qr_code'
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price
                    ]);
                }

                CartItem::where('user_id', Auth::id())->delete();
            });

            return redirect()->route('orders.index')->with('success', 'สั่งซื้อสำเร็จ!');

        } catch (\Exception $e) {
            dd($e->getMessage()); 
        }
    }

    public function orderHistory() {
        $orders = Order::where('user_id', Auth::id())
                        ->with('items.product')
                        ->orderBy('created_at', 'desc')
                        ->get();
        return view('orders.index', compact('orders'));
    }
}