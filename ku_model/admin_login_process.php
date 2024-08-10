<?php
include 'config.php'; // เชื่อมต่อกับฐานข้อมูล

// รับค่าจากฟอร์ม
$email = $_POST['email'];
$password = $_POST['password'];

// ป้องกัน SQL Injection โดยใช้ Prepared Statements
$stmt = $conn->prepare("SELECT * FROM admins WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) { 
    echo "Admin Login Successful!";
    // ทำการ Redirect ไปยังหน้าแผงควบคุมของ Admin
    header("Location: mains.php");
    exit(); // ออกจากสคริปต์เพื่อป้องกันการทำงานที่เหลืออยู่
} else {
    echo "Invalid E-mail or Password!";
}

$stmt->close();
$conn->close();
?>
