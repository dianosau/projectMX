@extends('layouts.app')

@section('title', 'ยืนยันอีเมลล์')

@section('content')
    <div class="container text-center mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-5 border-0 shadow-lg rounded-4">
                    <div class="mb-4">
                        <i class="fas fa-envelope-open-text fa-5x text-primary mb-3"></i>
                        <h4 class="fw-bold">กรุณายืนยันอีเมลของคุณ</h4>
                        <p class="text-muted">ระบบได้ส่งลิงก์ยืนยันไปที่อีเมลแล้ว</p>
                    </div>

                    @if (session('message'))
                        <div class="alert alert-success rounded-3 mb-4">
                            <i class="fas fa-check-circle me-2"></i>{{ session('message') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold">
                            <i class="fas fa-paper-plane me-2"></i>ส่งอีเมลยืนยันอีกครั้ง
                        </button>
                    </form>

                    <div class="mt-4 p-3 bg-light rounded-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            หากไม่พบอีเมล โปรดตรวจสอบในกล่องจดหมายขยะ (Spam)
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection