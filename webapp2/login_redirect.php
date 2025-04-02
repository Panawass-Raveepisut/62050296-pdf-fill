<?php
session_start();

// รับบทบาทที่เลือกจาก role_selection.php
$role = $_GET['role'] ?? '';

// ตรวจสอบบทบาทและเปลี่ยนเส้นทางไปยังหน้า login ที่เหมาะสม
switch ($role) {
    case 'student':
        header("Location: login_student.php");
        break;
    case 'teacher':
        header("Location: login_teacher.php");
        break;
    case 'president':
        header("Location: login_president.php");
        break;
    case 'dean':
        header("Location: login_dean.php");
        break;
    case 'staff':
        header("Location: login_staff.php");
        break;
    default:
        $_SESSION["Error"] = "Please select a valid role.";
        header("Location: role_selection.php");
}
exit();
