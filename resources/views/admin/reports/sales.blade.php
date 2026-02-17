@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">รายงานยอดขายตามช่วงเวลา</h2>
            <button onclick="window.print()" class="btn btn-outline-dark rounded-pill"><i
                    class="fas fa-print me-2"></i>พิมพ์ PDF</button>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <form action="{{ route('admin.reports.sales') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold small">ตั้งแต่วันที่</label>
                    <input type="date" name="start_date" class="form-control rounded-3" value="{{ $start }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">ถึงวันที่</label>
                    <input type="date" name="end_date" class="form-control rounded-3" value="{{ $end }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 rounded-3">
                        <i class="fas fa-search me-2"></i>กรองข้อมูล
                    </button>
                </div>
            </form>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card bg-primary text-white border-0 shadow-sm rounded-4 p-4">
                    <small class="opacity-75">ยอดขายในช่วงที่เลือก</small>
                    <h2 class="fw-bold mb-0">฿{{ number_format($totalRevenue, 2) }}</h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white border-0 shadow-sm rounded-4 p-4">
                    <small class="opacity-75">จำนวนออเดอร์</small>
                    <h2 class="fw-bold mb-0">{{ $orderCount }} รายการ</h2>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-4">กราฟแสดงยอดขายประจำวัน</h5>
            <div style="height: 350px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // โค้ด Chart.js เดิม (จะใช้ค่าจาก $chartData ที่กรองแล้วโดยอัตโนมัติ)
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->pluck('date')) !!},
                datasets: [{
                    label: 'ยอดขาย (บาท)',
                    data: {!! json_encode($chartData->pluck('total')) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>
@endsection