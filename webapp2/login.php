<?php
session_start();

// ตรวจสอบว่า role ถูกส่งมาจาก query string หรือไม่
if (!isset($_GET['role']) || !in_array($_GET['role'], ['student', 'teacher', 'president', 'dean', 'staff'])) {
  // ถ้าไม่มีการส่ง role หรือ role ไม่ตรงกับที่กำหนดให้ redirect ไปยังหน้าเลือก role
  header("Location: role_selection.php"); 
  exit();
}

$role = $_GET['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login as <?php echo ucfirst($role); ?></title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<br>
<div class="container">
  <div class="row">
    <div class="col-md-3 badge bg-light text-dark">
    <br>
      <h5>Login as <?php echo ucfirst($role); ?></h5>
      <form method="POST" action="login_check.php">
        <input type="hidden" name="role" value="<?php echo $role; ?>"> <!-- ส่งค่า role ไปด้วย -->
        <input type="text" name="username" class="form-control" required placeholder="Username">
        <br>
        <input type="password" name="password" class="form-control" required placeholder="Password">
        <br>
        <?php
        // แสดงข้อความผิดพลาด หากมี
        if (isset($_SESSION["Error"])) {
            echo "<div class='text-danger'>";
            echo $_SESSION["Error"];
            echo "</div>";
            unset($_SESSION["Error"]); // ลบข้อความผิดพลาดหลังแสดงผล
        }
        ?>
        <input type="submit" name="submit" value="Login" class="btn btn-primary">
      </form>
    </div>
  </div>
  <br>
  <a href="register.php">Register</a>
  <!-- เพิ่มปุ่มไปยังหน้าเลือก role -->
  <a href="role_selection.php" style="margin-left: 150px;">Role Selection</a> <!-- ปุ่มใหม่ -->
</div>
</body>
</html>
