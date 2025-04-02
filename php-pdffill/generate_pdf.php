<?php
// ใช้ autoload ของ Composer
require_once 'vendor/autoload.php';

use setasign\Fpdi\Fpdi;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
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
    $signature = $_POST['signature']; // ลายเซ็น Base64

    // โหลด PDF ที่เป็นแบบฟอร์ม
    $pdf = new Fpdi();
    $pdf->AddPage();
    $pdf->setSourceFile('pdfforms/RE05.pdf');
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx, 0, 0, 210);

    // กรอกข้อมูลลงในฟอร์ม
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetXY(50, 40);
    $pdf->Write(0, $fullname);

    // ป้อนข้อมูลเพิ่มเติมในฟอร์มที่ต้องการ
    // ทำแบบนี้ต่อไปจนถึงข้อมูลทุกอย่างที่ต้องการ

    // บันทึกลายเซ็น
    $img = base64_decode($signature);
    file_put_contents('signature.png', $img);
    $pdf->Image('signature.png', 100, 100, 40);

    // ส่ง PDF ออกมา
    $pdf->Output('D', 'generated_form.pdf');
}
?>
