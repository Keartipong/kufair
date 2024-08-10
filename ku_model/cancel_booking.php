<?php
include 'config.php';


// ดึงข้อมูลการจองของสมาชิก
$sql_bookings = "SELECT * FROM bookings WHERE user_id = ? AND status = 'จอง'";
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
    <title>ยกเลิกการจอง</title>
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
            <h4 class="header">ยกเลิกการจอง</h4>
            <form action="process_cancellation.php" method="post">
                <div class="form-group">
                    <label for="booking_id">เลือกการจองที่ต้องการยกเลิก:</label>
                    <select name="booking_id" id="booking_id" class="form-control" required>
                        <?php while ($booking = $result_bookings->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
                                <?php echo htmlspecialchars($booking['booth_id']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-danger">ยกเลิกการจอง</button>
            </form>
        </div>
    </div>
</body>
</html>
