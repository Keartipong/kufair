<?php
include 'config.php'; // เชื่อมต่อกับฐานข้อมูล

// รับค่า zone_id จาก URL
$zone_id = isset($_GET['zone_id']) ? $_GET['zone_id'] : '';

if (empty($zone_id)) {
    echo "ข้อมูลโซนไม่ถูกต้อง";
    exit();
}

// คำสั่ง SQL สำหรับดึงข้อมูลบูธ
$sql = "SELECT booth_id, booth_name, booth_size, booth_status, booth_price FROM booths WHERE zone_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $zone_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>รายละเอียดบูธ</title>
    <style type="text/css">
        body {
            background: linear-gradient(135deg, #ff6f61, #d4a5a5);
            font-family: 'Poppins', sans-serif;
            color: #333;
            padding: 0;
            margin: 0;
        }

        .container-custom {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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

        .btn-secondary {
            background-color: #ff6f61;
            border: 2px solid #ff6f61;
            color: #fff;
            font-weight: 600;
            transition: background-color 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #e74c3c;
            border-color: #e74c3c;
            transform: scale(1.05);
        }

        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table thead th {
            background-color: #ff6f61;
            color: #fff;
            font-weight: 600;
            position: relative;
        }

        .table tbody tr {
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
            transform: translateY(-2px);
        }

        .table tbody td {
            transition: color 0.3s ease;
        }

        .table tbody td:hover {
            color: #ff6f61;
        }

        .footer {
            text-align: center;
            color: #fff;
            padding: 20px 0;
            background-color: #330000;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-10 offset-md-1 container-custom">
                <h4 class="header">รายละเอียดบูธ</h4>
                <a href="mains.php" class="btn btn-secondary">กลับ</a>
                <hr>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>รหัสบูธ</th>
                            <th>ชื่อบูธ</th>
                            <th>ขนาดบูธ</th>
                            <th>สถานะปัจจุบัน</th>
                            <th>ราคา</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['booth_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['booth_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['booth_size']); ?></td>
                                    <td>
                                        <?php
                                        $status = htmlspecialchars($row['booth_status']);
                                        if ($status == 0) {
                                            echo "ว่าง";
                                        } elseif ($status == 1) {
                                            echo "อยู่ระหว่างตรวจสอบ";
                                        } else {
                                            echo "จองแล้ว";
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['booth_price']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">ไม่มีข้อมูล</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 Your Company Name</p>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close(); // ปิดการเชื่อมต่อกับฐานข้อมูล
?>
