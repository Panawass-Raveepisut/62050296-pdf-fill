<?php
session_start(); // เริ่มต้น session เพื่อดึงข้อมูลผู้ใช้งาน

// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบว่ามีข้อมูลครบถ้วนหรือไม่
    if (!isset($_POST['form_id'], $_POST['signature'], $_POST['signer_role'])) {
        echo "ข้อมูลที่ส่งมาไม่ครบถ้วน";
        exit;
    }

    // รับข้อมูลจากฟอร์ม
    $form_id = $_POST['form_id']; // รหัสฟอร์ม
    $signature_data = $_POST['signature']; // ข้อมูลลายเซ็นในรูปแบบ base64
    $signer_role = $_POST['signer_role']; // บทบาทของผู้เซ็น เช่น "student", "advisor"
    $signer_id = $_SESSION['username']; // ดึงข้อมูลรหัสผู้ใช้จาก session
    $signature_date = date('Y-m-d H:i:s'); // วันที่เซ็น

    // ลบส่วนหัวของข้อมูล base64 หากมี "data:image/png;base64,"
    if (strpos($signature_data, 'data:image/png;base64,') === 0) {
        $signature_data = substr($signature_data, strlen('data:image/png;base64,'));
    }

    // ตรวจสอบให้แน่ใจว่ามีโฟลเดอร์สำหรับเก็บไฟล์ลายเซ็น
    $signature_dir = 'signatures/';
    if (!is_dir($signature_dir)) {
        mkdir($signature_dir, 0777, true); // สร้างโฟลเดอร์หากยังไม่มี
    }

    // กำหนดชื่อไฟล์สำหรับลายเซ็นโดยใช้ชื่อไฟล์แบบไม่ซ้ำกัน
    $signature_file = $signature_dir . uniqid('signature_', true) . '.png';

    // แปลงข้อมูล base64 เป็นไฟล์ภาพ
    $decoded_signature = base64_decode($signature_data);
    if ($decoded_signature === false) {
        echo "การถอดรหัส base64 ไม่สำเร็จ";
        exit;
    }

    // บันทึกไฟล์ภาพลายเซ็น
    if (file_put_contents($signature_file, $decoded_signature) === false) {
        echo "ไม่สามารถบันทึกไฟล์ภาพลายเซ็นได้";
        exit;
    }

    // เชื่อมต่อฐานข้อมูล
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_members_student";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
    }

    // SQL สำหรับการบันทึกลายเซ็นลงในฐานข้อมูล
    $sql = "INSERT INTO form_signatures (form_id, signature_image, signer_role, signer_id, signature_date) 
            VALUES (?, ?, ?, ?, ?)";

    // เตรียมคำสั่ง SQL และผูกค่าตัวแปร
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $form_id, $signature_file, $signer_role, $signer_id, $signature_date);

    // ดำเนินการบันทึกข้อมูล
    if ($stmt->execute()) {
        echo "ลายเซ็นถูกบันทึกเรียบร้อยแล้ว!";
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกลายเซ็น: " . $stmt->error;
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $stmt->close();
    $conn->close();
} else {
    echo "ไม่มีข้อมูลส่งมา";
}
?>
