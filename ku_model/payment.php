<?php
include 'config.php'; 
session_start();

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['member_id'])) {
    header("Location: loginm.php");
    exit();
}

// รับข้อมูลที่ส่งมาจากฟอร์มใน `book_booth.php`
$zone_id = isset($_POST['zone_id']) ? $_POST['zone_id'] : '';
$booth_id = isset($_POST['booth_id']) ? $_POST['booth_id'] : '';

if ($zone_id && $booth_id) {
    // ดึงข้อมูลบูธที่เลือก
    $sql_booth = "SELECT booth_name, booth_size, booth_price 
                  FROM booths 
                  WHERE booth_id = ?";
    $stmt_booth = $conn->prepare($sql_booth);
    $stmt_booth->bind_param("i", $booth_id);
    $stmt_booth->execute();
    $result_booth = $stmt_booth->get_result();
    $booth = $result_booth->fetch_assoc();
    $stmt_booth->close();

    // ดึงข้อมูลโซนที่เลือก
    $sql_zone = "SELECT zone_name FROM zones WHERE zone_id = ?";
    $stmt_zone = $conn->prepare($sql_zone);
    $stmt_zone->bind_param("i", $zone_id);
    $stmt_zone->execute();
    $result_zone = $stmt_zone->get_result();
    $zone = $result_zone->fetch_assoc();
    $stmt_zone->close();
} else {
    die('ข้อมูลไม่ครบถ้วน');
}

// สร้างรหัสการจอง (สมมติว่ารหัสนี้จะถูกสร้างหรือดึงจากฐานข้อมูลจริง)
$booking_id = rand(1000, 9999); // ตัวอย่างการสร้างรหัสการจอง
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>ชำระเงิน</title>
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

        .parallax {
            position: relative;
            background: url('https://source.unsplash.com/random/1920x1080') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            z-index: -1;
            filter: brightness(50%);
        }

        .container-custom {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            border: 50px solid #ff6f61;
            margin-top: -1000px ; /* Adjust to overlap with parallax effect */
            position: relative;
            z-index: 1;
        }

        .header {
            color: #ff6f61;
            text-align: center;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 30px;
            position: relative;
            animation: fadeIn 2s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .form-group label {
            font-size: 18px;
            color: #333;
            font-weight: 600;
        }

        .form-control, .form-control-file {
            border-radius: 25px;
            border: 2px solid #ff6f61;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 400;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus, .form-control-file:focus {
            border-color: #e74c3c;
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
            background-color: #f9f9f9;
        }

        .btn-success {
            border-radius: 25px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            background-color: #ff6f61;
            border: 2px solid #ff6f61;
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-success::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(255, 255, 255, 0.2);
            transition: transform 0.4s ease;
            transform: translate(-50%, -50%) scale(0);
            border-radius: 50%;
        }

        .btn-success:hover::after {
            transform: translate(-50%, -50%) scale(1);
        }

        .btn-success:hover {
            background-color: #e74c3c;
            border-color: #e74c3c;
            color: #fff;
        }

        .btn-back {
            display: inline-block;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            color: #ff6f61;
            border: 2px solid #ff6f61;
            background-color: transparent;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-back:hover {
            background-color: #ff6f61;
            color: #fff;
            border-color: #ff6f61;
            transform: translateY(-2px);
        }

        .btn-back:active {
            background-color: #e74c3c;
            border-color: #e74c3c;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transform: translateY(1px);
        }

        .modal-content {
            border-radius: 15px;
            padding: 20px;
            border: 2px solid #ff6f61;
        }
    </style>
</head>
<body>
    <div class="parallax"></div>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-10 offset-md-1 container-custom">
                <h4 class="header">ชำระเงิน</h4>
                <form action="process_payment.php" method="post" enctype="multipart/form-data" onsubmit="showConfirmationModal(event)">
                    <!-- ข้อมูลที่ต้องส่งไปยัง `process_payment.php` -->
                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>">
                    <input type="hidden" name="booth_id" value="<?php echo htmlspecialchars($booth_id); ?>">
                    <input type="hidden" name="zone_id" value="<?php echo htmlspecialchars($zone_id); ?>">
                    <div class="form-group">
                        <label for="zone_name">โซนที่เลือก</label>
                        <input type="text" id="zone_name" class="form-control" value="<?php echo htmlspecialchars($zone['zone_name']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="booth_details">รายละเอียดบูธที่เลือก</label>
                        <input type="text" id="booth_details" class="form-control" value="<?php echo htmlspecialchars($booth['booth_name'] . ' - ' . $booth['booth_size'] . ' - ' . $booth['booth_price']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="payment_date">วันที่ชำระเงิน</label>
                        <input type="date" id="payment_date" name="payment_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_slip">อัพโหลดสลิปการชำระเงิน</label>
                        <input type="file" id="payment_slip" name="payment_slip" class="form-control-file" required>
                    </div>
                    <button type="submit" class="btn btn-success">บันทึกการชำระเงิน</button><br>
                    <a href="book_booth.php" class="btn btn-outline-light btn-back">กลับไปหน้าจอง</a>
                </form>
                <br>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">ยืนยันการชำระเงิน</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    การชำระเงินของคุณได้ถูกบันทึกแล้ว
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary">ไปที่หน้าอื่น</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script>
        function showConfirmationModal(event) {
            event.preventDefault(); // Prevent the default form submission
            $('#confirmationModal').modal('show'); // Show the confirmation modal
            setTimeout(() => {
                event.target.submit(); // Submit the form after showing the modal
            }, 2000); // Delay form submission to allow modal display
        }
    </script>
</body>
</html>
