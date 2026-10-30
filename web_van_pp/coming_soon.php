
<?php
include __DIR__ . '/includes/header.php';

$tenChucNang = $_GET['feature'] ?? 'Chức năng này';
?>

<section class="info-card">
    <h1 class="section-title">
        <?= htmlspecialchars($tenChucNang) ?>
    </h1>

    <p>
        Chức năng đang được hoàn thiện.
        Bạn có thể tiếp tục xem các sản phẩm tại Học Tốt.
    </p>

    <a class="btn-primary" href="/HOC_TOT/products.php">
        Xem sản phẩm
    </a>

    <a href="/HOC_TOT/index.php">
        Về trang chủ
    </a>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
