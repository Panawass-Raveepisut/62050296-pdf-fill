<!DOCTYPE html>
<html lang="en">
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
        
        <!-- แสดงตารางคำร้อง -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Form</th>
                    <th>วันที่ได้รับ</th>
                    <th>เวลา</th>
                    <th>เรื่องคำร้อง</th>
                    <th>ผู้ส่ง</th>
                    <th>ผลการอนุมัติ (อาจารย์ที่ปรึกษา)</th>
                    <th>ผลการอนุมัติ (ประธานหลักสูตร)</th>
                    <th>ผลการอนุมัติ (คณบดี)</th>
                    <th>สถานะคำร้อง</th>
                    <th>ส่งแบบคำร้องให้พิจารณา</th>
                    <th>ผู้รับ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // ดึงข้อมูลคำร้องจากฐานข้อมูล
                $sql = "SELECT * FROM forms"; // ตัวอย่างการดึงข้อมูลจากตาราง forms
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["form_id"] . "</td>";
                        echo "<td>" . date("d/m/Y", strtotime($row["received_date"])) . "</td>";
                        echo "<td>" . date("H:i", strtotime($row["received_date"])) . "</td>";
                        echo "<td>" . $row["form_topic"] . "</td>";
                        echo "<td>" . $row["submitted_by"] . "</td>";
                        
                        // แสดงผลการอนุมัติ
                        echo "<td>" . ($row["advisor_approval"] ?? "รอการพิจารณา") . "</td>";
                        echo "<td>" . ($row["chair_approval"] ?? "รอการพิจารณา") . "</td>";
                        echo "<td>" . ($row["dean_approval"] ?? "รอการพิจารณา") . "</td>";
                        
                        // สถานะคำร้อง
                        echo "<td>" . ($row["status"] ?? "ยังไม่ส่งให้พิจารณา") . "</td>";
                        
                        // ปุ่มส่งแบบคำร้องให้พิจารณา
                        echo "<td><a href='select_reviewer.php?form_id=" . $row["form_id"] . "' class='btn btn-primary'>คลิกเพื่อเลือกผู้รับ</a></td>";
                        
                        // แสดงผู้รับปัจจุบัน
                        echo "<td>" . ($row["current_reviewer"] ?? "-") . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>ไม่มีคำร้องเข้ามา</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <br>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>

<?php $conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล ?>
