@extends('layouts.app')

@section('title', 'จัดการบัญชีของฉัน')

@section('content')
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="p-4 text-center bg-primary text-white">
                            <div class="mb-3">
                                <i class="fas fa-user-circle fa-4x"></i>
                            </div>
                            <h6 class="fw-bold mb-1">{{ Auth::user()->name }}</h6>
                            <small class="opacity-75">{{ Auth::user()->email }}</small>
                        </div>
                        <div class="list-group list-group-flush p-2 text-start">
                            <a href="{{ route('profile.index') }}"
                                class="list-group-item list-group-item-action border-0 rounded-3 active mb-1">
                                <i class="fas fa-address-card me-2"></i> ข้อมูลส่วนตัว / ที่อยู่
                            </a>
                            <a href="{{ route('orders.index') }}"
                                class="list-group-item list-group-item-action border-0 rounded-3 mb-1">
                                <i class="fas fa-history me-2"></i> ประวัติการสั่งซื้อ
                            </a>
                            <hr class="my-2 text-muted">
                            <a href="#" class="list-group-item list-group-item-action border-0 rounded-3 text-danger"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> ออกจากระบบ
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-4 mb-4 text-start">
                    <form action="{{ route('profile.update.info') }}" method="POST" id="profileForm">
                        @csrf
                        @method('PUT')
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0 text-primary">โปรไฟล์ส่วนตัว</h5>
                                <button type="button" id="editBtn" class="btn btn-light btn-sm rounded-pill px-3 fw-bold"
                                    onclick="toggleEdit(true)">แก้ไข</button>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="small text-muted d-block mb-1">ชื่อ-นามสกุล</label>
                                    <div id="nameContainer">
                                        <span class="view-mode fw-bold text-dark fs-5">{{ Auth::user()->name }}</span>
                                        <input type="text" name="name"
                                            class="edit-mode form-control bg-light border-0 py-2 d-none"
                                            value="{{ Auth::user()->name }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted d-block mb-1">อีเมล</label>
                                    <div id="emailContainer">
                                        <span class="view-mode fw-bold text-dark fs-5">{{ Auth::user()->email }}</span>
                                        <input type="email" name="email"
                                            class="edit-mode form-control bg-light border-0 py-2 d-none"
                                            value="{{ Auth::user()->email }}" required>
                                    </div>
                                </div>
                            </div>

                            <div id="actionButtons" class="text-end mt-4 d-none">
                                <button type="button" class="btn btn-link text-muted text-decoration-none me-2 shadow-none"
                                    onclick="toggleEdit(false)">ยกเลิก</button>
                                <button type="submit"
                                    class="btn btn-primary rounded-pill px-4 shadow-sm">ยืนยันการแก้ไข</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-start">สมุดที่อยู่</h5>
                    <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#addAddressModal">
                        <i class="fas fa-plus me-1"></i> เพิ่มที่อยู่ใหม่
                    </button>
                </div>

                <div class="row g-3">
                    @forelse($addresses->sortByDesc('is_default') as $addr)
                        <div class="col-md-6 text-start">
                            <div
                                class="card border-0 shadow-sm rounded-4 h-100 position-relative {{ $addr->is_default ? 'border-top border-primary border-4' : '' }}">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            @if($addr->is_default)
                                                <span class="badge bg-primary-subtle text-primary rounded-pill mb-2 px-3"
                                                    style="font-size: 0.7rem;">ที่อยู่หลัก</span>
                                            @endif
                                            <h6 class="fw-bold mb-0 text-dark">{{ $addr->recipient_name }}</h6>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                                @if(!$addr->is_default)
                                                    <li>
                                                        <form action="{{ route('address.set-default', $addr->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit"
                                                                class="dropdown-item small text-primary fw-bold">ตั้งเป็นที่อยู่หลัก</button>
                                                        </form>
                                                    </li>
                                                @endif
                                                <li><button class="dropdown-item small" data-bs-toggle="modal"
                                                        data-bs-target="#editAddressModal{{ $addr->id }}">แก้ไขที่อยู่</button>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form action="{{ route('address.delete', $addr->id) }}" method="POST"
                                                        id="del-{{ $addr->id }}">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="dropdown-item small text-danger"
                                                            onclick="confirmDelete('{{ $addr->id }}')">ลบที่อยู่</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="small text-muted mb-0">
                                        {{ $addr->address_detail }} ต.{{ $addr->subdistrict }} อ.{{ $addr->district }}
                                        จ.{{ $addr->province }} {{ $addr->zipcode }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="editAddressModal{{ $addr->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('address.update', $addr->id) }}" method="POST"
                                    class="modal-content border-0 rounded-4 text-start">
                                    @csrf @method('PUT')
                                    <div class="modal-header border-0 p-4 pb-0">
                                        <h5 class="fw-bold mb-0">แก้ไขที่อยู่</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">รายละเอียดที่อยู่</label>
                                            <input type="text" name="address_detail" class="form-control bg-light border-0"
                                                value="{{ $addr->address_detail }}" required>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-6"><input type="text" name="subdistrict"
                                                    class="form-control bg-light border-0" value="{{ $addr->subdistrict }}"
                                                    placeholder="ตำบล" required></div>
                                            <div class="col-6"><input type="text" name="district"
                                                    class="form-control bg-light border-0" value="{{ $addr->district }}"
                                                    placeholder="อำเภอ" required></div>
                                            <div class="col-6"><input type="text" name="province"
                                                    class="form-control bg-light border-0" value="{{ $addr->province }}"
                                                    placeholder="จังหวัด" required></div>
                                            <div class="col-6"><input type="text" name="zipcode"
                                                    class="form-control bg-light border-0" value="{{ $addr->zipcode }}"
                                                    placeholder="รหัสไปรษณีย์" required></div>
                                        </div>
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" name="is_default" value="1"
                                                id="isDefault{{ $addr->id }}" {{ $addr->is_default ? 'checked' : '' }}>
                                            <label class="form-check-label small fw-bold text-primary"
                                                for="isDefault{{ $addr->id }}">ตั้งเป็นที่อยู่หลัก</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit"
                                            class="btn btn-primary w-100 rounded-pill py-2">บันทึกการแก้ไข</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 bg-light rounded-4">
                            <p class="text-muted mb-0 fs-5">ยังไม่มีที่อยู่จัดส่งสินค้า</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered shadow-lg">
            <form action="{{ route('address.store') }}" method="POST" class="modal-content border-0 rounded-4 text-start">
                @csrf
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0">เพิ่มที่อยู่จัดส่งใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">รายละเอียดที่อยู่</label>
                        <input type="text" name="address_detail" class="form-control bg-light border-0 py-2"
                            placeholder="เช่น 123/45 ซอยทองหล่อ" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6"><input type="text" name="subdistrict" class="form-control bg-light border-0 py-2"
                                placeholder="ตำบล" required></div>
                        <div class="col-6"><input type="text" name="district" class="form-control bg-light border-0 py-2"
                                placeholder="อำเภอ" required></div>
                        <div class="col-6"><input type="text" name="province" class="form-control bg-light border-0 py-2"
                                placeholder="จังหวัด" required></div>
                        <div class="col-6"><input type="text" name="zipcode" class="form-control bg-light border-0 py-2"
                                placeholder="รหัสไปรษณีย์" required></div>
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefaultNew"
                            checked>
                        <label class="form-check-label small fw-bold text-primary"
                            for="isDefaultNew">ตั้งเป็นที่อยู่หลัก</label>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'ยืนยันการลบที่อยู่?',
                text: "ข้อมูลนี้จะหายไปถาวร!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'ลบเลย!',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('del-' + id).submit();
                }
            });
        }

        function toggleEdit(isEdit) {
            const viewElements = document.querySelectorAll('.view-mode');
            const editElements = document.querySelectorAll('.edit-mode');
            const actionButtons = document.getElementById('actionButtons');
            const editBtn = document.getElementById('editBtn');

            if (isEdit) {
                viewElements.forEach(el => el.classList.add('d-none'));
                editElements.forEach(el => el.classList.remove('d-none'));
                actionButtons.classList.remove('d-none');
                editBtn.classList.add('d-none');
            } else {
                viewElements.forEach(el => el.classList.remove('d-none'));
                editElements.forEach(el => el.classList.add('d-none'));
                actionButtons.classList.add('d-none');
                editBtn.classList.remove('d-none');
                document.getElementById('profileForm').reset();
            }
        }
    </script>
@endpush