<?php include __DIR__ . '/includes/header.php'; ?>

<section class="info-hero contact-hero">
    <span class="info-eyebrow">LIÊN HỆ</span>
    <h1>Học Tốt luôn sẵn sàng lắng nghe</h1>
    <p>Gửi câu hỏi hoặc góp ý để chúng tôi có thể cải thiện website tốt hơn.</p>
</section>

<section class="contact-layout">
    <div class="contact-details">
        <article class="contact-detail-card">
            <div class="contact-icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></div>
            <div>
                <h2>Địa chỉ</h2>
                <p>Cần Thơ, Việt Nam</p>
                <small>Địa chỉ giới thiệu của dự án website.</small>
            </div>
        </article>
        <article class="contact-detail-card">
            <div class="contact-icon"><i class="fa-solid fa-envelope" aria-hidden="true"></i></div>
            <div>
                <h2>Email</h2>
                <p><a href="mailto:hotro@hoctot.example">hotro@hoctot.example</a></p>
                <small>Địa chỉ minh họa — hãy thay bằng email thật trước khi triển khai.</small>
            </div>
        </article>
        <article class="contact-detail-card">
            <div class="contact-icon"><i class="fa-solid fa-clock" aria-hidden="true"></i></div>
            <div>
                <h2>Thời gian hỗ trợ</h2>
                <p>Thứ Hai – Thứ Sáu</p>
                <small>Giờ hành chính (thông tin dự kiến).</small>
            </div>
        </article>
    </div>

    <div class="contact-form-card">
        <h2>Gửi lời nhắn</h2>
        <p class="contact-form-intro">Điền thông tin bên dưới. Đây là biểu mẫu giao diện mẫu, chưa gửi dữ liệu đến máy chủ.</p>

        <form id="contact-form" class="contact-form">
            <div class="form-field">
                <label for="contact-name">Họ và tên <span>*</span></label>
                <input id="contact-name" name="name" type="text" autocomplete="name" required maxlength="100" placeholder="Nhập họ và tên">
            </div>
            <div class="form-field">
                <label for="contact-email">Email <span>*</span></label>
                <input id="contact-email" name="email" type="email" autocomplete="email" required maxlength="150" placeholder="name@example.com">
            </div>
            <div class="form-field">
                <label for="contact-subject">Chủ đề</label>
                <select id="contact-subject" name="subject">
                    <option value="Tư vấn sản phẩm">Tư vấn sản phẩm</option>
                    <option value="Góp ý website">Góp ý website</option>
                    <option value="Hỗ trợ tài khoản">Hỗ trợ tài khoản</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
            <div class="form-field">
                <label for="contact-message">Nội dung <span>*</span></label>
                <textarea id="contact-message" name="message" rows="5" required maxlength="2000" placeholder="Nhập nội dung cần hỗ trợ..."></textarea>
            </div>
            <button type="submit" class="btn-primary contact-submit">Gửi lời nhắn</button>
            <p id="contact-feedback" class="contact-feedback" role="status" aria-live="polite"></p>
        </form>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("contact-form");
    const feedback = document.getElementById("contact-feedback");

    if (!form || !feedback) return;

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        if (!form.reportValidity()) return;

        feedback.textContent = "Thông tin đã được kiểm tra ở giao diện mẫu. Biểu mẫu hiện chưa gửi hoặc lưu dữ liệu; cần kết nối máy chủ để nhận tin nhắn.";
        feedback.classList.add("is-visible");
        form.reset();
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
