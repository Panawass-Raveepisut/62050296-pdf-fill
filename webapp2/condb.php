<?php
// เชื่อมต่อฐานข้อมูล
$servername = "localhost";  // ชื่อเซิร์ฟเวอร์ฐานข้อมูล
$username = "root";         // ชื่อผู้ใช้ฐานข้อมูล
$password = "";             // รหัสผ่านฐานข้อมูล
$dbname = "db_members_student";  // ใช้ชื่อฐานข้อมูลที่ถูกต้อง

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

?>
