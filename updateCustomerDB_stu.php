<?php
require "connect.php";

$customer_id = $_POST['customer_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];

$sql = "UPDATE customers SET 
            first_name = '$first_name', 
            last_name = '$last_name', 
            phone = '$phone', 
            email = '$email' 
        WHERE customer_id = '$customer_id'";

$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<script>
            alert('แก้ไขข้อมูลสำเร็จ');
            window.location.href = 'index.php';
          </script>";
} else {
    echo "<script>
            alert('เกิดข้อผิดพลาดในการแก้ไขข้อมูล');
            window.location.href = 'index.php';
          </script>";
}

mysqli_close($conn);
?>