<?php
session_start();

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $servername = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "kumodel";

    $conn = new mysqli($servername, $username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, prefix, firstname, lastname, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $prefix, $firstname, $lastname, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['member_id'] = $id;
            $_SESSION['prefix'] = $prefix;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;

            header("Location: mains.php");
            exit();
        } else {
            $login_error = "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $login_error = "ไม่พบผู้ใช้งานด้วยอีเมลนี้";
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
    <title>ล็อคอิน</title>
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
            height: 100vh;
            overflow: hidden;
            color: #ffffff;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
            overflow: hidden;
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

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: none;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            outline: none;
            transition: box-shadow 0.3s ease;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.6);
        }

        input[type="submit"] {
            width: 100%;
            background: linear-gradient(135deg, #89fffd, #ef32d9);
            color: #ffffff;
            border: none;
            padding: 15px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        input[type="submit"]:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.4);
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
        }

        p a:hover {
            text-decoration: underline;
        }

        /* Popup Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-content h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .modal-content button {
            background-color: #ef32d9;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .modal-content button:hover {
            background-color: #c62da9;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>ล็อคอิน</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="email">ชื่อผู้ใช้:</label>
            <input type="text" id="email" name="email" placeholder="ชื่อผู้ใช้" required><br>
            
            <label for="password">รหัสผ่าน:</label>
            <input type="password" id="password" name="password" placeholder="รหัสผ่าน" required><br>
            
            <input type="submit" value="ล็อคอิน">

            <p>ยังไม่เป็นเป็นสมาชิก?   <a href="registration.php">เข้าสู่ระบบที่นี่</a></p>
        </form>
    </div>

    <!-- Modal Structure -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <h3 id="errorMessage"></h3>
            <button onclick="closeModal()">ตกลง</button>
        </div>
    </div>

    <script>
        function closeModal() {
            document.getElementById('errorModal').style.display = 'none';
        }

        function showModal(message) {
            document.getElementById('errorMessage').innerText = message;
            document.getElementById('errorModal').style.display = 'flex';
        }

        <?php if (!empty($login_error)): ?>
            showModal("<?php echo $login_error; ?>");
        <?php endif; ?>
    </script>
</body>
</html>
