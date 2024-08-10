<?php
include 'config.php'; // เชื่อมต่อกับฐานข้อมูล


session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, firstname, lastname FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $firstname, $lastname);
        $stmt->fetch();
        
        $_SESSION['user_id'] = $user_id;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        
        echo "<p class='message success'>User Login Successful!</p>";
        echo "<meta http-equiv='refresh' content='5;url=mains.php'>";
    } else {
        echo "<p class='message error'>Invalid E-mail or Password!</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
