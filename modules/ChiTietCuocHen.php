<?php
    require_once('../includes/connect.php');
    require_once('checkLogin.php');

    $username = $_SESSION['username'];
    $query1 = "SELECT id FROM ACCOUNT WHERE username = '$username'";
    $IDresult = $conn->query($query1);

    $idRow = $IDresult->fetch_assoc();
    $idValue = $idRow['id'];

    $query = "SELECT studentID FROM INVENTORY WHERE IDaccount = '$idValue'";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        $studentId = $row['studentID'];
    
        $searchquery = "SELECT * FROM printorder WHERE studentid = '$studentId' AND orderid ='" . $_GET['id'] . "'";
        $res = $conn->query($searchquery);
        $row = $res->fetch_assoc();
        $searchquery2 = "SELECT * FROM printdetails WHERE orderid ='" . $_GET['id'] . "'";
        $res2 = $conn->query($searchquery2);
    } else {
        echo "Truy vấn thất bại: " . $conn->error;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/styles/ChiTietCuocHen.css">
    <link rel="stylesheet" href="../templates/styles/main.css">
    <link rel="stylesheet" href="../templates/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../templates/icon/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">
    <style>
        .header_list a {
            font-size: 18px; 
            color: #000; 
        }
    </style>
    <title>Document</title>
</head>
<body>
<header>
    <div class="logo">
        <img src="../templates/img/logo.png" alt="">
    </div>
    <div class="header_wrap">
        <a href=""><i class="header_menu fa-solid fa-bars"></i></a>
        <span class="header_name">  Chi tiết cuộc hẹn</span>
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
            <span class="icon_name">CHI TIẾT ĐƠN HẸN</span>
            <img src="../templates/img/quill-paper.png" alt="">
        </div>
        <div class="infor">
            ID đơn hẹn: <?php echo $row['orderid']; ?>
            <br/>
            MSSV: <?php echo $row['studentid']; ?>
            <br/>
            ID máy in: <?php echo $row['orderedprinter']; ?>
            <br/>
            Các thuộc tính:

            <?php
            if ($res2->num_rows > 0) {
                while ($row2 = $res2->fetch_assoc()) {
                    if ($row2['colorused'] == 'Black') $tmp = "In màu";
                    else $tmp = "In đen trắng";
                    echo '<br/>
                        + ID: ' . $row2['id'] . '
                        <br/>
                        + Tên tệp: ' . $row2['filename'] . '
                        <br/>
                        + Kiểu in: ' . $tmp . '
                        <br/>
                        + Khổ trang in: ' . $row2['usedpaper'] . '
                        <br/>
                        + Số mặt in: ' . $row2['pagesperpaper'] . '
                        <br/>
                        + Số trang muốn in: ' . $row2['numpages'] . '
                        <br/>
                        + Số bản sao: ' . $row2['numofcopies'] . '
                        <br/>
                        + Giá: ' . $row2['price'] . '
                        <br/>
                    </div>';
                }
            } else {
                echo '<br/>
                        + ID: 0
                        <br/>
                        + Loại tệp: 0
                        <br/>
                        + Kiểu in: 0
                        <br/>
                        + Khổ trang in: 0
                        <br/>
                        + Số mặt in: 0
                        <br/>
                        + Số trang muốn in: 0
                        <br/>
                        + Số bản sao: 0
                        <br/>
                        + Giá: 0
                        <br/>
                    </div>';
            }
            ?>

            <table class="table table-green table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nơi nhận kết quả</th>
                    <th>Thời gian tạo</th>
                    <th>Tình trạng xử lí</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($row['payed'] == 1) $tmp = 'Đã trả tiền';
                else $tmp = 'Chưa xử lý';
                $tmp = 'Chưa xử lý';
                echo '<tr>
                            <td>' . $row['orderid'] . '</td>
                            <td>H1</td>
                            <td>' . $row['orderdate'] . '</td>
                            <td>' . $tmp . '</td>';
                if ($tmp == 'Chưa xử lý')
                    echo '<td>
                                <a href="./TBHuyDon.php?id=' . $row['orderid'] . '">
                                    <button class="show-modal">Huỷ cuộc hẹn</button>
                                </a>
                            </td>';
                else
                    echo '<td></td>';
                echo '</tr>';
                ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<footer>
    <div class="div"> </div>
    <div class="footer_info">
        <p>Copyright 2023 Trường Đại Học Bách Khoa - ĐHQG TP.HCM. All Rights Reserved.</p>
        <p>Địa chỉ: Đông Hòa, TP Dĩ An, Bình Dương.</p>
    </div>
</footer>
</body>
</html>
