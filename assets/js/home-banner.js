document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("home-banners");
    if (!container) {
        console.error("Không tìm thấy phần tử #home-banners.");
        return;
    }

    let banners = [];
    try {
        const saved = localStorage.getItem("next_store_banners");
        const parsed = JSON.parse(saved || "[]");
        banners = Array.isArray(parsed) ? parsed : [];
    } catch (error) {
        console.error("Không đọc được dữ liệu banner:", error);
    }

    const activeBanners = banners.filter(function (banner) {
        return banner && banner.active === true && typeof banner.image === "string";
    });

    container.replaceChildren();

    if (activeBanners.length === 0) {
        // Không hiện thông báo trống trên trang chủ để tránh làm xấu giao diện.
        container.hidden = true;
        return;
    }

    container.hidden = false;

    activeBanners.forEach(function (banner) {
        const img = document.createElement("img");
        img.src = banner.image;
        img.alt = banner.name || "Banner Học Tốt";
        img.className = "home-banner-image";
        container.appendChild(img);
    });
});
