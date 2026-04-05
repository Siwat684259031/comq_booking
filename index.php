<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>ComQ Booking - รายการจอง</title>
</head>
<body>

<?php require "connect.php"; ?> 

<div class="container-fluid"> 
    <div class="row">
        <div class="col-md-12"> <br>
            <h3 class="border-bottom pb-2">รายละเอียดการจองของลูกค้า 
                <div class="float-end">
                    <a href="add_customer.php" class="btn btn-success">++ เพิ่มลูกค้าใหม่</a> 
                    <a href="addcustomer_dropdownfull_swapinsert.php" class="btn btn-info">+ เพิ่มการจอง</a>
                </div>
            </h3>

            <table class="table table-striped table-hover table-responsive table-bordered mt-3">
                <thead align="center" class="table-dark">
                    <tr>
                        <th width="5%">ID จอง</th> <th width="15%">ชื่อ-นามสกุล</th> <th width="10%">เบอร์โทร</th> <th width="10%">วันที่จอง</th> <th width="12%">เวลา</th> <th width="8%">เครื่อง</th> <th width="10%">ประเภท</th> <th width="8%">สถานะ</th> <th width="12%">จัดการ</th> <th width="5%">แก้ไข</th> <th width="5%">ลบ</th> </tr>
                </thead>
                <tbody>
                <?php
                    $sql = "SELECT 
                                r.reservation_id AS res_id, 
                                r.reservation_date, r.start_time, r.end_time, 
                                r.status AS res_status,
                                c.customer_id, c.first_name, c.last_name, c.phone,
                                m.machine_number, mt.type_name
                            FROM reservations r
                            LEFT JOIN customers c ON r.customer_id = c.customer_id
                            LEFT JOIN machines m ON r.machine_id = m.machine_id
                            LEFT JOIN machine_types mt ON m.machine_type_id = mt.machine_type_id
                            ORDER BY r.reservation_id DESC";
                    
                    $result = mysqli_query($conn, $sql);
                    while ($r = mysqli_fetch_array($result)) {
                ?>
                    <tr>
                        <td align="center"><strong><?= $r['res_id'] ?></strong></td>
                        <td><?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) ?></td>
                        <td><?= htmlspecialchars($r['phone']) ?></td>
                        <td align="center"><?= $r['reservation_date'] ?></td>
                        <td align="center"><?= substr($r['start_time'], 0, 5) ?> - <?= substr($r['end_time'], 0, 5) ?></td>
                        <td align="center">เครื่อง <?= $r['machine_number'] ?></td>
                        <td><?= $r['type_name'] ?></td>
                        <td align="center">
                            <?php 
                                $badge = ($r['res_status'] == 'Pending') ? 'bg-primary' : 'bg-secondary';
                                if($r['res_status'] == 'Completed') $badge = 'bg-success';
                            ?>
                            <span class="badge <?= $badge ?>"><?= $r['res_status'] ?></span>
                        </td>

                       <td align="center">
                            <?php if($r['res_status'] == 'Pending') { ?>
                                <button type="button" 
                                    class="btn btn-success btn-sm w-100" 
                                    onclick="openPaymentModal('<?= $r['res_id'] ?>', '<?= htmlspecialchars($r['first_name'].' '.$r['last_name']) ?>', '<?= $r['reservation_date'].' '.$r['start_time'] ?>', '<?= $r['reservation_date'].' '.$r['end_time'] ?>')">
                                    จ่ายเงิน
                                </button>
                            <?php } else { ?>
                                <span class="text-success fw-bold small">เรียบร้อย</span>
                            <?php } ?>
                        </td>

                        <td align="center">
                            <a href="updateCustomerForm_stu.php?customer_id=<?= $r['customer_id'] ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                        </td>
                        
                        <td align="center">
                            <a href="deleteCustomerDB_stu.php?customer_id=<?= $r['customer_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบ?');">ลบ</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="complete_booking_db.php" method="POST" class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="paymentModalLabel">บันทึกการชำระเงินและการใช้งาน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="reservation_id" id="modal_res_id">
                <input type="hidden" name="start_full" id="modal_start">
                <input type="hidden" name="end_full" id="modal_end">

                <div class="mb-3">
                    <label class="form-label fw-bold">ชื่อลูกค้า:</label>
                    <div id="display_cust_name" class="form-control-plaintext border-bottom"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">จำนวนเงินที่จ่าย (บาท):</label>
                    <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">วิธีการชำระเงิน:</label>
                    <select name="payment_method" class="form-select">
                        <option value="Cash">เงินสด (Cash)</option>
                        <option value="Thai QR">Thai QR</option>
                        <option value="Transfer">โอนเงินเข้าบัญชี</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="submit" class="btn btn-success">ยืนยันและบันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openPaymentModal(resId, custName, start, end) {
    document.getElementById('modal_res_id').value = resId;
    document.getElementById('display_cust_name').innerText = custName;
    document.getElementById('modal_start').value = start;
    document.getElementById('modal_end').value = end;
    
    var myModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    myModal.show();
}
</script>

</body>
</html>