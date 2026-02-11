@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">จัดการคำสั่งซื้อ (Admin)</h2>

    <div class="table-responsive">
        <table class="table table-hover bg-white shadow-sm rounded">
            <thead class="table-dark">
                <tr>
                    <th>เลขที่สั่งซื้อ</th>
                    <th>ลูกค้า</th>
                    <th>ยอดรวม</th>
                    <th>สถานะปัจจุบัน</th>
                    <th>เลขพัสดุ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <td>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td class="fw-bold">฿{{ number_format($order->total_amount) }}</td>

                        {{-- ใส่เลขพัสดุ --}}
                        <td>
                            <input type="text" name="tracking_number"
                                value="{{ $order->tracking_number }}"
                                class="form-control form-control-sm"
                                placeholder="ระบุเลขพัสดุ">
                        </td>

                        {{-- เลือกสถานะ --}}
                        <td>
                            <select name="status" class="form-select form-select-sm">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>รอชำระเงิน</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>เตรียมจัดส่ง</option>
                                <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>จัดส่งแล้ว</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>สำเร็จ</option>
                            </select>
                        </td>

                        <td>
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="fas fa-save me-1"></i> บันทึก
                            </button>
                        </td>
                    </form>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection