@extends('layouts.app')

@section('title', 'ลงทะเบียน')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 border-0 shadow-lg rounded-4">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-user-plus fa-4x text-primary"></i>
                    </div>
                    <h3 class="fw-bold">สมัครสมาชิก</h3>
                    <p class="text-muted mb-0">เริ่มต้นการช้อปปิ้งกับเรา</p>
                </div>

                <form action="{{ url('/register') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-user text-primary me-2"></i>ชื่อผู้ใช้
                        </label>
                        <input type="text" name="name" class="form-control form-control-lg rounded-3" required placeholder="ชื่อ-นามสกุล">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-envelope text-primary me-2"></i>อีเมล
                        </label>
                        <input type="email" name="email" class="form-control form-control-lg rounded-3" required placeholder="example@email.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-lock text-primary me-2"></i>รหัสผ่าน
                        </label>
                        <input type="password" name="password" class="form-control form-control-lg rounded-3" required placeholder="••••••••">
                        <small class="text-muted">ต้องมีอย่างน้อย 8 ตัวอักษร</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-check-circle text-primary me-2"></i>ยืนยันรหัสผ่าน
                        </label>
                        <input type="password" name="password_confirmation" class="form-control form-control-lg rounded-3" required placeholder="••••••••">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>ที่อยู่
                        </label>
                        <input type="text" name="address" class="form-control form-control-lg rounded-3" placeholder="ที่อยู่สำหรับจัดส่งสินค้า">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-phone text-primary me-2"></i>เบอร์โทร
                        </label>
                        <input type="text" name="phone" class="form-control form-control-lg rounded-3" placeholder="0XX-XXX-XXXX">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill mt-3 fw-bold">
                        <i class="fas fa-user-check me-2"></i>สมัครสมาชิก
                    </button>

                    <div class="text-center mt-3">
                        <small>มีบัญชีอยู่แล้ว? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">เข้าสู่ระบบ <i class="fas fa-arrow-right ms-1"></i></a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(63, 81, 181, 0.15);
    }
</style>
@endsection