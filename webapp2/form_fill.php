<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] != "student") {
    header("Location: login.php");
    exit();
}

$form = isset($_GET['form']) ? $_GET['form'] : '';

// ตรวจสอบว่าได้เลือกฟอร์มที่ถูกต้อง
$form_names = ['RE01', 'RE05', 'RE10', 'RE11', 'RE24'];
if (!in_array($form, $form_names)) {
    echo "Invalid form.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fill Form: <?php echo $form; ?></title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <style>
        #pdf-canvas {
            border: 1px solid #ddd;
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h4>กรุณากรอกข้อมูลในฟอร์ม <?php echo $form; ?></h4>
        
        <!-- ตัวอย่างฟอร์ม -->
        <h5>ตัวอย่างฟอร์ม:</h5>
        <canvas id="pdf-canvas"></canvas>
        
        <!-- สคริปต์แสดง PDF ด้วย PDF.js -->
        <script>
            const form = "<?php echo $form; ?>"; // รับค่าฟอร์มจาก PHP
            const pdfPath = "pdfforms/" + form + ".pdf"; // สร้างเส้นทางของไฟล์ PDF

            const canvas = document.getElementById("pdf-canvas");
            const context = canvas.getContext("2d");

            // โหลด PDF ด้วย PDF.js
            pdfjsLib.getDocument(pdfPath).promise.then(function(pdf) {
                // รับหน้าแรกของ PDF
                pdf.getPage(1).then(function(page) {
                    const scale = 1.5;
                    const viewport = page.getViewport({ scale: scale });

                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // เรียกใช้ render เพื่อแสดง PDF ใน canvas
                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    page.render(renderContext);
                });
            });
        </script>

        <br>
        <!-- ฟอร์มกรอกข้อมูล -->
        <?php
        // ปุ่ม "ถัดไป" จะนำไปยังหน้าแตกต่างกันตามฟอร์ม
        if ($form == 'RE05') {
            // ถ้าเป็น RE05 นำไปหน้า fill-re05.html
            echo '<form action="fill-re05.html" method="get">';
            echo '<input type="submit" class="btn btn-primary" value="ถัดไป">';
            echo '</form>';
        } else {
            // สำหรับฟอร์มอื่น ๆ ที่ไม่ใช่ RE05 จะไปหน้าเปล่า
            echo '<form action="empty_page.php" method="get">';
            echo '<input type="submit" class="btn btn-primary" value="ถัดไป">';
            echo '</form>';
        }
        ?>

        <br>
        <!-- ปุ่มย้อนกลับไปยังหน้า home-student.php -->
        <a href="home-student.php" class="btn btn-secondary">ย้อนกลับ</a>
    </div>
</body>
</html>
