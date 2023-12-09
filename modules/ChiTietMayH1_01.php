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
    <link rel="stylesheet" href="../templates/styles/ChiTietMayH1_01.css">
    <link rel="stylesheet" href="../templates/styles/SearchBar.css">
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
            <p class="title">THÔNG TIN MÁY IN</p>
            <div class="printer-infomation">
               
            <?php
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if ($id !== null) {
                $sql_building = "SELECT printerid, printername, printerstatus, printertype FROM printer WHERE printerid = ?";
                $stmt = $conn->prepare($sql_building);

                if ($stmt) {
                    $stmt->bind_param("s", $id);
                    $stmt->execute();
                    $result_building = $stmt->get_result();

                    if ($result_building->num_rows > 0) {
                        $row_building = $result_building->fetch_assoc(); 
                        ?>
                        <div class="printer-img"> 
                            <img src="../templates/img/printer.png" alt="printer">
                            <p class="printer-name">
                                ID: <?php echo $row_building['printerid'] ?><br/>
                                Model: <?php echo $row_building['printertype'] ?>
                            </p>
                        </div>
                        <div class="printer-detail">
                            <p> Tên máy in: <?php echo $row_building['printername'] ?><br/> </p>
                            <p> Giấy in sử dụng: A4, A5 </p>
                            <p> Màu in:
                                <?php
                                $sql_color = "SELECT colorname FROM printercolor WHERE printerid = ?";
                                $stmt_color = $conn->prepare($sql_color);
                
                                if ($stmt_color) {
                                    $stmt_color->bind_param("s", $id);
                                    $stmt_color->execute();
                                    $result_color = $stmt_color->get_result();
                                
                                    $firstRow = true; 
                                
                                    while ($row_color = $result_color->fetch_assoc()) {
                                        if (!$firstRow) {
                                            echo ', '; 
                                        } else {
                                            $firstRow = false; 
                                        }
                                        echo $row_color['colorname'];
                                    }
                                }
                                ?>
                                
                            </p>
                            <p> Tình trạng: <?php echo $row_building['printerstatus'] ?><br/> </p>
                            <button class="appointment-creation" onclick="redirectToAppointmentPage()">Đặt lịch in</button>
                        </div>
                        <?php
                    }
                    $stmt->close();
                }
            }
            ?>
            </div>
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
<script>
        function redirectToAppointmentPage() {
            window.location.href = "TuyChinhDonHen.php?printerid=<?php echo htmlspecialchars($row_building['printerid']); ?>";
        }
</script>