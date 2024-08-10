<?php
include 'config.php';

session_start();

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION['member_id'])) {
    header("Location: loginm.php");
    exit();
}

// ดึงข้อมูลโซนทั้งหมด
$sql_zones = "SELECT * FROM zones";
$result_zones = $conn->query($sql_zones);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จองบูธ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, rgba(34, 193, 195, 0.7), rgba(253, 187, 45, 0.7));
            color: #333;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-attachment: fixed; /* Fixes background for parallax effect */
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
            opacity: 0.4;
            animation: backgroundAnimation 30s linear infinite; /* Add animation for background */
        }

        @keyframes backgroundAnimation {
            0% { background-position: 0 0; }
            100% { background-position: 100% 100%; }
        }

        .container-custom {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            margin-top: 60px;
            position: relative;
            z-index: 1;
            border: 2px solid #28a745;
            overflow: hidden;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .container-custom:hover {
            transform: scale(1.03);
            box-shadow: 0 25px 40px rgba(0, 0, 0, 0.3);
        }

        .header {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 34px;
            font-weight: 600;
            position: relative;
            z-index: 1;
            transition: color 0.4s ease, transform 0.3s ease;
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

        .header:hover {
            transform: scale(1.05);
        }

        .form-group label {
            font-size: 20px;
            color: #333;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .form-control {
            border-radius: 25px;
            border: 2px solid #28a745;
            transition: border-color 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
            position: relative;
            padding: 8px 20px;
            font-size: 16px;
            font-weight: 400;
        }

        .form-control:focus {
            border-color: #218838;
            box-shadow: 0 0 0 0.2rem rgba(72, 180, 97, 0.25), 0 0 5px rgba(72, 180, 97, 0.5);
            background-color: #eafaf0;
            transform: scale(1.02);
        }

        .form-control::placeholder {
            color: #333;
            font-weight: 300;
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
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            background: #28a745;
            color: #ffffff;
            border: 2px solid #28a745;
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

        .btn-custom:active::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            border-radius: 50%;
            z-index: 0;
            animation: rippleEffect 0.6s linear;
        }

        @keyframes rippleEffect {
            0% { transform: scale(0); }
            100% { transform: scale(1); opacity: 0; }
        }

        .btn-success-custom {
            background: #28a745;
            border: 2px solid #28a745;
            color: #ffffff;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        .btn-success-custom:hover {
            background: #218838;
            color: #ffffff;
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
            animation: slideIn 0.6s forwards;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .user-info:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.05);
        }

        .fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #28a745;
            color: #fff;
            border-radius: 50%;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 24px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .fab:hover {
            background: #218838;
            transform: scale(1.1);
        }

        .tooltip-inner {
            background-color: #28a745;
            color: #fff;
            border-radius: 5px;
            padding: 10px;
            font-size: 14px;
        }

        .tooltip-arrow {
            border-top-color: #28a745;
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #28a745;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #28a745;
            border-radius: 15px;
            animation: modalIn 0.5s ease-out;
        }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-footer .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 1px solid #28a745;
            transition: background 0.3s, color 0.3s;
        }

        .modal-footer .btn-secondary:hover {
            background: #e2e6ea;
            color: #28a745;
        }

        .btn-back {
            display: inline-block;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            color: #28a745;
            border: 2px solid #28a745;
            background-color: transparent;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-back:hover {
            background-color: #28a745;
            color: #fff;
            border-color: #28a745;
            transform: translateY(-2px);
        }

        .btn-back:active {
            background-color: #28a745;
            border-color: #28a745;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transform: translateY(1px);
        }
    </style>
</head>
<body>
    <!-- Background Overlay -->
    <div class="background-overlay"></div>

    <div class="container">
        <div class="container-custom">
            <h4 class="header">จองบูธ</h4>
            <form action="payment.php" method="post">
                <div class="form-group">
                    <label for="zone_id" data-toggle="tooltip" data-placement="right" title="เลือกโซนที่ต้องการ">เลือกโซน:</label>
                    <select name="zone_id" id="zone_id" class="form-control" required>
                        <?php while ($zone = $result_zones->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($zone['zone_id']); ?>">
                                <?php echo htmlspecialchars($zone['zone_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="booth_id" data-toggle="tooltip" data-placement="right" title="เลือกบูธที่ต้องการ">เลือกบูธ:</label>
                    <select name="booth_id" id="booth_id" class="form-control" required>
                        <option value="">กรุณาเลือกโซนก่อน</option>
                    </select>
                    <div id="loader" class="loader" style="display: none;"></div>
                </div>
                <button type="submit" class="btn btn-custom btn-success-custom">ทำการจอง</button><br>
                <a href="mains.php" class="btn btn-outline-light btn-back">กลับไปหน้าจอง</a>
            </form>
            
        </div>
    </div>

    <div class="fab" data-toggle="tooltip" data-placement="left" title="Feedback">
        <i class="fas fa-comment-dots"></i>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">ข้อมูลเพิ่มเติม</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ใส่ข้อมูลหรือคำแนะนำเพิ่มเติมที่นี่
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Tooltip initialization
            $('[data-toggle="tooltip"]').tooltip();
            
            $('#zone_id').change(function() {
                var zoneId = $(this).val();
                var $boothId = $('#booth_id');
                var $loader = $('#loader');

                if (zoneId) {
                    $loader.show();
                    $.ajax({
                        type: 'POST',
                        url: 'get_booths.php',
                        data: { zone_id: zoneId },
                        success: function(data) {
                            $boothId.html(data);
                        },
                        error: function() {
                            $boothId.html('<option value="">ไม่สามารถโหลดบูธได้</option>');
                        },
                        complete: function() {
                            $loader.hide();
                        }
                    });
                } else {
                    $boothId.html('<option value="">กรุณาเลือกโซนก่อน</option>');
                }
            });

            // Initialize modal
            $('#infoModal').modal({ show: false });
        });
    </script>
</body>
</html>


