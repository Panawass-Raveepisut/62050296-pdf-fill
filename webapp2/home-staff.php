<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] != "staff") {
    header("Location: login.php"); // ถ้าไม่มี session หรือไม่ใช่ staff ให้ไปหน้า login
    exit();
}

// เชื่อมต่อฐานข้อมูล
$servername = "localhost"; // ชื่อเซิร์ฟเวอร์
$username = "root"; // ชื่อผู้ใช้ฐานข้อมูล
$password = ""; // รหัสผ่านฐานข้อมูล (ถ้ามี)
$dbname = "db_members_student"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลจากตาราง re05_collected_data
$sql = "SELECT form_id, date, time, request_issue, name FROM re05_collected_data";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Home</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <br>
        <div class="alert alert-info h4" role="alert">
            รายการคำร้องที่ได้รับเข้ามา
        </div>
        <p>สวัสดี, <?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"]; ?>!</p>
        <br>

        <!-- ตารางแสดงรายการสถานะคำร้อง -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Form</th>
                    <th>วันที่ได้รับ</th>
                    <th>เวลา</th>
                    <th>เรื่องคำร้อง</th>
                    <th>ผู้ส่ง</th>
                    <th colspan="3">ผลการอนุมัติ</th>
                    <th>สถานะคำร้อง</th>
                    <th>ส่งแบบคำร้องให้พิจารณา</th>
                    <th>ผู้รับ</th>
                </tr>
                <tr>
                    <th colspan="5"></th>
                    <th>อาจารย์ที่ปรึกษา</th>
                    <th>ประธานหลักสูตร</th>
                    <th>คณบดี</th>
                    <th colspan="3"></th>
                </tr>
            </thead>
            <tbody id="formStatusTable">
                <?php
                // ตรวจสอบผลลัพธ์จากการดึงข้อมูล
                if ($result->num_rows > 0) {
                    // ข้อมูลแต่ละแถว
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["form_id"] . "</td>";
                        echo "<td>" . date("d/m/Y", strtotime($row["date"])) . "</td>"; // แสดงวันที่ในรูปแบบที่ต้องการ
                        echo "<td>" . $row["time"] . "</td>";
                        echo "<td>" . $row["request_issue"] . "</td>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["advisor_status"] . "</td>";
                        echo "<td>" . $row["program_chair_status"] . "</td>";
                        echo "<td>" . $row["dean_status"] . "</td>";
                        echo "<td>" . $row["form_status"] . "</td>";
                        echo "<td><a href='select_reviewer.php?form_id=" . $row["form_id"] . "' class='text-primary'>คลิกเพื่อเลือกผู้รับ</a></td>";
                        echo "<td>-</td>"; // สามารถปรับตามความต้องการ
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>ไม่มีข้อมูลคำร้องที่ได้รับ</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <br>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <script src="home_staff.js"></script>
</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
