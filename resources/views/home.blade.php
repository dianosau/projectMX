@extends('layouts.app')

@section('title', 'หน้าแรก')

@section('content')
    <!-- Hero Carousel -->
    <div class="mb-5">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
            </div>
            <div class="carousel-inner rounded-3 shadow-lg overflow-hidden">
                <div class="carousel-item active">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1511379938547-c1f69419868d" class="d-block w-100"
                            alt="เครื่องดนตรี" style="max-height: 550px; object-fit: cover; filter: brightness(0.7);">
                        <div
                            class="carousel-caption d-md-block position-absolute top-50 start-50 translate-middle text-center w-100">
                            <h1 class="display-4 fw-bold mb-3 animate__animated animate__fadeInDown">
                                เครื่องดนตรีคุณภาพเยี่ยม</h1>
                            <p class="lead mb-4 animate__animated animate__fadeInUp">ร้านเครื่องดนตรีออนไลน์ที่คุณไว้วางใจ
                            </p>
                            <a href="#"
                                class="btn btn-light btn-lg px-5 py-3 rounded-pill shadow-sm animate__animated animate__fadeInUp animate__delay-1s">
                                <i class="fas fa-shopping-bag me-2"></i>ช้อปเลย
                            </a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1468164016595-6108e4c60c8b" class="d-block w-100"
                            alt="กีต้าร์" style="max-height: 550px; object-fit: cover; filter: brightness(0.7);">
                        <div
                            class="carousel-caption d-md-block position-absolute top-50 start-50 translate-middle text-center w-100">
                            <h1 class="display-4 fw-bold mb-3">กีต้าร์หลากหลายรุ่น</h1>
                            <p class="lead mb-4">มีให้เลือกครบทุกแบรนด์ดัง</p>
                            <a href="{{ route('category.products', 1) }}"
                                class="btn btn-light btn-lg px-5 py-3 rounded-pill shadow-sm">
                                <i class="fas fa-guitar me-2"></i>ดูกีต้าร์ทั้งหมด
                            </a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1598488035139-bdbb2231ce04" class="d-block w-100"
                            alt="เปียโน" style="max-height: 550px; object-fit: cover; filter: brightness(0.7);">
                        <div
                            class="carousel-caption d-md-block position-absolute top-50 start-50 translate-middle text-center w-100">
                            <h1 class="display-4 fw-bold mb-3">เปียโนและคีย์บอร์ด</h1>
                            <p class="lead mb-4">สำหรับมือใหม่และมืออาชีพ</p>
                            <a href="{{ route('category.products', 2) }}"
                                class="btn btn-light btn-lg px-5 py-3 rounded-pill shadow-sm">
                                <i class="fas fa-music me-2"></i>ดูเปียโนทั้งหมด
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="row mb-5">
        <div class="col-12 mb-5">
            <div class="text-center">
                <h2 class="display-5 fw-bold mb-2">หมวดหมู่เครื่องดนตรี</h2>
                <div class="d-inline-block border-bottom border-primary border-3 mb-4" style="width: 80px;"></div>
                <p class="text-muted">เลือกดูสินค้าตามหมวดหมู่ที่คุณสนใจ</p>
            </div>
        </div>
        @foreach(App\Models\ProductCategory::all() as $category)
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 category-card overflow-hidden">
                    <div class="position-relative overflow-hidden">
                        <img src="{{ $category->image ? $category->image : 'https://via.placeholder.com/300x200?text=ไม่มีรูปภาพ' }}"
                            class="card-img-top category-image" alt="{{ $category->name }}"
                            style="height: 250px; object-fit: cover; transition: transform 0.3s ease;">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-25"></div>
                    </div>
                    <div class="card-body text-center p-4">
                        <h3 class="card-title h4 fw-bold mb-3">{{ $category->name }}</h3>
                        <p class="card-text text-muted mb-4">{{ $category->description }}</p>
                        <a href="{{ route('category.products', $category->id) }}" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-arrow-right me-2"></i>ดูสินค้า
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Featured Products Section -->
    <div class="bg-light py-5 mb-5 rounded-3">
        <div class="row mb-4">
            <div class="col-12 mb-5">
                <div class="text-center">
                    <h2 class="display-5 fw-bold mb-2">สินค้าแนะนำ</h2>
                    <div class="d-inline-block border-bottom border-primary border-3 mb-4" style="width: 80px;"></div>
                    <p class="text-muted">สินค้าคุณภาพที่เราคัดสรรมาเพื่อคุณ</p>
                </div>
            </div>
            @foreach(App\Models\Product::inRandomOrder()->limit(8)->get() as $product)
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm h-100 product-card">
                        <div class="position-relative overflow-hidden">
                            <img src="{{ $product->image ? $product->image : 'https://via.placeholder.com/300x200?text=ไม่มีรูปภาพ' }}"
                                class="card-img-top product-image" alt="{{ $product->name }}"
                                style="height: 220px; object-fit: cover; transition: transform 0.3s ease;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-danger rounded-pill px-3 py-2">HOT</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                            <p class="card-text text-muted small mb-3">{{ Str::limit($product->description, 80) }}</p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 fw-bold text-primary mb-0">฿{{ number_format($product->price, 2) }}</span>
                                    <div class="text-warning small">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                </div>
                                <a href="{{ route('product.show', $product->id) }}"
                                    class="btn btn-outline-primary w-100 rounded-pill">
                                    <i class="fas fa-eye me-2"></i>ดูรายละเอียด
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <a href="{{ route('all.product') }}" class="btn btn-lg btn-primary px-5 py-3 rounded-pill shadow-sm">
                    <i class="fas fa-th me-2"></i>ดูสินค้าทั้งหมด
                </a>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="row mb-5">
        <div class="col-12 mb-5">
            <div class="text-center">
                <h2 class="display-5 fw-bold mb-2">ทำไมต้องเลือกเรา</h2>
                <div class="d-inline-block border-bottom border-primary border-3 mb-4" style="width: 80px;"></div>
                <p class="text-muted">บริการที่ทำให้เราแตกต่างจากคู่แข่ง</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm text-center h-100 feature-card">
                <div class="card-body p-4">
                    <div class="feature-icon bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                        style="width: 80px; height: 80px;">
                        <i class="fas fa-shipping-fast fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-3">จัดส่งรวดเร็ว</h4>
                    <p class="text-muted">จัดส่งภายใน 24 ชั่วโมงหลังยืนยันการชำระเงิน</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm text-center h-100 feature-card">
                <div class="card-body p-4">
                    <div class="feature-icon bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                        style="width: 80px; height: 80px;">
                        <i class="fas fa-shield-alt fa-2x text-success"></i>
                    </div>
                    <h4 class="fw-bold mb-3">รับประกันสินค้า</h4>
                    <p class="text-muted">รับประกันสินค้าทุกชิ้น พร้อมบริการหลังการขาย</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm text-center h-100 feature-card">
                <div class="card-body p-4">
                    <div class="feature-icon bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                        style="width: 80px; height: 80px;">
                        <i class="fas fa-money-bill-wave fa-2x text-warning"></i>
                    </div>
                    <h4 class="fw-bold mb-3">หลายช่องทางชำระเงิน</h4>
                    <p class="text-muted">QR Code, โอนเงิน และเก็บเงินปลายทาง</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm text-center h-100 feature-card">
                <div class="card-body p-4">
                    <div class="feature-icon bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                        style="width: 80px; height: 80px;">
                        <i class="fas fa-headset fa-2x text-info"></i>
                    </div>
                    <h4 class="fw-bold mb-3">บริการลูกค้า</h4>
                    <p class="text-muted">ทีมงานพร้อมให้คำปรึกษาตลอด 7 วัน</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .category-card:hover .category-image {
            transform: scale(1.1);
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .category-card,
        .product-card,
        .feature-card {
            transition: all 0.3s ease;
        }

        .category-card:hover,
        .product-card:hover,
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
        }

        .feature-icon {
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: rotate(360deg);
        }
    </style>
@endsection