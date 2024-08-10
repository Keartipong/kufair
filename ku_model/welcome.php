<?php
session_start();

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['member_id'])) {
    header("Location: loginm.php");
    exit();
}

// ดึงข้อมูลจากเซสชัน
$prefix = $_SESSION['prefix'];
$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h2>Welcome</h2>
    <p>Prefix: <?php echo htmlspecialchars($prefix); ?></p>
    <p>First Name: <?php echo htmlspecialchars($firstname); ?></p>
    <p>Last Name: <?php echo htmlspecialchars($lastname); ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>
