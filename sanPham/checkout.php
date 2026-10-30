<?php include __DIR__ . '/../includes/header.php'; ?>

<link rel="stylesheet" href="/web_van_pp/assets/css/cart_style.css">

<div class="container my-4" style="max-width: 1000px; margin: 0 auto; padding: 20px 15px;">
    <!-- Step Bar -->
    <div class="checkout-steps">
        <div class="step-item"><span class="step-number">1</span> Giỏ hàng</div>
        <div class="step-item active"><span class="step-number">2</span> Thông tin giao hàng</div>
        <div class="step-item"><span class="step-number">3</span> Hoàn tất đơn hàng</div>
    </div>

    <form action="order_success.php" method="POST" id="checkoutForm">
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <!-- Cột 1: Thông tin người nhận -->
            <div style="flex: 1.4; min-width: 300px;">
                <div class="tl-card">
                    <h3 style="font-size: 18px; margin-top: 0; margin-bottom: 15px;">Thông tin giao hàng</h3>
                    
                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 4px;">Họ tên người nhận *</label>
                        <input type="text" name="fullname" required style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 4px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 4px;">Số điện thoại *</label>
                        <input type="tel" name="phone" id="phone" required style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 4px; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 4px;">Địa chỉ chi tiết *</label>
                        <textarea name="address" rows="3" required placeholder="Số nhà, tên đường, phường/xã..." style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 4px; box-sizing: border-box;"></textarea>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 4px;">Ghi chú giao hàng</label>
                        <input type="text" name="note" placeholder="Ví dụ: Giao giờ hành chính" style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 4px; box-sizing: border-box;">
                    </div>
                </div>
            </div>

            <!-- Cột 2: Chọn Phương thức thanh toán -->
            <div style="flex: 1; min-width: 280px;">
                <div class="tl-card">
                    <h3 style="font-size: 18px; margin-top: 0; margin-bottom: 15px;">Phương thức thanh toán</h3>

                    <label class="payment-option selected">
                        <input type="radio" name="payment_method" value="COD" checked>
                        <div>
                            <strong>Thanh toán khi nhận hàng (COD)</strong>
                            <div style="font-size: 12px; color: #6B7280;">Trả tiền mặt cho shipper khi nhận hàng</div>
                        </div>
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="BANK">
                        <div>
                            <strong>Chuyển khoản ngân hàng</strong>
                            <div style="font-size: 12px; color: #6B7280;">Chuyển tiền trực tiếp vào tài khoản shop</div>
                        </div>
                    </label>

                    <div style="margin-top: 20px; border-top: 1px solid #E5E7EB; padding-top: 15px;">
                        <button type="submit" class="btn-tl-primary">Đặt hàng ngay (103.400đ)</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// highlight phương thức thanh toán
document.querySelectorAll('.payment-option input').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
        this.closest('.payment-option').classList.add('selected');
    });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
