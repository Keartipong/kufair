<?php
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


    $sql = "SELECT password FROM users WHERE email=? AND password=? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email ,$password);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        header("Location: login.html?loginResult=OK");
    } else {
        header("Location: login.html?loginResult=failed");
    }

    $stmt->close();
    $conn->close();
}
?>
