<?php
require "connect.php";

$customer_id = $_POST['customer_id'];
$machine_id = $_POST['machine_id'];
$reservation_date = $_POST['reservation_date'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$status = 'Pending'; 

$sql_insert = "INSERT INTO reservations (reservation_date, start_time, end_time, status, customer_id, machine_id) 
               VALUES ('$reservation_date', '$start_time', '$end_time', '$status', '$customer_id', '$machine_id')";

$sql_update_machine = "UPDATE machines SET status = 'Busy' WHERE machine_id = '$machine_id'";

if (mysqli_query($conn, $sql_insert)) {
    mysqli_query($conn, $sql_update_machine); 
    echo "<script>
            alert('เพิ่มการจองสำเร็จ');
            window.location.href = 'index.php';
          </script>";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>