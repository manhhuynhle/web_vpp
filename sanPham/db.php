<?php
$host = '127.0.0.1'; //localhost
$dbname = 'HOC_TOT';
$username = 'root';
$password = '1412'; // Nếu MySQL của bạn có đặt mật khẩu (VD: 123456 hoặc root), hãy điền vào đây

// Danh sách cổng ưu tiên: thử 3306 trước, nếu máy dùng 3307 sẽ tự động chuyển sang 3307
$ports = ['3306', '3307'];
$conn = null;
$lastError = '';

foreach ($ports as $port) {
    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        break; // Kết nối thành công thì thoát vòng lặp
    } catch (PDOException $e) {
        $lastError = $e->getMessage();
    }
}

if (!$conn) {
    echo "Lỗi kết nối: " . $lastError;
    die();
}
?>