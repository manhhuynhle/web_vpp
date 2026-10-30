<?php
$host = '127.0.0.1'; //localhost
$dbname = 'bookstore';
$port = '3307'; //3306
$username = 'root';
$password = '';

try {
   $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Lỗi kết nối: " . $e->getMessage();
    die();
} 
?>
<!-- cổng 3306
 $conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8mb4");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
} -->