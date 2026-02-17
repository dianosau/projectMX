@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-4">
                <div class="d-flex justify-content-between align-items-center">

                    {{-- ฝั่งซ้าย: ปุ่มย้อนกลับแบบชัดเจน --}}
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.products.categories') }}"
                            class="btn btn-secondary rounded-pill px-4 me-3 shadow-sm d-flex align-items-center">
                            <i class="fas fa-chevron-left me-2"></i>
                            <span>กลับหน้าประเภทสินค้า</span>
                        </a>
                        <div class="vr me-3" style="height: 30px; opacity: 0.1;"></div> {{-- เส้นแบ่ง --}}
                        <div>
                            <h2 class="fw-bold mb-0" style="font-size: 1.5rem;">จัดการสินค้า</h2>
                            <small class="text-muted">ประเภท: {{ $category->name }}</small>
                        </div>
                    </div>

                    {{-- ฝั่งขวา: ปุ่มเพิ่มสินค้า --}}
                    <div>
                        <button class="btn btn-success rounded-pill px-4 shadow-sm fw-bold" onclick="openAddModal()">
                            <i class="fas fa-plus-circle me-1"></i> เพิ่มสินค้าใหม่
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>รูปภาพ</th>
                                <th>ชื่อสินค้า</th>
                                <th>ราคา</th>
                                <th>สต็อก</th>
                                <th class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>@if($product->image)
                                        @if(filter_var($product->image, FILTER_VALIDATE_URL))
                                            {{-- ถ้าเป็น URL ให้ดึงมาแสดงตรงๆ --}}
                                            <img src="{{ $product->image }}" width="50" class="rounded shadow-sm"
                                                alt="{{ $product->name }}">
                                        @else
                                            {{-- ถ้าไม่ใช่ URL (เป็นชื่อไฟล์) ให้ดึงจากโฟลเดอร์ products --}}
                                            <img src="{{ asset('images/products/' . $product->image) }}" width="50"
                                                class="rounded shadow-sm" alt="{{ $product->name }}">
                                        @endif
                                    @else
                                        <img src="https://via.placeholder.com/50?text=No+Img" class="rounded shadow-sm">@endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>฿{{ number_format($product->price) }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary" onclick="openEditModal({{ $product }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('ยืนยันการลบ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="productForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="methodField"></div>

                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-primary text-white border-0">
                            <h5 class="modal-title fw-bold" id="modalTitle">เพิ่มสินค้า</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            {{-- เก็บ category_id จากตัวแปร $category ที่ส่งมาจาก Controller --}}
                            <input type="hidden" name="category_id" value="{{ $category->id }}">

                            <div class="mb-3">
                                <label class="form-label fw-bold">ชื่อสินค้า</label>
                                <input type="text" name="name" id="p_name" class="form-control rounded-pill"
                                    placeholder="ระบุชื่อสินค้า" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">รายละเอียดสินค้า</label>
                                <textarea name="description" id="p_description" class="form-control rounded-4" rows="3"
                                    placeholder="ระบุรายละเอียดสินค้า"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">ราคา (บาท)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">฿</span>
                                        <input type="number" name="price" id="p_price" class="form-control" step="0.01"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">จำนวนสต็อก</label>
                                    <input type="number" name="stock" id="p_stock" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">รูปภาพสินค้า</label>

                                <div class="d-flex gap-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="image_type" id="type_file"
                                            value="file" checked onclick="toggleImageInput('file')">
                                        <label class="form-check-label" for="type_file">อัพโหลดไฟล์</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="image_type" id="type_url"
                                            value="url" onclick="toggleImageInput('url')">
                                        <label class="form-check-label" for="type_url">เพิ่มลิงก์ URL</label>
                                    </div>
                                </div>

                                <div id="input_file_container">
                                    <input type="file" name="image_file" id="p_image_file" class="form-control">
                                </div>

                                <div id="input_url_container" class="d-none">
                                    <input type="url" name="image_url" id="p_image_url" class="form-control"
                                        placeholder="ตัวอย่าง: https://images.unsplash.com/photo-123456789">
                                </div>

                                <div id="currentImageContainer" class="mt-2 d-none">
                                    <small class="text-muted">รูปภาพปัจจุบัน:</small><br>
                                    <img id="currentImage" src="" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light rounded-pill px-4"
                                data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">บันทึกข้อมูล</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
@endsection

    @push('script')
        <script>
            const modalElement = document.getElementById('productModal');
            const modal = new bootstrap.Modal(modalElement);
            const form = document.getElementById('productForm');
            const methodField = document.getElementById('methodField');

            function openAddModal() {
                document.getElementById('modalTitle').innerText = "เพิ่มสินค้าใหม่ในหมวด {{ $category->name }}";
                form.action = "{{ route('admin.products.store') }}";
                methodField.innerHTML = "";
                form.reset(); // ล้างค่าในฟอร์มทั้งหมด

                // 1. บังคับให้ Radio กลับไปที่ 'file'
                document.getElementById('type_file').checked = true;

                // 2. เรียกฟังก์ชันเพื่อสลับช่อง Input ให้แสดงช่องไฟล์ (และซ่อนช่อง URL)
                toggleImageInput('file');

                // 3. ซ่อนส่วนแสดงรูปภาพปัจจุบัน (เพราะเป็นสินค้าใหม่)
                document.getElementById('currentImageContainer').classList.add('d-none');

                modal.show();
            }

            function toggleImageInput(type) {
                const fileContainer = document.getElementById('input_file_container');
                const urlContainer = document.getElementById('input_url_container');

                if (type === 'file') {
                    fileContainer.classList.remove('d-none'); // แสดงช่องไฟล์
                    urlContainer.classList.add('d-none');    // ซ่อนช่อง URL
                } else {
                    fileContainer.classList.add('d-none');    // ซ่อนช่องไฟล์
                    urlContainer.classList.remove('d-none'); // แสดงช่อง URL
                }
            }

            function openEditModal(product) {
                const modal = new bootstrap.Modal(document.getElementById('productModal'));
                const form = document.getElementById('productForm');

                // เปลี่ยนหัวข้อและ Action
                document.getElementById('modalTitle').innerText = 'แก้ไขสินค้า';
                form.action = `{{ url('admin/products') }}/${product.id}`;
                document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

                // เติมข้อมูลพื้นฐาน
                document.getElementById('p_name').value = product.name;
                document.getElementById('p_description').value = product.description || '';
                document.getElementById('p_price').value = product.price;
                document.getElementById('p_stock').value = product.stock;

                const currentImg = document.getElementById('currentImage');
                const container = document.getElementById('currentImageContainer');
                const urlInput = document.getElementById('p_image_url'); // ช่องกรอก URL

                if (product.image) {
                    // ตรวจสอบว่าเป็น URL (ขึ้นต้นด้วย http) หรือเป็นไฟล์ในเครื่อง
                    const isUrl = product.image.startsWith('http');

                    if (isUrl) {
                        // กรณีเป็น Link URL
                        currentImg.src = product.image;
                        urlInput.value = product.image; // <--- ใส่ค่า link ลงในช่อง input
                        document.getElementById('type_url').checked = true;
                        toggleImageInput('url');
                    } else {
                        // กรณีเป็นไฟล์อัปโหลด
                        currentImg.src = `{{ asset('storage') }}/${product.image}`; // แก้จาก images/products เป็น storage ตาม Controller
                        urlInput.value = ''; // ล้างค่าในช่อง URL
                        document.getElementById('type_file').checked = true;
                        toggleImageInput('file');
                    }
                    container.classList.remove('d-none');
                } else {
                    // กรณีไม่มีรูปภาพเลย
                    container.classList.add('d-none');
                    urlInput.value = '';
                    document.getElementById('p_image_url').placeholder = 'วาง URL รูปภาพที่นี่ (เช่น https://...)'; // แสดง Hint
                }

                modal.show();
            }
        </script>
    @endpush