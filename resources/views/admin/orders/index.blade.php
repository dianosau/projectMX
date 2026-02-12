@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4 fw-bold text-start">จัดการคำสั่งซื้อ (Admin)</h2>

        <ul class="nav nav-pills mb-4 p-2 rounded-4 shadow-sm nav-justified" id="orderTabs" role="tablist"
            style="background-color: #eee;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill px-4 fw-bold" id="active-orders-tab" data-bs-toggle="tab"
                    data-bs-target="#active-orders" type="button" role="tab">
                    <i class="fas fa-clock me-2"></i>รายการที่ต้องจัดการ
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 fw-bold" id="completed-orders-tab" data-bs-toggle="tab"
                    data-bs-target="#completed-orders" type="button" role="tab">
                    <i class="fas fa-check-circle me-2"></i>รายการที่สำเร็จแล้ว (Completed)
                </button>
            </li>
        </ul>

        <form action="{{ route('admin.orders.bulkUpdate') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="tab-content" id="orderTabsContent">
                <div class="tab-pane fade show active" id="active-orders" role="tabpanel">
                    @include('admin.orders.partials.order_table', ['orders_list' => $orders->whereIn('shipping_status', ['pending', 'processing', 'shipping'])])
                </div>

                <div class="tab-pane fade" id="completed-orders" role="tabpanel">
                    <div class="alert alert-success border-0 rounded-4 small">
                        <i class="fas fa-info-circle me-2"></i>รายการเหล่านี้ถูกจัดส่งและถึงมือผู้รับเรียบร้อยแล้ว
                    </div>
                    @include('admin.orders.partials.order_table', ['orders_list' => $orders->where('shipping_status', 'completed')])
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm fw-bold">
                    <i class="fas fa-save me-2"></i> บันทึกการเปลี่ยนแปลงทั้งหมด
                </button>
            </div>
        </form>

        @include('admin.orders.partials.proof_modal')
    </div>
@endsection

@push('script')
    <style>
        /* ปรับแต่งพื้นหลังของแถบ Tabs ให้เข้มขึ้นเล็กน้อยเพื่อให้ตัดกับปุ่ม */
        #orderTabs {
            background-color: #e9ecef !important;
            /* สีเทาที่เข้มขึ้นเล็กน้อย */
            border: none;
        }

        /* สไตล์สำหรับปุ่มที่ยังไม่ถูกเลือก (เพิ่มความชัดเจนของตัวอักษร) */
        #orderTabs .nav-link {
            color: #495057 !important;
            /* เปลี่ยนเป็นสีเทาเข้มเกือบดำ */
            background: transparent;
            transition: all 0.2s ease-in-out;
        }

        /* สไตล์สำหรับปุ่มที่ถูกเลือก (Active) */
        #orderTabs .nav-link.active {
            background-color: #0d6efd !important;
            /* สีน้ำเงินหลัก */
            color: #ffffff !important;
            /* ตัวอักษรสีขาวบริสุทธิ์ */
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
        }

        /* เอฟเฟกต์ตอน Hover */
        #orderTabs .nav-link:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.5);
            color: #0d6efd !important;
        }
    </style>
@endpush