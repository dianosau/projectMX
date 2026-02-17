@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-2">จัดการคลังสินค้า</h2>
            <div class="d-inline-block border-bottom border-primary border-3 mb-4" style="width: 80px;"></div>
            <p class="text-muted">เลือกประเภทสินค้าที่ต้องการจัดการข้อมูล</p>

            <div class="mt-2">
                {{-- ปุ่มเรียก JavaScript --}}
                <button class="btn btn-success rounded-pill px-4 shadow-sm fw-bold" onclick="openAddCategoryModal()">
                    <i class="fas fa-plus-circle me-2"></i>เพิ่มหมวดหมู่ใหม่
                </button>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($categories as $category)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm category-card overflow-hidden">
                        <div class="position-relative overflow-hidden">
                            @php
                                $imageUrl = $category->image;
                                if ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                    $imageUrl = asset('storage/' . $imageUrl);
                                }
                            @endphp
                            <img src="{{ $imageUrl ?: 'https://via.placeholder.com/300x200?text=ไม่มีรูปภาพ' }}"
                                class="card-img-top category-image" alt="{{ $category->name }}"
                                style="height: 200px; object-fit: cover;">

                            <div class="position-absolute top-0 end-0 p-2 d-flex gap-2" style="z-index: 10;">
                                <button class="btn btn-light btn-sm rounded-circle shadow-sm"
                                    onclick="openEditCategoryModal({{ json_encode($category) }})">
                                    <i class="fas fa-edit text-primary"></i>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                    onsubmit="return confirm('ยืนยันการลบหมวดหมู่?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm">
                                        <i class="fas fa-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="card-body text-center p-4">
                            <h4 class="fw-bold mb-2">{{ $category->name }}</h4>
                            <p class="text-muted small mb-3">{{ $category->description }}</p>
                            <p class="badge bg-light text-primary rounded-pill px-3">
                                มีสินค้าทั้งหมด {{ $category->products_count }} รายการ
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('admin.products.index', $category->id) }}"
                                    class="btn btn-primary rounded-pill px-4">
                                    เข้าจัดการสินค้า <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="catModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form id="categoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="categoryMethod"></div>
                    <div class="modal-header border-0 pb-0">
                        <h5 class="fw-bold" id="catModalTitle">จัดการหมวดหมู่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ชื่อหมวดหมู่</label>
                            <input type="text" name="name" id="cat_name" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">คำอธิบาย</label>
                            <textarea name="description" id="cat_description" class="form-control rounded-3"
                                rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold d-block">รูปภาพหมวดหมู่</label>
                            <div class="btn-group w-100 mb-3" role="group">
                                <input type="radio" class="btn-check" name="cat_img_type" id="cat_type_file" value="file"
                                    checked onclick="toggleCatImg('file')">
                                <label class="btn btn-outline-primary" for="cat_type_file">อัปโหลดไฟล์</label>
                                <input type="radio" class="btn-check" name="cat_img_type" id="cat_type_url" value="url"
                                    onclick="toggleCatImg('url')">
                                <label class="btn btn-outline-primary" for="cat_type_url">ลิงก์ URL</label>
                            </div>

                            <div id="cat_file_input">
                                <input type="file" name="image_file" class="form-control">
                            </div>
                            <div id="cat_url_input" class="d-none">
                                <input type="url" name="image_url" id="cat_url_val" class="form-control"
                                    placeholder="https://...">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">บันทึกข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ประกาศตัวแปร Modal ไว้ด้านนอกเพื่อให้ทุกฟังก์ชันเข้าถึงได้
        let catModalInstance;

        document.addEventListener('DOMContentLoaded', function () {
            catModalInstance = new bootstrap.Modal(document.getElementById('categoryModal'));
        });

        function toggleCatImg(type) {
            document.getElementById('cat_file_input').classList.toggle('d-none', type !== 'file');
            document.getElementById('cat_url_input').classList.toggle('d-none', type !== 'url');
        }

        function openAddCategoryModal() {
            const form = document.getElementById('categoryForm');
            document.getElementById('catModalTitle').innerText = "เพิ่มหมวดหมู่ใหม่";
            form.action = "{{ route('admin.categories.store') }}";
            document.getElementById('categoryMethod').innerHTML = ""; // ล้าง Method PUT
            form.reset();
            toggleCatImg('file');
            catModalInstance.show();
        }

        function openEditCategoryModal(category) {
            const form = document.getElementById('categoryForm');
            document.getElementById('catModalTitle').innerText = "แก้ไขหมวดหมู่: " + category.name;
            form.action = `/admin/categories/${category.id}`;
            document.getElementById('categoryMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('cat_name').value = category.name;
            document.getElementById('cat_description').value = category.description || '';

            if (category.image && category.image.startsWith('http')) {
                document.getElementById('cat_type_url').checked = true;
                toggleCatImg('url');
                document.getElementById('cat_url_val').value = category.image;
            } else {
                document.getElementById('cat_type_file').checked = true;
                toggleCatImg('file');
                document.getElementById('cat_url_val').value = '';
            }
            catModalInstance.show();
        }
    </script>

    <style>
        .category-card {
            transition: all 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
        }

        .category-image {
            transition: transform 0.3s ease;
        }

        .category-card:hover .category-image {
            transform: scale(1.05);
        }
    </style>
@endsection