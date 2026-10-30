
<?php
// Header dùng chung cho các trang
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tuKhoaTimKiem = $_GET['q'] ?? '';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Văn phòng phẩm Học Tốt</title>
    
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet"
          href="/web_van_pp/assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-top container">
        <a href="/web_van_pp/index.php" class="logo">
            <span class="logo-icon">HT</span>
            <span>
                <strong>HỌC TỐT</strong>
                <small>Đồng hành cùng tri thức</small>
            </span>
        </a>

        <form class="search-form"
              action="/web_van_pp/sanPham/products.php" method="get">
            <input type="search" name="q"
                   placeholder="Tìm bút, tập vở, dụng cụ học tập..."
                   value="<?= htmlspecialchars($tuKhoaTimKiem) ?>">
            <button type="submit" class="search-button" aria-label="Tìm kiếm">
    <i class="fa-solid fa-magnifying-glass"></i>
</button>
        </form>

        
<div class="account-links">
    <a href="/web_van_pp/sanPham/order_history.php?feature=Kiểm%20tra%20đơn%20hàng">
        Kiểm tra đơn hàng
    </a>

    <a href="/web_van_pp/taiKhoan/DangNhap.php?feature=Đăng%20nhập">
        Đăng nhập
    </a>

    <a href="/web_van_pp/taiKhoan/DangKy.php?feature=Đăng%20ký">
        Đăng ký
    </a>

    <a href="/web_van_pp/sanPham/cart.php?feature=Giỏ%20hàng"
       class="cart-link">
        <span class="cart-icon">🛒</span>
        <span>Giỏ hàng</span>
    </a>
</div>

    </div>

    <nav class="main-nav">
        <div class="container nav-inner">
            
    <div class="category-menu">
        <button class="menu-toggle"
            id="category-toggle"
            type="button"
            aria-expanded="false"
            aria-controls="category-dropdown">
           <i class="fa-solid fa-bars"></i>
          Danh mục
          <i class="fa-solid fa-chevron-down"></i>
     </button>

     <ul class="category-dropdown" id="category-dropdown" hidden>
        <li>
            <a href="/web_van_pp/sanPham/products.php?keyword=But">
                <i class="fa-solid fa-pen"></i>
                Bút viết
            </a>
        </li>
        <li>
            <a href="/web_van_pp/sanPham/products.php?keyword=Tap">
                <i class="fa-solid fa-book-open"></i>
                Tập vở
            </a>
        </li>
        <li>
            <a href="/web_van_pp/sanPham/products.php?keyword=Dung cu hoc tap">
                <i class="fa-solid fa-ruler"></i>
                Dụng cụ học tập
            </a>
        </li>
        <li>
            <a href="/web_van_pp/sanPham/products.php?keyword=Van phong pham">
                <i class="fa-solid fa-boxes-stacked"></i>
                Văn phòng phẩm
            </a>
        </li>
         <li>
            <a href="/web_van_pp/sanPham/products.php?keyword=My thuat">
                <i class="fa-solid fa-palette"></i>
                Mỹ thuật
            </a>
        </li>
         <li>
            <a href="/web_van_pp/sanPham/products.php?keyword=Giay in">
                <i class="fa-solid fa-file-lines"></i>
                Giấy in
            </a>
        </li>
        <li>
            <a href="/web_van_pp/sanPham/products.php">
                <i class="fa-solid fa-layer-group"></i>
                Tất cả sản phẩm
            </a>
        </li>
    </ul>
    </div>


            <?php
$currentPage = basename($_SERVER['PHP_SELF']);
$currentPath = $_SERVER['PHP_SELF'];
?>

<ul class="nav-list" id="nav-list">
    <li>
        <a href="/web_van_pp/index.php"
           class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">
            Trang chủ
        </a>
    </li>

    <li>
        <a href="/web_van_pp/sanPham/products.php"
           class="<?= strpos($currentPath, '/sanPham/') !== false ? 'active' : '' ?>">
            Sản phẩm
        </a>
    </li>

    <li>
        <a href="/web_van_pp/about.php"
           class="<?= $currentPage === 'about.php' ? 'active' : '' ?>">
            Giới thiệu
        </a>
    </li>

    <li>
        <a href="/web_van_pp/contact.php"
           class="<?= $currentPage === 'contact.php' ? 'active' : '' ?>">
            Liên hệ
        </a>
    </li>
</ul>
        </div>
    </nav>
    
</header>

<main class="container page-content">
