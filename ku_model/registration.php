<?php
function generateUniqueMemberID($conn) {
    $count = 0;
    do {
        // สุ่มตัวเลข 4 หลัก
        $member_id = rand(1000, 9999);

        // ตรวจสอบว่ามี member_id นี้ในฐานข้อมูลแล้วหรือยัง
        $sql = "SELECT COUNT(*) FROM users WHERE member_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $member_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    } while ($count > 0); // ถ้ามี member_id นี้ในฐานข้อมูลแล้ว ให้สุ่มใหม่

    return $member_id;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prefix = $_POST['prefix'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // แฮชรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $servername = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "kumodel";

    // สร้างการเชื่อมต่อกับฐานข้อมูล
    $conn = new mysqli($servername, $username, $db_password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // สร้าง Member ID ที่ไม่ซ้ำ
    $member_id = generateUniqueMemberID($conn);

    // เตรียมและดำเนินการ SQL สำหรับการสมัครสมาชิก
    $sql = "INSERT INTO users (member_id, prefix, firstname, lastname, phone, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $member_id, $prefix, $firstname, $lastname, $phone, $email, $hashed_password);
    
    if ($stmt->execute() === TRUE) {
        echo "Registration successful. Your Member ID is " . $member_id;
    } else {
        echo "Error: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 150vh;
            color: #ffffff;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            font-size: 2em;
            color: #ffffff;
        }

        label {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 10px;
            display: block;
            text-align: left;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            outline: none;
        }

        input[type="text"]::placeholder,
        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: #e0e0e0;
        }

        button[type="submit"] {
            width: 100%;
            background: linear-gradient(135deg, #89fffd, #ef32d9);
            color: #ffffff;
            border: none;
            padding: 15px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);

        }

        button[type="submit"]:hover {
            transform: scale(1.05);
            display: block;
            filter: drop-shadow(0 0 10px #ef32d9);
        }

        p {
            margin-top: 20px;
            color: #ffffff;
            font-size: 14px;
        }

        p a {
            color: #89fffd;
            text-decoration: none;
            font-weight: 600;
            filter: drop-shadow(0 0 10px #ef32d9);
            
        }

        p a:hover {
            text-decoration: underline;
            
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>

        <form action="" method="post">
            <label for="prefix">คำนำหน้า:</label>
            <input type="text" id="prefix" name="prefix" placeholder="คำนำหน้า" required>

            <label for="firstname">ชื่อ:</label>
            <input type="text" id="firstname" name="firstname" placeholder="ชื่อ" required>

            <label for="lastname">นามสกุล:</label>
            <input type="text" id="lastname" name="lastname" placeholder="นามสกุล" required>

            <label for="phone">เบอร์โทร:</label>
            <input type="text" id="phone" name="phone" placeholder="เบอร์โทร" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" placeholder="E-mail" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <button type="submit">Register</button>

            <p>เป็นสมาชิกอยู่แล้ว? <a href="loginm.php">เข้าสู่ระบบที่นี่</a></p>
        </form>
    </div>
</body>
</html>

