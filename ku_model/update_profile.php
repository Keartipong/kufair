<?php
include 'config.php';


// ดึงข้อมูลผู้ใช้ปัจจุบัน
$sql_user = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลตัวเอง</title>
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
            <h4 class="header">แก้ไขข้อมูลตัวเอง</h4>
            <form action="process_profile_update.php" method="post">
                <div class="form-group">
                    <label for="first_name">ชื่อ:</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">นามสกุล:</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">เบอร์โทรศัพท์:</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">อีเมล:</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">รหัสผ่าน:</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">บันทึกการเปลี่ยนแปลง</button>
            </form>
        </div>
    </div>
</body>
</html>
