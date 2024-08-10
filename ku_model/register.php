<!DOCTYPE html>
<html lang="th">
<head>
    <!-- ส่วนนี้ใช้สำหรับกำหนดข้อมูลพื้นฐานของหน้าเว็บ -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- เชื่อมต่อไฟล์ CSS สำหรับการตกแต่งหน้าเว็บ -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- ส่วนหัวของหน้าเว็บ (หัวเรื่อง) -->
    <h2>Register</h2>

    <!-- ฟอร์มสำหรับกรอกข้อมูลเพื่อสมัครสมาชิก -->
    <form action="register_process.php" method="post">
        <!-- ป้ายกำกับและช่องกรอกข้อมูลสำหรับรหัสสมาชิก -->
        <label for="member_id">รหัสสมาชิก:</label>
        <input type="text" id="member_id" name="member_id" required><br><br>
        
        <!-- ป้ายกำกับและช่องกรอกข้อมูลสำหรับคำนำหน้า -->
        <label for="prefix">คำนำหน้า:</label>
        <input type="text" id="prefix" name="prefix" required><br><br>
        
        <!-- ป้ายกำกับและช่องกรอกข้อมูลสำหรับชื่อ -->
        <label for="firstname">ชื่อ:</label>
        <input type="text" id="firstname" name="firstname" required><br><br>
        
        <!-- ป้ายกำกับและช่องกรอกข้อมูลสำหรับนามสกุล -->
        <label for="lastname">นามสกุล:</label>
        <input type="text" id="lastname" name="lastname" required><br><br>
        
        <!-- ป้ายกำกับและช่องกรอกข้อมูลสำหรับเบอร์โทร -->
        <label for="phone">เบอร์โทร:</label>
        <input type="text" id="phone" name="phone" required><br><br>
        
        <!-- ป้ายกำกับและช่องกรอกข้อมูลสำหรับ E-mail -->
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <!-- ป้ายกำกับและช่องกรอกข้อมูลสำหรับรหัสผ่าน -->
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <!-- ปุ่มสำหรับยืนยันการสมัครสมาชิก -->
        <button type="submit">Register</button>
    </form>

    <!-- ลิงก์ไปยังหน้าเข้าสู่ระบบในกรณีที่ผู้ใช้มีบัญชีแล้ว -->
    <p>เป็นสมาชิกอยู่แล้ว? <a href="loginm.php">เข้าสู่ระบบที่นี่</a></p>
</body>
</html>
