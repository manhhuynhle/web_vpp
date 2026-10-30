// Xử lý đóng và mở menu trên điện thoại
const nutMenu = document.getElementById("menu-toggle");
const danhSachMenu = document.getElementById("nav-list");
if (nutMenu && danhSachMenu) {
  nutMenu.addEventListener("click", function () {
    const dangMo = danhSachMenu.classList.toggle("active");
    nutMenu.setAttribute("aria-expanded", dangMo);
  });
  document.addEventListener("DOMContentLoaded", function () {
    const menuLinks = document.querySelectorAll(".nav-list a");

    // Lấy đường dẫn của trang đang mở
    const currentPath = window.location.pathname;

    menuLinks.forEach(function (link) {
      const linkUrl = new URL(link.href, window.location.origin);
      const linkPath = linkUrl.pathname;

      // So sánh đường dẫn trang hiện tại với đường dẫn của menu
      if (linkPath === currentPath) {
        link.classList.add("active");
        link.setAttribute("aria-current", "page");
      } else {
        link.classList.remove("active");
        link.removeAttribute("aria-current");
      }
    });
  });
}
// Mở/đóng danh sách danh mục sản phẩm
document.addEventListener("DOMContentLoaded", function () {
  const button = document.getElementById("category-toggle");
  const dropdown = document.getElementById("category-dropdown");

  if (!button || !dropdown) return;

  button.addEventListener("click", function (event) {
    event.stopPropagation();

    const isOpening = dropdown.hidden;
    dropdown.hidden = !isOpening;

    button.setAttribute("aria-expanded", String(isOpening));
  });

  // Đóng danh mục khi nhấn bên ngoài
  document.addEventListener("click", function (event) {
    if (!event.target.closest(".category-menu")) {
      dropdown.hidden = true;
      button.setAttribute("aria-expanded", "false");
    }
  });

  // Đóng danh mục khi nhấn phím Escape
  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      dropdown.hidden = true;
      button.setAttribute("aria-expanded", "false");
    }
  });
});
