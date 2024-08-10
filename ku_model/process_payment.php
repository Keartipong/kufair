<?php

include 'config.php'; // เชื่อมต่อฐานข้อมูล

session_start();

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['member_id'])) {
    header("Location: loginm.php");
    exit();
}

// ดึงข้อมูล member_id จากเซสชัน
$member_id = $_SESSION['member_id'];

// ตรวจสอบว่าตัวแปร booths_to_book ถูกตั้งค่าและเป็นอาร์เรย์หรือไม่
if (!isset($_SESSION['booths_to_book']) || !is_array($_SESSION['booths_to_book'])) {
    die("Error: No booths to book.");
}

$booths_to_book = $_SESSION['booths_to_book'];

// ตรวจสอบจำนวนบูธที่จองแล้วของผู้ใช้
$sql_check_bookings = "SELECT booked_booths FROM users WHERE member_id = ?";
$stmt = $conn->prepare($sql_check_bookings);
$stmt->bind_param("s", $member_id);
$stmt->execute();
$stmt->bind_result($booked_booths);
$stmt->fetch();
$stmt->close();

// จำนวนบูธที่สามารถจองได้
$max_booths = 4;
$available_booths = $max_booths - $booked_booths;

if (count($booths_to_book) > $available_booths) {
    die("Error: You can only book up to $available_booths more booths.");
}

// เพิ่มการจองใหม่ลงในตาราง reservations
$sql_insert_booking = "INSERT INTO reservations (zone_id, booth_number, product, payment_proof, user_id, reservation_date, email) VALUES (?, ?, ?, ?, ?, NOW(), ?)";
$stmt = $conn->prepare($sql_insert_booking);

foreach ($booths_to_book as $booth) {
    $zone_id = $booth['zone_id'];
    $booth_number = $booth['booth_number'];
    $product = $booth['product'];
    $payment_proof = $booth['payment_proof'];

    $stmt->bind_param("iissss", $zone_id, $booth_number, $product, $payment_proof, $member_id, $user_email);
    $stmt->execute();
}

// รับข้อมูลจากฟอร์ม
$booking_id = isset($_POST['booking_id']) ? $_POST['booking_id'] : '';
$booth_id = isset($_POST['booth_id']) ? $_POST['booth_id'] : '';
$zone_id = isset($_POST['zone_id']) ? $_POST['zone_id'] : '';
$member_id = $_SESSION['member_id'];

// ตรวจสอบการอัปโหลดไฟล์
if (isset($_FILES['payment_slip']) && $_FILES['payment_slip']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . basename($_FILES['payment_slip']['name']);
    
    if (move_uploaded_file($_FILES['payment_slip']['tmp_name'], $upload_file)) {
        // บันทึกข้อมูลการชำระเงินในฐานข้อมูล
        $sql = "INSERT INTO payments (booking_id, member_id, payment_slip, status) VALUES (?, ?, ?, 'ชำระเงิน')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $booking_id, $member_id, $upload_file);

        if ($stmt->execute()) {
            // อัปเดตสถานะของบูธ
            $sql_update_booth = "UPDATE booths SET booth_status = 'booked' WHERE booth_id = ?";
            $stmt_update_booth = $conn->prepare($sql_update_booth);
            $stmt_update_booth->bind_param("i", $booth_id);
            $stmt_update_booth->execute();
            $stmt_update_booth->close();

            // เปลี่ยนเส้นทางไปยัง `mains.php`
            header("Location: mains.php?status=success");
            exit();
        } else {
            // จัดการข้อผิดพลาด
            echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
        }
        
        $stmt->close();
    } else {
        // จัดการข้อผิดพลาดในการอัปโหลดไฟล์
        echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
    }
} else {
    echo "ไม่พบไฟล์ที่อัปโหลด";
}

// อัปเดตจำนวนบูธที่จองในตาราง users
$sql_update_user = "UPDATE users SET booked_booths = booked_booths + ? WHERE member_id = ?";
$stmt = $conn->prepare($sql_update_user);
$booked_count = count($booths_to_book);
$stmt->bind_param("is", $booked_count, $member_id);
$stmt->execute();
$stmt->close();

header("Location: success.php"); // เปลี่ยนเส้นทางไปยังหน้าสำเร็จ
exit();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>ผลการชำระเงิน</title>
    <style type="text/css">
        body {
            background: linear-gradient(135deg, #ff6f61, #d4a5a5);
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
            font-family: 'Poppins', sans-serif;
            color: #333;
            padding: 0;
            margin: 0;
            overflow-x: hidden;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0% 0%; }
        }

        .container-custom {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            border: 2px solid #ff6f61;
            margin-top: 50px;
            position: relative;
        }

        .header {
            color: #ff6f61;
            text-align: center;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 30px;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -10px;
            transform: translateX(-50%);
            height: 5px;
            width: 60px;
            background: #ff6f61;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2 container-custom">
                <h4 class="header">ผลการชำระเงิน</h4>
                <!-- ผลลัพธ์จะถูกแสดงที่นี่ -->
                <div class="text-center">
                    <a href="mains.php" class="btn btn-primary">กลับไปที่หน้าหลัก</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>
