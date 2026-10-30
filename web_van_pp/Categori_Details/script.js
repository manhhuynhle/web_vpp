document.addEventListener("DOMContentLoaded", function () {
  const params = new URLSearchParams(window.location.search);
  const productId = parseInt(params.get("id"), 10) || 1;

  // 1. Nhúng Header vào #header-placeholder trong index.html
  const headerPlaceholder = document.getElementById("header-placeholder");
  if (headerPlaceholder) {
    headerPlaceholder.innerHTML = `
            <header class="site-header">
                <nav class="navbar">
                    <a href="products.php" class="brand-logo">HỌC TỐT</a>
                    <button class="menu-toggle" id="menuToggle">☰</button>
                    <ul class="nav-links" id="navLinks">
                        <li><a href="products.php">Trang chủ</a></li>
                        <li><a href="products.php" class="active">Sản phẩm</a></li>
                        <li><a href="contact.php">Liên hệ</a></li>
                        <li>
                            <a href="cart.php">
                                Giỏ hàng <span class="cart-badge" id="cartBadge">0</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </header>
            <div id="toastContainer"></div>
        `;
  }

  // 2. Nhúng Footer & Nút lên đầu trang vào #footer-placeholder
  const footerPlaceholder = document.getElementById("footer-placeholder");
  if (footerPlaceholder) {
    footerPlaceholder.innerHTML = `
            <button id="backToTop" title="Lên đầu trang">↑</button>
            <footer class="site-footer">
                &copy; ${new Date().getFullYear()} Văn phòng phẩm Học Tốt - Hệ thống tra cứu và mua sắm học cụ.
            </footer>
        `;
  }

  // Menu di động & Nút lên đầu trang
  const menuToggle = document.getElementById("menuToggle");
  const navLinks = document.getElementById("navLinks");
  if (menuToggle && navLinks) {
    menuToggle.addEventListener("click", () =>
      navLinks.classList.toggle("show"),
    );
  }

  const backToTopBtn = document.getElementById("backToTop");
  if (backToTopBtn) {
    window.addEventListener("scroll", () => {
      backToTopBtn.style.display = window.scrollY > 200 ? "flex" : "none";
    });
    backToTopBtn.addEventListener("click", () =>
      window.scrollTo({ top: 0, behavior: "smooth" }),
    );
  }

  function showToast(message, type = "info") {
    const container = document.getElementById("toastContainer");
    if (!container) return;
    const toast = document.createElement("div");
    toast.className = `toast-msg toast-${type}`;
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
  }

  // 3. Gọi fetch() lấy chi tiết sản phẩm theo ID và cập nhật giao diện index.html
  function loadProductDetail(id) {
    fetch(`products.php?ajax=detail&id=${id}`)
      .then((res) => res.json())
      .then((data) => {
        if (!data.success || !data.product) {
          showToast("Không tìm thấy thông tin sản phẩm!", "error");
          return;
        }

        const p = data.product;
        const priceNum = Number(p.price);
        const oldPriceNum = Math.round(priceNum * 1.25); // Giá trước giảm (-25%)
        const imgUrl =
          p.image && p.image.trim() !== ""
            ? p.image
            : `https://placehold.co/500x400/DCE8F9/1565C0?text=${encodeURIComponent(p.name)}`;
        const author = p.author || "Thiên Long / Học Tốt";
        const publisher = p.publisher || "NXB Giáo Dục Việt Nam";
        const categoryName = p.category_name || "Sách & Văn phòng phẩm";

        // Cập nhật số lượng giỏ hàng trên Header
        const badge = document.getElementById("cartBadge");
        if (badge && typeof data.cart_count !== "undefined") {
          badge.textContent = data.cart_count;
        }

        // Cập nhật tiêu đề tab trình duyệt & Breadcrumb
        document.title = `${p.name} - Văn phòng phẩm Học Tốt`;
        const breadcrumb = document.querySelector(".breadcrumb");
        if (breadcrumb) {
          breadcrumb.innerHTML = `
                        <a href="products.php">Trang chủ</a>
                        <span>/</span>
                        <a href="products.php?category_id=${p.category_id || 0}">${categoryName}</a>
                        <span>/</span>
                        <span class="current">${p.name}</span>
                    `;
        }

        // Cập nhật Ảnh chính & Thumbnail trong index.html
        const mainImg = document.getElementById("main-product-img");
        if (mainImg) {
          mainImg.src = imgUrl;
          mainImg.alt = p.name;
        }
        const thumbList = document.querySelector(".thumbnail-list");
        if (thumbList) {
          thumbList.innerHTML = `
                        <img class="thumb active" src="${imgUrl}" data-full="${imgUrl}" alt="${p.name}">
                    `;
        }

        // Cập nhật Tên sản phẩm, Tác giả, Nhà xuất bản, Mã SP
        const titleEl = document.querySelector(
          ".product-details .product-title",
        );
        if (titleEl) titleEl.textContent = p.name;

        const metaEl = document.querySelector(".product-meta");
        if (metaEl) {
          metaEl.innerHTML = `
                        <span>Tác giả / Thương hiệu: <strong>${author}</strong></span>
                        <span class="divider">|</span>
                        <span>Nhà xuất bản: <strong>${publisher}</strong></span>
                        <span class="divider">|</span>
                        <span>Mã SP: <strong id="sku-code">SP-00${p.id}</strong></span>
                        <span class="divider">|</span>
                        <span>Tình trạng: <strong class="in-stock">Còn hàng</strong></span>
                    `;
        }

        // Cập nhật Giá bán
        const currentPriceEl = document.getElementById("current-price");
        const oldPriceEl = document.getElementById("old-price");
        if (currentPriceEl)
          currentPriceEl.textContent = priceNum.toLocaleString("vi-VN") + "₫";
        if (oldPriceEl)
          oldPriceEl.textContent = oldPriceNum.toLocaleString("vi-VN") + "₫";

        // Ẩn tùy chọn màu/gáy của Bìa còng nếu đang xem sản phẩm khác
        const optionGroups = document.querySelectorAll(".option-group");
        optionGroups.forEach((group) => {
          group.style.display = "none";
        });

        // Cập nhật Mô tả / Tóm tắt nội dung
        const infoSections = document.querySelectorAll(
          ".details-grid .info-section",
        );
        if (infoSections.length >= 1) {
          infoSections[0].innerHTML = `
                        <h2 class="section-heading">Tóm tắt nội dung & Mô tả sản phẩm</h2>
                        <p style="line-height: 1.7; font-size: 15px;">
                            ${p.description ? p.description.replace(/\n/g, "<br>") : "Sản phẩm chính hãng chất lượng cao tại hệ thống Văn phòng phẩm Học Tốt."}
                        </p>
                        <h3>Cam kết chất lượng:</h3>
                        <ul class="feature-list">
                            <li><strong>Chính hãng 100%:</strong> Sản phẩm được kiểm định nguồn gốc từ ${publisher}.</li>
                            <li><strong>Đổi trả dễ dàng:</strong> Hỗ trợ đổi mới trong 7 ngày nếu có lỗi từ nhà sản xuất.</li>
                            <li><strong>Giao hàng nhanh:</strong> Đóng gói cẩn thận, nhận hàng và kiểm tra trước khi thanh toán.</li>
                        </ul>
                    `;
        }

        // Cập nhật Bảng Thông tin chi tiết (.specs-table)
        const specsTableBody = document.querySelector(".specs-table tbody");
        if (specsTableBody) {
          specsTableBody.innerHTML = `
                        <tr><th>Mã sản phẩm</th><td>SP-00${p.id}</td></tr>
                        <tr><th>Tên sản phẩm</th><td>${p.name}</td></tr>
                        <tr><th>Danh mục</th><td>${categoryName}</td></tr>
                        <tr><th>Tác giả / Thương hiệu</th><td>${author}</td></tr>
                        <tr><th>Nhà xuất bản / Sản xuất</th><td>${publisher}</td></tr>
                        <tr><th>Giá bán</th><td><strong style="color:#1565C0;">${priceNum.toLocaleString("vi-VN")}₫</strong></td></tr>
                        <tr><th>Xuất xứ</th><td>Việt Nam</td></tr>
                    `;
        }

        // Hiển thị khu vực Bình luận / Đánh giá
        renderReviewsSection(p.id, data.reviews || []);
      })
      .catch(() => {
        showToast("Không thể tải dữ liệu chi tiết sản phẩm.", "error");
      });
  }

  // 4. Tạo & xử lý khu vực Bình luận / Đánh giá ngay bên dưới .details-grid
  function renderReviewsSection(prodId, reviews) {
    let reviewContainer = document.getElementById("dynamic-review-section");
    if (!reviewContainer) {
      reviewContainer = document.createElement("section");
      reviewContainer.id = "dynamic-review-section";
      reviewContainer.className = "product-card info-section";
      reviewContainer.style.marginTop = "24px";
      const mainEl = document.querySelector("main.page-content");
      if (mainEl) mainEl.appendChild(reviewContainer);
    }

    const reviewItemsHtml =
      reviews.length > 0
        ? reviews
            .map(
              (r) => `
                <div class="review-item">
                    <div class="review-header">
                        <strong>${r.reviewer_name}</strong>
                        <span class="star-rating">${"★".repeat(Number(r.rating))}${"☆".repeat(5 - Number(r.rating))}</span>
                    </div>
                    <p style="margin: 5px 0;">${r.comment}</p>
                    <small style="color: #6B7280;">${r.created_at}</small>
                </div>
            `,
            )
            .join("")
        : `<p style="color: #6B7280;">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên nhận xét!</p>`;

    reviewContainer.innerHTML = `
            <h2 class="section-heading">Đánh giá & Bình luận (${reviews.length})</h2>
            <div id="review-list" style="margin-bottom: 24px;">
                ${reviewItemsHtml}
            </div>
            <h3 style="color: #1565C0; margin-bottom: 12px;">Gửi đánh giá của bạn</h3>
            <form id="ajaxReviewForm" novalidate>
                <div class="form-group">
                    <label>Họ và tên (*)</label>
                    <input type="text" id="revName" class="form-control" placeholder="Nhập họ và tên của bạn">
                    <div class="error-msg" id="errRevName">Vui lòng nhập họ tên (tối thiểu 2 ký tự).</div>
                </div>
                <div class="form-group">
                    <label>Email (*)</label>
                    <input type="email" id="revEmail" class="form-control" placeholder="example@gmail.com">
                    <div class="error-msg" id="errRevEmail">Vui lòng nhập địa chỉ email hợp lệ.</div>
                </div>
                <div class="form-group">
                    <label>Số sao đánh giá (*)</label>
                    <select id="revRating" class="form-control">
                        <option value="5">★★★★★ - Rất hài lòng (5 sao)</option>
                        <option value="4">★★★★☆ - Hài lòng (4 sao)</option>
                        <option value="3">★★★☆☆ - Bình thường (3 sao)</option>
                        <option value="2">★★☆☆☆ - Chưa tốt (2 sao)</option>
                        <option value="1">★☆☆☆☆ - Không hài lòng (1 sao)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nội dung bình luận (*)</label>
                    <textarea id="revComment" rows="3" class="form-control" placeholder="Chia sẻ cảm nhận về sản phẩm..."></textarea>
                    <div class="error-msg" id="errRevComment">Nội dung nhận xét cần ít nhất 5 ký tự.</div>
                </div>
                <button type="submit" class="btn btn-blue">Gửi đánh giá</button>
            </form>
        `;

    // Kiểm tra biểu mẫu đánh giá bằng JavaScript trước khi gửi
    const form = document.getElementById("ajaxReviewForm");
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      let valid = true;
      const nameEl = document.getElementById("revName");
      const emailEl = document.getElementById("revEmail");
      const ratingEl = document.getElementById("revRating");
      const commentEl = document.getElementById("revComment");
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (nameEl.value.trim().length < 2) {
        document.getElementById("errRevName").style.display = "block";
        nameEl.classList.add("input-error");
        valid = false;
      } else {
        document.getElementById("errRevName").style.display = "none";
        nameEl.classList.remove("input-error");
      }

      if (!emailRegex.test(emailEl.value.trim())) {
        document.getElementById("errRevEmail").style.display = "block";
        emailEl.classList.add("input-error");
        valid = false;
      } else {
        document.getElementById("errRevEmail").style.display = "none";
        emailEl.classList.remove("input-error");
      }

      if (commentEl.value.trim().length < 5) {
        document.getElementById("errRevComment").style.display = "block";
        commentEl.classList.add("input-error");
        valid = false;
      } else {
        document.getElementById("errRevComment").style.display = "none";
        commentEl.classList.remove("input-error");
      }

      if (!valid) {
        showToast("Vui lòng kiểm tra lại thông tin đánh giá!", "error");
        return;
      }

      fetch("products.php?ajax=add_review", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          product_id: prodId,
          reviewer_name: nameEl.value.trim(),
          reviewer_email: emailEl.value.trim(),
          rating: ratingEl.value,
          comment: commentEl.value.trim(),
        }),
      })
        .then((res) => res.json())
        .then((res) => {
          if (res.success) {
            showToast(res.message, "success");
            loadProductDetail(prodId); // Nạp lại bình luận mới ngay lập tức
          } else {
            showToast(res.message, "error");
          }
        });
    });
  }

  // 5. Xử lý nút Tăng / Giảm số lượng và Thêm vào giỏ hàng trên index.html
  const qtyInput = document.getElementById("qty-input");
  const qtyMinus = document.getElementById("qty-minus");
  const qtyPlus = document.getElementById("qty-plus");

  if (qtyMinus && qtyInput) {
    qtyMinus.addEventListener("click", () => {
      const val = parseInt(qtyInput.value, 10) || 1;
      if (val > 1) qtyInput.value = val - 1;
    });
  }
  if (qtyPlus && qtyInput) {
    qtyPlus.addEventListener("click", () => {
      const val = parseInt(qtyInput.value, 10) || 1;
      if (val < 99) qtyInput.value = val + 1;
    });
  }

  const addToCartBtn = document.getElementById("add-to-cart-btn");
  if (addToCartBtn) {
    addToCartBtn.addEventListener("click", function () {
      const qty = parseInt(qtyInput ? qtyInput.value : 1, 10);
      if (isNaN(qty) || qty < 1) {
        showToast("Số lượng sản phẩm phải lớn hơn 0!", "error");
        return;
      }

      fetch("products.php?ajax=add_to_cart", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_id: productId, qty: qty }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            showToast(data.message, "success");
            const badge = document.getElementById("cartBadge");
            if (badge) badge.textContent = data.cart_count;
          } else {
            showToast(data.message || "Lỗi khi thêm vào giỏ", "error");
          }
        });
    });
  }

  // Nút Mua ngay: thêm vào giỏ và chuyển thẳng tới cart.php
  const buyNowBtn = document.querySelector(".btn-buy-now");
  if (buyNowBtn) {
    buyNowBtn.removeAttribute("onclick");
    buyNowBtn.addEventListener("click", function () {
      const qty = parseInt(qtyInput ? qtyInput.value : 1, 10) || 1;
      fetch("products.php?ajax=add_to_cart", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_id: productId, qty: qty }),
      }).then(() => {
        window.location.href = "cart.php";
      });
    });
  }

  // Khởi chạy nạp dữ liệu chi tiết sản phẩm
  loadProductDetail(productId);
});
