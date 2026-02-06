@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 fw-bold"><i class="fas fa-shopping-cart me-2 text-primary"></i>ตะกร้าสินค้าของคุณ</h2>

    @if($cartItems->isEmpty())
        <div class="card shadow-sm border-0 rounded-4 p-5 text-center">
            <h4 class="text-muted">ตะกร้าของคุณยังว่างเปล่า</h4>
            <a href="{{ route('all.product') }}" class="btn btn-primary mt-3 px-5">ไปที่หน้าร้านค้า</a>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">สินค้า</th>
                                <th>ราคา</th>
                                <th>จำนวน</th>
                                <th>รวม</th>
                                <th class="text-end pe-4">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($cartItems as $item)
                                @php 
                                    $subtotal = $item->product->price * $item->quantity;
                                    $total += $subtotal;
                                    $imageUrl = filter_var($item->product->image, FILTER_VALIDATE_URL) 
                                                ? $item->product->image 
                                                : asset('images/products/' . $item->product->image);
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3" style="width: 60px; height: 60px; min-width: 60px;">
                                                <img src="{{ $imageUrl }}" 
                                                     class="rounded-3 shadow-sm" 
                                                     style="width: 100%; height: 100%; object-fit: cover;"
                                                     onerror="this.src='https://via.placeholder.com/60?text=No+Img'">
                                            </div>
                                            <div>
                                                <span class="fw-bold d-block">{{ $item->product->name }}</span>
                                                <small class="text-muted">ID: {{ str_pad($item->product->id, 5, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->product->price) }} .-</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="fw-bold text-primary">{{ number_format($subtotal) }} .-</td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('ยืนยันการลบ?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 p-4">
                    <h5 class="fw-bold mb-4">สรุปคำสั่งซื้อ</h5>
                    <div class="d-flex justify-content-between mb-3">
                        <span>ยอดรวมทั้งหมด</span>
                        <span class="h4 fw-bold text-primary">{{ number_format($total) }} .-</span>
                    </div>
                    <hr>
                    
                    {{-- แก้ไข: เปลี่ยนปุ่มให้ลิงก์ไปยังหน้า Checkout --}}
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm mb-3">
                        สั่งซื้อสินค้า
                    </a>
                    
                    <a href="{{ route('all.product') }}" class="btn btn-outline-secondary w-100 rounded-pill">เลือกซื้อสินค้าเพิ่ม</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection