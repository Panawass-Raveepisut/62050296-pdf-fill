<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
</head>
<body>
<br>
<div class="container">
  <div class="row">
    <div class="col-md-3 badge bg-light text-dark">
    <br>
      <h5>Login</h5>
      <form method="POST" action="login_check.php">
        <input type="text" name="username" class="form-control" required placeholder="Username">
        <br>
        <input type="password" name="password" class="form-control" required placeholder="Password">
        <br>
        <?php
        if (isset(($_SESSION["Error"]))){
            echo "<div class='text-danger'>";
            echo $_SESSION["Error"];
            echo "</div>";
        }
        ?>
        <input type="submit" name="submit" value="Login" class="btn btn-primary">
      </form>
    </div>
</div>
<br>
<a href="register.php">Register</a>
</body>
</html>