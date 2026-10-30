const STORAGE_KEY = "next_store_banners";
const MAX_IMAGE_SIZE = 1 * 1024 * 1024; // Tối đa 1 MB

const form = document.getElementById("banner-form");
const nameInput = document.getElementById("banner-name");
const imageInput = document.getElementById("banner-image");
const preview = document.getElementById("image-preview");
const bannerList = document.getElementById("banner-list");

// Đọc danh sách banner đã lưu
function getBanners() {
  try {
    const data = JSON.parse(localStorage.getItem(STORAGE_KEY) || "[]");

    return Array.isArray(data) ? data : [];
  } catch (error) {
    return [];
  }
}

// Lưu danh sách banner
function saveBanners(banners) {
  try {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(banners));
    return true;
  } catch (error) {
    alert("Không đủ dung lượng lưu ảnh. Hãy dùng ảnh nhỏ hơn.");
    return false;
  }
}

// Xem trước ảnh khi người dùng chọn file
imageInput.addEventListener("change", function () {
  const file = imageInput.files[0];

  if (!file) {
    preview.hidden = true;
    preview.removeAttribute("src");
    return;
  }

  if (!["image/jpeg", "image/png", "image/webp"].includes(file.type)) {
    alert("Chỉ chấp nhận ảnh JPG, PNG hoặc WebP.");
    imageInput.value = "";
    preview.hidden = true;
    return;
  }

  if (file.size > MAX_IMAGE_SIZE) {
    alert("Ảnh tối đa 1 MB. Hãy chọn ảnh nhỏ hơn.");
    imageInput.value = "";
    preview.hidden = true;
    return;
  }

  const reader = new FileReader();

  reader.onload = function () {
    preview.src = reader.result;
    preview.hidden = false;
  };

  reader.readAsDataURL(file);
});

// Thêm banner mới
form.addEventListener("submit", function (event) {
  event.preventDefault();

  const name = nameInput.value.trim();
  const file = imageInput.files[0];

  if (!name || !file) {
    alert("Vui lòng nhập tên và chọn ảnh banner.");
    return;
  }

  if (
    !["image/jpeg", "image/png", "image/webp"].includes(file.type) ||
    file.size > MAX_IMAGE_SIZE
  ) {
    alert("Ảnh không hợp lệ hoặc lớn hơn 1 MB.");
    return;
  }

  const reader = new FileReader();

  reader.onload = function () {
    const banners = getBanners();

    const newBanner = {
      id: Date.now().toString(),
      name: name,
      image: reader.result,
      active: true,
    };

    banners.push(newBanner);

    if (saveBanners(banners)) {
      form.reset();
      preview.hidden = true;
      preview.removeAttribute("src");
      renderBanners();
      alert("Đã thêm banner thành công!");
    }
  };

  reader.onerror = function () {
    alert("Không thể đọc ảnh. Vui lòng thử lại.");
  };

  reader.readAsDataURL(file);
});

// Hiển thị danh sách banner
function renderBanners() {
  const banners = getBanners();

  bannerList.replaceChildren();

  if (banners.length === 0) {
    bannerList.textContent = "Chưa có banner nào.";
    return;
  }

  banners.forEach(function (banner) {
    const item = document.createElement("div");
    item.className = "banner-item";

    const image = document.createElement("img");
    image.src = banner.image;
    image.alt = banner.name;

    const title = document.createElement("h3");
    title.textContent = banner.name;

    const status = document.createElement("p");
    status.className = "banner-status";
    status.textContent = banner.active
      ? "Trạng thái: Đang bật"
      : "Trạng thái: Đang tắt";

    const toggleButton = document.createElement("button");
    toggleButton.type = "button";
    toggleButton.textContent = banner.active ? "Tắt banner" : "Bật banner";

    toggleButton.addEventListener("click", function () {
      const updated = getBanners().map(function (item) {
        if (item.id === banner.id) {
          return { ...item, active: !item.active };
        }
        return item;
      });

      if (saveBanners(updated)) {
        renderBanners();
      }
    });

    const deleteButton = document.createElement("button");
    deleteButton.type = "button";
    deleteButton.textContent = "Xóa banner";

    deleteButton.addEventListener("click", function () {
      if (!confirm("Bạn có chắc muốn xóa banner này?")) {
        return;
      }

      const updated = getBanners().filter(function (item) {
        return item.id !== banner.id;
      });

      if (saveBanners(updated)) {
        renderBanners();
      }
    });

    item.append(image, title, status, toggleButton, deleteButton);
    bannerList.appendChild(item);
  });
}

// Hiển thị dữ liệu đã lưu khi mở trang quản trị
renderBanners();
