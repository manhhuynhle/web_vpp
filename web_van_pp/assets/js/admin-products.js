const PRODUCT_STORAGE_KEY = "hoctot_products";

const tableBody = document.getElementById("product-table-body");
const modal = document.getElementById("product-modal");
const form = document.getElementById("product-form");

const fields = {
  id: document.getElementById("product-id"),
  name: document.getElementById("product-name"),
  category: document.getElementById("product-category"),
  price: document.getElementById("product-price"),
  quantity: document.getElementById("product-quantity"),
  image: document.getElementById("product-image"),
};

let editingId = null;

const categoryNames = {
  "but-viet": "Bút viết",
  "tap-vo": "Tập vở",
  "dung-cu-hoc-tap": "Dụng cụ học tập",
  "van-phong-pham": "Văn phòng phẩm",
  "my-thuat": "Mỹ thuật",
  "giay-in": "Giấy in",
};

// Đọc sản phẩm từ localStorage
function getProducts() {
  try {
    const data = JSON.parse(localStorage.getItem(PRODUCT_STORAGE_KEY) || "[]");

    return Array.isArray(data) ? data : [];
  } catch (error) {
    return [];
  }
}

// Lưu sản phẩm
function saveProducts(products) {
  try {
    localStorage.setItem(PRODUCT_STORAGE_KEY, JSON.stringify(products));

    return true;
  } catch (error) {
    alert("Không thể lưu dữ liệu sản phẩm vào trình duyệt.");
    return false;
  }
}

// Định dạng giá tiền
function formatPrice(price) {
  return Number(price).toLocaleString("vi-VN") + " đ";
}

// Cập nhật số liệu thống kê
function renderStats(products) {
  document.getElementById("total-products").textContent = products.length;

  document.getElementById("available-products").textContent = products.filter(
    (p) => Number(p.quantity) > 0,
  ).length;

  document.getElementById("out-products").textContent = products.filter(
    (p) => Number(p.quantity) === 0,
  ).length;
}

// Hiển thị danh sách sản phẩm
function renderProducts() {
  const products = getProducts();

  const keyword = document
    .getElementById("search-product")
    .value.trim()
    .toLowerCase();

  const category = document.getElementById("filter-category").value;
  const stock = document.getElementById("filter-stock").value;

  renderStats(products);
  tableBody.replaceChildren();

  const filtered = products.filter(function (product) {
    const matchKeyword =
      String(product.id).toLowerCase().includes(keyword) ||
      String(product.name).toLowerCase().includes(keyword);

    const matchCategory = !category || product.category === category;

    const matchStock =
      !stock ||
      (stock === "available" && Number(product.quantity) > 0) ||
      (stock === "out" && Number(product.quantity) === 0);

    return matchKeyword && matchCategory && matchStock;
  });

  if (filtered.length === 0) {
    const row = document.createElement("tr");
    const cell = document.createElement("td");

    cell.colSpan = 8;
    cell.textContent = "Không tìm thấy sản phẩm.";
    cell.style.textAlign = "center";

    row.appendChild(cell);
    tableBody.appendChild(row);
    return;
  }

  filtered.forEach(function (product) {
    const row = document.createElement("tr");

    function addCell(value) {
      const cell = document.createElement("td");
      cell.textContent = value;
      row.appendChild(cell);
      return cell;
    }

    addCell(product.id);

    const imageCell = document.createElement("td");
    const image = document.createElement("img");

    image.className = "product-thumb";
    image.alt = product.name;
    image.src = product.image || "../assets/images/banner.jpg";

    image.onerror = function () {
      image.onerror = null;
      image.src = "../assets/images/banner.jpg";
    };

    imageCell.appendChild(image);
    row.appendChild(imageCell);

    addCell(product.name);
    addCell(categoryNames[product.category] || product.category);
    addCell(formatPrice(product.price));
    addCell(String(product.quantity));

    const statusCell = document.createElement("td");
    const badge = document.createElement("span");

    badge.className =
      Number(product.quantity) > 0
        ? "stock-badge stock-available"
        : "stock-badge stock-out";

    badge.textContent = Number(product.quantity) > 0 ? "Còn hàng" : "Hết hàng";

    statusCell.appendChild(badge);
    row.appendChild(statusCell);

    const actionCell = document.createElement("td");

    const editButton = document.createElement("button");
    editButton.type = "button";
    editButton.className = "product-action product-edit";
    editButton.textContent = "Sửa";

    editButton.addEventListener("click", function () {
      openEditForm(product);
    });

    const deleteButton = document.createElement("button");
    deleteButton.type = "button";
    deleteButton.className = "product-action product-delete";
    deleteButton.textContent = "Xóa";

    deleteButton.addEventListener("click", function () {
      deleteProduct(product.id);
    });

    actionCell.append(editButton, deleteButton);
    row.appendChild(actionCell);

    tableBody.appendChild(row);
  });
}

