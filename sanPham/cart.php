<?php
// cart.php — giỏ hàng tự cập nhật bằng PHP Session + AJAX
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function cart_e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function cart_totals(array $cart): array {
    $subtotal = 0;
    $items = 0;
    foreach ($cart as $item) {
        $qty = max(0, (int)($item['qty'] ?? 0));
        $subtotal += (float)($item['price'] ?? 0) * $qty;
        $items += $qty;
    }
    return [$subtotal, $items];
}

// AJAX cập nhật số lượng/xóa sản phẩm mà không tải lại trang.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_cart'])) {
    header('Content-Type: application/json; charset=utf-8');
    $action = (string)($_POST['action'] ?? '');
    $productId = (int)($_POST['product_id'] ?? 0);

    if ($action === 'update' && $productId > 0 && isset($_SESSION['cart'][$productId])) {
        $qty = max(1, min(999, (int)($_POST['qty'] ?? 1)));
        $_SESSION['cart'][$productId]['qty'] = $qty;
    } elseif ($action === 'remove' && $productId > 0) {
        unset($_SESSION['cart'][$productId]);
    } elseif ($action === 'clear') {
        $_SESSION['cart'] = [];
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    [$subtotal, $totalItems] = cart_totals($_SESSION['cart']);
    echo json_encode([
        'success' => true,
        'subtotal' => $subtotal,
        'total_items' => $totalItems,
        'cart' => $_SESSION['cart']
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Hỗ trợ xóa nếu trình duyệt không dùng AJAX.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $productId = (int)($_POST['product_id'] ?? 0);
    if ($action === 'remove' && $productId > 0) {
        unset($_SESSION['cart'][$productId]);
    } elseif ($action === 'clear') {
        $_SESSION['cart'] = [];
    }
    header('Location: cart.php');
    exit;
}

$cart = $_SESSION['cart'];
[$subTotal, $totalItems] = cart_totals($cart);

include __DIR__ . '/../includes/header.php';
?>
<link rel="stylesheet" href="/web_van_pp/assets/css/cart_style.css">

<div class="container my-4" style="max-width:1140px; margin:0 auto; padding:20px 15px;">
    <div class="checkout-steps">
        <div class="step-item active"><span class="step-number">1</span> Giỏ hàng</div>
        <div class="step-item"><span class="step-number">2</span> Thông tin giao hàng</div>
        <div class="step-item"><span class="step-number">3</span> Hoàn tất đơn hàng</div>
    </div>

    <h2 style="font-size:22px; margin-bottom:20px;">
        Giỏ hàng của bạn (<span id="cartItemCount"><?= $totalItems ?></span> sản phẩm)
    </h2>

    <div id="cartMessage" role="status" style="margin-bottom:12px;"></div>

    <div style="display:flex; gap:20px; flex-wrap:wrap;">
        <div style="flex:2; min-width:320px;">
            <div class="tl-card">
                <table class="cart-table" id="cartTable">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cartBody">
                    <?php if (!$cart): ?>
                        <tr class="empty-cart">
                            <td colspan="5" style="text-align:center; padding:30px;">
                                Giỏ hàng đang trống.
                                <br><a href="products.php" class="btn-tl-primary" style="display:inline-block; margin-top:12px;">Tiếp tục mua hàng</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cart as $id => $item):
                            $name = $item['name'] ?? 'Sản phẩm';
                            $price = (float)($item['price'] ?? 0);
                            $qty = max(1, (int)($item['qty'] ?? 1));
                            $lineTotal = $price * $qty;
                        ?>
                        <tr data-id="<?= (int)$id ?>" data-price="<?= cart_e($price) ?>">
                            <td><div style="display:flex; gap:12px; align-items:center;">
                                <div style="width:60px; height:60px; flex:0 0 60px; background:#E5E7EB; border-radius:4px; display:flex; align-items:center; justify-content:center; font-size:10px; color:#6B7280;">Ảnh SP</div>
                                <div><span class="cart-item-title"><?= cart_e($name) ?></span></div>
                            </div></td>
                            <td><span class="price-current"><?= number_format($price, 0, ',', '.') ?>đ</span></td>
                            <td>
                                <div class="qty-control" style="display:flex; align-items:center; gap:5px;">
                                    <button type="button" class="qty-btn btn-minus" aria-label="Giảm số lượng">−</button>
                                    <input type="number" class="qty-input" value="<?= $qty ?>" min="1" max="999" style="width:55px;">
                                    <button type="button" class="qty-btn btn-plus" aria-label="Tăng số lượng">+</button>
                                </div>
                            </td>
                            <td class="item-total" style="font-weight:bold; color:#1565C0;"><?= number_format($lineTotal, 0, ',', '.') ?>đ</td>
                            <td><button type="button" class="btn-remove" style="background:none; border:none; color:#EF4444; cursor:pointer;" title="Xóa" aria-label="Xóa sản phẩm">✕</button></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="flex:1; min-width:280px;">
            <div class="tl-card">
                <h3 style="font-size:18px; margin-top:0; border-bottom:1px solid #E5E7EB; padding-bottom:10px;">Thành tiền</h3>
                <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
                    <span style="color:#4B5563;">Tạm tính:</span>
                    <strong id="subTotal"><?= number_format($subTotal, 0, ',', '.') ?>đ</strong>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
                    <span style="color:#4B5563;">Phí vận chuyển:</span>
                    <span id="shippingText" style="color:#10B981; font-weight:600;"><?= $subTotal >= 100000 ? 'Miễn phí' : 'Tính tại bước thanh toán' ?></span>
                </div>
                <hr style="border:0; border-top:1px dashed #E5E7EB; margin:15px 0;">
                <div style="display:flex; justify-content:space-between; margin-bottom:20px; font-size:18px;">
                    <span>Tổng tiền:</span>
                    <strong id="finalTotal" style="color:#D32F2F;"><?= number_format($subTotal, 0, ',', '.') ?>đ</strong>
                </div>
                <a href="checkout.php" class="btn-tl-primary" id="checkoutButton" <?= !$cart ? 'style="pointer-events:none; opacity:.5;"' : '' ?>>Tiến hành đặt hàng</a>
                <button type="button" id="clearCartBtn" class="btn-remove" style="cursor:pointer; margin-top:10px; <?= !$cart ? 'display:none;' : '' ?>">Xóa toàn bộ giỏ hàng</button>
            </div>
        </div>
    </div>
</div>

<script>
(() => {
    const body = document.getElementById('cartBody');
    const message = document.getElementById('cartMessage');
    const money = value => Number(value || 0).toLocaleString('vi-VN') + 'đ';

    function showMessage(text, error = false) {
        message.textContent = text;
        message.style.color = error ? '#dc2626' : '#15803d';
    }

    function updateSummary(data) {
        document.getElementById('subTotal').textContent = money(data.subtotal);
        document.getElementById('finalTotal').textContent = money(data.subtotal);
        document.getElementById('cartItemCount').textContent = data.total_items;

        const shipping = document.getElementById('shippingText');
        shipping.textContent = data.subtotal >= 100000 ? 'Miễn phí' : 'Tính tại bước thanh toán';

        const badge = document.getElementById('cartBadge');
        if (badge) badge.textContent = data.total_items;

        const hasItems = data.total_items > 0;
        document.getElementById('checkoutButton').style.pointerEvents = hasItems ? '' : 'none';
        document.getElementById('checkoutButton').style.opacity = hasItems ? '' : '.5';
        document.getElementById('clearCartBtn').style.display = hasItems ? 'inline-block' : 'none';

        if (!hasItems) {
            body.innerHTML = '<tr class="empty-cart"><td colspan="5" style="text-align:center;padding:30px;">Giỏ hàng đang trống.<br><a href="products.php" class="btn-tl-primary" style="display:inline-block;margin-top:12px;">Tiếp tục mua hàng</a></td></tr>';
        }
    }

    async function saveCart(action, row = null, qty = null) {
        const form = new URLSearchParams();
        form.set('ajax_cart', '1');
        form.set('action', action);
        if (row) form.set('product_id', row.dataset.id);
        if (qty !== null) form.set('qty', String(qty));

        try {
            const response = await fetch('cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'},
                body: form.toString()
            });
            const data = await response.json();
            if (!response.ok || !data.success) throw new Error(data.message || 'Không thể cập nhật giỏ hàng.');

            if (row && action === 'update') {
                const price = Number(row.dataset.price || 0);
                row.querySelector('.qty-input').value = qty;
                row.querySelector('.item-total').textContent = money(price * qty);
            } else if (row && action === 'remove') {
                row.remove();
            } else if (action === 'clear') {
                body.querySelectorAll('tr[data-id]').forEach(tr => tr.remove());
            }

            updateSummary(data);
            showMessage(action === 'remove' ? 'Đã xóa sản phẩm khỏi giỏ hàng.' :
                        action === 'clear' ? 'Đã xóa toàn bộ giỏ hàng.' : 'Giỏ hàng đã được cập nhật.');
        } catch (error) {
            showMessage(error.message || 'Lỗi kết nối máy chủ.', true);
        }
    }

    body.addEventListener('click', event => {
        const row = event.target.closest('tr[data-id]');
        if (!row) return;

        if (event.target.closest('.btn-plus')) {
            const input = row.querySelector('.qty-input');
            const qty = Math.min(999, (parseInt(input.value, 10) || 1) + 1);
            saveCart('update', row, qty);
        } else if (event.target.closest('.btn-minus')) {
            const input = row.querySelector('.qty-input');
            const qty = Math.max(1, (parseInt(input.value, 10) || 1) - 1);
            saveCart('update', row, qty);
        } else if (event.target.closest('.btn-remove')) {
            if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) saveCart('remove', row);
        }
    });

    body.addEventListener('change', event => {
        if (!event.target.matches('.qty-input')) return;
        const row = event.target.closest('tr[data-id]');
        const qty = Math.max(1, Math.min(999, parseInt(event.target.value, 10) || 1));
        saveCart('update', row, qty);
    });

    document.getElementById('clearCartBtn').addEventListener('click', () => {
        if (confirm('Bạn muốn xóa toàn bộ giỏ hàng?')) saveCart('clear');
    });
})();
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
