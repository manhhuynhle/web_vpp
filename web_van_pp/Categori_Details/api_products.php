<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Tự động tìm tệp db.php dù đặt trong thư mục api/ hay thư mục gốc
if (file_exists(__DIR__ . '/../db.php')) {
    require __DIR__ . '/../db.php';
} else {
    require __DIR__ . '/db.php';
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

try {
    // 1. XỬ LÝ THÊM VÀO GIỎ HÀNG BẰNG FETCH() AJAX (Đồng bộ với cart.php)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add_to_cart') {
        $input = json_decode(file_get_contents('php://input'), true);
        $product_id = isset($input['product_id']) ? (int)$input['product_id'] : 0;
        $qty = isset($input['qty']) ? (int)$input['qty'] : 1;

        if ($product_id <= 0 || $qty <= 0) {
            echo json_encode(['success' => false, 'message' => 'Số lượng hoặc mã sản phẩm không hợp lệ!']);
            exit;
        }

        $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại!']);
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$product_id] = [
                'name'  => $product['name'],
                'price' => (float)$product['price'],
                'qty'   => $qty
            ];
        }

        $total_items = array_sum(array_column($_SESSION['cart'], 'qty'));
        echo json_encode([
            'success'    => true,
            'message'    => 'Đã thêm "' . $product['name'] . '" vào giỏ hàng!',
            'cart_count' => $total_items
        ]);
        exit;
    }

    // 2. XỬ LÝ TÌM KIẾM & LỌC DANH MỤC SẢN PHẨM (FR-02, FR-03, FR-04, FR-14)
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

    $sql = "SELECT p.*, c.category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE 1=1";
    $params = [];

    if ($keyword !== '') {
        $sql .= " AND p.name LIKE ?";
        $params[] = "%" . $keyword . "%";
    }

    if ($category_id > 0) {
        $sql .= " AND p.category_id = ?";
        $params[] = $category_id;
    }

    $sql .= " ORDER BY p.id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'count'   => count($products),
        'data'    => $products
    ]);
} catch (Exception $e) {
    // NFR-09: Không hiển thị lỗi SQL chi tiết cho người dùng cuối
    echo json_encode([
        'success' => false,
        'message' => 'Đã xảy ra lỗi khi tải dữ liệu sản phẩm.'
    ]);
}
?>