<div class="table-responsive">
    <table class="table table-hover bg-white shadow-sm rounded-4 overflow-hidden">
        <thead class="table-dark">
            <tr>
                <th>เลขที่สั่งซื้อ</th>
                <th>ลูกค้า</th>
                <th>ยอดรวม</th>
                <th>สลิป</th>
                <th style="width: 200px;">เลขพัสดุ</th>
                <th style="width: 180px;">สถานะการจัดส่ง</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders_list as $order)
                @php
                    $rowColor = '';
                    switch ($order->shipping_status) {
                        case 'pending': $rowColor = 'table-warning'; break;
                        case 'processing': $rowColor = 'table-info'; break;
                        case 'shipping': $rowColor = 'table-primary'; break;
                        case 'completed': $rowColor = 'table-success'; break;
                    }
                @endphp
                <tr class="{{ $rowColor }} align-middle">
                    <td>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}<input type="hidden" name="orders[{{ $order->id }}][id]" value="{{ $order->id }}"></td>
                    <td>{{ $order->user->name }}</td>
                    <td class="fw-bold">฿{{ number_format($order->total_amount) }}</td>
                    <td>
                        @if($order->payment && $order->payment->payment_proof)
                            <button type="button" class="btn btn-outline-info btn-sm rounded-pill px-3" onclick="showProof('{{ asset('storage/' . $order->payment->payment_proof) }}', '{{ number_format($order->total_amount, 2) }}')"><i class="fas fa-image me-1"></i> ดูสลิป</button>
                        @else
                            <span class="text-muted small">ยังไม่แนบสลิป</span>
                        @endif
                    </td>
                    <td><input type="text" name="orders[{{ $order->id }}][tracking_number]" value="{{ $order->tracking_number }}" class="form-control form-control-sm border-0 shadow-sm" placeholder="ระบุเลขพัสดุ"></td>
                    <td>
                        <select name="orders[{{ $order->id }}][shipping_status]" class="form-select form-select-sm border-0 shadow-sm">
                            <option value="pending" {{ $order->shipping_status == 'pending' ? 'selected' : '' }}>รอตรวจสลิป</option>
                            <option value="processing" {{ $order->shipping_status == 'processing' ? 'selected' : '' }}>เตรียมจัดส่ง</option>
                            <option value="shipping" {{ $order->shipping_status == 'shipping' ? 'selected' : '' }}>จัดส่งแล้ว</option>
                            <option value="completed" {{ $order->shipping_status == 'completed' ? 'selected' : '' }}>สำเร็จ</option>
                        </select>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">ไม่พบรายการสินค้า</td></tr>
            @endforelse
        </tbody>
    </table>
</div>