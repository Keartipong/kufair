<?php
include 'config.php';

if (isset($_POST['booth_id'])) {
    $booth_id = $_POST['booth_id'];

    // ดึงรายละเอียดของบูธ
    $sql_booth_details = "SELECT booth_name, booth_size, booth_price FROM booths WHERE booth_id = ?";
    $stmt_booth_details = $conn->prepare($sql_booth_details);
    $stmt_booth_details->bind_param("i", $booth_id);
    $stmt_booth_details->execute();
    $result_booth_details = $stmt_booth_details->get_result();

    if ($result_booth_details->num_rows > 0) {
        $booth = $result_booth_details->fetch_assoc();
        echo json_encode($booth);
    } else {
        echo json_encode([]);
    }

    $stmt_booth_details->close();
    $conn->close();
} else {
    echo json_encode([]);
}
