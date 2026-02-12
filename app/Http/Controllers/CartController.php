<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\UserAddress;
use App\Services\PromptPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request, $productId)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->where('product_id', $productId)->first();
        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity ?? 1);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $request->quantity ?? 1,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'เพิ่มลงตะกร้าแล้ว');
    }

    public function remove($id)
    {
        CartItem::where('user_id', Auth::id())->where('id', $id)->delete();

        return back()->with('success', 'ลบสินค้าแล้ว');
    }

    public function checkout()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $total = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        // สร้าง Payload สำหรับ QR Code
        $qrPayload = PromptPayService::generatePayload($total);

        $defaultAddress = UserAddress::with('user')
            ->where('user_id', Auth::id())
            ->where('is_default', 1)
            ->first();

        return view('cart.checkout', compact('cartItems', 'total', 'defaultAddress', 'qrPayload'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|digits_between:9,10',
            'address_detail' => 'required|string',
            'subdistrict' => 'required|string',
            'district' => 'required|string',
            'province' => 'required|string',
            'zipcode' => 'required|string',
            'payment_slip' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'กรุณากรอกชื่อ-นามสกุลผู้รับ',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.numeric' => 'เบอร์โทรศัพท์ต้องเป็นตัวเลขเท่านั้น',
            'phone.digits_between' => 'เบอร์โทรศัพท์ต้องมีความยาว 9 ถึง 10 หลัก',
            'address_detail.required' => 'กรุณากรอกบ้านเลขที่/ถนน',
            'subdistrict.required' => 'กรุณากรอกตำบล',
            'district.required' => 'กรุณากรอกอำเภอ',
            'province.required' => 'กรุณากรอกจังหวัด',
            'zipcode.required' => 'กรุณากรอกรหัสไปรษณีย์',
            'payment_slip.required' => 'กรุณาอัปโหลดสลิปเพื่อยืนยันการโอนเงิน',
            'payment_slip.image' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น',
            'payment_slip.max' => 'รูปภาพต้องมีขนาดไม่เกิน 2MB',
        ]);

        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'ไม่มีสินค้าในตะกร้า');
        }

        try {
            DB::transaction(function () use ($cartItems, $request) {
                $total = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

                $fullAddress = $request->address_detail.' ต.'.$request->subdistrict.
                       ' อ.'.$request->district.' จ.'.$request->province.
                       ' '.$request->zipcode;

                // 1. สร้าง Order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_amount' => $total,
                    'shipping_address' => $fullAddress,
                    'payment_method' => 'qr_code',
                    'shipping_status' => 'pending',

                ]);

                // 2. สร้าง Order
                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,

                    ]);

                    $item->product->decrement('stock', $item->quantity);
                }

                // 3. บันทึกข้อมูลการชำระเงินและไฟล์สลิป (ลงตาราง payments)
                if ($request->hasFile('payment_slip')) {
                    $path = $request->file('payment_slip')->store('slips', 'public');

                    Payment::create([
                        'order_id' => $order->id,
                        'amount' => $total,
                        'method' => 'qr_code',
                        'payment_proof' => $path,
                    ]);
                }

                // 4. ลบสินค้าในตะกร้า
                CartItem::where('user_id', Auth::id())->delete();
            });

            return redirect()->route('orders.index')->with('success', 'แจ้งชำระเงินเรียบร้อย โปรดรอการตรวจสอบสลิป');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: '.$e->getMessage());
        }
    }

    public function orderHistory()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }
}
