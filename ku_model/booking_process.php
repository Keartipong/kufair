<?php
include 'config.php';
session_start();

if (!isset($_SESSION['member_id'])) {
    header('Location: loginm.php');
    exit();
}

// ตรวจสอบและรับค่า booth_id, product
if (!isset($_POST['booth_id']) || !isset($_POST['product']) || !isset($_FILES['payment_proof'])) {
    die('ข้อมูลไม่ครบถ้วน');
}

$booth_id = htmlspecialchars($_POST['booth_id']);
$product = htmlspecialchars($_POST['product']);
$zone_id = htmlspecialchars($_POST['zone_id']);
$booth_number = htmlspecialchars($_POST['booth_number']);
$email = htmlspecialchars($_POST['email']);
$product = htmlspecialchars($_POST['product']);


// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kumodel";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบจำนวนบูธที่จองโดยสมาชิกคนนี้
$sql_check = "SELECT COUNT(*) AS count FROM bookings WHERE member_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $_SESSION['member_id']);
$stmt_check->execute();
$result = $stmt_check->get_result();
$row = $result->fetch_assoc();

if ($row['count'] >= 4) {
    die('คุณได้จองบูธครบ 4 บูธแล้ว');
}

// ตรวจสอบสถานะบูธและอัปเดต
$sql_booth_check = "SELECT booth_status FROM booths WHERE booth_name = ?";
$stmt_booth_check = $conn->prepare($sql_booth_check);
$stmt_booth_check->bind_param("s", $booth_id);
$stmt_booth_check->execute();
$booth_result = $stmt_booth_check->get_result();

if ($booth_result->num_rows === 0) {
    die('ไม่พบข้อมูลบูธที่เลือก');
}

$booth_row = $booth_result->fetch_assoc();

if ($booth_row['booth_status'] != 'ว่าง') {
    die('บูธที่เลือกไม่ว่างหรือไม่สามารถจองได้');
}

// อัปเดตสถานะบูธเป็น "อยู่ระหว่างตรวจสอบ" (ตั้งค่าเป็น '1' หรือ 'จอง')
$sql_update_booth = "UPDATE booths SET booth_status = 'อยู่ระหว่างตรวจสอบ' WHERE booth_name = ?";
$stmt_update_booth = $conn->prepare($sql_update_booth);
$stmt_update_booth->bind_param("s", $booth_id);

if (!$stmt_update_booth->execute()) {
    die("เกิดข้อผิดพลาดในการอัปเดตสถานะบูธ: " . $stmt_update_booth->error);
}

// จัดการไฟล์ที่อัปโหลด
$upload_dir = 'uploads/';
$upload_file = $upload_dir . basename($_FILES['payment_proof']['name']);
if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $upload_file)) {
    echo "ไฟล์ถูกอัปโหลดเรียบร้อยแล้ว\n";
} else {
    die("เกิดข้อผิดพลาดในการอัปโหลดไฟล์\n");
}
// ตรวจสอบจำนวนบูธที่จองโดยอีเมลเดียว
$sql_check = "SELECT COUNT(*) AS count FROM reservations WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result = $stmt_check->get_result();
$row = $result->fetch_assoc();

// บันทึกข้อมูลการจองไปยังตาราง bookings
$booking_date = date("Y-m-d");
$status = "จอง";

// เพิ่มข้อมูลการจอง
$sql_insert_booking = "INSERT INTO bookings (booking_date, payment_date, booth_id, price, slip_path, status, product_details, member_id, event_id) VALUES (?, NULL, ?, NULL, ?, ?, ?, ?)";
$stmt_insert_booking = $conn->prepare($sql_insert_booking);
$stmt_insert_booking->bind_param("ssssssi", $booking_date, $booth_id, $upload_file, $status, $product, $_SESSION['member_id'], $event_id);

if ($stmt_insert_booking->execute()) {
    echo "จองบูธสำเร็จและบันทึกข้อมูลการจองเรียบร้อยแล้ว";
} else {
    die("เกิดข้อผิดพลาดในการบันทึกข้อมูลการจอง: " . $stmt_insert_booking->error);
}

// เตรียมคำสั่ง SQL
$sql = "INSERT INTO reservations (zone_id, booth_number, product, payment_proof, email, user_id) VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iisssi", $zone_id, $booth_number, $product, $upload_file, $email, $_SESSION['user_id']);

if ($stmt->execute()) {
    echo "Reservation successful";
} else {
    echo "Error: " . $stmt->error;
}


// ปิดการเชื่อมต่อ
$stmt_check->close();
$stmt_booth_check->close();
$stmt_update_booth->close();
$stmt_insert_booking->close();
$conn->close();
?>