<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";  // ปรับให้เป็นข้อมูลของคุณ
$password = "";      // ปรับให้เป็นข้อมูลของคุณ
$dbname = "db_members_student"; // ปรับให้เป็นชื่อฐานข้อมูลของคุณ

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับรหัสนักศึกษาหรือข้อมูลที่ต้องการจาก URL หรือ session
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

// ตรวจสอบว่ามีรหัสนักศึกษาให้ค้นหาหรือไม่
if ($student_id != '') {
    // คำสั่ง SQL เพื่อดึงข้อมูลจากฐานข้อมูล
    $sql = "SELECT * FROM form_data WHERE student_id = '$student_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // หากพบข้อมูล, ดึงข้อมูลในแต่ละแถว
        $row = $result->fetch_assoc();
        $signature_path = $row['signature_path'];  // เส้นทางไฟล์ลายเซ็นที่บันทึกในฐานข้อมูล

        // ตรวจสอบว่ามีไฟล์ลายเซ็นหรือไม่
        if (file_exists($signature_path)) {
            // แสดงลายเซ็น
            echo "<h2>ลายเซ็นของนักศึกษา $student_id</h2>";
            echo "<img src='$signature_path' alt='ลายเซ็น' style='max-width: 100%; height: auto;'>";
        } else {
            echo "<p>ไม่พบลายเซ็นของนักศึกษา $student_id</p>";
        }
    } else {
        echo "<p>ไม่พบข้อมูลนักศึกษานี้</p>";
    }
} else {
    echo "<p>โปรดระบุรหัสนักศึกษา</p>";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
