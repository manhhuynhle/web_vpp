<?php
session_start();
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    
    if ($stmt->execute([$_POST['status'], $_POST['order_id']])) {
        $_SESSION['msg'] = "thông tin lưu thành công";
        $_SESSION['msg_type'] = "green";
    } else {
        $_SESSION['msg'] = "thông tin chưa được lưu thành công";
        $_SESSION['msg_type'] = "red";
    }
    $_SESSION['updated_id'] = $_POST['order_id']; // Lưu ID để biết dòng nào cần hiện thông báo
    
    header("Location: admin_orders.php");
    exit;
}

$sql = "SELECT o.*, u.full_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn hàng</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Danh sách Đơn hàng</h2>
    <table>
        <tr><th>Mã đơn</th><th>Khách hàng</th><th>Tổng tiền</th><th>Trạng thái</th><th>Thao tác</tr>
        <!-- Đổi FETCH_ASSOC nếu là cổng 3306 còn PDO::FETCH_ASSOC cho cổng 3307 -->
       <?php while($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
<tr>
    <td><?= $row['order_code'] ?></td>
    <td><?= htmlspecialchars($row['full_name']) ?></td>
    <td><?= number_format($row['total_price']) ?> đ</td>
    <td>
        <form method="POST" style="display:flex; gap: 5px; justify-content: center;">
            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
            <select name="status">
                <option value="pending" <?= $row['status']=='pending'?'selected':'' ?>>Chờ duyệt</option>
                <option value="shipping" <?= $row['status']=='shipping'?'selected':'' ?>>Đang giao</option>
                <option value="completed" <?= $row['status']=='completed'?'selected':'' ?>>Đã giao</option>
                <option value="canceled" <?= $row['status']=='canceled'?'selected':'' ?>>Hủy</option>
            </select>
            <button type="submit" class="btn btn-blue">Lưu</button>
        </form>
    </td>
    <td>
        <!-- Cột Thao tác: Chỉ chứa thông báo -->
        <?php if(isset($_SESSION['updated_id']) && $_SESSION['updated_id'] == $row['id']): ?>
            <p style="color: <?= $_SESSION['msg_type'] ?>; font-size: 14px; margin: 0; text-align: center; font-weight: bold;">
                <?= $_SESSION['msg'] ?>
            </p>
            <?php 
                unset($_SESSION['updated_id']); 
                unset($_SESSION['msg']); 
                unset($_SESSION['msg_type']); 
            ?>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
    </table>
</div>
</body>
</html>