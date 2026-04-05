<?php 
  // ตรงนี้สามารถใส่โค้ด PHP เช่น เช็กการ Login หรือดึงข้อมูลจาก DB ได้
  $page_title = "เพิ่มรายชื่อลูกค้าใหม่"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title><?php echo $page_title; ?></title> </head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><?php echo $page_title; ?></h4>
                </div>
                <div class="card-body">
                    <form action="add_customer_db.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">ชื่อจริง</label>
                            <input type="text" name="first_name" class="form-control" placeholder="ระบุชื่อ" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">นามสกุล</label>
                            <input type="text" name="last_name" class="form-control" placeholder="ระบุนามสกุล" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" class="form-control" placeholder="08XXXXXXXX">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">อีเมล</label>
                            <input type="email" name="email" class="form-control" placeholder="example@email.com">
                        </div>
                        <button type="submit" class="btn btn-success">บันทึกข้อมูลลูกค้า</button>
                        <a href="index.php" class="btn btn-secondary">กลับหน้าหลัก</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>