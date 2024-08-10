<?php
include 'config.php';


// ดึงข้อมูลการจองของสมาชิก
$sql_bookings = "SELECT b.booth_name, z.zone_name, bk.price, bk.status FROM bookings bk INNER JOIN booths b ON bk.booth_id = b.booth_id INNER JOIN zones z ON b.zone_id = z.zone_id WHERE bk.user_id = ?";
$stmt = $conn->prepare($sql_bookings);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_bookings = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการการจอง</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
        body {
            background-color: #330000;
        }
        .container-custom {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            margin-top: 50px;
        }
        .header {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="container-custom">
            <h4 class="header">รายการการจองของคุณ</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ชื่อบูธ</th>
                        <th>ชื่อโซน</th>
                        <th>จำนวนเงินที่ชำระ</th>
                        <th>สถานะการจอง</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_bookings->num_rows > 0): ?>
                        <?php while ($row = $result_bookings->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['booth_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['zone_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['price']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">ไม่มีข้อมูลการจอง</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
