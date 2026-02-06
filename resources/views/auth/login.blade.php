@extends('layouts.app')

@section('title', 'เข้าสู่ระบบ')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card p-4 border-0 shadow-lg rounded-4">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-user-circle fa-4x text-primary"></i>
                        </div>
                        <h3 class="fw-bold">เข้าสู่ระบบ</h3>
                        <p class="text-muted mb-0">ยินดีต้อนรับกลับมา</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 small mb-3">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 small mb-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ url('/login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-envelope text-primary me-2"></i>อีเมล
                            </label>
                            <input type="email" name="email" class="form-control form-control-lg rounded-3" required value="{{ old('email') }}" placeholder="example@email.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-lock text-primary me-2"></i>รหัสผ่าน
                            </label>
                            <input type="password" name="password" class="form-control form-control-lg rounded-3" required placeholder="••••••••">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill mt-3 fw-bold">
                            <i class="fas fa-sign-in-alt me-2"></i>เข้าสู่ระบบ
                        </button>

                        <div class="text-center mt-3">
                            <small>ยังไม่มีบัญชี? <a href="{{ route('register') }}" class="text-decoration-none fw-bold">สมัครสมาชิกเลย <i class="fas fa-arrow-right ms-1"></i></a></small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus {
            border-color: #0d6efd; /* แก้เป็นค่าสีพื้นฐานหรือตัวแปรที่คุณใช้ */
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }
    </style>
@endsection