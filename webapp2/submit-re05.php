<?php
// ไฟล์ submit-re05.php
include 'condb.php'; // เชื่อมต่อฐานข้อมูลจากไฟล์ condb.php

// ตรวจสอบว่า request มาจาก POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $name = $_POST['name'];
    $student_id = $_POST['studentId'];
    $faculty = $_POST['faculty'];
    $telephone = $_POST['phone'];
    $major = $_POST['major'];
    $reason = $_POST['reason'];
    $course_code = $_POST['courseCode'];
    $section = $_POST['section'];
    $subject = $_POST['courseName'];
    $credits = $_POST['credits'];
    $instructor = $_POST['instructor'];
    $date = $_POST['date'];
    $signature = $_POST['studentSignature']; // ลายเซ็นในรูปแบบ base64

    // SQL สำหรับบันทึกข้อมูลลงในตาราง re05_collected_data
    $sql = "INSERT INTO re05_collected_data (name, student_id, faculty, telephone, reason, course_code, section, subject, credits, instructor, date, student_signature) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // เตรียม statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssss", $name, $student_id, $faculty, $telephone, $reason, $course_code, $section, $subject, $credits, $instructor, $date, $signature);

    // บันทึกข้อมูลและตรวจสอบผลลัพธ์
    if ($stmt->execute()) {
        echo "บันทึกข้อมูลเรียบร้อยแล้ว";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    // ปิด statement และการเชื่อมต่อฐานข้อมูล
    $stmt->close();
    $conn->close();
} else {
    echo "ไม่อนุญาตให้เข้าถึงหน้านี้โดยตรง";
}
?>
