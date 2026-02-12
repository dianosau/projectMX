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

        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <div class="col-md-7">
                    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                        <h4 class="mb-4 text-center fw-bold text-primary"><i
                                class="fas fa-user-edit me-2"></i>ข้อมูลการจัดส่ง</h4>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">ชื่อ-นามสกุลผู้รับ</label>
                            <input type="text" name="name"
                                class="form-control bg-light border-0 py-2 @error('name') is-invalid @enderror"
                                value="{{ $defaultAddress->user->name ?? old('name') }}"
                                placeholder="ระบุชื่อ-นามสกุลผู้รับ">
                            @error('name')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        ที่อยู่ (บ้านเลขที่/ซอย/ถนน)</label>
                                    <input type="text" id="address_autocomplete" name="address_detail" class="form-control"
                                        value="{{ $defaultAddress->address_detail ?? old('address_detail') }}"
                                        placeholder="เช่น 123/45 หมู่ 5">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>ตำบล/แขวง</label>
                                    <input type="text" id="subdistrict" name="subdistrict"
                                        class="form-control @error('subdistrict') is-invalid @enderror"
                                        value="{{ $defaultAddress->subdistrict ?? old('subdistrict') }}">
                                    @error('subdistrict') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>อำเภอ/เขต</label>
                                    <input type="text" id="district" name="district"
                                        class="form-control @error('district') is-invalid @enderror"
                                        value="{{ $defaultAddress->district ?? old('district') }}">
                                    @error('district') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>จังหวัด</label>
                                    <input type="text" id="province" name="province"
                                        class="form-control @error('province') is-invalid @enderror"
                                        value="{{ $defaultAddress->province ?? old('province') }}">
                                    @error('province') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>รหัสไปรษณีย์</label>
                                    <input type="text" id="zipcode" name="zipcode"
                                        class="form-control @error('zipcode') is-invalid @enderror"
                                        value="{{ $defaultAddress->zipcode ?? old('zipcode') }}">
                                    @error('zipcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone"
                                class="form-control bg-light border-0 py-2 @error('phone') is-invalid @enderror"
                                value="{{ $defaultAddress->user->phone ?? old('phone') }}" placeholder="0XX-XXX-XXXX">
                            @error('phone')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">วิธีการจัดส่ง</label>
                            <select name="shipping_method" class="form-select bg-light border-0 py-2">
                                <option value="standard" {{ old('shipping_method') == 'standard' ? 'selected' : '' }}>
                                    ส่งด่วนปกติ (Flash/Kerry)</option>
                                <option value="ems" {{ old('shipping_method') == 'ems' ? 'selected' : '' }}>EMS (ไปรษณีย์ไทย)
                                </option>
                            </select>
                        </div>
                    </div>
                    @if(isset($defaultAddress))
                        <div class="alert alert-info border-0 small mb-0">
                            <i class="fas fa-info-circle me-2"></i>ระบบดึงข้อมูลจาก "ที่อยู่หลัก" ในบัญชีของคุณ
                        </div>
                    @endif
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
                                {{-- ใช้ QR API กับ Payload ที่คำนวณมาแล้ว --}}
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ $qrPayload }}"
                                    alt="PromptPay QR" class="img-fluid">
                            </div>
                            <p class="text-danger small fw-bold mb-0">*ยอดโอน {{ number_format($total, 2) }} บาท*</p>
                            <p class="extra-small text-muted mt-1">ล็อคยอดเงินให้อัตโนมัติ โปรดสแกนด้วยแอปธนาคาร</p>
                        </div>

                        {{-- เพิ่มช่องแนบสลิป --}}
                        <div class="card p-3 mb-4 border-dashed">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-file-invoice-dollar me-2"></i>อัปโหลดสลิปเพื่อยืนยัน
                            </label>
                            <input type="file" name="payment_slip" id="slip_input"
                                class="form-control @error('payment_slip') is-invalid @enderror" accept="image/*"
                                value="{{ old("payment_slip") }}">
                            @error('payment_slip')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted small mt-1">รองรับไฟล์ภาพ JPG, PNG ไม่เกิน 2MB</div>
                        </div>
                        <button type="submit" id="submit_btn"
                            class="btn btn-success btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm mb-3" disabled>
                            <i class="fas fa-check-circle me-1"></i> ยืนยันการชำระเงินและสั่งซื้อ
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
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slipInput = document.getElementById('slip_input');
            const submitBtn = document.getElementById('submit_btn');
            const form = document.getElementById('checkout-form');

            // 1. ถ้ามี Error จาก Controller ให้เลื่อนหน้าจอไปหาจุดที่ Error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                const yOffset = -120; // ระยะเผื่อ Navbar
                const y = firstError.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }

            // 2. เช็คการเลือกไฟล์สลิป
            slipInput.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50');
                }
            });

            // 3. ป้องกันการส่งฟอร์มซ้ำ
            form.addEventListener('submit', function (e) {
                if (!slipInput.files.length) {
                    e.preventDefault();
                    alert('กรุณาอัปโหลดสลิปก่อนยืนยัน');
                    return;
                }
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> กำลังประมวลผล...';
                submitBtn.style.pointerEvents = 'none';
            });
        });
    </script>
@endpush