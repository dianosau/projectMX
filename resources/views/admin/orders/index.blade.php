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
                    <td>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>฿{{ number_format($order->total_amount) }}</td>
                    <td>
                        <span class="badge {{ $order->status == 'completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf @method('PUT')
                        <td>
                            <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" 
                                   class="form-control form-control-sm" placeholder="ใส่เลขพัสดุ">
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>รอชำระเงิน</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>เตรียมจัดส่ง</option>
                                    <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>จัดส่งแล้ว</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>สำเร็จ</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">บันทึก</button>
                            </div>
                        </td>
                    </form>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection