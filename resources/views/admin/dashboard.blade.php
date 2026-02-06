@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Admin Dashboard</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-4">
                <i class="fas fa-shopping-cart fa-3x text-primary mb-3"></i>
                <h4>จัดการคำสั่งซื้อ</h4>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary mt-2">เข้าดูรายการ</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-4 text-muted">
                <i class="fas fa-box fa-3x mb-3"></i>
                <h4>จัดการสินค้า</h4>
                <button class="btn btn-secondary mt-2" disabled>เร็วๆ นี้</button>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-4 text-muted">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h4>จัดการสมาชิก</h4>
                <button class="btn btn-secondary mt-2" disabled>เร็วๆ นี้</button>
            </div>
        </div>
    </div>
</div>
@endsection