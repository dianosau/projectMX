@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-2">จัดการคลังสินค้า</h2>
            <div class="d-inline-block border-bottom border-primary border-3 mb-4" style="width: 80px;"></div>
            <p class="text-muted">เลือกประเภทสินค้าที่ต้องการจัดการข้อมูล</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($categories as $category)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm category-card overflow-hidden">
                        {{-- ส่วนแสดงรูปภาพหมวดหมู่ --}}
                        <div class="position-relative overflow-hidden">
                            {{-- ตรวจสอบรูปภาพเหมือนหน้า Home --}}
                            <img src="{{ $category->image ? $category->image : 'https://via.placeholder.com/300x200?text=ไม่มีรูปภาพ' }}"
                                class="card-img-top category-image" alt="{{ $category->name }}"
                                style="height: 200px; object-fit: cover; transition: transform 0.3s ease;">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-25"></div>
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

    <style>
        /* ใช้ Effect เดียวกับหน้า Home */
        .category-card {
            transition: all 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }

        .category-card:hover .category-image {
            transform: scale(1.1);
        }
    </style>
@endsection