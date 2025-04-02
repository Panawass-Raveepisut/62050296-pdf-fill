<?php
include 'condb.php';
session_start();
$username = $_POST['username'];
$password = $_POST['password'];
//เข้ารหัสด้วย sha512 กับ password
$password=hash('sha512',$password);

$sql="SELECT * FROM members WHERE username='$username' and password='$password'";
$result=mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result);

if($row > 0){
    $_SESSION["username"]=$row['username'];
    $_SESSION["password"]=$row['password'];
    $_SESSION["firstname"]=$row['name'];
    $_SESSION["lastname"]=$row['lastname'];
    $show=header("location:index.php");
} else {
    $_SESSION["Error"] = "Invalid username or password </p>";
    $show=header("location:login.php");
}
echo $show;
?>