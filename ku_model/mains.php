<?php

include 'config.php'; // เชื่อมต่อฐานข้อมูล

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
$member_id = $_SESSION['member_id'];

// ดึงข้อมูลโซน
$sql = "SELECT zone_id, zone_name, zone_info, booth_count FROM zones";
$result = $conn->query($sql);

// คำสั่ง SQL สำหรับดึงข้อมูลการจองบูธของสมาชิก
$sql_bookings = "SELECT COUNT(*) as booked_booths FROM bookings WHERE member_id = ?";
$stmt = $conn->prepare($sql_bookings);
$stmt->bind_param("s", $member_id);
$stmt->execute();
$stmt->bind_result($booked_booths);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลโซนตั้งบูธ</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, rgba(34, 193, 195, 1), rgba(253, 187, 45, 1));
            overflow: hidden;
            position: relative;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://www.example.com/animated-background-image.png') no-repeat center center;
            background-size: cover;
            z-index: -1;
            opacity: 0.5;
            animation: backgroundAnimation 30s infinite linear;
        }

        @keyframes backgroundAnimation {
            0% { background-position: 0% 0%; }
            100% { background-position: 100% 100%; }
        }

        .container-custom {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            margin-top: 50px;
            position: relative;
            z-index: 1;
            border: 2px solid #28a745;
            overflow: hidden;
        }

        .container-custom::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            width: 80%;
            height: 80%;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            z-index: -1;
            animation: pulse 15s infinite ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(0.8); opacity: 0.3; }
            50% { transform: scale(1.2); opacity: 0.5; }
            100% { transform: scale(0.8); opacity: 0.3; }
        }

        .header {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 34px;
            font-weight: 600;
            position: relative;
            z-index: 1;
            transition: color 0.4s ease;
        }

        .header::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            height: 5px;
            width: 100%;
            background: #28a745;
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .header:hover::after {
            transform: scaleX(1);
        }

        .btn-custom {
            border-radius: 25px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            z-index: 1;
            transition: all 0.4s ease;
        }

        .btn-custom::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(0, 0, 0, 0.1);
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.4s ease;
            border-radius: 50%;
            z-index: 0;
        }

        .btn-custom:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }

        .btn-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .btn-danger-custom {
            background: #dc3545;
            border: none;
            color: #ffffff;
        }

        .btn-success-custom {
            background: #28a745;
            border: none;
            color: #ffffff;
        }

        .btn-info-custom {
            background: #17a2b8;
            border: none;
            color: #ffffff;
        }

        .table thead th {
            background: #343a40;
            color: #ffffff;
            font-weight: 600;
            text-align: center;
            position: relative;
            border-radius: 8px;
        }

        .table thead th::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 5px;
            width: 100%;
            background: #28a745;
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .table thead th:hover::after {
            transform: scaleX(1);
        }

        .table tbody tr:nth-child(even) {
            background: rgba(248, 248, 248, 0.8);
            transition: background 0.4s ease;
        }

        .table tbody tr:nth-child(odd) {
            background: rgba(255, 255, 255, 0.9);
            transition: background 0.4s ease;
        }

        .table tbody tr:hover {
            background: rgba(229, 229, 229, 0.8);
            transform: translateY(-4px);
            transition: all 0.4s ease;
        }

        .user-info {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            font-size: 16px;
            color: #333;
            transition: all 0.4s ease;
            border: 1px solid #28a745;
        }

        .user-info:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.05);
        }

        .modal-custom {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 2000;
            transition: opacity 0.4s ease;
        }

        .modal-custom.show {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 80%;
            max-width: 450px;
            animation: modalEntrance 0.5s ease-out;
            position: relative;
        }

        .modal-content::before {
            content: '';
            position: absolute;
            top: -20px;
            left: -20px;
            width: calc(100% + 40px);
            height: calc(100% + 40px);
            background: rgba(0, 0, 0, 0.05);
            border-radius: 15px;
            z-index: -1;
            transform: rotate(-2deg);
        }

        .modal-content h3 {
            color: #333;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .modal-content button {
            background: #28a745;
            border: none;
            color: #ffffff;
            padding: 15px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .modal-content button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(0, 0, 0, 0.1);
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.3s ease;
            border-radius: 50%;
            z-index: 0;
        }

        .modal-content button:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }

        .modal-content button:hover {
            background: #218838;
            color: #ffffff;
            transform: translateY(-3px);
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            pointer-events: none;
        }

        .floating-elements .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.1);
            animation: float 10s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-30px);
            }
            100% {
                transform: translateY(0);
            }
        }

        /* Add more details */
        .card-custom {
            border: 1px solid #28a745;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            margin: 15px 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card-custom img {
            width: 100%;
            height: auto;
        }

        .card-custom-body {
            padding: 20px;
            background: #ffffff;
        }

        .card-custom-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .card-custom-text {
            font-size: 16px;
            color: #555;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="background-overlay"></div>

    <div class="floating-elements">
        <div class="circle" style="width: 120px; height: 120px; top: 10%; left: 20%; background: rgba(255, 255, 255, 0.1);"></div>
        <div class="circle" style="width: 180px; height: 180px; top: 40%; left: 60%; background: rgba(0, 0, 0, 0.1);"></div>
        <div class="circle" style="width: 250px; height: 250px; top: 70%; left: 30%; background: rgba(255, 255, 255, 0.2);"></div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12 col-md-10 offset-md-1 container-custom">
                <h4 class="header">ข้อมูลโซนตั้งบูธ</h4>
                <div class="d-flex justify-content-between mb-3">
                    <a href="logout.php" class="btn btn-custom btn-danger-custom"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
                    <a href="book_booth.php" class="btn btn-custom btn-success-custom"><i class="fas fa-plus"></i> จองบูธ</a>
                </div>
                <hr>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>รหัสโซน</th>
                            <th>ชื่อโซน</th>
                            <th>ข้อมูลโซน</th>
                            <th>จำนวนบูธในโซน</th>
                            <th>ดูรายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['zone_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['zone_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['zone_info']); ?></td>
                                    <td><?php echo htmlspecialchars($row['booth_count']); ?></td>
                                    <td>
                                        <a href="booth_details.php?zone_id=<?php echo urlencode($row['zone_id']); ?>" class="btn btn-info-custom btn-custom"><i class="fas fa-eye"></i> ดูรายละเอียด</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">ไม่มีข้อมูล</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal-custom" id="errorModal">
        <div class="modal-content">
            <h3 id="errorMessage">ข้อความแจ้งเตือน</h3>
            <button onclick="closeModal()">ตกลง</button>
        </div>
    </div>

    <div class="user-info">
        <p><strong>ชื่อ:</strong> <?php echo htmlspecialchars($firstname); ?></p>
        <p><strong>นามสกุล:</strong> <?php echo htmlspecialchars($lastname); ?></p>
        <p><strong>จำนวนบูธที่จอง:</strong> <?php echo htmlspecialchars($booked_booths); ?></p>
    </div>

    <script>
        function closeModal() {
            document.getElementById('errorModal').classList.remove('show');
        }

        function showModal(message) {
            document.getElementById('errorMessage').innerText = message;
            document.getElementById('errorModal').classList.add('show');
        }

        // Example: Show modal on page load if there is an error
        window.onload = function() {
            var errorMessage = '<?php echo $login_error ?? ""; ?>';
            if (errorMessage) {
                showModal(errorMessage);
            }
        }
    </script>
</body>
</html>
