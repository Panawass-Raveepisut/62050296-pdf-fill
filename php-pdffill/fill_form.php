<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $date = $_POST['date'];
    $fullname = $_POST['fullname'];
    $studentId = $_POST['studentId'];
    $faculty = $_POST['faculty'];
    $phone = $_POST['phone'];
    $major = $_POST['major'];
    $reason = $_POST['reason'];
    $courseCode = $_POST['courseCode'];
    $section = $_POST['section'];
    $subjectName = $_POST['subjectName'];
    $credits = $_POST['credits'];
    $instructor = $_POST['instructor'];
    $signature = $_POST['signature']; // ลายเซ็นในรูปแบบ Base64

    // คุณสามารถนำข้อมูลเหล่านี้ไปใช้งานต่อ เช่น บันทึกในฐานข้อมูล หรือส่งอีเมล์
    // ตัวอย่างการแสดงผลข้อมูล
    echo "<h2>ข้อมูลที่กรอก:</h2>";
    echo "<p>วันที่: $date</p>";
    echo "<p>ชื่อ: $fullname</p>";
    echo "<p>รหัสประจำตัว: $studentId</p>";
    echo "<p>คณะ: $faculty</p>";
    echo "<p>เบอร์โทรศัพท์: $phone</p>";
    echo "<p>สาขาวิชา: $major</p>";
    echo "<p>เหตุผลในการลงทะเบียนล่าช้า: $reason</p>";
    echo "<p>รหัสวิชา: $courseCode</p>";
    echo "<p>กลุ่มเรียน: $section</p>";
    echo "<p>ชื่อวิชา: $subjectName</p>";
    echo "<p>หน่วยกิต: $credits</p>";
    echo "<p>ชื่อผู้สอน: $instructor</p>";
    echo "<p>ลายเซ็นนิสิต: <img src='$signature' alt='ลายเซ็น'></p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fill RE05 Form</title>
  <style>
    #signatureModal {
      display: none; 
      position: fixed; 
      z-index: 1000; 
      left: 0; 
      top: 0; 
      width: 100%; 
      height: 100%; 
      background-color: rgba(0,0,0,0.5); 
      justify-content: center; 
      align-items: center; 
    }

    #signaturePad {
      border: 1px solid #ccc;
      width: 400px;
      height: 200px;
      background: white;
    }

    .modal-content {
      background-color: #fff; 
      padding: 20px; 
      border-radius: 10px;
    }
  </style>
</head>
<body>
  <h1>Fill RE05 Form</h1>
  <form id="form" method="POST" action="generate_pdf.php">
    <!-- วัน เดือน ปี -->
    <label>วันที่:
      <select id="day" name="day" required>
        <option value="">วัน</option>
      </select>
      <select id="month" name="month" required>
        <option value="">เดือน</option>
      </select>
      <select id="year" name="year" required>
        <option value="">ปี</option>
      </select>
      <input type="hidden" id="formattedDate" name="date">
    </label><br>

    <label>ชื่อ: <input type="text" name="fullname" required></label><br>
    <label>รหัสประจำตัว: <input type="number" name="studentId" required></label><br>
    <label>คณะ: <input type="text" name="faculty" required></label><br>
    <label>เบอร์โทรศัพท์: <input type="text" name="phone" required></label><br>
    <label>สาขาวิชา: <input type="text" name="major" required></label><br>
    <label>เหตุผลในการลงทะเบียนล่าช้า: <textarea name="reason" required></textarea></label><br><br>

    <!-- ข้อมูลการลงทะเบียน -->
    <label>รหัสวิชา: <input type="text" name="courseCode" required></label><br>
    <label>กลุ่มเรียน: <input type="text" name="section" required></label><br>
    <label>ชื่อวิชา (ภาษาอังกฤษ): <input type="text" name="subjectName" required></label><br>
    <label>หน่วยกิต: <input type="number" name="credits" required></label><br>
    <label>ชื่อผู้สอน: <input type="text" name="instructor" required></label><br>

    <!-- ลายเซ็นนิสิต -->
    <label>ลายเซ็นนิสิต (จำเป็นต้องกรอก):</label><br>
    <button type="button" id="openSignatureModal">เซ็นที่นี่</button><br><br>

    <button type="submit">Submit</button>
  </form>

  <!-- Modal สำหรับลายเซ็น -->
  <div id="signatureModal">
    <div class="modal-content">
      <h2>ลายเซ็น</h2>
      <canvas id="signaturePad"></canvas><br>
      <button type="button" id="clearSignature">ล้างลายเซ็น</button><br>
      <button type="button" id="closeSignatureModal">เสร็จสิ้น</button>
    </div>
  </div>

  <!-- สคริปต์ -->
  <script>
    // สร้างตัวเลือกวัน
    const daySelect = document.getElementById('day');
    for (let i = 1; i <= 31; i++) {
      const option = document.createElement('option');
      option.value = i.toString().padStart(2, '0');
      option.textContent = i;
      daySelect.appendChild(option);
    }

    // สร้างตัวเลือกเดือน
    const monthSelect = document.getElementById('month');
    for (let i = 1; i <= 12; i++) {
      const option = document.createElement('option');
      option.value = i.toString().padStart(2, '0');
      option.textContent = i;
      monthSelect.appendChild(option);
    }

    // สร้างตัวเลือกปี
    const yearSelect = document.getElementById('year');
    const currentYear = new Date().getFullYear();
    for (let i = currentYear; i >= currentYear - 100; i--) {
      const option = document.createElement('option');
      option.value = i;
      option.textContent = i;
      yearSelect.appendChild(option);
    }

    // การจัดการลายเซ็น
    const canvas = document.getElementById('signaturePad');
    const ctx = canvas.getContext('2d');
    let drawing = false;

    canvas.addEventListener('mousedown', () => { drawing = true; });
    canvas.addEventListener('mouseup', () => { drawing = false; ctx.beginPath(); });
    canvas.addEventListener('mousemove', draw);
    
    document.getElementById('clearSignature').addEventListener('click', clearSignature);

    function draw(event) {
      if (!drawing) return;
      ctx.lineWidth = 2;
      ctx.lineCap = 'round';
      ctx.strokeStyle = 'black';

      ctx.lineTo(event.clientX - canvas.offsetLeft, event.clientY - canvas.offsetTop);
      ctx.stroke();
      ctx.beginPath();
      ctx.moveTo(event.clientX - canvas.offsetLeft, event.clientY - canvas.offsetTop);
    }

    function clearSignature() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    // เปิด modal เพื่อให้เซ็น
    document.getElementById('openSignatureModal').addEventListener('click', () => {
      document.getElementById('signatureModal').style.display = 'flex';
    });

    // ปิด modal และบันทึกลายเซ็น
    document.getElementById('closeSignatureModal').addEventListener('click', () => {
      const signatureDataUrl = canvas.toDataURL();
      const hiddenSignatureInput = document.createElement('input');
      hiddenSignatureInput.type = 'hidden';
      hiddenSignatureInput.name = 'signature';
      hiddenSignatureInput.value = signatureDataUrl;
      document.getElementById('form').appendChild(hiddenSignatureInput);

      document.getElementById('signatureModal').style.display = 'none';
    });

    // เมื่อกด submit จะรวมวันที่เป็นฟอร์แมต dd-mm-yyyy และเก็บใน input ที่ซ่อนอยู่
    document.getElementById('form').addEventListener('submit', (event) => {
      const date = `${document.getElementById('day').value}-${document.getElementById('month').value}-${document.getElementById('year').value}`;
      document.getElementById('formattedDate').value = date;
    });
  </script>
</body>
</html>
