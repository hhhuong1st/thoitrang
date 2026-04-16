<?php
include 'ket_noi.php';
$sql = "SELECT trang_thai FROM don_hang WHERE ma_dh = 0";
$res = mysqli_query($conn, $sql);
var_dump($res);
echo " Error: " . mysqli_error($conn);
?>