// Mở form thêm mới
function openAddForm() {
  editingId = null;
  form.reset();

  fields.id.disabled = false;

  document.getElementById("modal-title").textContent = "Thêm sản phẩm";

  modal.hidden = false;
}

// Mở form chỉnh sửa
function openEditForm(product) {
  editingId = product.id;

  fields.id.value = product.id;
  fields.name.value = product.name;
  fields.category.value = product.category;
  fields.price.value = product.price;
  fields.quantity.value = product.quantity;
  fields.image.value = product.image || "";

  // Không cho sửa mã khi đang chỉnh sửa
  fields.id.disabled = true;

  document.getElementById("modal-title").textContent = "Chỉnh sửa sản phẩm";

  modal.hidden = false;
}

// Đóng form
function closeForm() {
  modal.hidden = true;
  form.reset();
  fields.id.disabled = false;
  editingId = null;
}

// Lưu sản phẩm mới hoặc cập nhật sản phẩm
form.addEventListener("submit", function (event) {
  event.preventDefault();

  const id = fields.id.value.trim();
  const name = fields.name.value.trim();
  const category = fields.category.value;
  const price = Number(fields.price.value);
  const quantity = Number(fields.quantity.value);
  const image = fields.image.value.trim();

  if (
    !id ||
    !name ||
    !Number.isFinite(price) ||
    !Number.isInteger(quantity) ||
    price < 0 ||
    quantity < 0
  ) {
    alert("Vui lòng kiểm tra lại thông tin sản phẩm.");
    return;
  }

  const products = getProducts();

  if (editingId === null) {
    const exists = products.some(function (product) {
      return String(product.id).toLowerCase() === id.toLowerCase();
    });

    if (exists) {
      alert("Mã sản phẩm đã tồn tại.");
      return;
    }

    products.push({
      id,
      name,
      category,
      price,
      quantity,
      image,
    });
  } else {
    const index = products.findIndex(function (product) {
      return product.id === editingId;
    });

    if (index === -1) {
      alert("Không tìm thấy sản phẩm cần sửa.");
      return;
    }

    products[index] = {
      ...products[index],
      name,
      category,
      price,
      quantity,
      image,
    };
  }

  if (saveProducts(products)) {
    closeForm();
    renderProducts();
    alert("Đã lưu sản phẩm.");
  }
});

// Xóa sản phẩm
function deleteProduct(id) {
  if (!confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
    return;
  }

  const products = getProducts().filter(function (product) {
    return product.id !== id;
  });

  if (saveProducts(products)) {
    renderProducts();
  }
}

// Gắn sự kiện
document
  .getElementById("btn-add-product")
  .addEventListener("click", openAddForm);

document
  .getElementById("btn-cancel-product")
  .addEventListener("click", closeForm);

document
  .getElementById("search-product")
  .addEventListener("input", renderProducts);

document
  .getElementById("filter-category")
  .addEventListener("change", renderProducts);

document
  .getElementById("filter-stock")
  .addEventListener("change", renderProducts);

// Hiển thị khi mở trang
renderProducts();
