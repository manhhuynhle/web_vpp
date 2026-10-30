
<?php include __DIR__ . '/../includes/header.php'; ?>

<section class="admin-products">
    <div class="admin-heading">
        <div>
            <h1>Quản lý sản phẩm</h1>
            <p>Quản lý danh sách văn phòng phẩm Học Tốt</p>
        </div>

        <button type="button" id="btn-add-product">
            + Thêm sản phẩm
        </button>
    </div>

    <!-- Thống kê -->
    <div class="product-stats">
        <div class="stat-card">
            <span>Tổng sản phẩm</span>
            <strong id="total-products">0</strong>
        </div>

        <div class="stat-card">
            <span>Còn hàng</span>
            <strong id="available-products">0</strong>
        </div>

        <div class="stat-card">
            <span>Hết hàng</span>
            <strong id="out-products">0</strong>
        </div>
    </div>

    <!-- Tìm kiếm và lọc -->
    <div class="product-toolbar">
        <input
            type="search"
            id="search-product"
            placeholder="Tìm theo mã hoặc tên sản phẩm..."
        >

        <select id="filter-category">
            <option value="">Tất cả danh mục</option>
            <option value="but-viet">Bút viết</option>
            <option value="tap-vo">Tập vở</option>
            <option value="dung-cu-hoc-tap">Dụng cụ học tập</option>
            <option value="van-phong-pham">Văn phòng phẩm</option>
            <option value="my-thuat">Mỹ thuật</option>
            <option value="giay-in">Giấy in</option>
        </select>

        <select id="filter-stock">
            <option value="">Tất cả trạng thái</option>
            <option value="available">Còn hàng</option>
            <option value="out">Hết hàng</option>
        </select>
    </div>

    <!-- Bảng danh sách -->
    <div class="product-table-wrapper">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Mã SP</th>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá bán</th>
                    <th>Số lượng</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>

            <tbody id="product-table-body"></tbody>
        </table>
    </div>
</section>

<!-- Hộp thoại thêm / sửa sản phẩm -->
<div class="product-modal" id="product-modal" hidden>
    <div class="product-modal-content">
        <h2 id="modal-title">Thêm sản phẩm</h2>

        <form id="product-form">
            <label for="product-id">Mã sản phẩm</label>
            <input type="text" id="product-id" required>

            <label for="product-name">Tên sản phẩm</label>
            <input type="text" id="product-name" required>

            <label for="product-category">Danh mục</label>
            <select id="product-category" required>
                <option value="but-viet">Bút viết</option>
                <option value="tap-vo">Tập vở</option>
                <option value="dung-cu-hoc-tap">Dụng cụ học tập</option>
                <option value="van-phong-pham">Văn phòng phẩm</option>
                <option value="my-thuat">Mỹ thuật</option>
                <option value="giay-in">Giấy in</option>
            </select>

            <label for="product-price">Giá bán (VNĐ)</label>
            <input
                type="number"
                id="product-price"
                min="0"
                step="100"
                required
            >

            <label for="product-quantity">Số lượng</label>
            <input
                type="number"
                id="product-quantity"
                min="0"
                step="1"
                required
            >

            <label for="product-image">Đường dẫn ảnh</label>
            <input
                type="text"
                id="product-image"
                placeholder="assets/images/but-bi.jpg"
            >

            <div class="modal-actions">
                <button type="submit">Lưu sản phẩm</button>

                <button type="button" id="btn-cancel-product">
                    Hủy
                </button>
            </div>
        </form>
    </div>
</div>

<script src="../assets/js/admin-products.js"></script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
