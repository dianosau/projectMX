@extends('layouts.app')

@section('title', 'สั่งซื้อและชำระเงิน')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 fw-bold">สั่งซื้อและชำระเงิน</h2>
    
    {{-- ส่วนแสดง Error กรณี Validation ไม่ผ่าน --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                    <h4 class="mb-4 text-center fw-bold text-primary"><i class="fas fa-user-edit me-2"></i>ข้อมูลการจัดส่ง</h4>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">ชื่อ-นามสกุลผู้รับ</label>
                        {{-- ดึงชื่อจาก Address Book อัตโนมัติ --}}
                        <input type="text" name="name" class="form-control bg-light border-0 py-2" 
                               value="{{ $defaultAddress->recipient_name ?? old('name') }}" placeholder="ระบุชื่อ-นามสกุลผู้รับ" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">ที่อยู่การจัดส่ง</label>
                        {{-- ดึงที่อยู่จาก Address Book อัตโนมัติ --}}
                        <textarea name="address" class="form-control bg-light border-0" rows="3" 
                                  placeholder="บ้านเลขที่, ถนน, แขวง/ตำบล, เขต/อำเภอ, จังหวัด, รหัสไปรษณีย์" required>{{ $defaultAddress->address_detail ?? old('address') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">เบอร์โทรศัพท์</label>
                        {{-- ดึงเบอร์โทรจาก Address Book อัตโนมัติ --}}
                        <input type="text" name="phone" class="form-control bg-light border-0 py-2" 
                               value="{{ $defaultAddress->phone ?? old('phone') }}" placeholder="เช่น 0812345678" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">วิธีการจัดส่ง</label>
                        <select name="shipping_method" class="form-select bg-light border-0 py-2">
                            <option value="standard" {{ old('shipping_method') == 'standard' ? 'selected' : '' }}>ส่งด่วนปกติ (Flash/Kerry)</option>
                            <option value="ems" {{ old('shipping_method') == 'ems' ? 'selected' : '' }}>EMS (ไปรษณีย์ไทย)</option>
                        </select>
                    </div>
                    
                    @if(isset($defaultAddress))
                        <div class="alert alert-info border-0 small mb-0">
                            <i class="fas fa-info-circle me-2"></i>ระบบดึงข้อมูลจาก "ที่อยู่หลัก" ในบัญชีของคุณ
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm border-2 border-dark rounded-4 p-4">
                    <h4 class="fw-bold mb-4">สรุปยอดรวม</h4>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">ราคาสินค้าในตะกร้า</span>
                        <span class="fw-bold h5">{{ number_format($total) }} .-</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4 border-bottom pb-3">
                        <span class="text-muted">ค่าจัดส่ง</span>
                        <span class="text-success fw-bold small">ฟรีค่าจัดส่ง</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold">ยอดสุทธิ</h4>
                        <h3 class="fw-bold text-danger">{{ number_format($total) }} .-</h3>
                    </div>

                    <div class="text-center mb-4 p-3 border rounded-3 bg-light">
                        <p class="fw-bold mb-2 text-dark">สแกน QR Code เพื่อโอนเงิน</p>
                        <div class="bg-white p-2 d-inline-block shadow-sm mb-2 rounded">
                            {{-- สร้าง QR Code ตามยอดเงินสุทธิ --}}
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=MusicWave_Payment_{{ $total }}" 
                                 alt="QR Code Payment" class="img-fluid">
                        </div>
                        <p class="text-danger small fw-bold mb-0">*ยอดโอน {{ number_format($total) }} บาท*</p>
                        <p class="text-muted extra-small mb-0 mt-1" style="font-size: 0.7rem;">
                            *โปรดตรวจสอบยอดเงินให้ถูกต้องก่อนโอนเงิน*
                        </p>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm mb-3">
                        ยืนยันการสั่งซื้อ
                    </button>
                    
                    <a href="{{ route('cart.index') }}" class="btn btn-link w-100 text-decoration-none text-muted">
                        <i class="fas fa-arrow-left me-1"></i>กลับไปที่ตะกร้า
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection