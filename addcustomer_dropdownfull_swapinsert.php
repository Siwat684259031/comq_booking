<?php
require "connect.php";

$sql_customers = "SELECT * FROM customers ORDER BY customer_id DESC";
$res_customers = mysqli_query($conn, $sql_customers);

$sql_machines = "SELECT m.*, mt.type_name 
                 FROM machines m 
                 LEFT JOIN machine_types mt ON m.machine_type_id = mt.machine_type_id 
                 ORDER BY m.machine_number ASC";
$res_machines = mysqli_query($conn, $sql_machines);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>เพิ่มการจองใหม่ - ComQ</title>
    <style>
        body { background-color: #f4f7f6; }
        .card { border: none; border-radius: 12px; }
        .card-header { border-radius: 12px 12px 0 0 !important; }
        .form-select, .form-control { border-radius: 8px; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 text-center">บันทึกการจองคอมพิวเตอร์</h5>
                </div>
                <div class="card-body p-4">
                    <form action="addcustomer_dropdownfull_swapinsert_db.php" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">เลือกลูกค้า</label>
                            <select name="customer_id" class="form-select border-primary" required>
                                <option value="">-- กรุณาเลือกลูกค้า --</option>
                                <?php if(mysqli_num_rows($res_customers) > 0) { ?>
                                    <?php while($row = mysqli_fetch_assoc($res_customers)) { ?>
                                        <option value="<?= $row['customer_id'] ?>">
                                            ID: <?= $row['customer_id'] ?> | <?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?>
                                        </option>
                                    <?php } ?>
                                <?php } else { ?>
                                    <option value="" disabled>ยังไม่มีข้อมูลลูกค้า</option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">เลือกเครื่องคอมพิวเตอร์</label>
                            <select name="machine_id" class="form-select border-primary" required>
                                <option value="">-- กรุณาเลือกเครื่อง --</option>
                                <?php while($row_m = mysqli_fetch_assoc($res_machines)) { ?>
                                    <option value="<?= $row_m['machine_id'] ?>">
                                        เครื่อง <?= $row_m['machine_number'] ?> (<?= $row_m['type_name'] ?? 'ไม่มีประเภท' ?>) - สถานะ: <?= $row_m['status'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">วันที่จอง</label>
                            <input type="date" name="reservation_date" class="form-control border-primary" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">เวลาเริ่ม</label>
                                <input type="time" name="start_time" class="form-control border-primary" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">เวลาสิ้นสุด</label>
                                <input type="time" name="end_time" class="form-control border-primary" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">ยืนยันการจอง</button>
                            <a href="index.php" class="btn btn-light border">กลับหน้าหลัก</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <p class="text-center mt-3 text-muted">
                <small>* หากไม่พบชื่อลูกค้า โปรดไปเพิ่มที่เมนู <a href="add_customer.php">เพิ่มลูกค้าใหม่</a></small>
            </p>
        </div>
    </div>
</div>

</body>
</html>