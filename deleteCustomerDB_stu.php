<?php
require "connect.php";

$customer_id = $_GET['customer_id'];

if (isset($customer_id)) {
    
    mysqli_begin_transaction($conn);

    try {
        
        $sql_delete_payments = "DELETE p FROM payments p 
                                JOIN usages u ON p.usage_id = u.usage_id
                                JOIN reservations r ON u.reservation_id = r.reservation_id
                                WHERE r.customer_id = '$customer_id'";
        mysqli_query($conn, $sql_delete_payments);

        $sql_delete_usages = "DELETE u FROM usages u 
                              JOIN reservations r ON u.reservation_id = r.reservation_id
                              WHERE r.customer_id = '$customer_id'";
        mysqli_query($conn, $sql_delete_usages);

        $sql_delete_reservations = "DELETE FROM reservations WHERE customer_id = '$customer_id'";
        mysqli_query($conn, $sql_delete_reservations);

        $sql_delete_customer = "DELETE FROM customers WHERE customer_id = '$customer_id'";
        mysqli_query($conn, $sql_delete_customer);

        mysqli_commit($conn);

        echo "<script>
                alert('ลบข้อมูลเรียบร้อยแล้ว');
                window.location.href = 'index.php';
              </script>";

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>
                alert('เกิดข้อผิดพลาด: " . $e->getMessage() . "');
                window.location.href = 'index.php';
              </script>";
    }

} else {
    header("Location: index.php");
}

mysqli_close($conn);
?>