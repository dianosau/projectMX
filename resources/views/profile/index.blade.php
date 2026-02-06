@extends('layouts.app')

@section('title', 'จัดการบัญชีของฉัน')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <h5 class="fw-bold mb-3 p-2 border-bottom">Manage My Account</h5>
                <div class="list-group list-group-flush">
                    <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action border-0 rounded-3 active mb-1">
                        <i class="fas fa-user me-2"></i> My Profile / Address
                    </a>
                    <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                        <i class="fas fa-history me-2"></i> My Orders
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <h4 class="fw-bold mb-4">Manage My Account</h4>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-muted">Personal Profile</h6>
                            <a href="#" class="text-primary small text-decoration-none">EDIT</a>
                        </div>
                        <div class="pt-2">
                            <p class="mb-1 fw-bold fs-5 text-dark">{{ Auth::user()->name }}</p>
                            <p class="text-muted small mb-0">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-muted">Address Book</h6>
                            <a href="javascript:void(0)" class="text-primary small text-decoration-none fw-bold" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                + ADD NEW
                            </a>
                        </div>
                        <div class="pt-2">
                            @php 
                                $defaultAddress = $addresses->where('is_default', 1)->first(); 
                            @endphp

                            @if($defaultAddress)
                                <p class="mb-1 fw-bold text-success small">Default Shipping Address</p>
                                <p class="mb-0 small fw-bold text-dark">{{ $defaultAddress->recipient_name }}</p>
                                <p class="mb-0 small text-muted">{{ $defaultAddress->address_detail }}</p>
                                <p class="small text-muted mb-0">Phone: {{ $defaultAddress->phone }}</p>
                            @else
                                <div class="text-center py-3">
                                    <p class="text-muted small mb-0">ยังไม่มีข้อมูลที่อยู่จัดส่ง</p>
                                    <button class="btn btn-link btn-sm text-decoration-none" data-bs-toggle="modal" data-bs-target="#addAddressModal">เพิ่มที่อยู่ตอนนี้</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($addresses->count() > 1)
            <div class="mt-2">
                <h6 class="fw-bold mb-3 text-muted small">Other Addresses</h6>
                @foreach($addresses->where('is_default', 0) as $addr)
                <div class="card border-0 shadow-sm rounded-4 p-3 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="small fw-bold">{{ $addr->recipient_name }}</span>
                            <span class="small text-muted ms-2">{{ $addr->address_detail }}</span>
                        </div>
                        <form action="{{ route('address.set-default', $addr->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill py-0 px-3" style="font-size: 0.7rem;">Set Default</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('address.store') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            <div class="modal-header border-0">
                <h5 class="fw-bold mb-0">เพิ่มที่อยู่จัดส่งใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label small fw-bold text-muted">ที่อยู่ (บ้านเลขที่/ซอย/ถนน)</label>
                        <input type="text" name="address_detail" class="form-control bg-light border-0 py-2" placeholder="เช่น 123/45 หมู่ 5" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">ตำบล/แขวง</label>
                        <input type="text" name="subdistrict" class="form-control bg-light border-0 py-2" placeholder="ตำบล/แขวง" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">อำเภอ/เขต</label>
                        <input type="text" name="district" class="form-control bg-light border-0 py-2" placeholder="อำเภอ/เขต" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">จังหวัด</label>
                        <input type="text" name="province" class="form-control bg-light border-0 py-2" placeholder="จังหวัด" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">รหัสไปรษณีย์</label>
                        <input type="text" name="zipcode" class="form-control bg-light border-0 py-2" placeholder="รหัสไปรษณีย์" required>
                    </div>
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefaultCheck" checked>
                    <label class="form-check-label small" for="isDefaultCheck">
                        ตั้งเป็นที่อยู่หลัก
                    </label>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">บันทึกที่อยู่</button>
            </div>
        </form>
    </div>
</div>
@endsection