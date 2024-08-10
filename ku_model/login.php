<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $servername = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "kumodel";

    // สร้างการเชื่อมต่อกับฐานข้อมูล
    $conn = new mysqli($servername, $username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ตรวจสอบข้อมูลการเข้าสู่ระบบ
    $sql = "SELECT id, prefix, firstname, lastname, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $prefix, $firstname, $lastname, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // เก็บข้อมูลในเซสชัน
            $_SESSION['member_id'] = $id;
            $_SESSION['prefix'] = $prefix;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;

            // เปลี่ยนเส้นทางไปยังหน้า welcome.php
            header("Location: mains.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <!-- ส่วนนี้ใช้สำหรับกำหนดข้อมูลพื้นฐานของหน้าเว็บ -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <!-- เชื่อมต่อไฟล์ CSS สำหรับการตกแต่งหน้าเว็บ -->
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
        <title>Document</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap/css/bootstrap.min.css"/>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>  
        <style type="text/css">
        #overlay {
            position: absolute;
            top: 0px;   
            left: 0px;  
            background: #ccc;   
            width: 100%;   
            height: 100%;   
            opacity: .75;   
            filter: alpha(opacity=75);   
            -moz-opacity: .75;  
            z-index: 999;  
            background: #fff url(https://media4.giphy.com/media/v1.Y2lkPTc5MGI3NjExdW94b2s2ZHQ1aTR5dWh0bnNoaWRvaDEybDYxcGJydm9xNTZzb3k0MyZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/4EFt4UAegpqTy3nVce/giphy.webp) 50% 50% no-repeat;
        }   
        .main-contain{
            position: absolute;  
            top: 0px;   
            left: 0px;  
            width: 100%;   
            height: 100%;   
            overflow: hidden;
        }
        </style>
        </head>
        <div id="overlay"></div>	
        <div class="main-contain" >
        <body>
    <!-- ส่วนหัวของหน้าเว็บ (หัวเรื่อง) -->
    <h2>Admin Login</h2>

    <!-- ฟอร์มสำหรับกรอกข้อมูลเพื่อเข้าสู่ระบบของ Admin -->
    <form action="mains.php" method="post">
        <!-- ป้ายกำกับและช่องกรอกข้อมูลสำหรับ E-mail -->
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <!-- ป้ายกำกับและช่องกรอกข้อมูลสำหรับรหัสผ่าน -->
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <!-- ปุ่มสำหรับยืนยันการเข้าสู่ระบบ -->
        <button type="submit" >Login</button>
    </form>

    <p>เป็นสมาชิกทั่วไป? <a href="register.php">เข้าสู่ระบบที่นี่</a></p>
</body>
        </div>

        <script type="text/javascript">
            
        $(function(){
           
            $("#overlay").fadeOut();
            $(".main-contain").removeClass("main-contain");
        });
        </script>  
</head>

</html>
