@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="mb-5">
            <h2 class="fw-bold display-6 mb-2">Admin Dashboard</h2>
            <p class="text-muted">ยินดีต้อนรับเข้าสู่ระบบจัดการร้านค้า เลือกเมนูที่ต้องการดำเนินการ</p>
            <div class="border-bottom border-primary border-3" style="width: 60px;"></div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 admin-card p-4 text-center">
                    <div class="icon-circle bg-primary-subtle text-primary mb-3">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                    <h5 class="fw-bold">จัดการคำสั่งซื้อ</h5>
                    <p class="text-muted small">ตรวจสอบสลิปและสถานะการจัดส่งสินค้า</p>
                    <a href="{{ route('admin.orders.index') }}"
                        class="btn btn-primary rounded-pill mt-auto">เข้าดูรายการ</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 admin-card p-4 text-center">
                    <div class="icon-circle bg-success-subtle text-success mb-3">
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                    <h5 class="fw-bold">จัดการสินค้า</h5>
                    <p class="text-muted small">เพิ่ม แก้ไข หรือลบหมวดหมู่และรายการสินค้า</p>
                    <a href="{{ route('admin.products.categories') }}"
                        class="btn btn-success rounded-pill mt-auto">เข้าดูรายการ</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 admin-card p-4 text-center">
                    <div class="icon-circle bg-info-subtle text-info mb-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h5 class="fw-bold">จัดการสมาชิก</h5>
                    <p class="text-muted small">ดูรายชื่อสมาชิกและจัดการสิทธิ์การเข้าใช้งาน</p>
                    <a href="{{ route('admin.users.index') }}"
                        class="btn btn-info text-white rounded-pill mt-auto">เข้าดูรายการ</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 admin-card p-4 text-center">
                    <div class="icon-circle bg-warning-subtle text-warning mb-3">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                    <h5 class="fw-bold">รายงานยอดขาย</h5>
                    <p class="text-muted small">สรุปรายได้และสถิติยอดขายแยกตามช่วงเวลา</p>
                    <a href="{{ route('admin.reports.sales') }}" class="btn btn-warning text-white rounded-pill mt-auto">ดูรายงาน</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .admin-card {
            transition: all 0.3s ease;
            border-radius: 20px;
        }

        .admin-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto;
        }

        .bg-primary-subtle {
            background-color: #e7f1ff;
        }

        .bg-success-subtle {
            background-color: #e6ffed;
        }

        .bg-info-subtle {
            background-color: #e1f5fe;
        }

        .bg-warning-subtle {
            background-color: #fff3cd;
        }
    </style>
@endsection