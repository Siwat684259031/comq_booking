<?php
require "connect.php";

// รับค่าจากฟอร์ม
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];

// INSERT โดยไม่ระบุ customer_id เพื่อให้ระบบ Auto Increment ทำงาน
$sql = "INSERT INTO customers (first_name, last_name, phone, email) 
        VALUES ('$first_name', '$last_name', '$phone', '$email')";

if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('เพิ่มรายชื่อลูกค้าสำเร็จ');
            window.location.href = 'addcustomer_dropdownfull_swapinsert.php';
          </script>";
} else {
    echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
}

mysqli_close($conn);
?>