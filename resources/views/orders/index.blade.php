@extends('layouts.app')

@section('title', 'สถานะคำสั่งซื้อ')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 fw-bold"><i class="fas fa-box me-2 text-primary"></i>สถานะคำสั่งซื้อ</h2>

    <ul class="nav nav-pills nav-justified mb-4 shadow-sm rounded-4 bg-white p-2" id="orderStatusTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active rounded-pill" data-bs-toggle="tab" href="#pending">
                <i class="fas fa-wallet me-1"></i> รอชำระเงิน
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill" data-bs-toggle="tab" href="#processing">
                <i class="fas fa-box-open me-1"></i> ที่ต้องจัดส่ง
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill" data-bs-toggle="tab" href="#shipping">
                <i class="fas fa-truck me-1"></i> ระหว่างจัดส่ง
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill" data-bs-toggle="tab" href="#completed">
                <i class="fas fa-check-circle me-1"></i> สำเร็จแล้ว
            </a>
        </li>
    </ul>

    <div class="tab-content">
        @php
            $statuses = [
                'pending' => 'รอชำระเงิน',
                'processing' => 'ที่ต้องจัดส่ง',
                'shipping' => 'ระหว่างจัดส่ง',
                'completed' => 'สำเร็จแล้ว'
            ];
        @endphp

        @foreach($statuses as $statusKey => $statusName)
            <div class="tab-pane fade {{ $statusKey == 'pending' ? 'show active' : '' }}" id="{{ $statusKey }}">
                @php
                    $filteredOrders = $orders->where('status', $statusKey);
                @endphp

                @if($filteredOrders->isEmpty())
                    <div class="card shadow-sm border-0 rounded-4 p-5 text-center">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">ไม่มีรายการในหน้า "{{ $statusName }}"</h5>
                    </div>
                @else
                    @foreach($filteredOrders as $order)
                        <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">เลขที่สั่งซื้อ:</span>
                                    <span class="fw-bold text-primary">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                    <span class="ms-3 text-muted small">วันที่: {{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <span class="badge {{ $statusKey == 'completed' ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-3">
                                    {{ $statusName }}
                                </span>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table align-middle table-borderless">
                                        <tbody>
                                            @foreach($order->items as $item)
                                                <tr>
                                                    <td style="width: 80px;">
                                                        <img src="{{ filter_var($item->product->image, FILTER_VALIDATE_URL) ? $item->product->image : asset('images/products/' . $item->product->image) }}" 
                                                             class="rounded-3 shadow-sm" style="width: 60px; height: 60px; object-fit: cover;"
                                                             onerror="this.src='https://via.placeholder.com/60?text=No+Img'">
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 fw-bold">{{ $item->product->name }}</h6>
                                                        <small class="text-muted">ราคา: {{ number_format($item->price) }} .- | จำนวน: {{ $item->quantity }} ชิ้น</small>
                                                    </td>
                                                    <td class="text-end fw-bold">
                                                        {{ number_format($item->price * $item->quantity) }} .-
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                @if($order->tracking_number)
                                <div class="alert alert-info py-2 rounded-3 mb-0 mt-2">
                                    <i class="fas fa-truck me-2"></i> เลขพัสดุ: <strong>{{ $order->tracking_number }}</strong>
                                </div>
                                @endif
                            </div>

                            <div class="card-footer bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    @if($order->status == 'shipping')
                                        <form action="{{ route('orders.complete', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-4">ฉันได้รับสินค้าแล้ว</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold me-2">ยอดรวมสุทธิ:</span>
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

<style>
    .nav-pills .nav-link { color: #6c757d; font-weight: 500; }
    .nav-pills .nav-link.active { background-color: #0d6efd; color: white; }
    .nav-pills .nav-link:hover:not(.active) { background-color: #f8f9fa; }
</style>
@endsection