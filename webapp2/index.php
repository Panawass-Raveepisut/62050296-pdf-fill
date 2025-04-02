<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("location:login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <?php
    // ตรวจสอบ role ของผู้ใช้
    if (isset($_SESSION["role"])) {
        $role = $_SESSION["role"];
        $redirect_url = '';

        // กำหนด URL สำหรับการ redirect ตาม role ของผู้ใช้
        if ($role == "student") {
            $redirect_url = "home-student.php";
        } elseif ($role == "teacher") {
            $redirect_url = "home-teacher.php";
        } elseif ($role == "president") {
            $redirect_url = "home-president.php";
        } elseif ($role == "dean") {
            $redirect_url = "home-dean.php";
        } elseif ($role == "staff") {
            $redirect_url = "home-staff.php";
        }

        // ใช้ meta refresh สำหรับการ redirect หลังจาก 5 วินาที
        echo "<meta http-equiv='refresh' content='5;url=$redirect_url'>";
    }
    ?>
</head>
<body>
    <div class="container">
        <br>
        <div class="alert alert-primary h4" role="alert">
            Welcome
        </div>
        <?php
            if (isset($_SESSION["firstname"]) && isset($_SESSION["lastname"])) {
                echo "<div class='text-success'>";
                echo "Name: " . $_SESSION["firstname"] . " " . $_SESSION["lastname"];
                echo "</div>";
            }
            if (isset($_SESSION["role"])) {
                echo "<div class='text-info'>";
                echo "Role: " . ucfirst($_SESSION["role"]); // แสดง role ของผู้ใช้ และเปลี่ยนให้ตัวอักษรตัวแรกเป็นตัวใหญ่
                echo "</div>";
            }
        ?>
        <br>
        <div class="alert alert-info">
            คุณจะถูกเปลี่ยนเส้นทางภายใน 5 วินาที...
        </div>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
    <br>
</body>
</html>
