<!DOCTYPE html>
<html lang="th">
<head>
    <!-- ส่วนนี้ใช้สำหรับกำหนดข้อมูลพื้นฐานของหน้าเว็บ -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <!-- เชื่อมต่อไฟล์ CSS สำหรับการตกแต่งหน้าเว็บ -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- ส่วนหัวของหน้าเว็บ (หัวเรื่อง) -->
    <h2>Admin Login</h2>

    <!-- ฟอร์มสำหรับกรอกข้อมูลเพื่อเข้าสู่ระบบของ Admin -->
    <form action="admin_login_process.php" method="post">
        <!-- ป้ายกำกับและช่องกรอกข้อมูลสำหรับ E-mail -->
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <!-- ป้ายกำกับและช่องกรอกข้อมูลสำหรับรหัสผ่าน -->
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <!-- ปุ่มสำหรับยืนยันการเข้าสู่ระบบ -->
        <button type="submit">Login</button>
    </form>

    <!-- ลิงก์ไปยังหน้าเข้าสู่ระบบของผู้ใช้ในกรณีที่ Admin ต้องการเข้าสู่ระบบเป็นผู้ใช้ทั่วไป -->
    <p>เป็นสมาชิกทั่วไป? <a href="login.php">เข้าสู่ระบบที่นี่</a></p>
</body>
</html>
