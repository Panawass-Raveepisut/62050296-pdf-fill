<?php
// รายชื่อฟอร์มที่เก็บในโฟลเดอร์ pdfforms
$form_files = [
    'RE01' => 'คำร้องทั่วไป',
    'RE05' => 'คำร้องขอลงทะเบียนล่าช้า',
    'RE10' => 'คำร้องขอลาพักการเรียน',
    'RE11' => 'คำร้องขอลาออกจากการเป็นนิสิต',
    'RE24' => 'คำร้องขอขยายเวลาการชำระเงิน'
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เลือกฟอร์ม</title>
</head>
<body>
    <h1>เลือกฟอร์มที่ต้องการกรอกข้อมูล</h1>
    <form action="fill_form.php" method="post">
        <select name="form" required>
            <?php foreach ($form_files as $key => $name): ?>
                <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="กรอกข้อมูล">
    </form>
</body>
</html>
