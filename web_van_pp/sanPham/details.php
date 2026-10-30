<?php include __DIR__ . '/../includes/header.php'; ?>
<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      Bìa còng F4 gáy 50/70/90 Thiên Long Flexoffice – Mặt ngoài Simili, Mặt
      trong lót giấy
    </title>
    <!-- Font Awesome cho icon -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <!-- CSS chính -->

  </head>
  <body>
    <!-- Nơi nhúng Header bằng JS -->
    <div id="header-placeholder"></div>

    <main class="container page-content">
     
      <section class="product-card">
        <!-- Cột trái: Thư viện ảnh -->
        <div class="product-gallery">
          <div class="main-image-box">
            <span class="discount-badge">-25%</span>
            <img
              id="main-product-img"
              src="https://cdn.hstatic.net/products/1000230347/bia_cong_50f4_fo-bc13__1ms__xanh_b9d404d90a4f46a19d7889c831910558_master.jpg"
              alt="Bìa còng F4 Thiên Long"
            />
          </div>
          <div class="thumbnail-list">
            <img
              class="thumb active"
              src="https://cdn.hstatic.net/products/1000230347/bia_cong_50f4_fo-bc13__1ms__xanh_b9d404d90a4f46a19d7889c831910558_compact.jpg"
              data-full="https://cdn.hstatic.net/products/1000230347/bia_cong_50f4_fo-bc13__1ms__xanh_b9d404d90a4f46a19d7889c831910558_master.jpg"
              alt="Xanh Gáy 50"
            />
            <img
              class="thumb"
              src="https://cdn.hstatic.net/products/1000230347/bia_cong_50f4_fo-bc13__1ms__xanh_dam_6571d95098354395bcc088ca755a769a_compact.jpg"
              data-full="https://cdn.hstatic.net/products/1000230347/bia_cong_50f4_fo-bc13__1ms__xanh_dam_6571d95098354395bcc088ca755a769a_master.jpg"
              alt="Xanh đậm Gáy 50"
            />
            <img
              class="thumb"
              src="https://cdn.hstatic.net/products/1000230347/usp__4__4c88fc21ea4041399fb3251e715c26eb_compact.jpg"
              data-full="https://cdn.hstatic.net/products/1000230347/usp__4__4c88fc21ea4041399fb3251e715c26eb_master.jpg"
              alt="Đặc điểm 1"
            />
            <img
              class="thumb"
              src="https://cdn.hstatic.net/products/1000230347/usp__5__fb06c22bf1db49ed892a2452c0bdab0f_compact.jpg"
              data-full="https://cdn.hstatic.net/products/1000230347/usp__5__fb06c22bf1db49ed892a2452c0bdab0f_master.jpg"
              alt="Đặc điểm 2"
            />
            <img
              class="thumb"
              src="https://cdn.hstatic.net/products/1000230347/usp__6__89e65eadda264cd1afbb563ca915b23f_compact.jpg"
              data-full="https://cdn.hstatic.net/products/1000230347/usp__6__89e65eadda264cd1afbb563ca915b23f_master.jpg"
              alt="Đặc điểm 3"
            />
          </div>
        </div>

        <!-- Cột phải: Thông tin & Tùy chọn -->
        <div class="product-details">
          <h1 class="product-title">
            Bìa còng F4 gáy 50/70/90 Thiên Long Flexoffice – Mặt ngoài Simili,
            Mặt trong lót giấy
          </h1>

          <div class="product-meta">
            <span>Thương hiệu: <strong>Flexoffice (Thiên Long)</strong></span>
            <span class="divider">|</span>
            <span>Mã SP: <strong id="sku-code">63011531</strong></span>
            <span class="divider">|</span>
            <span>Tình trạng: <strong class="in-stock">Còn hàng</strong></span>
          </div>

          <div class="price-box">
            <span class="current-price" id="current-price">54,000₫</span>
            <span class="old-price" id="old-price">72,000₫</span>
            <span class="discount-tag">-25%</span>
          </div>

          <!-- Chọn Màu Sắc -->
          <div class="option-group">
            <label class="option-label"
              >Màu sắc: <strong id="selected-color-text">Xanh</strong></label
            >
            <div class="option-buttons" id="color-options">
              <button
                type="button"
                class="opt-btn active"
                data-color="Xanh"
                data-img="https://cdn.hstatic.net/products/1000230347/bia_cong_50f4_fo-bc13__1ms__xanh_b9d404d90a4f46a19d7889c831910558_master.jpg"
              >
                <span class="color-dot blue"></span> Xanh
              </button>
              <button
                type="button"
                class="opt-btn"
                data-color="Xanh đậm"
                data-img="https://cdn.hstatic.net/products/1000230347/bia_cong_50f4_fo-bc13__1ms__xanh_dam_6571d95098354395bcc088ca755a769a_master.jpg"
              >
                <span class="color-dot dark-blue"></span> Xanh đậm
              </button>
            </div>
          </div>

          <!-- Chọn Kích thước Gáy -->
          <div class="option-group">
            <label class="option-label"
              >Kích thước gáy:
              <strong id="selected-size-text">Gáy 50 FO-BC13</strong></label
            >
            <div class="option-buttons" id="size-options">
              <button
                type="button"
                class="opt-btn active"
                data-size="Gáy 50 FO-BC13"
                data-price="54,000₫"
                data-old="72,000₫"
              >
                Gáy 50 FO-BC13
              </button>
              <button
                type="button"
                class="opt-btn"
                data-size="Gáy 70 FO-BC14"
                data-price="54,000₫"
                data-old="72,000₫"
              >
                Gáy 70 FO-BC14
              </button>
              <button
                type="button"
                class="opt-btn"
                data-size="Gáy 70 FO-BC016"
                data-price="55,500₫"
                data-old="74,000₫"
              >
                Gáy 70 FO-BC016
              </button>
              <button
                type="button"
                class="opt-btn"
                data-size="Gáy 90 FO-BC018"
                data-price="85,500₫"
                data-old="114,000₫"
              >
                Gáy 90 FO-BC018
              </button>
            </div>
          </div>

          <!-- Khuyến mãi / Quà tặng kèm -->
          <div class="promo-box">
            <div class="promo-header">
              <i class="fa-solid fa-gift"></i> Các sản phẩm được tặng kèm (Chọn
              1 phần quà)
            </div>
            <div class="promo-body">
              <label class="promo-item">
                <input type="radio" name="gift" checked />
                <span
                  >Nhận ngay
                  <strong
                    >02 Bút gel B - Buttersmooth Gel Thiên Long GELB-032</strong
                  >
                  (Mực Xanh - Phiên bản Cà phê) khi đơn hàng đạt 200,000₫.</span
                >
              </label>
              <label class="promo-item">
                <input type="radio" name="gift" />
                <span
                  >Nhận ngay
                  <strong>05 Bút lông bảng Flexoffice FO-WB02/XK</strong> khi
                  đơn hàng đạt 600,000₫.</span
                >
              </label>
            </div>
          </div>

          <!-- Số lượng & Nút Mua -->
          <div class="purchase-actions">
            <div class="quantity-control">
              <button type="button" id="qty-minus">-</button>
              <input type="number" id="qty-input" value="1" min="1" max="99" />
              <button type="button" id="qty-plus">+</button>
            </div>

            <button type="button" class="btn-add-cart" id="add-to-cart-btn">
              <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ
            </button>
            <button
              type="button"
              class="btn-buy-now"
              onclick="alert('Chuyển hướng đến trang Thanh toán!')"
            >
              Mua ngay
            </button>
          </div>
        </div>
      </section>

      <!-- Khu vực Mô tả & Thông số kỹ thuật (Thẻ #FFFFFF) -->
      <div class="details-grid">
        <!-- Mô tả sản phẩm -->
        <section class="product-card info-section">
          <h2 class="section-heading">Mô tả sản phẩm</h2>
          <p>
            <strong>Bìa còng F4 gáy 50/70/90 Thiên Long Flexoffice</strong> có
            mặt ngoài bọc lớp simili bền đẹp, chống thấm nước nhẹ, dễ dàng lau
            chùi vệ sinh và mặt trong lót giấy cao cấp giúp tăng độ cứng cáp,
            hạn chế bong tróc và mang lại cảm giác sử dụng chuyên nghiệp cho văn
            phòng.
          </p>

          <h3>Đặc điểm nổi bật:</h3>
          <ul class="feature-list">
            <li>
              <strong>Khổ F4 thông dụng:</strong> Phù hợp lưu trữ thoải mái các
              loại hồ sơ, giấy tờ A4/F4, bìa lỗ và chứng từ kế toán mà không bị
              nhăn mép.
            </li>
            <li>
              <strong>Thiết kế dạng đứng vững chãi:</strong> Tiện lợi khi sắp
              xếp thành hàng đồng bộ trên kệ tủ văn phòng, lỗ xỏ ngón trên gáy
              giúp rút bìa dễ dàng.
            </li>
            <li>
              <strong>Còng kim loại chắc chắn:</strong> Cơ chế khóa còng bật
              nhạy, phủ lớp chống gỉ sét, giữ chặt xấp tài liệu dày tới 500 -
              700 tờ giấy.
            </li>
            <li>
              <strong>Đa dạng độ rộng gáy:</strong> Gáy 50mm, 70mm và 90mm đáp
              ứng linh hoạt từng nhu cầu lưu trữ ít hay nhiều của doanh nghiệp,
              sinh viên.
            </li>
          </ul>
        </section>

        <!-- Bảng thông số kỹ thuật -->
        <section class="product-card info-section">
          <h2 class="section-heading">Thông tin chi tiết</h2>
          <table class="specs-table">
            <tbody>
              <tr>
                <th>Thương hiệu</th>
                <td>Flexoffice (Tập đoàn Thiên Long)</td>
              </tr>
              <tr>
                <th>Khổ bìa</th>
                <td>F4 (Dạng đứng)</td>
              </tr>
              <tr>
                <th>Độ rộng gáy</th>
                <td>
                  50mm (FO-BC13) / 70mm (FO-BC14, BC016) / 90mm (FO-BC018)
                </td>
              </tr>
              <tr>
                <th>Chất liệu mặt ngoài</th>
                <td>Bọc màng Simili (PP/PVC cao cấp)</td>
              </tr>
              <tr>
                <th>Chất liệu mặt trong</th>
                <td>Lót giấy ép phẳng</td>
              </tr>
              <tr>
                <th>Cơ chế kẹp</th>
                <td>Còng kim loại mạ sáng bóng, có thanh chặn giấy</td>
              </tr>
              <tr>
                <th>Xuất xứ thương hiệu</th>
                <td>Việt Nam</td>
              </tr>
              <tr>
                <th>Khuyến cáo</th>
                <td>
                  Bảo quản nơi khô ráo, tránh xa nguồn nhiệt cao và hóa chất
                </td>
              </tr>
            </tbody>
          </table>
        </section>
      </div>
    </main>

    <!-- Nơi nhúng Footer bằng JS -->
    <div id="footer-placeholder"></div>

    <!-- File Javascript xử lý nhúng và tương tác -->
    <script src="script.js"></script>
  </body>
</html>

<?php include __DIR__ . '/../includes/footer.php'; ?>