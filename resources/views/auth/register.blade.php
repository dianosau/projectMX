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
                            <input type="text" name="name"
                                class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="ชื่อผู้ใช้">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-envelope text-primary me-2"></i>อีเมล
                            </label>
                            <input type="email" name="email"
                                class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="example@email.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-lock text-primary me-2"></i>รหัสผ่าน
                            </label>
                            <input type="password" name="password"
                                class="form-control form-control-lg rounded-3 @error('password') is-invalid @enderror"
                                value="{{ old('password') }}">
                            <small class="text-muted">ต้องมีอย่างน้อย 8 ตัวอักษร</small>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-check-circle text-primary me-2"></i>ยืนยันรหัสผ่าน
                            </label>
                            <input type="password" name="password_confirmation"
                                class="form-control form-control-lg rounded-3" value="{{ old('password_confirmation') }}">
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        ที่อยู่ (บ้านเลขที่/ซอย/ถนน)</label>
                                    <input type="text" id="address_autocomplete" name="address_detail" class="form-control"
                                        placeholder="เช่น 123/45 หมู่ 5">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>ตำบล/แขวง</label>
                                    <input type="text" id="subdistrict" name="subdistrict"
                                        class="form-control @error('subdistrict') is-invalid @enderror"
                                        value="{{ old('subdistrict') }}">
                                    @error('subdistrict') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>อำเภอ/เขต</label>
                                    <input type="text" id="district" name="district"
                                        class="form-control @error('district') is-invalid @enderror"
                                        value="{{ old('district') }}">
                                    @error('district') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>จังหวัด</label>
                                    <input type="text" id="province" name="province"
                                        class="form-control @error('province') is-invalid @enderror"
                                        value="{{ old('province') }}">
                                    @error('province') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>รหัสไปรษณีย์</label>
                                    <input type="text" id="zipcode" name="zipcode"
                                        class="form-control @error('zipcode') is-invalid @enderror"
                                        value="{{ old('zipcode') }}">
                                    @error('zipcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-phone text-primary me-2"></i>เบอร์โทร
                            </label>
                            <input type="text" name="phone"
                                class="form-control form-control-lg rounded-3  @error('phone') is-invalid @enderror"
                                placeholder="0XX-XXX-XXXX">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill mt-3 fw-bold">
                            <i class="fas fa-user-check me-2"></i>สมัครสมาชิก
                        </button>

                        <div class="text-center mt-3">
                            <small>มีบัญชีอยู่แล้ว? <a href="{{ route('login') }}"
                                    class="text-decoration-none fw-bold">เข้าสู่ระบบ <i
                                        class="fas fa-arrow-right ms-1"></i></a></small>
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
@push('scripts')
    <script>
        function initAutocomplete() {
            const input = document.getElementById("address_autocomplete");
            const autocomplete = new google.maps.places.Autocomplete(input, {
                componentRestrictions: { country: "th" },
                fields: ["address_components"]
            });

            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();

                let subdistrict = "";
                let district = "";
                let province = "";
                let zipcode = "";

                place.address_components.forEach(component => {
                    const types = component.types;

                    if (types.includes("sublocality_level_1")) {
                        subdistrict = component.long_name;
                    }
                    if (types.includes("administrative_area_level_2")) {
                        district = component.long_name;
                    }
                    if (types.includes("administrative_area_level_1")) {
                        province = component.long_name;
                    }
                    if (types.includes("postal_code")) {
                        zipcode = component.long_name;
                    }
                });

                document.getElementById("subdistrict").value = subdistrict;
                document.getElementById("district").value = district;
                document.getElementById("province").value = province;
                document.getElementById("zipcode").value = zipcode;
            });
        }

        window.onload = initAutocomplete;

        window.onload = function () {
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                const y = firstError.getBoundingClientRect().top + window.pageYOffset - 100;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }
        };
    </script>
@endpush