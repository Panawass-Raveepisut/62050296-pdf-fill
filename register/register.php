<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <br>
        <div class="row">
            <div class="col-md-6 bg-light text-dark">
        <div class="alert alert-primary h4" role="alert">
        สมัครสมาชิก
        </div>
        <form method="POST" action="insert_register.php">
        ชื่อ <input type="text" name="firstname" class="form-control" required>
        นามสกุล <input type="text" name="lastname" class="form-control" required>
        เบอร์โทรศัพท์ <input type="number" name="phone" class="form-control" required>
        Username <input type="text" name="username" maxlength="10" class="form-control" required>
        Password <input type="password" name="password" maxlength="10" class="form-control" required> <br>
        <input type="submit" name="submit" value="บันทึก" class="btn btn-primary">
        <input type="reset" name="cancel" value="ยกเลิก" class="btn btn-danger">
        <br>
        <a href="login.php"> Login </a>
        </form>
        </div>
        </div>
    </div>
</body>
</html>