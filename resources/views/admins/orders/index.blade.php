@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">จัดการคำสั่งซื้อ (Admin)</h2>
        <span class="badge bg-primary">ทั้งหมด {{ $orders->count() }} รายการ</span>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">เลขที่สั่งซื้อ</th>
                            <th>ลูกค้า</th>
                            <th>รายการสินค้า</th>
                            <th>ยอดรวม</th>
                            <th>หลักฐานการโอน</th>
                            <th>สถานะ/เลขพัสดุ</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>
                                <small>
                                    @foreach($order->items as $item)
                                        - {{ $item->product->name }} (x{{ $item->quantity }})<br>
                                    @endforeach
                                </small>
                            </td>
                            <td class="text-danger fw-bold">฿{{ number_format($order->total_amount) }}</td>
                            <td>
                                @if($order->slip_image)
                                    <a href="{{ asset('uploads/slips/' . $order->slip_image) }}" target="_blank">
                                        <img src="{{ asset('uploads/slips/' . $order->slip_image) }}" width="50" class="img-thumbnail rounded shadow-sm">
                                    </a>
                                @else
                                    <span class="text-muted small italic">ยังไม่ส่งสลิป</span>
                                @endif
                            </td>
                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                @csrf @method('PUT')
                                <td>
                                    <select name="status" class="form-select form-select-sm mb-1">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>รอชำระเงิน</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>ที่ต้องจัดส่ง (ยืนยันยอดเงินแล้ว)</option>
                                        <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>ระหว่างจัดส่ง</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>สำเร็จแล้ว</option>
                                    </select>
                                    <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" 
                                           class="form-control form-control-sm" placeholder="เลขพัสดุ (ถ้ามี)">
                                </td>
                                <td class="text-center">
                                    <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill">บันทึก</button>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection