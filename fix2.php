<?php
include 'ket_noi.php';
$sql = "SELECT trang_thai FROM don_hang";
$res = mysqli_query($conn, $sql);
var_dump($res);
if ($res) {
    while($row = mysqli_fetch_assoc($res)) var_dump($row);
}
?>
