<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] != "student") {
    header("Location: login.php"); // ถ้าไม่มี session หรือไม่ใช่ student ให้ไปหน้า login
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Home</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <br>
        <div class="alert alert-success h4" role="alert">
            กรุณาเลือกแบบคำร้องที่ต้องการ
        </div>
        <p>Hello, <?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"]; ?>!</p>
        <br>
        
        <!-- ปุ่มสำหรับเลือกฟอร์ม 5 แบบที่ใช้บ่อย -->
        <div class="row">
            <div class="col-md-3">
                <a href="form_fill.php?form=RE01" class="btn btn-primary btn-block">คำร้องทั่วไป (RE01)</a>
            </div>
            <div class="col-md-3">
                <a href="form_fill.php?form=RE05" class="btn btn-primary btn-block">คำร้องขอลงทะเบียนล่าช้า (RE05)</a>
            </div>
            <div class="col-md-3">
                <a href="form_fill.php?form=RE10" class="btn btn-primary btn-block">คำร้องขอลาพักการเรียน (RE10)</a>
            </div>
            <div class="col-md-3">
                <a href="form_fill.php?form=RE11" class="btn btn-primary btn-block">คำร้องขอลาออกจากการเป็นนิสิต (RE11)</a>
            </div>
            <div class="col-md-3">
                <a href="form_fill.php?form=RE24" class="btn btn-primary btn-block">คำร้องขอขยายเวลาการชำระเงิน (RE24)</a>
            </div>
        </div>

        <br>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
    <div class="col-md-3">
    <a href="check_status.php" class="btn btn-info btn-block">ตรวจสอบสถานะคำร้อง</a>
    </div>
    <br>
</body>
</html>
