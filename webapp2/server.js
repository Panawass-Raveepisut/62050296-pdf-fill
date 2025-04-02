// const express = require('express');
// const path = require('path');
// const bodyParser = require('body-parser');
// const { PDFDocument } = require('pdf-lib');
// const fs = require('fs');
// const uuid = require('uuid');
// const fontkit = require('@pdf-lib/fontkit');
// const app = express();
// const PORT = 3001;

// app.use(bodyParser.urlencoded({ extended: true }));
// app.use(bodyParser.json());

// app.get('/', (req, res) => {
//     res.send('Welcome to the server');
// });

// app.get('/fill-re05', (req, res) => {
//     res.sendFile(path.join(__dirname, 'fill-re05.html'));
// });

// app.use('/generated', express.static(path.join(__dirname, 'generated')));

// app.post('/submit-re05', async (req, res) => {
//     const { name, studentId, faculty, phone, major, reason, courseCode, section, courseName, credits, instructor, studentSignature, date } = req.body;

//     try {
//         if (!name || !studentId || !faculty || !phone || !major || !reason || !courseCode || !section || !courseName || !credits || !instructor || !studentSignature || !date) {
//             return res.status(400).send('กรุณากรอกข้อมูลทั้งหมด');
//         }

//         const pdfPath = path.join(__dirname, 'pdfforms', 'RE05.pdf');
//         const fontPath = 'C:\\Users\\panaw\\AppData\\Local\\Microsoft\\Windows\\Fonts\\THSarabunNew.ttf';

//         if (!fs.existsSync(pdfPath)) throw new Error('ไม่พบไฟล์ PDF template (RE05.pdf)');
//         if (!fs.existsSync(fontPath)) throw new Error('ไม่พบไฟล์ฟอนต์ (THSarabunNew.ttf)');

//         const existingPdfBytes = fs.readFileSync(pdfPath);
//         const pdfDoc = await PDFDocument.load(existingPdfBytes);

//         pdfDoc.registerFontkit(fontkit);
//         const fontBytes = fs.readFileSync(fontPath);
//         const customFont = await pdfDoc.embedFont(fontBytes);

//         const page = pdfDoc.getPages()[0];
//         const fontSize = 12;

//         const formattedCourseCode = courseCode.split('').join(' ');  // เพิ่มช่องว่างระหว่างหลัก
//         page.drawText(name, { x: 100, y: 700, size: fontSize, font: customFont });
//         page.drawText(studentId, { x: 100, y: 670, size: fontSize, font: customFont });
//         page.drawText(faculty, { x: 100, y: 640, size: fontSize, font: customFont });
//         page.drawText(phone, { x: 100, y: 610, size: fontSize, font: customFont });
//         page.drawText(major, { x: 100, y: 580, size: fontSize, font: customFont });
//         page.drawText(reason, { x: 100, y: 550, size: fontSize, font: customFont });
//         page.drawText(formattedCourseCode, { x: 100, y: 520, size: fontSize, font: customFont });
//         page.drawText(section, { x: 100, y: 490, size: fontSize, font: customFont });
//         page.drawText(courseName, { x: 100, y: 460, size: fontSize, font: customFont });
//         page.drawText(credits, { x: 100, y: 430, size: fontSize, font: customFont });
//         page.drawText(instructor, { x: 100, y: 400, size: fontSize, font: customFont });
//         page.drawText(date, { x: 100, y: 730, size: fontSize, font: customFont });

//         if (studentSignature) {
//             if (typeof studentSignature === 'string' && studentSignature.startsWith('data:image/png;base64,')) {
//                 const signatureImageBytes = Buffer.from(studentSignature.split(',')[1], 'base64');
//                 const signatureImage = await pdfDoc.embedPng(signatureImageBytes);
//                 const signatureDims = signatureImage.scale(0.5);
//                 page.drawImage(signatureImage, { x: 100, y: 250, width: signatureDims.width, height: signatureDims.height });
//             } else {
//                 throw new Error('ลายเซ็นนิสิตไม่ถูกต้อง');
//             }
//         } else {
//             throw new Error('ไม่พบลายเซ็นของนิสิต');
//         }

