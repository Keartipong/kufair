<?php
include 'config.php'; // เชื่อมต่อกับฐานข้อมูล

// รับค่าจากฟอร์ม
$member_id = $_POST['member_id'];
$prefix = $_POST['prefix'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['password'];

// ป้องกัน SQL Injection โดยใช้ Prepared Statements
$stmt = $conn->prepare("INSERT INTO users (member_id, prefix, firstname, lastname, phone, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $member_id, $prefix, $firstname, $lastname, $phone, $email, $password);

if ($stmt->execute()) {
    // รีไดเรกต์ไปยังหน้า Login หลังจากการสมัครสมาชิกสำเร็จ
    header("Location: loginm.php");
    exit(); // ออกจากสคริปต์เพื่อป้องกันการทำงานที่เหลืออยู่
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
