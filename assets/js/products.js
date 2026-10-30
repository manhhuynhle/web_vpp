const productSearch = document.getElementById("product-search");
const categoryFilter = document.getElementById("category-filter");
const productList = document.getElementById("product-list");
const productCount = document.getElementById("product-count");
const noProducts = document.getElementById("no-products");

// Đọc danh mục được truyền từ trang chủ qua URL
const params = new URLSearchParams(window.location.search);
const categoryFromUrl = params.get("category");

if (
  categoryFromUrl &&
  Array.from(categoryFilter.options).some(
    (option) => option.value === categoryFromUrl,
  )
) {
  categoryFilter.value = categoryFromUrl;
}

function filterProducts() {
  const keyword = productSearch.value.trim().toLowerCase();
  const selectedCategory = categoryFilter.value;

  const cards = productList.querySelectorAll(".product-card");
  let visibleCount = 0;

  cards.forEach(function (card) {
    const name = card.dataset.name.toLowerCase();
    const category = card.dataset.category;

    const matchesName = name.includes(keyword);
    const matchesCategory =
      selectedCategory === "" || category === selectedCategory;

    const isVisible = matchesName && matchesCategory;

    card.hidden = !isVisible;

    if (isVisible) {
      visibleCount++;
    }
  });

  productCount.textContent = "Tìm thấy " + visibleCount + " sản phẩm";

  noProducts.hidden = visibleCount !== 0;
}

productSearch.addEventListener("input", filterProducts);
categoryFilter.addEventListener("change", filterProducts);

// Hiển thị số sản phẩm khi mở trang
filterProducts();

// Nút xem chi tiết hiện mới là giao diện mẫu
document.querySelectorAll(".product-detail-btn").forEach(function (button) {
  button.addEventListener("click", function () {
    const card = button.closest(".product-card");
    const name = card.dataset.name;

    alert("Bạn đã chọn sản phẩm: " + name);
  });
});