//         const generatedPdfPath = path.join(__dirname, 'generated', `${uuid.v4()}.pdf`);
//         const pdfBytes = await pdfDoc.save();
//         fs.writeFileSync(generatedPdfPath, pdfBytes);

//         const pdfUrl = `/generated/${path.basename(generatedPdfPath)}`;
//         res.send(pdfUrl);
//     } catch (error) {
//         console.error('Error generating PDF:', error);
//         res.status(500).send(`ไม่สามารถสร้าง PDF ได้: ${error.message}`);
//     }
// });

// app.listen(PORT, () => {
//     console.log(`Server is running at http://localhost:${PORT}`);
// });


const express = require('express');
const path = require('path');
const bodyParser = require('body-parser');
const { PDFDocument } = require('pdf-lib');
const fs = require('fs');
const uuid = require('uuid');
const fontkit = require('@pdf-lib/fontkit');
const mysql = require('mysql');  // ใช้ MySQL
const app = express();
const PORT = 3001;
// ตั้งค่า body parser
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());
// ตั้งค่า MySQL database connection
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'db_members_student', // ชื่อฐานข้อมูล
});
db.connect((err) => {
    if (err) throw err;
    console.log('Connected to MySQL database');
});
// หน้า home หลัก
app.get('/', (req, res) => {
    res.send('Welcome to the server');
});
// ส่งไฟล์ HTML สำหรับกรอกฟอร์ม RE05
app.get('/fill-re05', (req, res) => {
    res.sendFile(path.join(__dirname, 'fill-re05.html'));
});
// กำหนดให้ไฟล์ในโฟลเดอร์ generated สามารถเข้าถึงได้
app.use('/generated', express.static(path.join(__dirname, 'generated')));
// ฟังก์ชันสำหรับรับข้อมูลจากฟอร์ม RE05 และสร้าง PDF
app.post('/submit-re05', async (req, res) => {
    const { name, studentId, faculty, phone, major, reason, courseCode, section, courseName, credits, instructor, studentSignature, date } = req.body;
    try {
        // ตรวจสอบว่าได้กรอกข้อมูลทั้งหมดหรือไม่
        if (!name || !studentId || !faculty || !phone || !major || !reason || !courseCode || !section || !courseName || !credits || !instructor || !studentSignature || !date) {
            return res.status(400).send('กรุณากรอกข้อมูลทั้งหมด');
        }
        // ตั้งค่าเส้นทางของไฟล์ PDF template และฟอนต์
        const pdfPath = path.join(__dirname, 'pdfforms', 'RE05.pdf');
        const fontPath = 'C:\\Users\\panaw\\AppData\\Local\\Microsoft\\Windows\\Fonts\\THSarabunNew.ttf';
        // ตรวจสอบว่าไฟล์ PDF และฟอนต์มีอยู่หรือไม่
        if (!fs.existsSync(pdfPath)) throw new Error('ไม่พบไฟล์ PDF template (RE05.pdf)');
        if (!fs.existsSync(fontPath)) throw new Error('ไม่พบไฟล์ฟอนต์ (THSarabunNew.ttf)');
        // โหลดไฟล์ PDF template
        const existingPdfBytes = fs.readFileSync(pdfPath);
        const pdfDoc = await PDFDocument.load(existingPdfBytes);
        pdfDoc.registerFontkit(fontkit);
        // โหลดฟอนต์และฝังลงใน PDF
        const fontBytes = fs.readFileSync(fontPath);
        const customFont = await pdfDoc.embedFont(fontBytes);
        const page = pdfDoc.getPages()[0];
        const fontSize = 12;
        // กำหนดตำแหน่งและขนาดฟอนต์สำหรับข้อมูลต่าง ๆ
        page.drawText(name, { x: 100, y: 700, size: fontSize, font: customFont });
        page.drawText(studentId, { x: 100, y: 670, size: fontSize, font: customFont });
        page.drawText(faculty, { x: 100, y: 640, size: fontSize, font: customFont });
        page.drawText(phone, { x: 100, y: 610, size: fontSize, font: customFont });
        page.drawText(major, { x: 100, y: 580, size: fontSize, font: customFont });
        page.drawText(reason, { x: 100, y: 550, size: fontSize, font: customFont });
        page.drawText(courseCode.split('').join(' '), { x: 100, y: 520, size: fontSize, font: customFont });
        page.drawText(section, { x: 100, y: 490, size: fontSize, font: customFont });
        page.drawText(courseName, { x: 100, y: 460, size: fontSize, font: customFont });
        page.drawText(credits, { x: 100, y: 430, size: fontSize, font: customFont });
        page.drawText(instructor, { x: 100, y: 400, size: fontSize, font: customFont });
        page.drawText(date, { x: 100, y: 730, size: fontSize, font: customFont });
        // การวางลายเซ็น
        if (studentSignature && typeof studentSignature === 'string' && studentSignature.startsWith('data:image/png;base64,')) {
            const signatureImageBytes = Buffer.from(studentSignature.split(',')[1], 'base64');
            const signatureImage = await pdfDoc.embedPng(signatureImageBytes);
            const signatureDims = signatureImage.scale(0.5);
            page.drawImage(signatureImage, { x: 100, y: 250, width: signatureDims.width, height: signatureDims.height });
        } else {
            throw new Error('ลายเซ็นนิสิตไม่ถูกต้อง');
        }
        // บันทึก PDF ที่สร้างเสร็จแล้ว
        const generatedPdfPath = path.join(__dirname, 'generated', `${uuid.v4()}.pdf`);
        const pdfBytes = await pdfDoc.save();
        fs.writeFileSync(generatedPdfPath, pdfBytes);
        // บันทึกข้อมูลคำร้องลงในฐานข้อมูล
        const formData = {
            name,
            student_id: studentId,
            faculty,
            phone,
            major,
            reason,
            course_code: courseCode,
            section,
            course_name: courseName,
            credits,
            instructor,
            status: 'waiting_for_review', // สถานะเริ่มต้น
            form_type: 'RE05',
            submitted_by: name,
            created_at: new Date()
        };
        const query = 'INSERT INTO re05_collected_data SET ?';
        db.query(query, formData, (err, result) => {
            if (err) {
                console.error('Error inserting form into database:', err);
                return res.status(500).send('ไม่สามารถบันทึกข้อมูลคำร้องลงในฐานข้อมูลได้');
            }
            console.log('Form data inserted into database');
            // ส่งลิงก์ไปยังไฟล์ PDF ที่สร้างขึ้น
            const pdfUrl = `/generated/${path.basename(generatedPdfPath)}`;
            res.send(pdfUrl);
        });
    } catch (error) {
        console.error('Error generating PDF:', error);
        res.status(500).send(`ไม่สามารถสร้าง PDF ได้: ${error.message}`);
    }
});
// ฟังก์ชันดึงข้อมูลคำร้องทั้งหมดที่รอการพิจารณา
app.get('/get-forms', (req, res) => {
    const query = 'SELECT * FROM forms WHERE status = "waiting_for_review"';
    db.query(query, (err, results) => {
        if (err) {
            console.error('Error fetching forms:', err);
            return res.status(500).send('ไม่สามารถดึงข้อมูลคำร้องได้');
        }
        res.json(results);
    });
});
// เริ่มเซิร์ฟเวอร์ที่พอร์ต 3001
app.listen(PORT, () => {
    console.log(`Server is running at http://localhost:${PORT}`);
});

