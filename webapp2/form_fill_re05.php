
<?php
// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_members_student";
// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);
// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}
// ตรวจสอบการรับข้อมูลจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม
    $form_id = 'RE05';
    $name = $_POST['name'];
    $student_id = $_POST['studentId'];
    $faculty = $_POST['faculty'];
    $telephone = $_POST['phone'];
    $reason = $_POST['reason'];
    $course_code = $_POST['courseCode'];
    $section = $_POST['section'];
    $subject = $_POST['courseName'];
    $credits = $_POST['credits'];
    $instructor = $_POST['instructor'];
    $date = date("Y-m-d");
    $status = 'Pending';
    $approval_comments = '';
    
    // ตรวจสอบการกรอกข้อมูล
    if (!empty($name) && !empty($student_id) && !empty($faculty) && !empty($telephone) && !empty($reason) && !empty($course_code)) {
        // ตรวจสอบว่า student_id และ telephone เป็นตัวเลข
        if (!preg_match("/^[0-9]{8}$/", $student_id)) {
            echo "กรุณากรอกรหัสนักศึกษาที่ถูกต้อง (8 หลัก)";
        } elseif (!preg_match("/^[0-9]{10}$/", $telephone)) {
            echo "กรุณากรอกหมายเลขโทรศัพท์ที่ถูกต้อง (10 หลัก)";
        } else {
            // ใช้ prepared statement สำหรับบันทึกข้อมูลอย่างปลอดภัย
            $stmt = $conn->prepare("INSERT INTO re05_collected_data (form_id, name, student_id, faculty, telephone, reason, course_code, section, subject, credits, instructor, date, status, approval_comments)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            // ผูกค่าพารามิเตอร์
            $stmt->bind_param("ssssssssssssss", $form_id, $name, $student_id, $faculty, $telephone, $reason, $course_code, $section, $subject, $credits, $instructor, $date, $status, $approval_comments);
            
            // ตรวจสอบการบันทึกข้อมูล
            if ($stmt->execute()) {
                echo "ฟอร์ม RE05 ได้รับข้อมูลเรียบร้อยแล้ว";
                // JavaScript เพื่อเปลี่ยนหน้าอัตโนมัติหลังจากผ่านไป 5 วินาที
                echo '<script>
                    setTimeout(function() {
                    window.location.href = "home-student.php";
                    }, 5000); // 5000 มิลลิวินาที = 5 วินาที
                    </script>';
            } else {
                echo "เกิดข้อผิดพลาด: " . $stmt->error;
            }
            // ปิด prepared statement
            $stmt->close();
        }
    } else {
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
    }
}
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
