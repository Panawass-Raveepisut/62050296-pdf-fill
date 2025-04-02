<?php
include 'condb.php';
//รับค่าตัวแปรจาก register
$name = $_POST['firstname'];
$lastname = $_POST['lastname'];
$phone = $_POST['phone'];
$username = $_POST['username'];
$password = $_POST['password'];

//เข้ารหัสด้วย sha512 กับ password
$password=hash('sha512',$password);

//เพิ่มข้อมูลลงตาราง members
$sql ="INSERT INTO members(name, lastname, telephone, username, password) 
Values('$name','$lastname','$phone','$username','$password')";
$result = mysqli_query($conn,$sql);
if($result){
    echo "<script> alert('บันทึกข้อมูลสำเร็จ'); </script>";
    echo "<script> window.location='register.php' </script>";
} else{
    echo "Error:" . $sql  . "<br>" . mysqli_connect_error();
    echo "<script> alert('บันทึกข้อมูลไม่สำเร็จ'); </script>";
}
mysqli_close($conn);
?>