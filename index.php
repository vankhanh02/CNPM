<?php
    session_start();
    require_once('./includes/connect.php');

    if (!isset($_SESSION['username'])) {
        header("Location: ./modules/DangNhap.php");
        exit();
    }
        
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $query1 = "SELECT id FROM ACCOUNT WHERE username = '$username'";
        $IDresult = $conn->query($query1);

        $idRow = $IDresult->fetch_assoc();
        $idValue = $idRow['id'];

        $query = "SELECT fullname FROM INVENTORY WHERE IDaccount = '$idValue'";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $fullname = $row['fullname'];
        } else {
            $fullname = 'Khách';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./templates/styles/main.css">
    <link rel="stylesheet" href="./templates/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="./templates/icon/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">
    <style>
        .header_list a {
            font-size: 18px; 
            color: #000; 
        }
        .center-title {
        text-align: center;
    }
    </style>
        <title class="center-title">TRANG CHU</title>
</head>
<body>
   <header>
        <div class="logo">
            <img src="./templates/img/logo.png" alt="">
        </div>
        <div class="header_wrap">
            <a href=""><i class="header_menu fa-solid fa-bars"></i></a>
            <span class="header_name"> TRANG CHỦ</span>
            <div class="header_list">
                <a href="./modules/TrangCaNhan.php"><?php echo $fullname; ?><i class="fa-solid fa-user"></i> </a>
                <a href="./modules/logout.php?action=logout"><i class="fa-solid fa-sign-out-alt"></i> Đăng xuất</a>
            </div>
        </div>
   </header>
   <main>
        <div class="nav">
            <ul class="nav_list">
                <li class="nav_item"><a href="./modules/DanhSachToaNha.php"><i class="fa-solid fa-pen-to-square"></i> Danh sách máy in</a></li>
                <li class="nav_item"><a href="./modules/DanhSachCuocHen.php"><i class="fa-solid fa-pen-to-square"></i> Danh sách cuộc hẹn</a></li>
                <li class="nav_item"><a href="./modules/printHistory.php"><i class="fa-solid fa-pen-to-square"></i> Lịch sử in ấn</a></li>
                <li class="nav_item"><a href="./modules/MuaGiay.php"><i class="fa-solid fa-pen-to-square"></i> Mua thêm trang</a></li>
            </ul>
        </div>
        <div class="content"> 
            <img src="./templates/img/homepage.jpg" alt="">
        </div>
   </main>
   <footer>
    <div class="div">
    </div>
    <div class="footer_info">
        <p>Copyright 2023 Trường Đại Học Bách Khoa - ĐHQG TP.HCM. All Rights Reserved.</p>
        <p>Địa chỉ: Đông Hòa, TP Dĩ An, Bình Dương.</p>
    </div>
   </footer>
</body>
</html>

