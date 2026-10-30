<?php include __DIR__ . '/../includes/header.php'; ?>
<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng ký tài khoản</title>
   <link rel="stylesheet" href="/web_van_pp/assets/css/taiKhoan.css" />

    <!-- Font chữ -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="/web_van_pp/assets/css/taiKhoan.css" />
  </head>
  <body>
    <div class="background-shapes">
      <span class="shape shape-1"></span>
      <span class="shape shape-2"></span>
      <span class="shape shape-3"></span>
      <span class="shape shape-4"></span>
      <span class="shape shape-5"></span>
    </div>

    <main class="register-container">
      <form class="register-form" action="#" method="post">
        <h1>ĐĂNG KÝ</h1>

        <p class="customer-title">Bạn là khách hàng:</p>

        <div class="input-grid">
          <div class="form-group">
            <label for="ho">Họ <span>(*)</span>:</label>
            <input type="text" id="ho" name="ho" placeholder="Họ" required />
          </div>

          <div class="form-group">
            <label for="ten">Tên <span>(*)</span>:</label>
            <input type="text" id="ten" name="ten" placeholder="Tên" required />
          </div>

          <div class="form-group">
            <label for="email">Email cá nhân <span>(*)</span>:</label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="Email cá nhân"
              required
            />
          </div>

          <div class="form-group">
            <label for="sdt">Số điện thoại <span>(*)</span>:</label>
            <input
              type="tel"
              id="sdt"
              name="sdt"
              placeholder="Số điện thoại"
              required
            />
          </div>

          <div class="form-group full-width">
            <label for="matkhau">Mật khẩu <span>(*)</span>:</label>
            <input
              type="password"
              id="matkhau"
              name="matkhau"
              placeholder="Mật khẩu"
              required
              minlength="6"
            />
          </div>
        </div>

        <button type="submit" class="register-button">Đăng ký</button>

        <p class="login-text">
          Bạn đã có tài khoản
          <a href="/web_van_pp/taiKhoan/DangNhap.php">Đăng nhập tại đây</a>
        </p>
      </form>
    </main>

    <script>
      // Chọn loại khách hàng
      const options = document.querySelectorAll(".customer-option");

      options.forEach((option) => {
        option.addEventListener("click", () => {
          options.forEach((item) => item.classList.remove("active"));
          option.classList.add("active");
        });
      });

      // Kiểm tra dữ liệu biểu mẫu
      document
        .querySelector(".register-form")
        .addEventListener("submit", function (event) {
          event.preventDefault();

          const ho = document.getElementById("ho").value.trim();
          const ten = document.getElementById("ten").value.trim();
          const email = document.getElementById("email").value.trim();
          const sdt = document.getElementById("sdt").value.trim();
          const matkhau = document.getElementById("matkhau").value;

          if (!ho || !ten || !email || !sdt || !matkhau) {
            alert("Vui lòng điền đầy đủ thông tin!");
            return;
          }

          if (matkhau.length < 6) {
            alert("Mật khẩu phải có ít nhất 6 ký tự!");
            return;
          }

          alert("Thông tin hợp lệ! Bạn cần kết nối hệ thống để tạo tài khoản.");
        });
    </script>
  </body>
</html>
<?php include __DIR__ . '/../includes/footer.php'; ?>