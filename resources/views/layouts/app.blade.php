<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ร้านเครื่องดนตรีออนไลน์') - MusicStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3f51b5;
            --secondary-color: #ff9800;
            --dark-color: #303f9f;
            --light-color: #c5cae9;
        }

        body {
            font-family: 'Prompt', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-color) 100%);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: white !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .cart-badge {
            font-size: 0.7rem;
            padding: 0.35em 0.5em;
            border: 2px solid var(--primary-color);
        }

        .search-form {
            position: relative;
            width: 280px;
        }

        .navbar .form-control {
            border-radius: 25px;
            padding-right: 45px;
        }

        .navbar .btn-search {
            position: absolute;
            right: 3px;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 20px;
            background-color: var(--secondary-color);
            border: none;
            color: white;
            padding: 2px 12px;
        }

        .footer {
            background: linear-gradient(135deg, var(--dark-color) 0%, #1a237e 100%);
            color: white;
            padding: 40px 0 20px 0;
            margin-top: 50px;
        }

        .footer h5 {
            font-weight: bold;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--secondary-color);
        }

        .footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: 0.3s;
        }

        .footer a:hover {
            color: white;
            padding-left: 5px;
        }

        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            margin-right: 10px;
        }

        .social-links a:hover {
            background: var(--secondary-color);
            transform: translateY(-5px);
        }

        /* 1. พื้นหลังของแถบ Tab ทั้งหมด (ทำให้เข้มขึ้นเล็กน้อยเพื่อให้เห็นขอบเขต) */
        #orderStatusTabs {
            background-color: #f1f3f5 !important;
            /* สีเทาอ่อน (Light Gray) */
            border: 1px solid #dee2e6;
            padding: 5px;
        }

        /* 2. สไตล์ของปุ่ม Tab ปกติ (ที่ยังไม่ได้เลือก) */
        #orderStatusTabs .nav-link {
            color: #495057 !important;
            /* บังคับให้เป็นสีเทาเข้มเกือบดำ */
            font-weight: 500;
            transition: all 0.2s ease;
        }

        /* 3. สไตล์ของปุ่ม Tab เมื่อถูกเลือก (Active) */
        #orderStatusTabs .nav-link.active {
            background-color: #0d6efd !important;
            /* สีน้ำเงินหลัก */
            color: #ffffff !important;
            /* ตัวหนังสือสีขาว (เฉพาะปุ่มที่เลือก) */
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
        }

        /* 4. เมื่อเอาเมาส์ไปวาง (Hover) แต่ยังไม่ได้กด */
        #orderStatusTabs .nav-link:hover:not(.active) {
            background-color: #e9ecef;
            color: #000000 !important;
        }
    </style>
    @yield('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home.view') }}">
                <i class="fas fa-guitar me-2"></i>Music Wave
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home.view') }}"><i class="fas fa-home me-1"></i>หน้าแรก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('all.product') }}"><i
                                class="fas fa-guitar me-1"></i>สินค้าทั้งหมด</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            หมวดหมู่
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(App\Models\ProductCategory::all() as $category)
                                <li><a class="dropdown-item"
                                        href="{{ route('category.products', $category->id) }}">{{ $category->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>

                <form class="d-flex me-3 search-form" action="{{ route('all.product') }}" method="GET">
                    <input class="form-control" type="search" name="query" placeholder="ค้นหาเครื่องดนตรี...">
                    <button class="btn btn-search" type="submit"><i class="fas fa-search"></i></button>
                </form>

                <ul class="navbar-nav align-items-center">
                    {{-- ตะกร้าสินค้า (เห็นทุกคน ทั้ง Admin และ User) --}}
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative p-2" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            @auth
                                @php
                                    $totalQuantity = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
                                @endphp
                                @if($totalQuantity > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                                        {{ $totalQuantity }}
                                    </span>
                                @endif
                            @endauth
                        </a>
                    </li>

                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">เข้าสู่ระบบ</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-warning text-dark ms-lg-2 px-3 rounded-pill"
                                href="{{ route('register') }}">สมัครสมาชิก</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                <li>
                                    <h6 class="dropdown-header text-muted">บัญชีของฉัน</h6>
                                </li>

                                {{-- ลิงก์ไปยังหน้าจัดการโปรไฟล์และที่อยู่ --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                                        <i class="fas fa-user-cog me-2"></i>จัดการบัญชี / ที่อยู่
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">
                                        <i class="fas fa-list-alt me-2"></i>คำสั่งซื้อ
                                    </a>
                                </li>

                                @if(Auth::user()->role === 'admin')
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-primary">การจัดการระบบ</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item fw-bold text-primary" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-chart-line me-2"></i>ไปที่ Admin Dashboard
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>ออกจากระบบ
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-info-circle me-2"></i>เกี่ยวกับเรา</h5>
                    <p class="text-white-50">ร้านเครื่องดนตรีออนไลน์ จำหน่ายเครื่องดนตรีคุณภาพ
                        มั่นใจได้ในคุณภาพและบริการ พร้อมส่งทั่วประเทศ</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-phone me-2"></i>ติดต่อเรา</h5>
                    <p class="mb-2"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> 123 ถนนพระยาสัจจา ชลบุรี
                        20000</p>
                    <p class="mb-2"><i class="fas fa-phone me-2 text-secondary"></i> 061-615-3677</p>
                    <p class="mb-2"><i class="fas fa-envelope me-2 text-secondary"></i> wave@musicstore.com</p>
                </div>
                <div class="col-md-4">
                    <h5><i class="fas fa-share-alt me-2"></i>ติดตามเรา</h5>
                    <div class="d-flex social-links">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-line"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="opacity: 0.1;">
            <div class="text-center">
                <p class="mb-0 text-white-50">© {{ date('Y') }} MusicStore. สงวนลิขสิทธิ์</p>
            </div>
        </div>
    </footer>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('script')
</body>

</html>