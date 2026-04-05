<?php
require "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $res_id = mysqli_real_escape_string($conn, $_POST['reservation_id']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $start_time = $_POST['start_full'];
    $end_time = $_POST['end_full'];

    // เริ่ม Transaction เพื่อป้องกันข้อมูลบันทึกไม่ครบ
    mysqli_begin_transaction($conn);

   // 1. บันทึกลงตาราง usages
        $start_ts = strtotime($start_time);
        $end_ts = strtotime($end_time);

        // ตรวจสอบว่าถ้าเวลาสิ้นสุดน้อยกว่าเวลาเริ่ม (กรณีใช้งานข้ามคืน)
        if ($end_ts < $start_ts) {
            $end_ts += 86400; // บวกเพิ่ม 24 ชั่วโมง (เป็นวินาที)
            $end_time = date('Y-m-d H:i:s', $end_ts); // ปรับค่า end_time ให้เป็นวันถัดไป
        }

        // คำนวณชั่วโมงการใช้งานใหม่จากค่าที่ตรวจสอบแล้ว
        $diff = $end_ts - $start_ts;
        $hours_used = round($diff / 3600, 2);

        $sql_usage = "INSERT INTO usages (start_time, end_time, hours_used, reservation_id) 
                      VALUES ('$start_time', '$end_time', '$hours_used', '$res_id')";
        
        if (!mysqli_query($conn, $sql_usage)) {
            throw new Exception("Error inserting into usages: " . mysqli_error($conn));
        }
        
        $usage_id = mysqli_insert_id($conn); // เก็บ ID ล่าสุดจากตาราง usages เพื่อเอาไปใช้ใน payments

        // 2. บันทึกลงตาราง payments
        $sql_payment = "INSERT INTO payments (amount, payment_method, payment_date, usage_id) 
                        VALUES ('$amount', '$method', NOW(), '$usage_id')";
        
        if (!mysqli_query($conn, $sql_payment)) {
            throw new Exception("Error inserting into payments: " . mysqli_error($conn));
        }

        // 3. อัปเดตสถานะในตาราง reservations เป็น Completed
        $sql_update_res = "UPDATE reservations SET status = 'Completed' WHERE reservation_id = '$res_id'";
        
        if (!mysqli_query($conn, $sql_update_res)) {
            throw new Exception("Error updating reservation status: " . mysqli_error($conn));
        }

        // ถ้าไม่มีปัญหาให้ยืนยันการบันทึกทั้งหมด
        mysqli_commit($conn);

        echo "<script>
                alert('บันทึกการชำระเงินและข้อมูลการใช้งานเรียบร้อยแล้ว');
                window.location.href = 'index.php';
              </script>";

    } catch (Exception $e) {
        // หากเกิดข้อผิดพลาดให้ยกเลิกการบันทึกทั้งหมด
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