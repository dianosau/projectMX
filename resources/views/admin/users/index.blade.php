@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-header bg-white border-0 py-4">
                <div class="row align-items-center g-3">
                    <div class="col-md-4">
                        <h2 class="fw-bold mb-0">จัดการสมาชิก</h2>
                    </div>
                    <div class="col-md-8">
                        <form action="{{ route('admin.users.index') }}" method="GET">
                            <div class="input-group shadow-sm rounded-pill overflow-hidden">
                                <span class="input-group-text bg-white border-0 ps-4">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-0 px-2"
                                    placeholder="ค้นหาชื่อหรืออีเมล..." value="{{ request('search') }}">

                                <select name="status" class="form-select border-0 border-start" style="max-width: 150px;">
                                    <option value="">ทุกสถานะ</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>ใช้งานปกติ
                                    </option>
                                    <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>ถูกระงับ
                                    </option>
                                </select>

                                <button type="submit" class="btn btn-primary px-4 fw-bold">ค้นหา</button>

                                @if(request()->has('search') || request()->has('status'))
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-light border-start"
                                        title="ล้างการค้นหา">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">สมาชิก</th>
                                <th>อีเมล</th>
                                <th class="text-center">บทบาท</th>
                                <th class="text-center">สถานะ</th>
                                <th class="text-center">วันที่เข้าร่วม</th>
                                <th class="text-end pe-4">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span class="fw-bold text-dark">{{ $user->name }}</span>
                                        </div>
                                    </td>

                                    <td class="text-muted">{{ $user->email }}</td>

                                    <td class="text-center">
                                        <span
                                            class="badge {{ $user->role === 'admin' ? 'text-bg-danger' : 'text-bg-info' }} rounded-pill px-3">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @if($user->is_active)
                                            <span class="badge text-bg-success rounded-pill px-3">ใช้งานปกติ</span>
                                        @else
                                            <span class="badge text-bg-secondary rounded-pill px-3">ถูกระงับ</span>
                                        @endif
                                    </td>

                                    <td class="text-center small text-muted">{{ $user->created_at->format('d/m/Y') }}</td>

                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">

                                            <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST"
                                                id="ban-form-{{ $user->id }}">
                                                @csrf @method('PATCH')
                                                <button type="button" class="btn btn-sm btn-white border-end"
                                                    title="{{ $user->is_active ? 'ระงับการใช้งาน' : 'ปลดระงับใช้งาน' }}"
                                                    onclick="confirmAction('ต้องการเปลี่ยนสถานะการใช้งานของคุณ {{ $user->name }}?', 'ban-form-{{ $user->id }}')">
                                                    <i
                                                        class="fas {{ $user->is_active ? 'fa-user-slash text-danger' : 'fa-user-check text-success' }}"></i>
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-sm btn-white border-end" data-bs-toggle="modal"
                                                data-bs-target="#roleModal{{ $user->id }}" title="เปลี่ยนบทบาท (User/Admin)">
                                                <i class="fas fa-user-shield text-warning"></i>
                                            </button>

                                            @if(auth()->id() !== $user->id)
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                    id="delete-form-{{ $user->id }}">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-white text-secondary"
                                                        title="ลบสมาชิกถาวร"
                                                        onclick="confirmAction('คุณแน่ใจหรือไม่ที่จะลบสมาชิกคนนี้? ข้อมูลจะหายไปถาวร!', 'delete-form-{{ $user->id }}')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="roleModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">สิทธิ์การใช้งาน: {{ $user->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.users.toggleRole', $user->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <div class="modal-body py-4 text-start">
                                                    <div class="d-grid gap-2">
                                                        <input type="radio" class="btn-check" name="role" value="user"
                                                            id="roleUser{{ $user->id }}" {{ $user->role == 'user' ? 'checked' : '' }}><label
                                                            class="btn btn-outline-info rounded-pill py-2 text-start px-3"
                                                            for="roleUser{{ $user->id }}">
                                                            <i class="fas fa-user me-2"></i> สมาชิกทั่วไป (User)
                                                        </label>

                                                        <input type="radio" class="btn-check" name="role" value="admin"
                                                            id="roleAdmin{{ $user->id }}" {{ $user->role == 'admin' ? 'checked' : '' }}><label
                                                            class="btn btn-outline-danger rounded-pill py-2 text-start px-3"
                                                            for="roleAdmin{{ $user->id }}">
                                                            <i class="fas fa-user-shield me-2"></i> ผู้ดูแลระบบ (Admin)
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="submit"
                                                        class="btn btn-primary rounded-pill w-100 py-2 fw-bold">บันทึกการเปลี่ยนแปลง</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-info-circle me-2"></i> ไม่พบข้อมูลสมาชิกที่ค้นหา
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        /**
         * ฟังก์ชันยืนยันก่อนทำรายการ (ลบ หรือ แบน)
         * @param {string} message - ข้อความแจ้งเตือน
         * @param {string} formId - ID ของ Form ที่จะกด Submit
         */
        function confirmAction(message, formId) {
            const isBan = message.includes('สถานะ'); // เช็คว่าเป็นคำสั่งแบนหรือไม่ เพื่อเปลี่ยนสีปุ่ม

            Swal.fire({
                title: 'โปรดยืนยันรายการ',
                text: message,
                icon: isBan ? 'warning' : 'error', // แบน=เตือน, ลบ=ผิดพลาด/อันตราย
                showCancelButton: true,
                confirmButtonColor: isBan ? '#ffc107' : '#d33', // แบน=เหลือง, ลบ=แดง
                cancelButtonColor: '#6e7881',
                confirmButtonText: 'ยืนยันการทำรายการ',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-4 border-0 shadow-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
@endpush