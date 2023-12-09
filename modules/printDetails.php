<?php
    require_once('../includes/connect.php');
    require_once('checkLogin.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/styles/page14.css">
    <link rel="stylesheet" href="../templates/styles/main.css">
    <link rel="stylesheet" href="../templates/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../templates/icon/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">
    <style>
        .header_list a {
            font-size: 18px; 
            color: #000; 
        }
    </style>
    <title>Print Detail</title>
</head>
<body>
   <header>
        <div class="logo">
            <img src="../templates/img/logo.png" alt="">
        </div>
        <div class="header_wrap">
            <a href=""><i class="header_menu fa-solid fa-bars"></i></a>
            <span class="header_name"> Xem cuộc hẹn cá nhân</span>
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
            <div class="icon_paper"> 
                <h3 class="icon_name">CHI TIẾT IN ẤN</h3>
                <img src="../templates/img/quill-paper.png" alt="page"> 
            </div>
            <?php
                if(isset($_GET['id'])) {
                    $id = $_GET['id'];
            
                    $sql = "SELECT * FROM printdetails WHERE id = '$id'";
                    $result = $conn->query($sql);
            
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $orderID = $row['orderid'];
            
                        $sqlOrder = "SELECT * FROM printorder WHERE orderid = '$orderID'";
                        $resultOrder = $conn->query($sqlOrder);
                        
                        if ($resultOrder->num_rows > 0) {
                            $rowOrder = $resultOrder->fetch_assoc();

                            $Printer = $rowOrder['orderedprinter'];
                            $sqlPlace = "SELECT * FROM printer WHERE printerid = '$Printer'";
                            $printerOrder = $conn->query($sqlPlace);
                            $rowPrinter = $printerOrder->fetch_assoc();

                ?>
            <div class="infor">
                ID đơn hẹn: <?php echo $row['orderid']; ?>
                <br/>
                MSSV: <?php echo $rowOrder['studentid']; ?>
                <br/>
                ID máy in: <?php echo $rowOrder['orderedprinter']; ?>
                <br/>
                Phòng in: <?php echo $rowPrinter['buildingid']; ?>
                <br/>
                Các thuộc tính:
                <br/>
                + Loại tệp: <?php echo isset($row['documenttype']) ? $row['documenttype'] : 'N/A'; ?>
                <br/>
                + Kiểu in: <?php echo isset($row['colorused']) ? $row['colorused'] : 'N/A'; ?>
                <br/>
                + Khổ trang in: <?php echo isset($row['usedpaper']) ? $row['usedpaper'] : 'N/A'; ?>
                <br/>
                + Số mặt in: <?php echo isset($row['pagesperpaper']) ? $row['pagesperpaper'] : 'N/A'; ?>
                <br/>
                + Số trang muốn in: <?php echo isset($row['numofcopies']) && isset($row['pagesperpaper']) ? $row['numofcopies'] * $row['pagesperpaper'] : 'N/A'; ?> <br>
                + Số bản sao: <?php echo isset($row['numofcopies']) ? $row['numofcopies'] : 'N/A'; ?>
            </div>
            <?php
                        } else {
                            echo "<p>Order details not found</p>";
                        }
                    } else {
                        echo "<p>Print detail not found</p>";
                    }
                } else {
                    echo "<p>ID parameter not set in the URL</p>";
                }


            ?>
            <div class="table-container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Nơi nhận kết quả</th>
                        <th>Thời gian tạo</th>
                    </tr>
                    <?php
                        echo "<tr>";
                        echo "<td>".$rowOrder['orderid']."</td>";
                        echo "<td>H1</td>";
                        echo  "<td>".$rowOrder['orderdate']."</td>";
                        echo "</tr>";
                    ?>
                </table>
                <a href="printHistory.php"> <button class="stylebutton">Quay lại</button></a>
                <style>
                    .stylebutton {
                        cursor: pointer;
                    }
                </style>
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
