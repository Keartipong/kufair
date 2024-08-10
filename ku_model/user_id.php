<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // หากยังไม่ได้ล็อกอิน, ให้เปลี่ยนเส้นทางไปที่หน้าเข้าสู่ระบบ
    header('Location: loginm.php');
    exit;
}

$user_id = $_SESSION['user_id'];
?>
