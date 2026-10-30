<?php include __DIR__ . '/../includes/header.php'; ?>
<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="/web_van_pp/assets/css/taiKhoan.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="/web_van_pp/assets/css/taiKhoan.css" />
  </head>
  <body>
    <!-- Các hình trang trí nền -->
    <!-- <div class="background-shapes">
      <span class="shape shape-1"></span>
      <span class="shape shape-2"></span>
      <span class="shape shape-3"></span>
      <span class="shape shape-4"></span>
      <span class="shape shape-5"></span>
    </div> -->

    <main class="auth-container">
      <!-- ================= ĐĂNG NHẬP ================= -->
      <section class="auth-card login-card" id="loginCard">
        <h1>ĐĂNG NHẬP</h1>

        <form id="loginForm">
          <div class="form">
            <label for="loginEmail">Email: <span>(*)</span></label>
            <input
              type="email"
              id="loginEmail"
              placeholder="Nhập Email"
              required
            />
          </div>

          <div class="form">
            <label for="loginPassword">Mật khẩu: <span>(*)</span></label>
            <input
              type="password"
              id="loginPassword"
              placeholder="Nhập Mật khẩu"
              required
            />
          </div>

          <p class="forgot-password">
            Quên mật khẩu?
            <a href="#" id="forgotLink">Nhấn vào đây</a>
          </p>

          <button type="submit" class="submit-button">Đăng nhập</button>
        </form>

        <p class="switch-text">
          Bạn chưa có tài khoản
          <a href="/web_van_pp/taiKhoan/DangKy.php" id="showRegister">Đăng ký tại đây</a>
        </p>
      </section>
    </main>

    <script src="script.js"></script>
  </body>
</html>
<?php include __DIR__ . '/../includes/footer.php'; ?>