<?php
require 'db.php';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $status = ($_GET['action'] == 'ban') ? 1 : 0;
    $stmt = $conn->prepare("UPDATE users SET is_banned = ? WHERE id = ?");
    //3306
    // $stmt->bind_param("ii", $status, $_GET['id']);
    //$stmt->execute();
    $stmt->execute([$status, $_GET['id']]);
    header("Location: admin_users.php");
    exit;
}

$result = $conn->query("SELECT id, full_name, email, role, is_banned, created_at FROM users");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Người dùng</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Danh sách Người dùng</h2>
    <table>
        <tr><th>ID</th><th>Họ tên</th><th>Email</th><th>Vai trò</th><th>Trạng thái</th><th>Thao tác</th></tr>
        <!-- Đổi $result->fetch_assoc() nếu là cổng 3306 còn fetch(PDO::FETCH_ASSOC) cho cổng 3307 -->
        <?php while($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= strtoupper($row['role']) ?></td>
            <td><?= $row['is_banned'] ? '<span style="color:red">Khóa</span>' : '<span style="color:green">Hoạt động</span>' ?></td>
            <td>
                <?php if($row['role'] !== 'admin'): ?>
                    <?php if($row['is_banned'] == 0): ?>
                        <a href="?action=ban&id=<?= $row['id'] ?>" class="btn btn-red">Khóa</a>
                    <?php else: ?>
                        <a href="?action=unban&id=<?= $row['id'] ?>" class="btn btn-green">Mở khóa</a>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>