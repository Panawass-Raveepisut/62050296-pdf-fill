<?php
session_start();
include('condb.php'); // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่า role ถูกส่งมาจากฟอร์มหรือไม่
if (!isset($_POST['role']) || !in_array($_POST['role'], ['student', 'teacher', 'president', 'dean', 'staff'])) {
    die("Invalid role selection");
}

$role = $_POST['role']; // role ที่เลือก
$username = $_POST['username']; // ชื่อผู้ใช้ที่กรอกมา
$password = $_POST['password']; // รหัสผ่านที่กรอกมา

// ป้องกัน SQL Injection โดยใช้ mysqli_real_escape_string
$username = mysqli_real_escape_string($conn, $username);

// เช็คข้อมูลผู้ใช้จากฐานข้อมูล
$query = "SELECT * FROM members_student WHERE username = '$username' AND role = '$role'"; // ปรับให้ตรงกับฐานข้อมูลที่ใช้
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    // ผู้ใช้พบในฐานข้อมูล
    $user = mysqli_fetch_assoc($result);
    
    // ตรวจสอบรหัสผ่าน
    if (password_verify($password, $user['password'])) { // ใช้ password_verify() เพื่อตรวจสอบรหัสผ่านที่ถูกเข้ารหัส
        // ตั้งค่าข้อมูล session
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        $_SESSION['user_id'] = $user['id'];  // สมมติว่ามี id ในตาราง
        $_SESSION['firstname'] = $user['name'];
        $_SESSION['lastname'] = $user['lastname'];
        header("Location: index.php"); // เปลี่ยนไปยังหน้า index.php หรือหน้าแดชบอร์ด
        exit();
    } else {
        // รหัสผ่านไม่ถูกต้อง
        $_SESSION["Error"] = "Invalid username or password";
        header("Location: login.php?role=$role");
        exit();
    }
} else {
    // ไม่พบผู้ใช้ในฐานข้อมูล
    $_SESSION["Error"] = "Invalid username or password";
    header("Location: login.php?role=$role");
    exit();
}
?>
