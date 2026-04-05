<?php
require "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $res_id = mysqli_real_escape_string($conn, $_POST['reservation_id']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $start_time = $_POST['start_full'];
    $end_time = $_POST['end_full'];

    mysqli_begin_transaction($conn);

        $start_ts = strtotime($start_time);
        $end_ts = strtotime($end_time);

        if ($end_ts < $start_ts) {
            $end_ts += 86400; 
            $end_time = date('Y-m-d H:i:s', $end_ts); 
        }

        $diff = $end_ts - $start_ts;
        $hours_used = round($diff / 3600, 2);

        $sql_usage = "INSERT INTO usages (start_time, end_time, hours_used, reservation_id) 
                      VALUES ('$start_time', '$end_time', '$hours_used', '$res_id')";
        
        if (!mysqli_query($conn, $sql_usage)) {
            throw new Exception("Error inserting into usages: " . mysqli_error($conn));
        }
        
        $usage_id = mysqli_insert_id($conn); 

        $sql_payment = "INSERT INTO payments (amount, payment_method, payment_date, usage_id) 
                        VALUES ('$amount', '$method', NOW(), '$usage_id')";
        
        if (!mysqli_query($conn, $sql_payment)) {
            throw new Exception("Error inserting into payments: " . mysqli_error($conn));
        }

        $sql_update_res = "UPDATE reservations SET status = 'Completed' WHERE reservation_id = '$res_id'";
        
        if (!mysqli_query($conn, $sql_update_res)) {
            throw new Exception("Error updating reservation status: " . mysqli_error($conn));
        }

        mysqli_commit($conn);

        echo "<script>
                alert('บันทึกการชำระเงินและข้อมูลการใช้งานเรียบร้อยแล้ว');
                window.location.href = 'index.php';
              </script>";

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>
                alert('เกิดข้อผิดพลาด: " . $e->getMessage() . "');
                window.history.back();
              </script>";
    }
} else {
    header("Location: index.php");
}

mysqli_close($conn);
?>