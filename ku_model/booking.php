<?php
include 'config.php';
session_start();
if (!isset($_SESSION['member_id'])) {
    header('Location: loginm.php');
    exit();
}

// ตรวจสอบและรับค่า zone_id และ booth_number
if (!isset($_GET['zone_id']) || !isset($_GET['booth_number'])) {
    die('ข้อมูลไม่ครบถ้วน');
}

$zone_id = htmlspecialchars($_GET['zone_id']);
$booth_number = htmlspecialchars($_GET['booth_number']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>จองบูธ</title>
    <style>
        body {
            background-color: black;
            color: gold;
        }
        .form-container {
            margin: 0 auto;
            width: 300px;
            padding: 30px;
            background: #222;
            border-radius: 10px;
        }
        .form-container input[type="text"],
        .form-container input[type="file"],
        .form-container input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
        }
        .form-container input[type="submit"] {
            background-color: gold;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            color: black;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>จองบูธ โซน <?php echo htmlspecialchars($zone_id); ?> บูธ <?php echo htmlspecialchars($booth_number); ?></h2>
        <form action="booking_process.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="zone_id" value="<?php echo htmlspecialchars($zone_id); ?>">
            <input type="hidden" name="booth_number" value="<?php echo htmlspecialchars($booth_number); ?>">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <br>
            <label for="product">สิ่งที่ขาย:</label>
            <input type="text" name="product" id="product" required>
            <br>
            <label for="payment_proof">อัพโหลดสลิปการชำระเงิน:</label>
            <input type="file" name="payment_proof" id="payment_proof" required>
            <br>
            <input type="submit" value="ยืนยันการจอง">
        </form>
    </div>
</body>
</html>
