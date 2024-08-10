<?php
include 'config.php';

// ตรวจสอบว่ามีการส่งข้อมูลล็อคอินหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // ใช้ prepared statements เพื่อลดความเสี่ยงจาก SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    
    // เรียกใช้งาน prepared statement
    $stmt->execute();
    $result = $stmt->get_result();
    
    // ตรวจสอบผลลัพธ์
    if ($result->num_rows > 0) {
        // การล็อคอินสำเร็จ
        echo "<p class='message success'>User Login Successful!</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'mains.php';
                }, 1000);
              </script>";
    } else {
        // การล็อคอินล้มเหลว
        echo "<p class='message error'>Invalid email or Password!</p>";
    }
    
    // ปิดการเชื่อมต่อ
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>หน้าล็อคอิน</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,255,0);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-top: 0;
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #666;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
        
    </style>
</head>
<body>
    <div class="login-container">
        <h2>ล็อคอิน</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="email">ชื่อผู้ใช้:</label>
            <input type="text" id="email" name="email" required><br>
            
            <label for="password">รหัสผ่าน:</label>
            <input type="password" id="password" name="password" required><br>
            
            <input type="submit" value="ล็อคอิน">
        </form>
    </div>
</body>
</html>
