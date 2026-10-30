<?php include __DIR__ . '/../includes/header.php'; ?>
<?php
// session_start();
// require 'db.php';

    // if(!empty($_SESSION['cart'])) {
    //     $order_code = "DH-" . date("YmdHis");
    //     $total_price = array_sum(array_map(function($item) { return $item['price'] * $item['qty']; }, $_SESSION['cart']));
    //     $user_id = 1; // Giả lập ID user đang đăng nhập
        
    //     // Câu lệnh prepare giữ nguyên
    //     $stmt = $conn->prepare("INSERT INTO orders (order_code, user_id, total_price, status) VALUES (?, ?, ?, 'pending')");
        
    //     // PDO: Truyền trực tiếp mảng giá trị vào execute() thay vì dùng bind_param
    //     $stmt->execute([$order_code, $user_id, $total_price]);
    //     // cổng 3306
    //     // $stmt = $conn->prepare("INSERT INTO orders (order_code, user_id, total_price, status) VALUES (?, ?, ?, 'pending')");
    //     //$stmt->bind_param("sid", $order_code, $user_id, $total_price);
    //     //$stmt->execute();
    //     unset($_SESSION['cart']);
    // } else {
    //     header("Location: cart.php");
    //     exit;
    // }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">  
    <title>Đặt hàng thành công</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container" style="text-align: center; padding: 50px;">
    <h2 style="border: none; color: #28a745;">✔️ Đặt hàng thành công!</h2>
    <p>Mã đơn hàng: <strong><?= isset($order_code) ? $order_code : '' ?></strong></p>
    <p>Đơn hàng sẽ được thanh toán sau khi bạn nhận và kiểm tra hàng.</p>
    <a href="../index.php" class="btn btn-blue">Về trang chủ</a>
</div>
</body>
</html>
<?php include __DIR__ . '/../includes/footer.php'; ?>