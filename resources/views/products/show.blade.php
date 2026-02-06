@extends('layouts.app')

@section('title', $product->name)

@section('styles')

    <style>
        .product-detail-card {
            background: #fff;
            border-radius: 40px;
            overflow: hidden;
        }

        .img-display-container {
            border: 2.5px solid var(--primary-color);
            border-radius: 35px;
            padding: 20px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .img-display-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .img-display-container img {
            transition: transform 0.3s ease;
            max-width: 100%;
            height: auto;
        }

        .img-display-container:hover img {
            transform: scale(1.05);
        }



        .info-display-container {
            border: 2.5px solid var(--primary-color);
            border-radius: 35px;
            padding: 30px;
            height: 100%;
            position: relative;
            background: #fff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .comment-section {
            border: 2.5px solid var(--primary-color);
            border-radius: 35px;
            padding: 20px;
            margin-top: 25px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .price-text {
            font-size: 3rem;
            font-weight: 800;
            color: #d32f2f;
            margin-bottom: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-buy {
            background: linear-gradient(135deg, #FFD54F 0%, #FFC107 100%);
            color: #5D4037;
            font-weight: bold;
            border-radius: 15px;
            padding: 12px 40px;
            border: none;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        }

        .btn-buy:hover {
            background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
        }

        .btn-login-to-buy {
            background: #f0ad4e;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border-radius: 15px;
            padding: 12px 40px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-login-to-buy:hover {
            background: #ec971f;
            color: white;
        }

        .quantity-selector {
            width: 80px;
            border: 2px solid #333;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
        }

        .comment-item {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 15px;
            transition: all 0.3s ease;
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="img-display-container">
                    @if($product->image)
                        @if(filter_var($product->image, FILTER_VALIDATE_URL))
                            <img src="{{ $product->image }}" class="img-fluid" alt="{{ $product->name }}">
                        @else
                            <img src="{{ asset('images/products/' . $product->image) }}" class="img-fluid"
                                alt="{{ $product->name }}">
                        @endif
                    @else
                        <img src="https://via.placeholder.com/500x500?text=No+Image" class="img-fluid" alt="No Image">
                    @endif
                </div>

                <div class="comment-section">
                    <h5 class="fw-bold mb-3"><i class="fas fa-comments me-2 text-primary"></i>ความคิดเห็นจากผู้ซื้อ</h5>
                    <div class="comment-item mb-2">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                                    style="width: 45px; height: 45px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>

                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold">User_MusicShop</h6>
                                <div class="text-warning small mb-1">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                        class="fas fa-star"></i><i class="fas fa-star"></i>
                                </div>
                                <p class="text-muted small mb-0">"เสียงดีมากครับ คุ้มราคา ส่งไวมาก"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="d-flex flex-column h-100">
                    <div class="mb-2">
                        <h1 class="display-5 fw-bold text-dark">{{ $product->name }}</h1>
                        <p class="text-muted">ID: {{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }} | หมวดหมู่:
                            {{ $product->category->name }}
                        </p>
                    </div>

                    <div class="info-display-container mb-4">
                        <h5 class="fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>รายละเอียดสินค้า</h5>
                        <p class="text-secondary" style="line-height: 1.8;">
                            {{ $product->description }}
                        </p>
                    </div>

                    <div class="mt-auto">
                        <div class="d-flex align-items-end mb-4">
                            <div class="ms-auto text-end">
                                <p class="mb-0 fw-bold text-muted">ราคาขาย</p>
                                <h2 class="price-text">{{ number_format($product->price) }} .-</h2>
                            </div>
                        </div>

                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex flex-column">
                                    <label class="small fw-bold mb-1">จำนวน</label>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                        class="form-control quantity-selector">
                                </div>

                                <div class="flex-grow-1 pt-4">
                                    @if($product->stock > 0)
                                        @auth
                                            <button type="submit" class="btn btn-buy w-100 shadow-sm">
                                                <i class="fas fa-cart-plus me-2"></i> หยิบใส่ตะกร้า
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-login-to-buy w-100 shadow-sm">
                                                <i class="fas fa-sign-in-alt me-2"></i> เข้าสู่ระบบเพื่อซื้อ
                                            </a>
                                        @endauth
                                    @else
                                        <button type="button" class="btn btn-secondary w-100 shadow-sm" disabled>
                                            สินค้าหมด
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <p class="mt-3 text-muted small"><i class="fas fa-box me-1"></i> สินค้าคงเหลือในคลัง:
                            {{ $product->stock }} ชิ้น
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection