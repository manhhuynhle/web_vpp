<?php
// products.php — dùng đúng cấu trúc database HOC_TOT / bảng san_pham
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tìm db.php ở các vị trí thường dùng trong dự án.
$dbCandidates = [
    __DIR__ . '/../includes/db.php',
    __DIR__ . '/includes/db.php',
    __DIR__ . '/db.php',
];
$dbLoaded = false;
foreach ($dbCandidates as $dbFile) {
    if (is_file($dbFile)) {
        require_once $dbFile;
        $dbLoaded = true;
        break;
    }
}
if (!$dbLoaded || !isset($conn) || !($conn instanceof PDO)) {
    http_response_code(500);
    exit('Không tìm thấy db.php hoặc kết nối PDO chưa được khởi tạo. Hãy kiểm tra đường dẫn db.php.');
}
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->exec("SET NAMES utf8mb4");

// Dữ liệu sản phẩm: đặt alias để tương thích với phần giao diện JavaScript cũ.
$productSelect = "SELECT
    sp.ma_san_pham AS id,
    sp.ten_san_pham AS name,
    sp.slug,
    sp.ma_danh_muc AS category_id,
    CONCAT('Danh mục ', COALESCE(sp.ma_danh_muc, 'chưa phân loại')) AS category_name,
    sp.thuong_hieu AS brand,
    sp.ma_san_xuat AS manufacturer_code,
    sp.mo_ta AS description,
    sp.gia_goc AS original_price,
    sp.gia_ban AS price,
    sp.so_luong_ton AS stock,
    sp.hinh_anh AS image,
    sp.trang_thai AS status,
    sp.noi_bat AS featured
    FROM san_pham sp";

try {
    if (isset($_GET['ajax'])) {
        header('Content-Type: application/json; charset=utf-8');
        $action = $_GET['ajax'];

        if ($action === 'search') {
            $kw = trim($_GET['keyword'] ?? '');
            $cat = (int)($_GET['category_id'] ?? 0);
            $sql = $productSelect . " WHERE sp.trang_thai = 1";
            $params = [];
            if ($kw !== '') {
                $sql .= " AND sp.ten_san_pham LIKE ?";
                $params[] = '%' . $kw . '%';
            }
            if ($cat > 0) {
                $sql .= " AND sp.ma_danh_muc = ?";
                $params[] = $cat;
            }
            $sql .= " ORDER BY sp.ma_san_pham DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'count' => count($rows), 'data' => $rows], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($action === 'detail') {
            $id = (int)($_GET['id'] ?? 0);
            $stmt = $conn->prepare($productSelect . " WHERE sp.ma_san_pham = ? AND sp.trang_thai = 1");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$product) {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm!'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $conn->exec("CREATE TABLE IF NOT EXISTS reviews (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                reviewer_name VARCHAR(100) NOT NULL,
                reviewer_email VARCHAR(150) NOT NULL,
                rating INT NOT NULL DEFAULT 5,
                comment TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $revStmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
            $revStmt->execute([$id]);
            $reviews = $revStmt->fetchAll(PDO::FETCH_ASSOC);
            $cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;
            echo json_encode([
                'success' => true,
                'product' => $product,
                'reviews' => $reviews,
                'cart_count' => $cartCount
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($action === 'add_to_cart' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true) ?: [];
            $productId = (int)($input['product_id'] ?? 0);
            $qty = (int)($input['qty'] ?? 1);
            if ($productId <= 0 || $qty <= 0) {
                echo json_encode(['success' => false, 'message' => 'Sản phẩm hoặc số lượng không hợp lệ!'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            $stmt = $conn->prepare("SELECT ma_san_pham AS id, ten_san_pham AS name, gia_ban AS price
                                    FROM san_pham WHERE ma_san_pham = ? AND trang_thai = 1");
            $stmt->execute([$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$product) {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm!'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['qty'] += $qty;
            } else {
                $_SESSION['cart'][$productId] = [
                    'name' => $product['name'],
                    'price' => (float)$product['price'],
                    'qty' => $qty
                ];
            }
            $totalItems = array_sum(array_column($_SESSION['cart'], 'qty'));
            echo json_encode([
                'success' => true,
                'message' => 'Đã thêm "' . $product['name'] . '" vào giỏ hàng!',
                'cart_count' => $totalItems
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($action === 'add_review' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true) ?: [];
            $productId = (int)($input['product_id'] ?? 0);
            $name = trim($input['reviewer_name'] ?? '');
            $email = trim($input['reviewer_email'] ?? '');
            $rating = max(1, min(5, (int)($input['rating'] ?? 5)));
            $comment = trim($input['comment'] ?? '');
            if ($productId <= 0 || $name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($comment) < 5) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin hợp lệ!'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            $conn->exec("CREATE TABLE IF NOT EXISTS reviews (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                reviewer_name VARCHAR(100) NOT NULL,
                reviewer_email VARCHAR(150) NOT NULL,
                rating INT NOT NULL DEFAULT 5,
                comment TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $stmt = $conn->prepare("INSERT INTO reviews (product_id, reviewer_name, reviewer_email, rating, comment) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$productId, $name, $email, $rating, $comment]);
            echo json_encode(['success' => true, 'message' => 'Đã gửi đánh giá thành công!'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Thao tác AJAX không hợp lệ!'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $keyword = trim($_GET['keyword'] ?? '');
    $category_id = (int)($_GET['category_id'] ?? 0);

    // File SQL hiện tại chưa có bảng danh_muc/categories, vì vậy lấy các mã danh mục từ san_pham.
    $catStmt = $conn->query("SELECT DISTINCT ma_danh_muc AS id,
        CONCAT('Danh mục ', ma_danh_muc) AS category_name
        FROM san_pham WHERE ma_danh_muc IS NOT NULL ORDER BY ma_danh_muc");
    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = $productSelect . " WHERE sp.trang_thai = 1";
    $params = [];
    if ($keyword !== '') {
        $sql .= " AND sp.ten_san_pham LIKE ?";
        $params[] = '%' . $keyword . '%';
    }
    if ($category_id > 0) {
        $sql .= " AND sp.ma_danh_muc = ?";
        $params[] = $category_id;
    }
    $sql .= " ORDER BY sp.ma_san_pham DESC";
    $prodStmt = $conn->prepare($sql);
    $prodStmt->execute($params);
    $initialProducts = $prodStmt->fetchAll(PDO::FETCH_ASSOC);
    $cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;
} catch (Throwable $e) {
    if (isset($_GET['ajax'])) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn database: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
        exit;
    }
    http_response_code(500);
    exit('Lỗi truy vấn database: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

// Chỉ nạp header sau khi xử lý AJAX để HTML không làm hỏng JSON.
$headerFile = __DIR__ . '/../includes/header.php';
if (is_file($headerFile)) {
    include $headerFile;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Văn phòng phẩm Học Tốt - Sản phẩm</title>
</head>
<body>
<div id="toastContainer"></div>
<main class="main-wrapper">
    <section class="hero-banner">
        <h1>Văn phòng phẩm Học Tốt</h1>
        <p>Đầy đủ dụng cụ học tập, sách tham khảo và văn phòng phẩm chất lượng</p>
    </section>

    <form id="searchForm" class="filter-bar" method="GET" action="products.php" novalidate>
        <input type="text" id="keywordInput" name="keyword" value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>" placeholder="Nhập tên sách hoặc văn phòng phẩm cần tìm...">
        <select id="categorySelect" name="category_id">
            <option value="0">-- Tất cả danh mục --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= (int)$cat['id'] ?>" <?= $category_id === (int)$cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['category_name'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-blue">Tìm kiếm</button>
        <button type="button" id="resetBtn" class="btn btn-outline">Đặt lại</button>
        <div id="searchError" class="error-msg" style="width:100%"></div>
    </form>

    <section id="productGrid" class="product-grid">
        <?php if (!empty($initialProducts)): ?>
            <?php foreach ($initialProducts as $item):
                $imgUrl = !empty($item['image'])
                    ? $item['image']
                    : 'https://placehold.co/400x300/DCE8F9/1565C0?text=' . urlencode($item['name']);
            ?>
            <article class="product-grid-card">
                <div>
                    <a href="index.html?id=<?= (int)$item['id'] ?>" class="product-thumb">
                        <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?>">
                    </a>
                    <div class="product-cat"><?= htmlspecialchars($item['category_name'], ENT_QUOTES, 'UTF-8') ?></div>
                    <a href="index.html?id=<?= (int)$item['id'] ?>" class="product-grid-title"><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?></a>
                </div>
                <div>
                    <div class="product-price"><?= number_format((float)$item['price'], 0, ',', '.') ?>đ</div>
                    <div class="product-actions">
                        <a href="index.html?id=<?= (int)$item['id'] ?>" class="btn btn-outline">Chi tiết</a>
                        <button type="button" class="btn btn-blue" onclick="addToCartAjax(<?= (int)$item['id'] ?>, 1)">+ Giỏ hàng</button>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">Không tìm thấy sản phẩm</div>
        <?php endif; ?>
    </section>
</main>
<button id="backToTop" title="Lên đầu trang">↑</button>
<script>
const menuToggle = document.getElementById('menuToggle');
const navLinks = document.getElementById('navLinks');
if (menuToggle && navLinks) menuToggle.addEventListener('click', () => navLinks.classList.toggle('show'));

const backToTopBtn = document.getElementById('backToTop');
if (backToTopBtn) {
    window.addEventListener('scroll', () => backToTopBtn.style.display = window.scrollY > 200 ? 'flex' : 'none');
    backToTopBtn.addEventListener('click', () => window.scrollTo({top: 0, behavior: 'smooth'}));
}
function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = `toast-msg toast-${type}`;
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
function addToCartAjax(productId, qty) {
    fetch('products.php?ajax=add_to_cart', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({product_id: productId, qty: qty})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            const badge = document.getElementById('cartBadge');
            if (badge) badge.textContent = data.cart_count;
        } else showToast(data.message || 'Lỗi khi thêm vào giỏ hàng', 'error');
    })
    .catch(() => showToast('Không thể kết nối máy chủ!', 'error'));
}
const searchForm = document.getElementById('searchForm');
const keywordInput = document.getElementById('keywordInput');
const categorySelect = document.getElementById('categorySelect');
const resetBtn = document.getElementById('resetBtn');
const searchError = document.getElementById('searchError');
const productGrid = document.getElementById('productGrid');
let debounceTimer = null;

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, ch => ({
        '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;'
    }[ch]));
}
function fetchProducts(notify = false) {
    const kw = keywordInput.value.trim();
    const cat = categorySelect.value;
    if (/[<>]/.test(kw)) {
        searchError.textContent = 'Từ khóa tìm kiếm không hợp lệ (không chứa < hoặc >).';
        searchError.style.display = 'block';
        keywordInput.classList.add('input-error');
        return;
    }
    searchError.style.display = 'none';
    keywordInput.classList.remove('input-error');
    const params = new URLSearchParams({keyword: kw, category_id: cat});
    window.history.replaceState({}, '', `products.php?${params.toString()}`);
    fetch(`products.php?ajax=search&${params.toString()}`)
        .then(res => res.json())
        .then(res => {
            if (!res.success) throw new Error(res.message || 'Không thể tải sản phẩm');
            if (!res.data || res.data.length === 0) {
                productGrid.innerHTML = '<div class="empty-state">Không tìm thấy sản phẩm</div>';
            } else {
                productGrid.innerHTML = res.data.map(item => {
                    const img = item.image && item.image.trim() !== ''
                        ? item.image
                        : `https://placehold.co/400x300/DCE8F9/1565C0?text=${encodeURIComponent(item.name)}`;
                    return `<article class="product-grid-card">
                        <div>
                            <a href="index.html?id=${Number(item.id)}" class="product-thumb"><img src="${escapeHtml(img)}" alt="${escapeHtml(item.name)}"></a>
                            <div class="product-cat">${escapeHtml(item.category_name || 'Văn phòng phẩm')}</div>
                            <a href="index.html?id=${Number(item.id)}" class="product-grid-title">${escapeHtml(item.name)}</a>
                        </div>
                        <div>
                            <div class="product-price">${Number(item.price).toLocaleString('vi-VN')}đ</div>
                            <div class="product-actions">
                                <a href="index.html?id=${Number(item.id)}" class="btn btn-outline">Chi tiết</a>
                                <button type="button" class="btn btn-blue" onclick="addToCartAjax(${Number(item.id)}, 1)">+ Giỏ hàng</button>
                            </div>
                        </div>
                    </article>`;
                }).join('');
            }
            if (notify) showToast(`Đã tìm thấy ${res.count} sản phẩm.`, 'info');
        })
        .catch(err => showToast(err.message || 'Không thể tải sản phẩm', 'error'));
}
if (searchForm) searchForm.addEventListener('submit', e => { e.preventDefault(); fetchProducts(true); });
if (keywordInput) keywordInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => fetchProducts(false), 300);
});
if (categorySelect) categorySelect.addEventListener('change', () => fetchProducts(true));
if (resetBtn) resetBtn.addEventListener('click', () => {
    keywordInput.value = '';
    categorySelect.value = '0';
    fetchProducts(true);
});
</script>
</body>
</html>
<?php
$footerFile = __DIR__ . '/../includes/footer.php';
if (is_file($footerFile)) include $footerFile;
?>
