@extends('layouts.app')

@section('title', 'สินค้า-ทั้งหมด')

@section('content')
<div class="container">
    <div class="text-center mb-5">
        <h2 class="display-6 fw-bold mb-2">
            @if(request('query'))
                <i class="fas fa-search text-primary me-2"></i>ผลการค้นหาสำหรับ <span class="text-primary">"{{ request('query') }}"</span>
            @else
                <i class="fas fa-th-large text-primary me-2"></i>สินค้าทั้งหมด
            @endif
        </h2>
        <div class="d-inline-block border-bottom border-primary border-3" style="width: 80px;"></div>
    </div>
        
    <div class="row">
        @forelse($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card product-card border-0 shadow-sm h-100">
                <div class="position-relative overflow-hidden">
                    <img src="{{ $product->image ? $product->image : 'https://placehold.co/300x200?text=ไม่มีรูปภาพ' }}"
                        class="card-img-top product-image" alt="{{ $product->name }}" style="height: 220px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-danger rounded-pill px-3 py-2">HOT</span>
                    </div>
                </div>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                    <p class="card-text text-muted small mb-3">{{ Str::limit($product->description, 100) }}</p>

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
                        <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline-primary w-100 rounded-pill">
                            <i class="fas fa-eye me-2"></i>ดูรายละเอียด
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-5x text-muted mb-3 opacity-50"></i>
                <h4 class="text-muted">❌ ไม่มีสินค้า</h4>
                @if(request('query'))
                    <p class="text-muted">ไม่พบสินค้าที่ตรงกับคำค้นหา "{{ request('query') }}"</p>
                @endif
            </div>
        </div>
        @endforelse
    </div>
</div>

<style>
    .product-card {
        transition: all 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    .product-image {
        transition: transform 0.3s ease;
    }
</style>
@endsection