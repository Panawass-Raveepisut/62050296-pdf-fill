<?php
include 'condb.php';

// รับค่าตัวแปรจากฟอร์ม register
$name = mysqli_real_escape_string($conn, $_POST['firstname']);
$lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$email = mysqli_real_escape_string($conn, $_POST['email']);  // รับค่าอีเมล
$password = $_POST['password'];
$role = $_POST['role']; // รับค่า role จากฟอร์ม

// ตรวจสอบว่ามีข้อมูลในฟอร์มหรือไม่
if (empty($name) || empty($lastname) || empty($phone) || empty($username) || empty($email) || empty($password) || empty($role)) {
    die("Please fill in all fields.");
}

// ตรวจสอบว่า username หรือ email ซ้ำหรือไม่
$sql_check_username = "SELECT * FROM members_student WHERE username = '$username' OR email = '$email'";  // ตรวจสอบทั้ง username และ email
$result_check = mysqli_query($conn, $sql_check_username);

if (mysqli_num_rows($result_check) > 0) {
    die("Username or Email already exists.");
}

// เข้ารหัสรหัสผ่านด้วย password_hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);  // ใช้ password_hash

// เพิ่มข้อมูลลงตาราง members
$sql = "INSERT INTO members_student (name, lastname, telephone, username, email, password, role) 
VALUES ('$name', '$lastname', '$phone', '$username', '$email', '$hashed_password', '$role')";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<script> alert('บันทึกข้อมูลสำเร็จ'); </script>";
    echo "<script> window.location='register.php' </script>";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    echo "<script> alert('บันทึกข้อมูลไม่สำเร็จ'); </script>";
}

mysqli_close($conn);
?>
