<?php 
require_once('../includes/connect.php');
require_once('checkLogin.php');
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/styles/main.css">
    <link rel="stylesheet" href="../templates/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../templates/icon/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">
    <link rel="stylesheet" href="../templates/styles/DanhSachMayInH1.css">
    <link rel="stylesheet" href="../templates/styles/SearchBar.css">
    <script src="../templates/script/router.js" defer></script>
    <title>Các máy in tòa H1</title>
</head>
<body>
   <header>
        <div class="logo">
            <img src="../templates/img/logo.png" alt="">
        </div>
        <div class="header_wrap">
            <a href=""><i class="header_menu fa-solid fa-bars"></i></a>
            <span class="header_name"> Xem máy in</span>
            <div class="header_list">
                <a href="TrangCaNhan.php"><?php echo $fullname; ?><i class="fa-solid fa-user"></i> </a>
                <a href="logout.php?action=logout"><i class="fa-solid fa-sign-out-alt"></i> Đăng xuất</a>
            </div>
        </div>
   </header>
   <main>
        <div class="nav">
            <ul class="nav_list">
                <li class="nav_item"><a href="DanhSachToaNha.php"><i class="fa-solid fa-pen-to-square"></i> Danh sách máy in</a></li>
                <li class="nav_item"><a href="DanhSachCuocHen.php"><i class="fa-solid fa-pen-to-square"></i> Danh sách cuộc hẹn</a></li>
                <li class="nav_item"><a href="printHistory.php"><i class="fa-solid fa-pen-to-square"></i> Lịch sử in ấn</a></li>
                <li class="nav_item"><a href="MuaGiay.php"><i class="fa-solid fa-pen-to-square"></i> Mua thêm trang</a></li>

            </ul>
        </div>
        <div class="content"> 
            <div class="search-bar">
                <input type="text" class="search-bar" 
                id="search-printer" placeholder="Search"
                oninput="choosePrinter()">
                <i class="fas fa-search"></i>
            </div>
            <p class="title">DANH SÁCH MÁY IN</p>
            <ul class="list-of-printer">
                <?php
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $sql_building = "SELECT printerid, printertype FROM printer WHERE buildingid = ?";
                    $stmt = $conn->prepare($sql_building);
                    $stmt->bind_param("s", $id);
                    $stmt->execute();
                
                    $result_building = $stmt->get_result();
                
                    if ($result_building) {
                        while ($row_building = $result_building->fetch_assoc()) {
                            ?>
                            <li>
                                <a href="ChiTietMayH1_01.php?id=<?php echo htmlspecialchars($row_building['printerid']); ?>">
                                    <img src="../templates/img/printer.png" alt="printer">
                                </a>
                                <div class="printer-info">
                                    <p>
                                    ID: <?php echo htmlspecialchars($row_building['printerid']); ?>
                                    </p>
                                    <p>
                                    Model: <?php echo htmlspecialchars($row_building['printertype']); ?>
                                    </p>
                                </div>
                            </li>
                            <?php
                        }
                
                        $result_building->free_result(); 
                
                    } else {
                        die("Lỗi truy vấn: " . $conn->error);
                    }
                
                    $stmt->close();
                }
                ?>
            </ul>
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