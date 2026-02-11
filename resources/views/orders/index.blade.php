@extends('layouts.app')

@section('title', 'สถานะคำสั่งซื้อ')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 fw-bold"><i class="fas fa-box me-2 text-primary"></i>สถานะคำสั่งซื้อ</h2>

    {{-- แถบสถานะใหม่ 4 สถานะ --}}
    <ul class="nav nav-pills nav-justified mb-4 shadow-sm rounded-4 bg-light p-2 border" id="orderStatusTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active rounded-pill" data-bs-toggle="tab" href="#pending">
                <i class="fas fa-receipt me-1"></i> รอตรวจสลิป
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill" data-bs-toggle="tab" href="#processing">
                <i class="fas fa-box-open me-1"></i> เตรียมจัดส่ง
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill" data-bs-toggle="tab" href="#shipping">
                <i class="fas fa-truck me-1"></i> จัดส่งแล้ว
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill" data-bs-toggle="tab" href="#completed">
                <i class="fas fa-check-circle me-1"></i> ยืนยันสินค้า
            </a>
        </li>
    </ul>

    <div class="tab-content">
        @php
        // กำหนดสถานะให้ตรงกับฐานข้อมูล
        $status_list = [
        'pending' => 'รอตรวจสลิป',
        'processing' => 'เตรียมจัดส่ง',
        'shipping' => 'จัดส่งแล้ว',
        'completed' => 'ยืนยันสินค้า'
        ];
        @endphp

        @foreach($status_list as $key => $label)
        <div class="tab-pane fade {{ $key == 'pending' ? 'show active' : '' }}" id="{{ $key }}">
            @php
            // ดึงข้อมูลโดยใช้คอลัมน์ shipping_status
            $filteredOrders = $orders->where('shipping_status', $key);
            @endphp

            @if($filteredOrders->isEmpty())
            <div class="text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="100" class="opacity-25 mb-3">
                <p class="text-muted">ไม่มีรายการในสถานะ{{ $label }}</p>
            </div>
            @else
            @foreach($filteredOrders as $order)
            <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold">เลขที่สั่งซื้อ: #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                            <span class="ms-2 text-muted small">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <span class="badge rounded-pill 
                                @if($key == 'pending') bg-warning text-dark
                                @elseif($key == 'processing') bg-info text-white
                                @elseif($key == 'shipping') bg-primary text-white
                                @else bg-success text-white @endif">
                            {{ $label }}
                        </span>
                    </div>
                </div>

                <div class="card-body border-top border-bottom">
                    @foreach ($order->orderItems as $item)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <img src="{{ $item->product->image}}"
                                class="rounded-3 shadow-sm" width="60" height="60" style="object-fit: cover;">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 fw-bold">{{ $item->product->name }}</h6>
                            <small class="text-muted">จำนวน: {{ $item->quantity }} ชิ้น</small>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold">{{ number_format($item->price * $item->quantity) }} .-</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="card-footer bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        @if($order->shipping_status == 'shipping')
                        <form action="{{ route('orders.complete', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 shadow-sm">
                                <i class="fas fa-check me-1"></i> ฉันได้รับสินค้าแล้ว
                            </button>
                        </form>
                        @elseif($order->tracking_number)
                        <div class="small">
                            <i class="fas fa-truck text-primary me-1"></i>
                            เลขพัสดุ: <span class="fw-bold text-dark">{{ $order->tracking_number }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="text-end">
                        <span class="text-muted small me-2">ยอดสุทธิ:</span>
                        <span class="h4 mb-0 fw-bold text-danger">{{ number_format($order->total_amount) }} .-</span>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection