<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] != "student") {
    header("Location: login.php"); // ถ้าไม่มี session หรือไม่ใช่ student ให้ไปหน้า login
    exit();
}

$student_id = $_SESSION['student_id']; // สมมุติว่าเราใช้ session ในการเก็บรหัสนักศึกษา
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_members_student";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// คำสั่ง SQL ดึงข้อมูลคำร้องที่เกี่ยวข้องกับนักศึกษา
$sql = "SELECT * FROM re05_collected_data WHERE student_id = '$student_id' ORDER BY submission_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบสถานะคำร้อง</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <br>
        <h2>ตรวจสอบสถานะคำร้อง</h2>
        <br>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ลำดับรายการ</th>
                    <th>วันที่ส่ง</th>
                    <th>เวลา</th>
                    <th>เรื่องคำร้อง</th>
                    <th>ID Form</th>
                    <th>สถานะ</th>
                    <th>ผลการอนุมัติ</th>
                    <th>ดาวน์โหลดคำร้อง</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $i = 1;
                    while($row = $result->fetch_assoc()) {
                        $status = 'ได้รับคำร้อง'; // สถานะเริ่มต้น
                        $approval_status = 'ยังไม่ได้อนุมัติ';
                        $can_download = false;

                        // สมมุติว่าในฐานข้อมูลมีคอลัมน์ status, approval_teacher, approval_program_chair, approval_dean
                        if ($row['approval_teacher'] == 'approved' && $row['approval_program_chair'] == 'approved' && $row['approval_dean'] == 'approved') {
                            $status = 'ได้รับการอนุมัติครบ';
                            $can_download = true;
                            $approval_status = 'อนุมัติครบทั้ง 3 ท่าน';
                        } else {
                            $approval_status = 'ยังไม่ครบ';
                        }
                        ?>

                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $row['submission_date']; ?></td>
                            <td><?php echo $row['submission_time']; ?></td>
                            <td><?php echo $row['reason']; ?></td>
                            <td><?php echo $row['form_id']; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $approval_status; ?></td>
                            <td>
                                <?php
                                if ($can_download) {
                                    echo '<a href="download.php?file=' . $row['file_path'] . '" class="btn btn-success">ดาวน์โหลดคำร้อง</a>';
                                } else {
                                    echo 'ยังไม่สามารถดาวน์โหลดได้';
                                }
                                ?>
                            </td>
                        </tr>

                        <?php
                    }
                } else {
                    echo "<tr><td colspan='8'>ไม่มีคำร้องที่เกี่ยวข้อง</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>

        <br>
        <a href="home-student.php" class="btn btn-primary">กลับสู่หน้าแรก</a>
    </div>
</body>
</html>
