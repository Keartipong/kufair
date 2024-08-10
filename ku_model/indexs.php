<?php
include 'config.php';
session_start();
// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['member_id'])) {
    header('Location: loginm.php');
   exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>หน้าแรก</title>
    <style>
        body {
            background-color: black;
            color: gold;
        }
        .zone-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .zone {
            background-color: #333;
            padding: 20px;
            margin: 10px;
            border-radius: 10px;
            width: 200px;
        }
        .zone h3 {
            text-align: center;
        }
        .booth {
            text-align: center;
            margin: 10px 0;
        }
        .booth button {
            background-color: gold;
            border: none;
            padding: 10px;
            border-radius: 5px;
            color: black;
        }
        .logout {
            text-align: right;
            margin: 20px;
        }
        .logout button {
            background-color: gold;
            border: none;
            padding: 10px;
            border-radius: 5px;
            color: black;
        }
    </style>
</head>
<body>
    <div class="logout">
        <form action="mains.php" method="post">
            <button type="submit">กลับ</button>
        </form>
    </div>
    <div class="zone-container">
        <?php
        // ดึงข้อมูลโซนและบูธจากฐานข้อมูล
        // ตัวอย่างข้อมูลโซน A-D แต่ละโซนมีบูธ 4 บูธ
        $zones = ['A', 'B', 'C', 'D'];
        foreach ($zones as $zone) {
            echo '<div class="zone">';
            echo '<h3>โซน ' . $zone . '</h3>';
            for ($i = 1; $i <= 4; $i++) {
                echo '<div class="booth">';
                echo '<p>บูธ ' . $i . '</p>';
                echo '<button onclick="bookBooth(\'' . $zone . '\', ' . $i . ')">จอง</button>';
                echo '</div>';
            }
            echo '</div>';
        }
        ?>
    </div>
    <script>
    function bookBooth(zone, booth) {
        window.location.href = 'booking.php?zone_id=' + encodeURIComponent(zone) + '&booth_number=' + encodeURIComponent(booth);
    }
</script>

</body>
</html>