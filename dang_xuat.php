<?php
session_start();
// Xóa các session liên quan đến người dùng
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);

// Điều hướng về trang chủ
header("Location: index.php");
exit();
?>