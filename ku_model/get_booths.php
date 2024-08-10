<?php
include 'config.php';

if (isset($_POST['zone_id'])) {
    $zone_id = $_POST['zone_id'];

    // ดึงข้อมูลบูธที่อยู่ในโซนที่เลือก
    $sql_booths = "SELECT booth_id, booth_name, booth_size, booth_price 
                   FROM booths 
                   WHERE zone_id = ? AND booth_status = 0"; // ตรวจสอบบูธที่ยังไม่ได้ถูกจอง
    $stmt_booths = $conn->prepare($sql_booths);
    $stmt_booths->bind_param("i", $zone_id);
    $stmt_booths->execute();
    $result_booths = $stmt_booths->get_result();

    $options = "";
    while ($booth = $result_booths->fetch_assoc()) {
        $options .= '<option value="' . htmlspecialchars($booth['booth_id']) . '">
            ' . htmlspecialchars($booth['booth_name']) . ' - ' . htmlspecialchars($booth['booth_size']) . ' - ' . htmlspecialchars($booth['booth_price']) . '
        </option>';
    }
    
    echo $options;

    $stmt_booths->close();
    $conn->close();
}
?>
