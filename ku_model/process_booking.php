<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: loginm.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$booth_id = $_POST['booth_id'];
$payment_date = $_POST['payment_date'];
$status = 'จอง'; // สถานะเริ่มต้นของการจอง

// อัพโหลดสลิปการชำระเงิน
if (isset($_FILES['payment_slip']) && $_FILES['payment_slip']['error'] === UPLOAD_ERR_OK) {
    $payment_slip_path = 'uploads/' . basename($_FILES['payment_slip']['name']);
    move_uploaded_file($_FILES['payment_slip']['tmp_name'], $payment_slip_path);
} else {
    $payment_slip_path = NULL;
}

// บันทึกข้อมูลการจอง
$sql_insert = "INSERT INTO bookings (booth_id, user_id, booking_date, payment_date, price, payment_slip_path, status) VALUES (?, ?, NOW(), ?, ?, ?, ?)";
$stmt = $conn->prepare($sql_insert);
$price = $_POST['price']; // ต้องได้รับการคำนวณหรือกำหนดที่นี่

$stmt->bind_param("iissss", $booth_id, $user_id, $payment_date, $price, $payment_slip_path, $status);
$stmt->execute();
$stmt->close();

// ปรับปรุงสถานะของบูธ
$sql_update_booth = "UPDATE booths SET booth_status = 1 WHERE booth_id = ?";
$stmt = $conn->prepare($sql_update_booth);
$stmt->bind_param("i", $booth_id);
$stmt->execute();
$stmt->close();

echo "บันทึกการจองเรียบร้อยแล้ว";
?>
