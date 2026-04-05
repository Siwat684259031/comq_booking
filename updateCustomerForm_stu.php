<?php
require "connect.php";

$customer_id = $_GET['customer_id'];

$sql = "SELECT * FROM customers WHERE customer_id = '$customer_id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>แก้ไขข้อมูลลูกค้า</title>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark"><h4>แก้ไขข้อมูลลูกค้า</h4></div>
                <div class="card-body">
                    <form action="updateCustomerDB_stu.php" method="POST">
                        <input type="hidden" name="customer_id" value="<?= $row['customer_id'] ?>">

                        <div class="mb-3">
                            <label class="form-label">ชื่อจริง</label>
                            <input type="text" name="first_name" class="form-control" value="<?= $row['first_name'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">นามสกุล</label>
                            <input type="text" name="last_name" class="form-control" value="<?= $row['last_name'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" class="form-control" value="<?= $row['phone'] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">อีเมล</label>
                            <input type="email" name="email" class="form-control" value="<?= $row['email'] ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-success">บันทึกการแก้ไข</button>
                        <a href="index.php" class="btn btn-secondary">ยกเลิก</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>