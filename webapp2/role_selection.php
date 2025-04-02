<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <br>
        <div class="alert alert-primary h4" role="alert">
            Please select your role
        </div>
        <div class="row">
            <div class="col-md-4">
                <a href="login.php?role=student" class="btn btn-primary btn-block">นิสิต</a>
            </div>
            <div class="col-md-4">
                <a href="login.php?role=teacher" class="btn btn-primary btn-block">อาจารย์ที่ปรึกษา</a>
            </div>
            <div class="col-md-4">
                <a href="login.php?role=president" class="btn btn-primary btn-block">ประธานหลักสูตร</a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-4">
                <a href="login.php?role=dean" class="btn btn-primary btn-block">คณบดี</a>
            </div>
            <div class="col-md-4">
                <a href="login.php?role=staff" class="btn btn-primary btn-block">เจ้าหน้าที่ฝ่ายวิชาการ</a>
            </div>
        </div>
    </div>
</body>
</html>
