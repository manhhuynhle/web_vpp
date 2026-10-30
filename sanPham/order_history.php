<?php include __DIR__ . '/../includes/header.php'; ?>

<link rel="stylesheet" href="/web_van_pp/assets/css/cart_style.css">

<div class="container my-4" style="max-width: 1000px; margin: 0 auto; padding: 20px 15px;">
    <h2 style="font-size: 22px; margin-bottom: 20px;">Lịch sử mua hàng</h2>

    <div class="tl-card">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Ngày đặt</th>
                    <th>Sản phẩm</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>#TL88902</strong></td>
                    <td>27/10/2026</td>
                    <td>Bút gel GEL-056 (x2), Tập vở (x5)</td>
                    <td style="color: #D32F2F; font-weight: bold;">103.400đ</td>
                    <td>COD</td>
                    <td><span class="status-badge status-pending">Chờ duyệt</span></td>
                </tr>
                <tr>
                    <td><strong>#TL88104</strong></td>
                    <td>20/10/2026</td>
                    <td>Bộ sưu tập bút xóa được Mazzic</td>
                    <td style="color: #D32F2F; font-weight: bold;">150.000đ</td>
                    <td>MoMo</td>
                    <td><span class="status-badge status-shipping">Đang giao</span></td>
                </tr>
                <tr>
                    <td><strong>#TL87510</strong></td>
                    <td>10/10/2026</td>
                    <td>Bút dạ quang pastel, Bút chì 2B</td>
                    <td style="color: #D32F2F; font-weight: bold;">85.000đ</td>
                    <td>VNPAY</td>
                    <td><span class="status-badge status-completed">Đã hoàn thành</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
